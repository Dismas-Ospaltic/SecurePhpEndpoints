<?php
require_once '../config/database.php';
require_once __DIR__. '/../models/Token.php';

header("Content-Type: application/json");

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
//         http_response_code(500);
//         echo json_encode(["message" => "Failed to revoke token"]);
//     }
// }

function logout() {
    global $mysqli;

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['refresh_token'])) {
        http_response_code(400);
        echo json_encode(["message" => "Refresh token is required"]);
        return;
    }

    $tokenModel = new Token($mysqli);
    
    if ($tokenModel->revokeToken($data['refresh_token'])) {
        http_response_code(200);
        echo json_encode(["message" => "Logged out successfully"]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid or expired refresh token"]);
    }
}


?>
