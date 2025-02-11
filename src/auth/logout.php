<?php
// require_once '../config/database.php';
// require_once __DIR__. '/../models/Token.php';

// header("Content-Type: application/json");



// function logout() {
//     global $mysqli;

//     $data = json_decode(file_get_contents("php://input"), true);
//     if (!isset($data['refresh_token'])) {
//         http_response_code(400);
//         echo json_encode(["message" => "Refresh token is required"]);
//         return;
//     }

//     $tokenModel = new Token($mysqli);
    
//     if ($tokenModel->revokeToken($data['refresh_token'])) {
//         http_response_code(200);
//         echo json_encode(["message" => "Logged out successfully"]);
//     } else {
//         http_response_code(401);
//         echo json_encode(["message" => "Invalid or expired refresh token"]);
//     }
// }



require_once '../config/database.php';
require_once __DIR__ . '/../models/Token.php';
// require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../../vendor/autoload.php';

header("Content-Type: application/json");

function logout() {
    global $mysqli;

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['refresh_token']) || !isset($data['access_token'])) {
        http_response_code(400);
        echo json_encode(["message" => "Refresh token and access token are required"]);
        return;
    }

    $tokenModel = new Token($mysqli);

    // Revoke the refresh token
    if ($tokenModel->revokeToken($data['refresh_token'])) {
        
        // Blacklist the access token
        $tokenModel->blacklistAccessToken($data['access_token']);

        http_response_code(200);
        echo json_encode(["message" => "Logged out successfully"]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid or expired refresh token"]);
    }
}

?>