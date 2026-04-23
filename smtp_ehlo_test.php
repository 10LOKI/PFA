<?php

$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;
$username = 'a85797584b6560';
$password = '555ba4957d4f1c';

echo 'Testing SMTP EHLO response...<br>';

$socket = @fsockopen($host, $port, $errno, $errstr, 10);
if (! $socket) {
    echo 'FAILED: Could not connect to '.$host.':'.$port.' - '.$errstr.' ('.$errno.')<br>';
} else {
    echo 'Connected to '.$host.':'.$port.'<br>';

    // Read server greeting
    $greeting = fgets($socket, 1024);
    echo 'Server: '.trim($greeting).'<br>';

    // Send EHLO and capture multi-line response
    fwrite($socket, "EHLO localhost\r\n");
    $response = '';
    while ($line = fgets($socket, 1024)) {
        $response .= $line;
        if (substr($line, 3, 1) == ' ') { // Last line of multi-line response
            break;
        }
    }
    echo 'EHLO Response:<br>';
    echo nl2br(htmlspecialchars($response)).'<br>';

    // Send QUIT
    fwrite($socket, "QUIT\r\n");
    $response = fgets($socket, 1024);
    echo 'QUIT: '.trim($response).'<br>';

    fclose($socket);
}
