<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaystackWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $signingKey = (string) (config('services.paystack.webhook_secret') ?: config('services.paystack.secret_key'));
        if ($signingKey === '') {
            return response('paystack not configured', 500);
        }

        $signature = (string) $request->header('x-paystack-signature', '');
        $payload = $request->getContent();

        if ($signature === '' || ! hash_equals(hash_hmac('sha512', $payload, $signingKey), $signature)) {
            return response('invalid signature', 400);
        }

        try {
            /** @var array<string, mixed> $data */
            $data = (array) $request->json()->all();
            $event = (string) ($data['event'] ?? '');

            if ($event === 'charge.success') {
                /** @var array<string, mixed> $eventData */
                $eventData = (array) ($data['data'] ?? []);
                $reference = (string) ($eventData['reference'] ?? '');
                $amount = (int) ($eventData['amount'] ?? 0);

                if ($reference !== '') {
                    $order = Order::query()
                        ->where('paystack_reference', $reference)
                        ->first();

                    if ($order && $order->payment_status !== 'paid') {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'paid',
                            'payment_meta' => $data,
                            'placed_at' => $order->placed_at ?? Carbon::now(),
                        ]);

                        // best-effort promo usage count
                        if ($order->promo_code) {
                            Promotion::query()
                                ->where('code', $order->promo_code)
                                ->increment('used_count');
                        }

                        $order->load(['user', 'items']);
                        try {
                            app(\App\Services\BrevoService::class)->sendTransactional(
                                [['email' => $order->user->email, 'name' => $order->user->name]],
                                'TZEL CAFÉ — Order confirmed',
                                view('emails.order-confirmation', ['order' => $order, 'user' => $order->user])->render()
                            );
                        } catch (Throwable) {
                        }

                        app(\App\Services\OrderNotificationService::class)->notifyNewOrderToAdmin($order);
                        app(\App\Services\OrderNotificationService::class)->awardLoyaltyPoints($order);

                        // sanity: amount mismatch is logged (do not fail webhook)
                        if ((int) $order->total_cents !== 0 && (int) $order->total_cents !== $amount) {
                            Log::warning('Paystack amount mismatch', [
                                'order_id' => $order->id,
                                'reference' => $reference,
                                'expected' => $order->total_cents,
                                'received' => $amount,
                            ]);
                        }
                    }
                }
            }

            return response('ok', 200);
        } catch (Throwable $e) {
            Log::error('Paystack webhook error', ['message' => $e->getMessage()]);
            return response('error', 200);
        }
    }
}
