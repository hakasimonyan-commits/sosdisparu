<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Twilio\Rest\Client;
use Dotenv\Dotenv;

// 1️⃣ Բեռնում ենք .env-ը
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// 2️⃣ Կարդում ենք գաղտնի տվյալները
$sid   = $_ENV['TWILIO_SID'];
$token = $_ENV['TWILIO_TOKEN'];
$from  = $_ENV['TWILIO_FROM'];

// 3️⃣ Տվյալները form-ից
$message = $_POST['finder_message'] ?? '';
$phone   = $_POST['finder_phone'] ?? '';

if ($message === '' || $phone === '') {
    die('Message կամ téléphone չկա');
}

// 4️⃣ Ուղարկում ենք SMS
$client = new Client($sid, $token);

$client->messages->create(
    $phone,
    [
        'from' => $from,
        'body' => $message
    ]
);

// 5️⃣ Պատասխան
echo "SMS envoyé";
