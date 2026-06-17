<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show(Request $request, Order $order): JsonResponse
    {
        if ((int) $order->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return response()->json(['data' => $this->orderPayload($order)]);
    }

    public function track(Request $request): JsonResponse
    {
        $data = $request->validate([
            'orderId' => ['required', 'integer', 'min:1'],
            'email' => ['nullable', 'email', 'max:255'],
            'token' => ['nullable', 'string', 'max:64'],
        ]);

        if (empty($data['email']) && empty($data['token'])) {
            return response()->json(['message' => 'Provide your email or tracking token.'], 422);
        }

        $query = Order::query()->with(['user', 'items'])->whereKey($data['orderId']);

        if (! empty($data['token'])) {
            $query->where('tracking_token', $data['token']);
        } else {
            $query->whereHas('user', fn ($q) => $q->where('email', $data['email']));
        }

        $order = $query->first();

        if (! $order) {
            return response()->json(['message' => 'No order found for those details.'], 404);
        }

        return response()->json(['data' => $this->orderPayload($order)]);
    }

    /**
     * @return array<string, mixed>
     */
    private function orderPayload(Order $order): array
    {
        $order->loadMissing(['items', 'user']);

        return [
            'id' => $order->id,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'order_type' => $order->order_type,
            'subtotal_cents' => $order->subtotal_cents,
            'discount_cents' => $order->discount_cents,
            'delivery_fee_cents' => $order->delivery_fee_cents,
            'total_cents' => $order->total_cents,
            'promo_code' => $order->promo_code,
            'dropoff_location' => $order->dropoff_location,
            'notes' => $order->notes,
            'paystack_reference' => $order->paystack_reference,
            'placed_at' => $order->placed_at?->toIso8601String(),
            'delivered_at' => $order->delivered_at?->toIso8601String(),
            'created_at' => $order->created_at?->toIso8601String(),
            'tracking_token' => $order->tracking_token,
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'unit_price_cents' => $item->unit_price_cents,
                'line_total_cents' => $item->line_total_cents,
            ])->values(),
        ];
    }
}
