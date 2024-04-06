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

if (!$is_agency) {
    echo json_encode([
        "success" => false,
        "message" => "You are not authorized"
    ]);
    die();
}





$agencyId = getUserId($token);


if (!isset($_POST['bus_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Bus id is required"
    ]);
    die();
}

$bus_id = $_POST['bus_id'];

global $CON;

$sql = "select * from buses where id='$bus_id'";

$result = mysqli_query($CON, $sql);

$bus = mysqli_fetch_assoc($result);

if (!$bus) {
    echo json_encode([
        "success" => false,
        "message" => "bus not found"
    ]);
    die();
}

if ($agencyId != $bus['agency_id']) {
    echo json_encode([
        "success" => false,
        "message" => "Your agency is not authorized"
    ]);
    die();
}

$isDeleted = $bus["isDeleted"] == 0 ? false : true;

$sql = '';

if ($isDeleted) {
    $sql = "update buses set isDeleted=0 where id='$bus_id'";
} else {
    $sql = "update buses set isDeleted=1 where id='$bus_id'";
}

$result = mysqli_query($CON, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update"
    ]);
    die();
}

if ($isDeleted) {
    echo json_encode([
        "success" => true,
        "message" => "Bus restored successfully!"
    ]);
    die();
} else {
    echo json_encode([
        "success" => true,
        "message" => "Bus deleted successfully!"
    ]);
    die();
}