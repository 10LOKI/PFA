<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

use Illuminate\Database\Capsule\Manager as DB;

$failedJobs = DB::table('failed_jobs')->get();

foreach ($failedJobs as $job) {
    echo "Failed Job ID: {$job->id}\n";
    echo "Connection: {$job->connection}\n";
    echo "Queue: {$job->queue}\n";
    echo "Exception: {$job->exception}\n";
    echo "Failed at: {$job->failed_at}\n";
    echo "----------------------------------------\n";

    // Decode payload
    $payload = json_decode($job->payload, true);
    if (isset($payload['data']['commandName'])) {
        echo "Command: {$payload['data']['commandName']}\n";
    }

    // Show the actual exception message
    if (isset($payload['exception'])) {
        echo "Exception Object: {$payload['exception']}\n";
    }

    echo "\n\n";
}
