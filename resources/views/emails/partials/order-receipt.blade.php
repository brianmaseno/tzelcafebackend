@php
    $subtotal = ($order->subtotal_cents ?? 0) / 100;
    $discount = ($order->discount_cents ?? 0) / 100;
    $delivery = ($order->delivery_fee_cents ?? 0) / 100;
    $total = ($order->total_cents ?? 0) / 100;
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:20px;border-collapse:collapse;font-family:Arial,sans-serif;font-size:13px;color:#2c1810;">
  <tr>
    <td colspan="2" style="padding:12px 0;border-bottom:2px solid #c5a059;">
      <strong style="font-size:11px;letter-spacing:3px;color:#8b6914;">TZEL CAFÉ — RECEIPT</strong>
    </td>
  </tr>
  <tr>
    <td style="padding:10px 0;color:#666;">Order #</td>
    <td style="padding:10px 0;text-align:right;font-weight:bold;">{{ $order->id }}</td>
  </tr>
  <tr>
    <td style="padding:4px 0;color:#666;">Date</td>
    <td style="padding:4px 0;text-align:right;">{{ ($order->placed_at ?? $order->created_at)?->format('F j, Y g:i A') }}</td>
  </tr>
  @if($order->paystack_reference)
  <tr>
    <td style="padding:4px 0;color:#666;">Payment ref</td>
    <td style="padding:4px 0;text-align:right;font-family:monospace;font-size:12px;">{{ $order->paystack_reference }}</td>
  </tr>
  @endif
  <tr>
    <td style="padding:4px 0;color:#666;">Fulfillment</td>
    <td style="padding:4px 0;text-align:right;text-transform:capitalize;">{{ $order->order_type }}</td>
  </tr>
  @if($order->dropoff_location)
  <tr>
    <td style="padding:4px 0;color:#666;vertical-align:top;">Delivery to</td>
    <td style="padding:4px 0;text-align:right;">{{ $order->dropoff_location }}</td>
  </tr>
  @endif
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;border-collapse:collapse;font-family:Arial,sans-serif;font-size:13px;">
  <thead>
    <tr style="background:#f8f4ef;">
      <th style="padding:10px 8px;text-align:left;color:#8b6914;font-size:11px;letter-spacing:1px;">ITEM</th>
      <th style="padding:10px 8px;text-align:center;color:#8b6914;font-size:11px;">QTY</th>
      <th style="padding:10px 8px;text-align:right;color:#8b6914;font-size:11px;">AMOUNT</th>
    </tr>
  </thead>
  <tbody>
    @foreach($order->items as $item)
    <tr>
      <td style="padding:10px 8px;border-bottom:1px solid #eee;">{{ $item->name }}</td>
      <td style="padding:10px 8px;border-bottom:1px solid #eee;text-align:center;">{{ $item->quantity }}</td>
      <td style="padding:10px 8px;border-bottom:1px solid #eee;text-align:right;">KES {{ number_format($item->line_total_cents / 100, 2) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:12px;border-collapse:collapse;font-family:Arial,sans-serif;font-size:13px;">
  <tr>
    <td style="padding:6px 8px;color:#666;">Subtotal</td>
    <td style="padding:6px 8px;text-align:right;">KES {{ number_format($subtotal, 2) }}</td>
  </tr>
  @if($discount > 0)
  <tr>
    <td style="padding:6px 8px;color:#666;">Discount</td>
    <td style="padding:6px 8px;text-align:right;color:#2d6a2d;">- KES {{ number_format($discount, 2) }}</td>
  </tr>
  @endif
  @if($delivery > 0)
  <tr>
    <td style="padding:6px 8px;color:#666;">Delivery</td>
    <td style="padding:6px 8px;text-align:right;">KES {{ number_format($delivery, 2) }}</td>
  </tr>
  @endif
  <tr>
    <td style="padding:12px 8px;font-weight:bold;font-size:15px;border-top:2px solid #c5a059;">Total paid</td>
    <td style="padding:12px 8px;text-align:right;font-weight:bold;font-size:15px;border-top:2px solid #c5a059;color:#8b6914;">KES {{ number_format($total, 2) }}</td>
  </tr>
</table>

<p style="margin-top:20px;font-size:12px;color:#666;line-height:1.6;">
  TZEL CAFÉ — Siwaka Plaza, Ole Sangale Road, Madaraka, Nairobi<br>
  {{ config('tzel.phone') }} · {{ config('tzel.email') }}
</p>
