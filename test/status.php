<?php

require '../vendor/autoload.php';

use Izisaurio\Instagram\MediaContainer,
    Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$userId = '#userid#';
$longToken = '#longtoken#';

$media = new MediaContainer($userId, $longToken);

$containerId = '#containerid#';

$status = $media->getStatus($containerId);

var_dump($status);