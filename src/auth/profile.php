<?php
require_once __DIR__. '/../middleware/auth_middleware.php';

header("Content-Type: application/json");

function getProfile() {
    $user = authenticate(); // Middleware will handle authentication

    http_response_code(200);
    echo json_encode([
        "id" => $user['id'],
        "user_id" => $user['user_id'],
        "email" => $user['email'],
        "created_at" => $user['created_at']
    ]);
}
?>
