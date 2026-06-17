<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckoutController extends Controller
{
    public function orders(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with(['items'])
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $orders]);
    }

    public function initialize(Request $request): JsonResponse
    {
        $data = $request->validate([
            'orderType' => ['required', Rule::in(['pickup', 'delivery'])],
            'dropoffLocation' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'promoCode' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        if ($data['orderType'] === 'delivery' && empty($data['dropoffLocation'])) {
            return response()->json([
                'message' => 'Drop-off location is required for delivery orders.',
            ], 422);
        }

        $paystackSecret = (string) config('services.paystack.secret_key');
        if ($paystackSecret === '') {
            return response()->json([
                'message' => 'Paystack is not configured.',
            ], 500);
        }

        $user = $request->user();

        /** @var array<int, array{id:int, quantity:int}> $itemsPayload */
        $itemsPayload = $data['items'];
        $menuItems = MenuItem::query()
            ->whereIn('id', collect($itemsPayload)->pluck('id'))
            ->get()
            ->keyBy('id');

        return DB::transaction(function () use ($data, $itemsPayload, $menuItems, $user, $paystackSecret) {
            $subtotalCents = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'order_type' => $data['orderType'],
                'promo_code' => $data['promoCode'] ?? null,
                'dropoff_location' => $data['dropoffLocation'] ?? null,
                'notes' => $data['notes'] ?? null,
                'payment_status' => 'initialized',
                'placed_at' => Carbon::now(),
            ]);

            foreach ($itemsPayload as $row) {
                /** @var MenuItem|null $menuItem */
                $menuItem = $menuItems->get($row['id']);
                if (! $menuItem || ! $menuItem->is_active) {
                    continue;
                }

                $unit = (int) $menuItem->price_cents;
                $qty = (int) $row['quantity'];
                $line = $unit * $qty;
                $subtotalCents += $line;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'unit_price_cents' => $unit,
                    'quantity' => $qty,
                    'line_total_cents' => $line,
                ]);
            }

            $deliveryFeeCents = $data['orderType'] === 'delivery' ? 250 * 100 : 0;
            $discountCents = $this->calculateDiscountCents($data['promoCode'] ?? null, $subtotalCents);
            $totalCents = max(0, $subtotalCents - $discountCents) + $deliveryFeeCents;

            $order->update([
                'subtotal_cents' => $subtotalCents,
                'discount_cents' => $discountCents,
                'delivery_fee_cents' => $deliveryFeeCents,
                'total_cents' => $totalCents,
            ]);

            $init = Http::withToken($paystackSecret)
                ->acceptJson()
                ->post('https://api.paystack.co/transaction/initialize', [
                    'email' => $user->email,
                    'amount' => $totalCents, // Paystack expects amount in kobo/cents
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_type' => $order->order_type,
                    ],
                    'callback_url' => rtrim((string) env('FRONTEND_URL', 'http://localhost:5173'), '/').'/payment/callback',
                ]);

            if (! $init->ok() || ! $init->json('status')) {
                return response()->json([
                    'message' => 'Failed to initialize payment.',
                    'detail' => $init->json(),
                ], 502);
            }

            $reference = (string) $init->json('data.reference');
            $authorizationUrl = (string) $init->json('data.authorization_url');

            $order->update([
                'paystack_reference' => $reference,
                'payment_meta' => $init->json(),
            ]);

            return response()->json([
                'data' => [
                    'orderId' => $order->id,
                    'reference' => $reference,
                    'authorizationUrl' => $authorizationUrl,
                    'amount' => $totalCents / 100,
                    'currency' => 'KES',
                ],
            ]);
        });
    }

    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'reference' => ['required', 'string', 'max:200'],
        ]);

        $paystackSecret = (string) config('services.paystack.secret_key');
        if ($paystackSecret === '') {
            return response()->json(['message' => 'Paystack is not configured.'], 500);
        }

        $reference = $data['reference'];

        $verify = Http::withToken($paystackSecret)
            ->acceptJson()
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (! $verify->ok() || ! $verify->json('status')) {
            return response()->json([
                'message' => 'Failed to verify payment.',
                'detail' => $verify->json(),
            ], 502);
        }

        $status = (string) $verify->json('data.status'); // success|failed|abandoned

        $order = Order::query()
            ->where('paystack_reference', $reference)
            ->where('user_id', $request->user()->id)
            ->with(['items'])
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        if ($status === 'success') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'paid',
                'payment_meta' => $verify->json(),
            ]);

            $this->notifyOrderPaid($order);
            app(\App\Services\OrderNotificationService::class)->notifyNewOrderToAdmin($order);
            app(\App\Services\OrderNotificationService::class)->awardLoyaltyPoints($order);
        } elseif ($status === 'failed') {
            $order->update([
                'payment_status' => 'failed',
                'payment_meta' => $verify->json(),
            ]);
        }

        return response()->json([
            'data' => [
                'orderId' => $order->id,
                'paymentStatus' => $order->payment_status,
                'status' => $order->status,
            ],
        ]);
    }

    private function calculateDiscountCents(?string $promoCode, int $subtotalCents): int
    {
        if (! $promoCode) return 0;

        $promotion = Promotion::query()
            ->where('code', $promoCode)
            ->where('is_active', true)
            ->first();

        if (! $promotion) return 0;

        $now = Carbon::now();
        if ($promotion->starts_at && $now->lt($promotion->starts_at)) return 0;
        if ($promotion->ends_at && $now->gt($promotion->ends_at)) return 0;
        if ($promotion->usage_limit !== null && $promotion->used_count >= $promotion->usage_limit) return 0;

        if ($promotion->type === 'fixed') {
            return min((int) $promotion->value, $subtotalCents);
        }

        // percent
        $percent = max(0, min(100, (int) $promotion->value));
        return (int) round($subtotalCents * ($percent / 100));
    }

    private function notifyOrderPaid(Order $order): void
    {
        try {
            /** @var \App\Models\User|null $user */
            $user = $order->user()->first();
            if (! $user) return;

            $subject = 'TZEL CAFÉ — Order confirmed';
            $html = view('emails.order-confirmation', ['order' => $order, 'user' => $user])->render();

            app(\App\Services\BrevoService::class)->sendTransactional(
                [['email' => $user->email, 'name' => $user->name]],
                $subject,
                $html
            );
        } catch (Throwable $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
