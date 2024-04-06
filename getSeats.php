<?php

if (!isset($_POST['token']) || !isset($_POST['bus_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Token or Bus ID not found!"
    ]);
    die();
}

include "./database/connection.php";
include "./helpers/auth.php";

$token = $_POST['token'];
$busId = $_POST['bus_id'];

if (!isset($busId)) {
    echo json_encode([
        "success" => false,
        "message" => "Bus ID not found!"
    ]);
    die();
}

global $CON;

$sql = "SELECT * FROM seats WHERE bus_id = ?";
$stmt = mysqli_prepare($CON, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $busId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $seats = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $seats[] = $row;
    }

    echo json_encode([
        "success" => true,
        "message" => "Seats have been fetched successfully!",
        "seats" => $seats
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error preparing statement: " . mysqli_error($CON)
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($CON);
?>
