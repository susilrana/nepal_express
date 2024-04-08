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

// $is_hospital = isHospital($token);
// $hospital_Id = getUserId($token);

$sql = '';
$sql = "SELECT * from trips";

global $CON;

$result = mysqli_query($CON, $sql);

if ($result) {
    $specialization = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $trip[] = $row;
    }

    echo json_encode([
        "success" => true,
        "message" => "trips fetched successfully!",
        "trip" => $trip
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!"
    ]);
}
