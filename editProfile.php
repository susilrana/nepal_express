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
$user_Id = getUserId($token);

if (!isset($_POST['email']) || !isset($_POST['fullname']) || !isset($_POST['address'])) {
    echo json_encode([
        "success" => false,
        "message" => "Email, fullname, and address are required fields!"
    ]);
    die();
}

$email = $_POST['email'];
$fullname = $_POST['fullname'];
$address = $_POST['address'];

$sql = "UPDATE users SET email = '$email', full_name = '$fullname', address = '$address' WHERE user_id = $user_Id";

global $CON;

if (mysqli_query($CON, $sql)) {
    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update profile!"
    ]);
}
