<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Test sending email through Laravel Mail facade
echo 'Testing Laravel Mail facade...<br>';

try {
    Mail::raw('Test message from Laravel Mail facade', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email from Laravel Mail');
    });

    echo 'Email sent successfully via Laravel Mail!<br>';
    echo 'Check your Mailtrap inbox.';
} catch (Exception $e) {
    echo 'Error sending email via Laravel Mail: '.$e->getMessage().'<br>';
    echo 'Trace: '.nl2br($e->getTraceAsString()).'<br>';

    // Log the error
    Log::error('Mail sending failed: '.$e->getMessage(), [
        'trace' => $e->getTraceAsString(),
    ]);
}
