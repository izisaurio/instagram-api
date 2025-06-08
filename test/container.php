<?php

require '../vendor/autoload.php';

use Izisaurio\Instagram\MediaContainer,
    Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$userId = '#userid#';
$longToken = '#longtoken#';

$media = new MediaContainer($userId, $longToken);
$container = $media->container([
    'video_url' => 'https://izisaurio.com/clips/capy.mp4',
    'media_type' => 'REELS',
    'caption' => 'Clip de prueba de mi librería en PHP para conectar y publicar a instagram desde web! Aún no está 100% terminada pero ya pronto'
]);

var_dump($container);