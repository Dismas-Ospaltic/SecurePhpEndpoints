<?php
// require_once '../config/database.php';
require_once __DIR__. '/../models/User.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;

header("Content-Type: application/json");

function login() {
    global $mysqli;

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(["message" => "Email and password are required"]);
        return;
    }

    $user = new User($mysqli);
    $userData = $user->getUserByEmail($data['email']);

    if (!$userData || !password_verify($data['password'], $userData['password'])) {
        http_response_code(401);
        echo json_encode(["message" => "Invalid email or password"]);
        return;
    }

    $issuedAt = time();
    $expiresAt = $issuedAt + 3600; // 1 hour
    $payload = [
        "iat" => $issuedAt,
        "exp" => $expiresAt,
        "user_id" => $userData['user_id']
    ];

    $accessToken = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');
    $refreshToken = bin2hex(random_bytes(32));

    require_once __DIR__ . '/../models/Token.php';

    $tokenModel = new Token($mysqli);
    $tokenModel->storeRefreshToken($userData['user_id'], $refreshToken, date("Y-m-d H:i:s", $issuedAt + 86400)); // 1 day expiry

    http_response_code(200);
    echo json_encode([
        "access_token" => $accessToken,
        "expires_in" => 3600,
        "refresh_token" => $refreshToken
    ]);
}
?>
