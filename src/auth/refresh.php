<?php
require_once '../config/database.php';
require_once __DIR__. '/../models/Token.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;

header("Content-Type: application/json");

// function refreshToken() {
//     global $mysqli;

//     $data = json_decode(file_get_contents("php://input"), true);
//     if (!isset($data['refresh_token'])) {
//         http_response_code(400);
//         echo json_encode(["message" => "Refresh token is required"]);
//         return;
//     }

//     $tokenModel = new Token($mysqli);
//     $tokenData = $tokenModel->getRefreshToken($data['refresh_token']);

//     if (!$tokenData) {
//         http_response_code(401);
//         echo json_encode(["message" => "Invalid or expired refresh token"]);
//         return;
//     }

//     $issuedAt = time();
//     $expiresAt = $issuedAt + 3600; // 1 hour
//     $payload = [
//         "iat" => $issuedAt,
//         "exp" => $expiresAt,
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

    // Check if the refresh token has expired
    $currentTime = time();
    $expiresAt = strtotime($tokenData['expires_at']); // Assuming 'expires_at' is stored as a datetime string

    if ($currentTime > $expiresAt) {
        http_response_code(401);
        echo json_encode(["message" => "Refresh token has expired"]);
        return;
    }

    // If valid, create a new access token
    $issuedAt = $currentTime;
    $expiresAt = $issuedAt + 3600; // 1 hour expiry for new access token
    $payload = [
        "iat" => $issuedAt,
        "exp" => $expiresAt,
        "user_id" => $tokenData['user_id']
    ];

    $newAccessToken = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');

    http_response_code(200);
    echo json_encode([
        "access_token" => $newAccessToken,
        "expires_in" => 3600
    ]);
}

?>
