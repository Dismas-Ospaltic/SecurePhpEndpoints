<?php
require_once __DIR__ . '/../auth/register.php';
require_once __DIR__ . '/../auth/login.php';
require_once __DIR__ . '/../auth/refresh.php';
require_once __DIR__ . '/../auth/logout.php';
require_once __DIR__ . '/../auth/profile.php';


header("Content-Type: application/json");

// Get request URI relative to your project
$requestUri = str_replace('/SecurePhpEndPoints', '', strtok($_SERVER['REQUEST_URI'], '?')); 
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestUri) {
    case '/':
        if ($requestMethod === 'GET') {
            http_response_code(200);
            echo json_encode(["message" => "API is healthy and running"]);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/register':
        if ($requestMethod === 'POST') {
            register();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/login':
        if ($requestMethod === 'POST') {
            login();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/refresh':
        if ($requestMethod === 'POST') {
            refreshToken();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/logout':
        if ($requestMethod === 'POST') {
            logout();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;


        case '/api/profile':
            if ($requestMethod === 'GET') {
                getProfile();
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
            }
            break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Endpoint Not Found"]);
        break;
}
?>
