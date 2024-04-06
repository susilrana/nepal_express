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

$sql = '';
$sql = "SELECT user_id, full_name, email, role, address FROM users where user_id = $user_Id";

global $CON;

$result = mysqli_query($CON, $sql);

if ($result) {
    $user = mysqli_fetch_assoc($result);

    while ($row = mysqli_fetch_assoc($result)) {
        $myDetails[] = $row;
    }

    echo json_encode([
        "success" => true,
        "message" => "User details fetched successfully!",
        "user" => $user
    ]);

} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!"
    ]);
}
