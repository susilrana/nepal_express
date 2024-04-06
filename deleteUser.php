<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
if (!isset($_POST['token'])) {
    echo json_encode([
        "success" => false,
        "message" => "Token not found!"
    ]);
    die();
}

include "./database/connection.php";
include "./helpers/auth.php";

$token = $_POST['token'];

$is_admin = isAdmin($token);

if (!$is_admin) {
    echo json_encode([
        "success" => false,
        "message" => "You are not authorized"
    ]);
    die();
}

$userId = getUserId($token);

if (!isset($_POST['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User id is required"
    ]);
    die();
}

$user_id = $_POST['user_id'];

global $CON;

$sql = "SELECT * FROM users WHERE user_id='$user_id'";

$result = mysqli_query($CON, $sql);

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    die();
}

$isDeleted = $user["isDeleted"] == 0 ? false : true;

$sql = '';

if ($isDeleted) {
    $sql = "UPDATE users SET isDeleted=0 WHERE user_id='$user_id'";
} else {
    $sql = "UPDATE users SET isDeleted=1 WHERE user_id='$user_id'";
}

$result = mysqli_query($CON, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update"
    ]);
    die();
}

if ($isDeleted) {
    echo json_encode([
        "success" => true,
        "message" => "User restored successfully!"
    ]);
    die();
} else {
    echo json_encode([
        "success" => true,
        "message" => "User deleted successfully!"
    ]);
    die();
}
?>
