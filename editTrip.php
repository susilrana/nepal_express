<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the token is present
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

if (
    isset(
        $_POST['trip_id'],
        $_POST['title'],
        $_POST['cityFrom'],
        $_POST['cityTo']
    )
) {
    $trip_id = $_POST['trip_id'];
    $title = $_POST['title'];
    $cityFrom = $_POST['cityFrom'];
    $cityTo = $_POST['cityTo'];

    global $CON;

    // Check if the trip with the given trip_id exists
    $sql = "SELECT * FROM trips WHERE trip_id = '$trip_id'";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            // Update trip details
            $sql = "UPDATE trips SET title='$title', cityFrom='$cityFrom', cityTo='$cityTo' WHERE trip_id='$trip_id'";

            $update_result = mysqli_query($CON, $sql);

            if ($update_result) {
                echo json_encode([
                    "success" => true,
                    "message" => "Trip details updated successfully!"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update trip details!"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Trip not found!"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong while checking trip!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "trip_id, title, cityFrom, and cityTo are required fields"
    ]);
}
?>
