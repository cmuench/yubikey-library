<?php

require_once 'vendor/autoload.php';

// with Guzzle
// composer require "php-http/guzzle5-adapter"

$otp = '<otp>'; // Onetime password
$yubikey = '<yubikey>'; // Yubikey of the user
$clientId = '<client_id>'; // Client-ID of the application
$clientSecret = '<api_secret>'; // API-Secret of the application

$apiConfiguration = new \CMuench\Yubikey\Value\ApiConfiguration($clientId, $clientSecret);

$httpClient = new \Http\Adapter\Guzzle5();
$requestFactory = new \Http\Message\MessageFactory\GuzzleMessageFactory();

$client = new \CMuench\Yubikey\AuthenticationClient($apiConfiguration, $httpClient, $requestFactory);

$isValid = $client->verify($otp, $yubikey);