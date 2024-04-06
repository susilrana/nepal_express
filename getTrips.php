<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
if (!isset($_GET['token'])) {
    echo json_encode([
        "success" => false,
        "message" => "Token not found!"
    ]);
    die();
}
include "./database/connection.php";
include "./helpers/auth.php";

$token = $_GET['token'];

// $is_hospital = isHospital($token);
// $hospital_Id = getUserId($token);

$sql = '';
$sql = "SELECT * from trips where isDeleted = 0";

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
