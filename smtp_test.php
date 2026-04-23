<?php

$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;
$username = 'a85797584b6560';
$password = '555ba4957d4f1c';

echo 'Testing connection to Mailtrap SMTP...<br>';
$socket = @fsockopen($host, $port, $errno, $errstr, 10);
if (! $socket) {
    echo 'FAILED: '.$errstr.' ('.$errno.')<br>';
} else {
    echo 'SUCCESS: Connected to '.$host.':'.$port.'<br>';
    // Read server greeting
    $greeting = fgets($socket, 1024);
    echo 'Server: '.trim($greeting).'<br>';
    fclose($socket);
}
