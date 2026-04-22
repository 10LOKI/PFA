<x-mail::message>
# Your QR Code for {{ $registration->event->title }}

Hello {{ $registration->user->name }},

You have successfully registered for the event **{{ $registration->event->title }}**.

📅 **Date & Time:** {{ $registration->event->starts_at->format('d/m/Y H:i') }}
📍 **Location:** {{ $registration->event->city }}, {{ $registration->event->address }}

Please present the QR code below at the event venue for check-in.

<div style="text-align: center; margin: 20px 0;">
    {!! $qrSvg !!}
</div>

**Important:**
- This QR code is unique to you and this event.
- Scan it at the check-in terminal to gain access.
- Keep this email or screenshot the QR code for offline access.

Thank you for participating!

<x-mail::panel>
Need help? Contact the event partner or support.
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
