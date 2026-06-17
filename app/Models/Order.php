<?php

namespace App\Models;

use App\Support\OrderTrackingSupport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_token',
        'status',
        'order_type',
        'subtotal_cents',
        'discount_cents',
        'delivery_fee_cents',
        'total_cents',
        'promo_code',
        'dropoff_location',
        'notes',
        'payment_provider',
        'paystack_reference',
        'payment_status',
        'payment_meta',
        'placed_at',
        'delivered_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->tracking_token)) {
                $order->tracking_token = OrderTrackingSupport::generateTrackingToken();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'subtotal_cents' => 'integer',
            'discount_cents' => 'integer',
            'delivery_fee_cents' => 'integer',
            'total_cents' => 'integer',
            'payment_meta' => 'array',
            'placed_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function trackingUrl(): string
    {
        $base = rtrim((string) config('services.tzel.frontend_url'), '/');

        return "{$base}/track-order?order={$this->id}&token={$this->tracking_token}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
