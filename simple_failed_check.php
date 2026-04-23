<?php

require __DIR__.'/vendor/autoload.php';

// Use PDO directly to avoid Laravel bootstrapping issues
$host = '127.0.0.1';
$dbname = 'pfaproject';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get failed jobs
    $stmt = $pdo->query('SELECT * FROM failed_jobs');
    $failedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo 'Found '.count($failedJobs).' failed jobs<br><br>';

    foreach ($failedJobs as $job) {
        echo 'Failed Job ID: '.$job['id'].'<br>';
        echo 'Connection: '.$job['connection'].'<br>';
        echo 'Queue: '.$job['queue'].'<br>';
        echo 'Failed at: '.$job['failed_at'].'<br>';
        echo 'Exception: '.htmlspecialchars($job['exception']).'<br>';
        echo '----------------------------------------<br>';

        // Decode payload to see what failed
        $payload = json_decode($job['payload'], true);
        if (isset($payload['data']['commandName'])) {
            echo 'Command: '.$payload['data']['commandName'].'<br>';
        }

        // Show the actual exception message from the payload if available
        if (isset($payload['exception'])) {
            echo 'Exception Object: '.htmlspecialchars($payload['exception']).'<br>';
        }

        echo '<br><br>';
    }
} catch (PDOException $e) {
    echo 'Database connection failed: '.$e->getMessage();
}
