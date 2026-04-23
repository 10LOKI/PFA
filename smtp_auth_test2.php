<?php

$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;
$username = 'a85797584b6560';
$password = '555ba4957d4f1c';

echo 'Testing SMTP authentication...<br>';

$socket = @fsockopen($host, $port, $errno, $errstr, 10);
if (! $socket) {
    echo 'FAILED: Could not connect to '.$host.':'.$port.' - '.$errstr.' ('.$errno.')<br>';
} else {
    echo 'Connected to '.$host.':'.$port.'<br>';

    // Read server greeting
    $greeting = fgets($socket, 1024);
    echo 'Server: '.trim($greeting).'<br>';

    // Send EHLO
    fwrite($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 1024);
    echo 'EHLO: '.trim($response).'<br>';

    // Check if AUTH LOGIN is supported
    if (strpos($response, 'AUTH LOGIN') !== false) {
        // Try AUTH LOGIN
        fwrite($socket, "AUTH LOGIN\r\n");
        $response = fgets($socket, 1024);
        echo 'AUTH LOGIN challenge: '.trim($response).'<br>';

        if (trim($response) == '334 VXNlcm5hbWU6') {
            // Send username
            fwrite($socket, base64_encode($username)."\r\n");
            $response = fgets($socket, 1024);
            echo 'Username response: '.trim($response).'<br>';

            if (trim($response) == '334 UGFzc3dvcmQ6') {
                // Send password
                fwrite($socket, base64_encode($password)."\r\n");
                $response = fgets($socket, 1024);
                echo 'Password response: '.trim($response).'<br>';

                if (strpos(trim($response), '235') === 0) {
                    echo 'Authentication SUCCESS!<br>';
                } else {
                    echo 'Authentication FAILED: '.trim($response).'<br>';
                }
            } else {
                echo 'Unexpected response after username: '.trim($response).'<br>';
            }
        } else {
            echo 'Unexpected response to AUTH LOGIN: '.trim($response).'<br>';
        }
    } else {
        echo 'AUTH LOGIN not supported by server<br>';
        echo 'Server capabilities: '.trim($response).'<br>';
    }

    // Send QUIT
    fwrite($socket, "QUIT\r\n");
    $response = fgets($socket, 1024);
    echo 'QUIT: '.trim($response).'<br>';

    fclose($socket);
}
