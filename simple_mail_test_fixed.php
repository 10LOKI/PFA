<?php

// Simple test without Laravel bootstrapping
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

// Test PHP mail function first
echo 'Testing PHP mail() function:<br>';
$result = mail('test@example.com', 'Test Subject', 'Test body');
echo 'mail() result: '.($result ? 'Success' : 'Failed').'<br>';

if (! $result) {
    echo 'PHP mail error: '.error_get_last()['message'] ?? 'Unknown error'.'<br>';
}

// Test SMTP connection directly
echo '<br>Testing SMTP connection to Mailtrap:<br>';
$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;
$timeout = 10;

$socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
if (! $socket) {
    echo "Failed to connect to $host:$port - Error: $errstr ($errno)<br>";
} else {
    echo "Successfully connected to $host:$port<br>";
    // Read greeting
    $greeting = fgets($socket, 1024);
    echo 'Server greeting: '.htmlspecialchars($greeting).'<br>';
    fclose($socket);
}

// Test with Symfony Mailer directly (what Laravel uses)
echo '<br>Testing Symfony Mailer directly:<br>';
try {
    $transport = (new EsmtpTransport('sandbox.smtp.mailtrap.io', 2525))
        ->setUsername('a85797584b6560')
        ->setPassword('555ba4957d4f1cb');

    $mailer = new Mailer($transport);

    $email = (new Email)
        ->from('sender@example.com')
        ->to('recipient@example.com')
        ->subject('Test Subject')
        ->text('This is a test email from Symfony Mailer');

    $result = $mailer->send($email);
    echo 'Symfony Mailer result: Email sent successfully<br>';
} catch (Exception $e) {
    echo 'Symfony Mailer error: '.$e->getMessage().'<br>';
    echo 'Trace: '.nl2br($e->getTraceAsString()).'<br>';
}
