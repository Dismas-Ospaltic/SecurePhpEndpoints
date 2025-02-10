<?php
require_once __DIR__ . '/config.php'; // Load config with dotenv

// Load database credentials
$DB_HOST = $_ENV['DB_HOST'] ?? 'localhost';
$DB_USER = $_ENV['DB_USER'] ?? 'root';
$DB_PASS = $_ENV['DB_PASS'] ?? '';
$DB_NAME = $_ENV['DB_NAME'] ?? 'auth_api_project';

// Connect to MySQL
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS);

// Check for connection errors
if ($mysqli->connect_error) {
    die("❌ Database Connection failed: " . $mysqli->connect_error);
}

// Create the database if it doesn’t exist
$mysqli->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME`");

// Select the database
$mysqli->select_db($DB_NAME);
?>
