<?php

require '../vendor/autoload.php';

use Izisaurio\Instagram\Authorization,
    Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
//Ngrok used for testing
$redirect = 'https://###.ngrok-free.app/';

$instagram = new Authorization($_ENV['INSTAGRAM_ID'], $redirect, [
    'instagram_business_basic',
    'instagram_business_content_publish'
]);

$url = $instagram->getAuthorizationUrl();

?>


<a href="<?= $url ?>">Connect to instagram</a>