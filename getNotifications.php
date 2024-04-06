<?php
if (!isset($_POST['token'])) {
    echo json_encode([
        "success" => false,
        "message" => "Token not found!"
    ]);
    die();
}

$token = $_POST['token'];

include "./database/connection.php";
include "./helpers/auth.php";

$user_id = getUserId($token);

$sql = "select * from notifications where user_id='$user_id' order by notification_id desc";

global $CON;

$result = mysqli_query($CON, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Could not fetch Notifications!"
    ]);
    die();
}

$notifications = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

echo json_encode([
    "success" => true,
    "message" => "Notifications fetched successfully!",
    "notifications" => $notifications
]);