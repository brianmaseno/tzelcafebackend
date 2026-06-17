<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Order Update</title></head>
<body style="font-family: Arial, sans-serif; color: #2c1810; background: #f8f4ef; padding: 24px;">
  <div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px;">
    <h1 style="color: #8b6914; font-size: 20px;">Order #{{ $order->id }} update</h1>
    <p>Hi {{ $user->name }},</p>
    <p>Your order status changed from <strong>{{ $previousStatus }}</strong> to <strong>{{ $order->status }}</strong>.</p>
    @if($order->dropoff_location)
      <p><strong>Delivery:</strong> {{ $order->dropoff_location }}</p>
    @endif
    <p><strong>Total:</strong> KES {{ number_format($order->total_cents / 100, 0) }}</p>
    <p style="margin-top: 16px;">
      <a href="{{ $order->trackingUrl() }}" style="display:inline-block;background:#8b6914;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:bold;">
        Track your order
      </a>
    </p>
    <p style="margin-top: 24px; font-size: 12px; color: #666;">TZEL CAFÉ — Eat. Sip. Connect.</p>
  </div>
</body>
</html>
