<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? '0';
$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'dev';
$_SERVER['JWT_SECRET_KEY'] = $_SERVER['JWT_SECRET_KEY'] ?? '%kernel.project_dir%/config/jwt/private.pem';
$_SERVER['JWT_PUBLIC_KEY'] = $_SERVER['JWT_PUBLIC_KEY'] ?? '%kernel.project_dir%/config/jwt/public.pem';

if (file_exists(dirname(__DIR__).'/.env.test.local') && file_exists(dirname(__DIR__).'/.env')) {
    if ('Test' === $_SERVER['HTTP_USER_AGENT']) {
        (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test.local');
    } else {
        (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
    }
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
