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

if (!isAgency($token)) {
    echo json_encode([
        "success" => false,
        "message" => "Not authorized!"
    ]);
    die();
}

global $CON;

if (isset($_POST['bus_id'])) {
    $bus_id = $_POST['bus_id'];

    $sql = "UPDATE seats SET availability = 1 WHERE bus_id = '$bus_id'";

    $result = mysqli_query($CON, $sql);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "Failed to update seat availability!"
        ]);
        die();
    } else {
        echo json_encode([
            "success" => true,
            "message" => "Seat availability updated successfully!"
        ]);
        die();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "bus_id is required!"
    ]);
    die();
}
