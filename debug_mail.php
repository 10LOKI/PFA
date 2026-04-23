<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Test Mailtrap connection
echo 'Testing Mailtrap connection...<br>';

try {
    Mail::raw('Test message from Laravel', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email from Laravel');
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
