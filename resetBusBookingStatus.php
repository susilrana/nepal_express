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

if (isset($_POST['bus_id'])) {
    $bus_id = $_POST['bus_id'];

    // Check the current value of isBookable
    $checkSql = "SELECT isBookable FROM buses WHERE id = '$bus_id'";
    $checkResult = mysqli_query($CON, $checkSql);

    if ($checkResult) {
        $row = mysqli_fetch_assoc($checkResult);
        $isBookable = $row['isBookable'];

        // Toggle the value of isBookable
        $newIsBookable = $isBookable == 1 ? 0 : 1;

        $updateSql = "UPDATE buses SET isBookable = '$newIsBookable' WHERE id = '$bus_id'";
        $updateResult = mysqli_query($CON, $updateSql);

        if (!$updateResult) {
            echo json_encode([
                "success" => false,
                "message" => "Failed to update bus bookability!"
            ]);
            die();
        } else {
            echo json_encode([
                "success" => true,
                "message" => "Bus bookability updated successfully!"
            ]);
            die();
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to fetch bus data!"
        ]);
        die();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "bus_id is required!"
    ]);
    die();
}

