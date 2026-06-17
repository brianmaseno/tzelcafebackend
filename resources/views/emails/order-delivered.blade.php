<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Order delivered</title></head>
<body style="font-family: Arial, sans-serif; color: #2c1810; background: #f8f4ef; padding: 24px;">
  <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 28px; border: 1px solid #e8dcc8;">
    <p style="margin:0;font-size:11px;letter-spacing:4px;color:#8b6914;font-weight:bold;">TZEL CAFÉ</p>
    <h1 style="color: #8b6914; font-size: 22px; margin: 12px 0 8px;">Your order has been delivered</h1>
    <p>Hi {{ $user->name }},</p>
    <p style="line-height:1.6;color:#444;">
      Thank you for dining with TZEL CAFÉ. Your order <strong>#{{ $order->id }}</strong> has been delivered.
      Please find your receipt below for your records.
    </p>

    @include('emails.partials.order-receipt', ['order' => $order])

    <p style="margin-top: 20px; text-align: center;">
      <a href="{{ $order->trackingUrl() }}" style="display:inline-block;background:#8b6914;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:bold;">
        View order details
      </a>
    </p>

    <p style="margin-top: 28px; font-size: 12px; color: #666; text-align: center; letter-spacing: 2px;">
      EAT. SIP. CONNECT.
    </p>
  </div>
</body>
</html>
