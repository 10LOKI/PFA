<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your QR Code for {{ $registration->event->title }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; }
        .content { padding: 20px 0; }
        .qr-container { text-align: center; margin: 30px 0; }
        .footer { font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 20px; }
        .panel { background: #f8f9fa; border-left: 4px solid #333; padding: 15px; margin: 20px 0; }
        h1 { margin: 0 0 20px; font-size: 24px; }
        p { margin: 0 0 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your QR Code for {{ $registration->event->title }}</h1>
        </div>

        <div class="content">
            <p>Hello {{ $registration->user->name }},</p>

            <p>You have successfully registered for the event <strong>{{ $registration->event->title }}</strong>.</p>

            <p><strong>📅 Date & Time:</strong> {{ $registration->event->starts_at->format('d/m/Y H:i') }}</p>
            <p><strong>📍 Location:</strong> {{ $registration->event->city }}, {{ $registration->event->address }}</p>

            <p>Please present the QR code below at the event venue for check-in.</p>

            <div class="qr-container">
                {!! $qrSvg !!}
            </div>

            <p><strong>Important:</strong></p>
            <ul>
                <li>This QR code is unique to you and this event.</li>
                <li>Scan it at the check-in terminal to gain access.</li>
                <li>Keep this email or screenshot the QR code for offline access.</li>
            </ul>

            <p>Thank you for participating!</p>

            <div class="panel">
                <p><strong>Need help?</strong> Contact the event partner or support.</p>
            </div>

            <div class="footer">
                Thanks,<br>
                {{ config('app.name') }}
            </div>
        </div>
    </div>
</body>
</html>
