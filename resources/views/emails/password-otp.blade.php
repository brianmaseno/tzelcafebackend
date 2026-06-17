<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>TZEL CAFÉ — Verification code</title>
  </head>
  <body style="margin:0;padding:0;background:#1a120b;color:#f5efe6;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:520px;margin:0 auto;padding:24px;">
      <div style="background:#2c1f14;border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:24px;">
        <p style="margin:0;font-size:11px;letter-spacing:6px;text-transform:uppercase;color:#c5a059;">TZEL CAFÉ</p>
        <h1 style="margin:12px 0 0;font-size:20px;">Your verification code</h1>
        <p style="margin:12px 0 0;font-size:14px;line-height:1.6;color:rgba(245,239,230,0.85);">
          Hi {{ $name }}, use this code to {{ $purpose === 'profile' ? 'update your password' : 'reset your password' }}.
        </p>
        <p style="margin:20px 0 0;font-size:32px;letter-spacing:8px;font-weight:700;color:#d4b06a;text-align:center;">
          {{ $code }}
        </p>
        <p style="margin:16px 0 0;font-size:12px;color:rgba(245,239,230,0.65);text-align:center;">
          Expires in 15 minutes. If you did not request this, you can ignore this email.
        </p>
      </div>
    </div>
  </body>
</html>
