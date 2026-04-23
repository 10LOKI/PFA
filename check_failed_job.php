<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\DB;

// Get the specific failed job
$job = DB::table('failed_jobs')->where('id', '9607102c-18c7-42a5-b1d2-36b367cefd6c')->first();

if ($job) {
    echo "Failed Job ID: {$job->id}\n";
    echo "Connection: {$job->connection}\n";
    echo "Queue: {$job->queue}\n";
    echo "Payload: {$job->payload}\n";
    echo "Exception: {$job->exception}\n";
    echo "Failed at: {$job->failed_at}\n";
    echo "----------------------------------------\n";

    // Try to decode payload to see what job failed
    $payload = json_decode($job->payload, true);
    if (isset($payload['data']['commandName'])) {
        echo "Command: {$payload['data']['commandName']}\n";
    }
    if (isset($payload['data']['command'])) {
        echo 'Command details: ';
        print_r($payload['data']['command']);
    }
    echo "\n\n";
} else {
    echo "Job not found\n";
}
