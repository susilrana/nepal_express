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

if (!isset($_POST['seat_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Seat ID not provided!"
    ]);
    die();
}

$seat_id = $_POST['seat_id'];

$sql = "SELECT * FROM seat_bookings WHERE seat_id = '$seat_id'";
$result = mysqli_query($CON, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Seat is already booked!"
    ]);
    die();
}


$sql = "SELECT * FROM seats WHERE seat_id = '$seat_id' AND availability = 1";
$result = mysqli_query($CON, $sql);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Seat not available!"
    ]);
    die();
}


$updateSql = "UPDATE seats SET availability = 0 WHERE seat_id = '$seat_id'";
if (mysqli_query($CON, $updateSql)) {

    $insertSql = "INSERT INTO seat_bookings (user_id, seat_id) VALUES ('$user_id', '$seat_id')";
    if (mysqli_query($CON, $insertSql)) {
        echo json_encode([
            "success" => true,
            "message" => "Seat booked successfully!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to book seat!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update seat availability!"
    ]);
}
?>
