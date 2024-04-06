<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Only GET method is allowed"
    ]);
    die();
}

// Check if token is provided
if (!isset($_GET['token'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "success" => false,
        "message" => "Token not found!"
    ]);
    die();
}

include "./database/connection.php";
include "./helpers/auth.php";

$token = $_GET['token'];

// Check if user is admin
if (!isAdmin($token)) {
    http_response_code(403); // Forbidden
    echo json_encode([
        "success" => false,
        "message" => "You are not authorized!"
    ]);
    die();
}

global $CON;

$sql = "SELECT * FROM users";

$result = mysqli_query($CON, $sql);

if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    echo json_encode([
        "success" => true,
        "users" => $users
    ]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch users"
    ]);
}
