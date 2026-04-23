<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/test-mail', function () {
    try {
        Mail::raw('Test message', function ($message) {
            $message->to('test@example.com')
                ->subject('Test Email');
        });

        return 'Mail sent successfully!';
    } catch (Exception $e) {
        return 'Error sending mail: '.$e->getMessage();
    }
});
