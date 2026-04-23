<?php

$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;

// Test both possible passwords
$tests = [
    ['username' => 'a85797584b6560', 'password' => '555ba4957d4f1cb', 'label' => 'Original from .env'],
    ['username' => 'a85797584b6560', 'password' => '555ba4957d4f1c', 'label' => 'From user message'],
];

foreach ($tests as $test) {
    echo "<h3>Testing with {$test['label']}</h3>";
    echo "Username: {$test['username']}<br>";
    echo "Password: {$test['password']}<br>";

    $socket = @fsockopen($host, $port, $errno, $errstr, 10);
    if (! $socket) {
        echo 'FAILED: Could not connect to '.$host.':'.$port.' - '.$errstr.' ('.$errno.')<br><br>';

        continue;
    }

    // Read server greeting
    $greeting = fgets($socket, 1024);

    // Send EHLO
    fwrite($socket, "EHLO localhost\r\n");
    $response = '';
    while ($line = fgets($socket, 1024)) {
        $response .= $line;
        if (substr($line, 3, 1) == ' ') { // Last line of multi-line response
            break;
        }
    }

    // Try AUTH LOGIN
    fwrite($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 1024);
    if (trim($response) != '334 VXNlcm5hbWU6') {
        echo 'FAILED: Unexpected response to AUTH LOGIN: '.trim($response).'<br>';
        fwrite($socket, "QUIT\r\n");
        fgets($socket, 1024);
        fclose($socket);
        echo '<br>';

        continue;
    }

    // Send username
    fwrite($socket, base64_encode($test['username'])."\r\n");
    $response = fgets($socket, 1024);
    if (trim($response) != '334 UGFzc3dvcmQ6') {
        echo 'FAILED: Unexpected response after username: '.trim($response).'<br>';
        fwrite($socket, "QUIT\r\n");
        fgets($socket, 1024);
        fclose($socket);
        echo '<br>';

        continue;
    }

    // Send password
    fwrite($socket, base64_encode($test['password'])."\r\n");
    $response = fgets($socket, 1024);
    $response = trim($response);

    if (strpos($response, '235') === 0) {
        echo 'SUCCESS: Authentication successful!<br>';
    } else {
        echo 'FAILED: Authentication failed. Response: '.$response.'<br>';
    }

    // Send QUIT
    fwrite($socket, "QUIT\r\n");
    fgets($socket, 1024);
    fclose($socket);
    echo '<br>';
}
