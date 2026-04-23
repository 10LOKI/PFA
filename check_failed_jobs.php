<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Get failed jobs
$failedJobs = DB::table('failed_jobs')->get();

foreach ($failedJobs as $job) {
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
}
