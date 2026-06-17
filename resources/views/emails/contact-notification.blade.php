<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Contact message</title></head>
<body style="font-family: Arial, sans-serif; color: #2c1810; background: #f8f4ef; padding: 24px;">
  <div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px;">
    <h1 style="color: #8b6914; font-size: 20px;">New contact message</h1>
    <p><strong>From:</strong> {{ $contact->name }} ({{ $contact->email }})</p>
    @if($contact->phone)
      <p><strong>Phone:</strong> {{ $contact->phone }}</p>
    @endif
    @if($contact->subject)
      <p><strong>Subject:</strong> {{ $contact->subject }}</p>
    @endif
    <p style="margin-top: 16px; white-space: pre-wrap;">{{ $contact->message }}</p>
    <p style="margin-top: 24px; font-size: 12px; color: #666;">View in admin: Contact Messages</p>
  </div>
</body>
</html>
