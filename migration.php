<?php


error_reporting(E_ALL ^ (E_DEPRECATED));

require_once __DIR__ . '/vendor/autoload.php';

// here we can load the .env files

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

$config = [
    'db' => [
        // this is imported from the .env file
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(__DIR__, $config);

$app->db->applyMigrations();