<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'pfaproject'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $failedJobs = $capsule::table('failed_jobs')->get();
    echo 'Found '.$failedJobs->count().' failed jobs'.PHP_EOL.PHP_EOL;

    foreach ($failedJobs as $job) {
        echo 'Failed Job ID: '.$job->id.PHP_EOL;
        echo 'Connection: '.$job->connection.PHP_EOL;
        echo 'Queue: '.$job->queue.PHP_EOL;
        echo 'Exception: '.$job->exception.PHP_EOL;
        echo 'Failed at: '.$job->failed_at.PHP_EOL;
        echo '----------------------------------------'.PHP_EOL;

        // Decode payload
        $payload = json_decode($job->payload, true);
        if (isset($payload['data']['commandName'])) {
            echo 'Command: '.$payload['data']['commandName'].PHP_EOL;
        }
        echo PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}
