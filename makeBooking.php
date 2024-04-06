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
include "./helpers/notify.php";

$token = $_POST['token'];
$user_id = getUserId($token);
$remarks = '';

if (
    isset(
        $_POST['bus_id'],
        $_POST['date'],
    )
) {
    $bus_id = $_POST['bus_id'];
    $date = $_POST['date'];
    if (isset($_POST['remarks'])) {
        $remarks = $_POST['remarks'];
    }
    $sql = "insert into bookings (user_id,bus_id,date,remarks) values ('$user_id','$bus_id','$date','$remarks')";
    global $CON;
    $result = mysqli_query($CON, $sql);
    $booking_id = mysqli_insert_id($CON);
    $sql = "select * from buses where id='$bus_id'";

    $result = mysqli_query($CON, $sql);

    $bus = mysqli_fetch_assoc($result);

    $bus_name = $bus['name'];
    $agency_id = $bus['agency_id'];

    $title = "Booking made with $bus_name";
    $description = "Your booking for $bus_name has been made successfully!";
    $user_id = getUserId($token);

    sendNotification($title, $description, $user_id);
    sendNotification($title, $description, $agency_id);
    if ($result) {
        echo json_encode([
            "success" => true,
            "message" => "Booking made successfully!",
            "booking_id" => $booking_id
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "bus_id and date are required!"
    ]);
}