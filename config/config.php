<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Ensure dotenv is loaded

use Dotenv\Dotenv;

// Load .env from the same directory as config.php
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// JWT Configuration
define('SECRET_KEY', $_ENV['SECRET_KEY'] ?? 'default_secret_key');
define('JWT_ISSUER', $_ENV['JWT_ISSUER'] ?? 'default_issuer');
define('JWT_AUDIENCE', $_ENV['JWT_AUDIENCE'] ?? 'default_audience');
define('ACCESS_TOKEN_EXPIRY', $_ENV['ACCESS_TOKEN_EXPIRY'] ?? 3600);
define('REFRESH_TOKEN_EXPIRY', $_ENV['REFRESH_TOKEN_EXPIRY'] ?? 86400);
?>
