<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>{{ $subject }}</title></head>
<body style="font-family: Arial, sans-serif; color: #2c1810; background: #f8f4ef; padding: 24px;">
  <div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px;">
    <p style="font-size: 11px; letter-spacing: 0.2em; color: #8b6914;">TZEL CAFÉ</p>
    <h1 style="color: #2c1810; font-size: 22px;">{{ $subject }}</h1>
    <div style="line-height: 1.6; color: #444;">{!! nl2br(e($body)) !!}</div>
    <p style="margin-top: 24px; font-size: 12px; color: #666;">Eat. Sip. Connect.</p>
  </div>
</body>
</html>
