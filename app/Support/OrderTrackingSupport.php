<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderTrackingSupport
{
    public static function generateTrackingToken(): string
    {
        return Str::lower(Str::random(32));
    }

    public static function backfillMissingTokens(): void
    {
        Order::query()
            ->whereNull('tracking_token')
            ->orderBy('id')
            ->each(function (Order $order) {
                $order->update(['tracking_token' => self::generateTrackingToken()]);
            });
    }
}
