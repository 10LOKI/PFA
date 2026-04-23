<?php

$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;
$timeout = 5;

$fp = fsockopen($host, $port, $errno, $errstr, $timeout);
if (! $fp) {
    echo "Connection failed: $errstr ($errno)\n";
} else {
    echo "Connection successful\n";
    fclose($fp);
}
