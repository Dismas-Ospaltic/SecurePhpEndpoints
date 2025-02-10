<?php
require_once __DIR__.'/../../config/config.php';
require __DIR__.'/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function authenticate() {
    global $mysqli;

    // Get Authorization header
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["message" => "Authorization header missing"]);
        exit;
    }

    // Extract the token from "Bearer <token>"
    $matches = [];
    if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        http_response_code(401);
        echo json_encode(["message" => "Invalid token format"]);
        exit;
    }

    $jwt = $matches[1];

    try {
        // Decode token
        $decoded = JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));
        
        // Get user ID from token
        $user_id = $decoded->user_id;

        // Fetch user details
        $userModel = new User($mysqli);
        $user = $userModel->getUserById($user_id);
        
        if (!$user) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid token"]);
            exit;
        }

        return $user;

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized", "error" => $e->getMessage()]);
        exit;
    }
}


?>
