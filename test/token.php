<?php

require '../vendor/autoload.php';

use Izisaurio\Instagram\AccessToken,
    Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
//Ngrok used for testing
$redirect = 'https://###.ngrok-free.app/';

$instagram = new AccessToken($_ENV['INSTAGRAM_ID'], $_ENV['INSTAGRAM_SECRET'], $redirect);

//Hardcoded code
$code = '#authcode#';

$shortToken = $instagram->getToken($code);

var_dump($shortToken);

if (isset($shortToken['error_message'])) {
    var_dump($shortToken['error_message']);
    exit;
}

$longLived = $instagram->getLongLivedToken($shortToken['access_token']);

var_dump($longLived);