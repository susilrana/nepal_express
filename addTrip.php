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

if (!isAdmin($token)) {
    echo json_encode([
        "success" => false,
        "message" => "You are not authorized!"
    ]);
    die();
}

global $CON;

if (isset($_POST['title'], $_POST['cityFrom'], $_POST['cityTo'])) {
    $title = $_POST['title'];
    $cityFrom = $_POST['cityFrom'];
    $cityTo = $_POST['cityTo'];

    $sql = "SELECT * FROM trips WHERE title = '$title'";

    $result = mysqli_query($CON, $sql);

    $count = mysqli_num_rows($result);

    if ($count > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Trip already exists!"
        ]);
        die();
    }

    $sql = "INSERT INTO trips (title, cityFrom, cityTo) VALUES ('$title', '$cityFrom', '$cityTo')";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        echo json_encode([
            "success" => true,
            "message" => "Trip added successfully!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "title, cityFrom, and cityTo are required!"
    ]);
}
?>
