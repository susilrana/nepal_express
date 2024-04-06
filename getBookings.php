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
$user_Id = getUserId($token);


$sql = '';

if ($is_agency) {

    $sql = "select bookings.*,buses.*,payments.user_id,payments.amount,payments.details from bookings left join payments on bookings.booking_id=payments.booking_id join buses on bookings.bus_id = buses.id where buses.agency_id = $user_Id";
} else {
    $sql = "select bookings.*,buses.*,payments.user_id,payments.amount,payments.details from bookings left join payments on bookings.booking_id=payments.booking_id join buses on bookings.bus_id=buses.id where bookings.user_id = $user_Id";
}

global $CON;

$result = mysqli_query($CON, $sql);

if ($result) {
    $bookings = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }

    echo json_encode([
        "success" => true,
        "message" => "bookings fetched successfully!",
        "bookings" => $bookings
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!"
    ]);
}