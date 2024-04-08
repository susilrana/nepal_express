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

$is_agency = isAgency($token);

$agency_Id = getUserId($token);

$sql = '';

if ($is_agency) {
    $sql = "SELECT buses.*,trips.*,users.full_name as agency_name, users.email as agency_email, users.address as agency_address FROM buses join users on buses.agency_id = users.user_id join trips on buses.trip_id = trips.trip_id where agency_id = $agency_Id";
} else {
    $sql = "SELECT buses.*, trips.*, users.full_name AS agency_name, users.email AS agency_email, users.address AS agency_address
    FROM buses
    JOIN users ON buses.agency_id = users.user_id
    JOIN trips ON buses.trip_id = trips.trip_id
    WHERE buses.isDeleted = 0
    LIMIT 0, 25;
    ";

}

global $CON;

$result = mysqli_query($CON, $sql);

if ($result) {
    $buses = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $buses[] = $row;
    }

    echo json_encode([
        "success" => true,
        "message" => "Buses have been fetched successfully!",
        "buses" => $buses
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!"
    ]);
}