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

if (isset($_POST['trip_id'])) {
    $trip_id = $_POST['trip_id'];

    $sql = "SELECT * FROM trips WHERE trip_id = '$trip_id'";
    $result = mysqli_query($CON, $sql);
    $trip = mysqli_fetch_assoc($result);

    if (!$trip) {
        echo json_encode([
            "success" => false,
            "message" => "Trip not found!"
        ]);
        die();
    }

    $isDeleted = $trip["isDeleted"] == 0 ? 1 : 0;

    $sql = "UPDATE trips SET isDeleted = '$isDeleted' WHERE trip_id = '$trip_id'";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        $message = $isDeleted ? "Trip marked as deleted!" : "Trip restored successfully!";
        echo json_encode([
            "success" => true,
            "message" => $message
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to update trip!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "trip_id is required!"
    ]);
}
?>
