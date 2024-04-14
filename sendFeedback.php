<?php
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
$user_id = getUserId($token);

if (!isset($_POST['description'])) {
    echo json_encode([
        "success" => false,
        "message" => "Description not provided!"
    ]);
    die();
}

$description = $_POST['description'];

$insertSql = "INSERT INTO feedbacks (user_id, description) VALUES ('$user_id', '$description')";
if (mysqli_query($CON, $insertSql)) {
    echo json_encode([
        "success" => true,
        "message" => "Feedback submitted successfully!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to submit feedback!"
    ]);
}
?>
