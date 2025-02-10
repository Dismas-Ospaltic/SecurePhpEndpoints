<?php
require_once '../config/database.php';
require_once __DIR__. '/../models/User.php';

header("Content-Type: application/json");

function register() {
    global $mysqli;

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(["message" => "Email and password are required"]);
        return;
    }

    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    if (!$email) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid email format"]);
        return;
    }

    $user = new User($mysqli);
    if ($user->createUser($email, $password)) {
        http_response_code(201);
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Registration failed"]);
    }
}
?>
