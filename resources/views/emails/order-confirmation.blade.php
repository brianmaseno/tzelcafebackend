<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TZEL CAFÉ — Order confirmed</title>
  </head>
  <body style="margin:0;padding:0;background:#1a120b;color:#f5efe6;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
      <div style="background:#2c1f14;border:1px solid rgba(255,255,255,0.08);border-radius:18px;overflow:hidden;">
        <div style="padding:22px 22px 10px;">
          <div style="letter-spacing:6px;text-transform:uppercase;font-size:11px;color:#c5a059;font-weight:700;">
            TZEL CAFÉ
          </div>
          <h1 style="margin:10px 0 0;font-size:22px;line-height:1.2;">
            Your order is confirmed
          </h1>
          <p style="margin:10px 0 0;font-size:14px;line-height:1.6;color:rgba(245,239,230,0.85);">
            Hi {{ $user->name }}, thank you for choosing TZEL CAFÉ — Eat. Sip. Connect.
          </p>
        </div>

        <div style="padding:0 22px 22px;">
          <div style="margin-top:14px;padding:14px 16px;border-radius:14px;background:rgba(26,18,11,0.5);border:1px solid rgba(255,255,255,0.06);">
            <p style="margin:0;font-size:13px;color:rgba(245,239,230,0.9);">
              <strong>Order #</strong>{{ $order->id }} &nbsp;•&nbsp;
              <strong>Status:</strong> {{ $order->status }} &nbsp;•&nbsp;
              <strong>Total:</strong> {{ number_format(($order->total_cents ?? 0) / 100, 2) }} KES
            </p>
          </div>

          <h2 style="margin:18px 0 10px;font-size:15px;color:#d4b06a;">Items</h2>
          <ul style="margin:0;padding:0;list-style:none;">
            @foreach ($order->items()->get() as $item)
              <li style="padding:10px 0;border-top:1px solid rgba(255,255,255,0.06);">
                <div style="display:flex;justify-content:space-between;gap:12px;">
                  <div style="font-size:13px;color:rgba(245,239,230,0.9);">
                    {{ $item->quantity }}× {{ $item->name }}
                  </div>
                  <div style="font-size:13px;color:rgba(245,239,230,0.9);white-space:nowrap;">
                    {{ number_format(($item->line_total_cents ?? 0) / 100, 2) }} KES
                  </div>
                </div>
              </li>
            @endforeach
          </ul>

          <div style="margin-top:14px;font-size:12px;color:rgba(245,239,230,0.7);line-height:1.6;">
            <a href="{{ $order->trackingUrl() }}" style="color:#d4b06a;font-weight:bold;">Track your order online</a>
          </div>

          <div style="margin-top:14px;font-size:12px;color:rgba(245,239,230,0.7);line-height:1.6;">
            If you have any questions, reply to this email. We’re happy to help.
          </div>
        </div>
      </div>

      <div style="margin-top:14px;text-align:center;font-size:11px;color:rgba(245,239,230,0.6);letter-spacing:2px;">
        EAT. SIP. CONNECT.
      </div>
    </div>
  </body>
</html>

