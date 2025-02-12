<?php

require_once __DIR__ . '/../models/Token.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json");

// function refreshToken() {
//     global $mysqli;

//     $headers = getallheaders();
    
//     // Stop script if Authorization header is missing
//     if (!isset($headers['Authorization']) || !preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
//         http_response_code(400);
//         echo json_encode(["message" => "Authorization header with Bearer token is required"]);
//         exit;
//     }

//     $oldAccessToken = $matches[1];

//     $data = json_decode(file_get_contents("php://input"), true);

//     if (!isset($data['refresh_token'])) {
//         http_response_code(400);
//         echo json_encode(["message" => "Refresh token is required"]);
//         exit;
//     }

//     $tokenModel = new Token($mysqli);
//     $tokenData = $tokenModel->getRefreshToken($data['refresh_token']);

//     if (!$tokenData) {
//         http_response_code(401);
//         echo json_encode(["message" => "Invalid refresh token"]);
//         exit;
//     }

//     // Check if the refresh token has expired
//     $currentTime = time();
//     $expiresAt = strtotime($tokenData['expires_at']); // Assuming 'expires_at' is stored as a datetime string

//     if ($currentTime > $expiresAt) {
//         http_response_code(401);
//         echo json_encode(["message" => "Refresh token has expired"]);
//         exit;
//     }

//     // Blacklist the old access token
//     $tokenModel->blacklistAccessToken($oldAccessToken);

//     // Generate new access token
//     $issuedAt = $currentTime;
//     $newExpiresAt = $issuedAt + 3600; // 1 hour expiry for new access token
//     $payload = [
//         "iat" => $issuedAt,
//         "exp" => $newExpiresAt,
//         "user_id" => $tokenData['user_id']
//     ];

//     $newAccessToken = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');

//     http_response_code(200);
//     echo json_encode([
//         "access_token" => $newAccessToken,
//         "expires_in" => 3600
//     ]);
// }


function refreshToken() {
    global $mysqli;

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['refresh_token'])) {
        http_response_code(400);
        echo json_encode(["message" => "Refresh token is required"]);
        return;
    }

    $tokenModel = new Token($mysqli);
    $tokenData = $tokenModel->getRefreshToken($data['refresh_token']);

    if (!$tokenData) {
        http_response_code(401);
        echo json_encode(["message" => "Invalid refresh token"]);
        return;
    }

    // Check if refresh token is expired
    if (time() > strtotime($tokenData['expires_at'])) {
        http_response_code(401);
        echo json_encode(["message" => "Refresh token has expired"]);
        return;
    }

    // Revoke the old refresh token (prevent reuse)
    $tokenModel->revokeToken($data['refresh_token']);

    // Generate a new access token
    $newAccessToken = JWT::encode([
        "iat" => time(),
        "exp" => time() + 3600,
        "user_id" => $tokenData['user_id']
    ], $_ENV['SECRET_KEY'], 'HS256');

    // Generate a new refresh token
    $newRefreshToken = bin2hex(random_bytes(32)); // Secure random token
    $newRefreshTokenExpiry = date('Y-m-d H:i:s', time() + 604800); // 7 days

    // Store the new refresh token in DB
    $tokenModel->storeRefreshToken($tokenData['user_id'], $newRefreshToken, $newRefreshTokenExpiry);

    http_response_code(200);
    echo json_encode([
        "access_token" => $newAccessToken,
        "refresh_token" => $newRefreshToken,
        "expires_in" => 3600
    ]);
}

?>
