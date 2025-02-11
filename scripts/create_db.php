<?php
require_once '../config/database.php';

// Create `users` table
$usersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
     user_id VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$mysqli->query($usersTable)) {
    die("Error creating users table: " . $mysqli->error);
}

// Create `refresh_tokens` table
$refreshTokensTable = "CREATE TABLE IF NOT EXISTS refresh_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    revoked_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";

if (!$mysqli->query($refreshTokensTable)) {
    die("Error creating refresh_tokens table: " . $mysqli->error);
}

//create blacklist table for revoked token
$blacklistTabble = "CREATE TABLE revoked_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(512) NOT NULL,
    revoked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";


if(!$mysqli -> query($blacklistTabble)){
    die("Error creating blacklist table table: " . $mysqli->error); 
}


echo "Database and tables created successfully.";

$mysqli->close();
?>
