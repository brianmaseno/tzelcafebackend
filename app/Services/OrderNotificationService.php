<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderNotificationService
{
    public function __construct(private BrevoService $brevo) {}

    public function notifyNewOrderToAdmin(Order $order): void
    {
        $adminEmail = (string) config('services.tzel.admin_notification_email');
        if ($adminEmail === '') {
            return;
        }

        $order->loadMissing(['user', 'items']);

        $subject = "TZEL CAFÉ — New paid order #{$order->id}";
        $html = view('emails.admin-new-order', ['order' => $order])->render();

        try {
            $this->brevo->sendTransactional(
                [['email' => $adminEmail, 'name' => 'TZEL Admin']],
                $subject,
                $html
            );
        } catch (Throwable $e) {
            Log::error('Failed to send admin new-order email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notifyCustomerStatusChange(Order $order, string $previousStatus): void
    {
        if ($order->status === $previousStatus) {
            return;
        }

        /** @var User|null $user */
        $user = $order->user()->first();
        if (! $user) {
            return;
        }

        $order->loadMissing(['items']);

        $isDelivered = strtolower((string) $order->status) === 'delivered';

        if ($isDelivered) {
            $subject = "TZEL CAFÉ — Order #{$order->id} delivered — Your receipt";
            $html = view('emails.order-delivered', [
                'order' => $order,
                'user' => $user,
            ])->render();
            $text = "Your TZEL CAFÉ order #{$order->id} has been delivered. Total: KES "
                . number_format($order->total_cents / 100, 2);
        } else {
            $subject = "TZEL CAFÉ — Order #{$order->id} is now {$order->status}";
            $html = view('emails.order-status', [
                'order' => $order,
                'user' => $user,
                'previousStatus' => $previousStatus,
            ])->render();
            $text = null;
        }

        try {
            $this->brevo->sendTransactional(
                [['email' => $user->email, 'name' => $user->name]],
                $subject,
                $html,
                $text
            );
        } catch (Throwable $e) {
            Log::error('Failed to send customer order status email', [
                'order_id' => $order->id,
                'status' => $order->status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function awardLoyaltyPoints(Order $order): void
    {
        if ($order->payment_status !== 'paid') {
            return;
        }

        /** @var User|null $user */
        $user = $order->user()->first();
        if (! $user) {
            return;
        }

        // 1 point per KES 100 spent (based on order total after discount, excluding delivery)
        $spendCents = max(0, (int) $order->total_cents - (int) $order->delivery_fee_cents);
        $points = (int) floor($spendCents / 10000);

        if ($points > 0) {
            $user->increment('reward_points', $points);
        }
    }
}
