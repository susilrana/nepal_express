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
$user_id = getUserId($token);


if (isset($_POST['seat_booking_id'], $_POST['remarks'])) {


    global $CON;
    $seat_booking_id = $_POST['seat_booking_id'];
    $remarks = $_POST['remarks'];


    $sql = "select * from seat_payments where seat_booking_id = $seat_booking_id";

    $result = mysqli_query($CON, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Payment already made, thank you!"
        ]);
        die();
    }


    $sql = "insert into seat_payments (user_id,seat_booking_id,remarks) values ('$user_id','$seat_booking_id','$remarks')";

    $result = mysqli_query($CON, $sql);

    $sql = "update seat_bookings set status = 'paid' where seat_booking_id = $seat_booking_id";
    $result = mysqli_query($CON, $sql);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong"
        ]);
    } else {
        echo json_encode([
            "success" => true,
            "message" => "Payment made successfully!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "seat_booking_Id and remarks are required!"
    ]);
    die();
}