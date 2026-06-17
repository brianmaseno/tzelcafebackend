<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>New Order</title></head>
<body style="font-family: Arial, sans-serif; color: #2c1810; background: #f8f4ef; padding: 24px;">
  <div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px;">
    <h1 style="color: #8b6914; font-size: 20px;">New paid order #{{ $order->id }}</h1>
    <p><strong>Customer:</strong> {{ $order->user?->name }} ({{ $order->user?->email }})</p>
    <p><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
    @if($order->dropoff_location)
      <p><strong>Drop-off:</strong> {{ $order->dropoff_location }}</p>
    @endif
    <p><strong>Total:</strong> KES {{ number_format($order->total_cents / 100, 0) }}</p>
    <ul>
      @foreach($order->items as $item)
        <li>{{ $item->quantity }}× {{ $item->name }} — KES {{ number_format($item->line_total_cents / 100, 0) }}</li>
      @endforeach
    </ul>
    <p style="margin-top: 24px; font-size: 12px; color: #666;">TZEL CAFÉ Admin</p>
  </div>
</body>
</html>
