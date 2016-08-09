# Yubikey Authentication Library

[![Build Status](https://travis-ci.org/cmuench/yubikey-library.svg?branch=master)](https://travis-ci.org/cmuench/yubikey-library)
[![Code Climate](https://codeclimate.com/github/cmuench/yubikey-library/badges/gpa.svg)](https://codeclimate.com/github/cmuench/yubikey-library)
[![Test Coverage](https://codeclimate.com/github/cmuench/yubikey-library/badges/coverage.svg)](https://codeclimate.com/github/cmuench/yubikey-library/coverage)

Provides a `\CMuench\Yubikey\AuthenticationClient` class which can be used to authenticate against
the yubikey auth servers.

It's possible to add own servers by config.

Example:

```php
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
```
