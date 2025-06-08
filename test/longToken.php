<?php

require '../vendor/autoload.php';

use Izisaurio\Instagram\AccessToken,
    Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
//Ngrok used for testing
$redirect = 'https://###.ngrok-free.app/';

$instagram = new AccessToken($_ENV['INSTAGRAM_ID'], $_ENV['INSTAGRAM_SECRET'], $redirect);

$accessToken = '#accesstoken#';

$longLived = $instagram->getLongLivedToken($accessToken);

var_dump($longLived);