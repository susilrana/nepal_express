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

if (isset($_POST['seatNumber']) && isset($_POST['bus_id'])) {
    $seatNumber = $_POST['seatNumber'];
    $bus_id = $_POST['bus_id'];

    $sql = "INSERT INTO seats (seatNumber, bus_id) VALUES ('$seatNumber', '$bus_id')";

    $result = mysqli_query($CON, $sql);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "Failed to add new seat!"
        ]);
        die();
    } else {
        echo json_encode([
            "success" => true,
            "message" => "New seat added successfully!"
        ]);
        die();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "seatNumber and bus_id are required!"
    ]);
    die();
}
