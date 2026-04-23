<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

// Test sending email synchronously (not queued)
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

echo 'Testing synchronous email sending...<br>';

try {
    Mail::raw('Test message from Laravel (synchronous)', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email from Laravel (Sync)');
    });

    echo 'Email sent successfully!<br>';
    echo 'Check your Mailtrap inbox.';
} catch (Exception $e) {
    echo 'Error sending email: '.$e->getMessage().'<br>';
    echo 'Trace: '.nl2br($e->getTraceAsString()).'<br>';

    // Log the error
    Log::error('Mail sending failed: '.$e->getMessage(), [
        'trace' => $e->getTraceAsString(),
    ]);
}
