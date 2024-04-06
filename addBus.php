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

if (isset($_POST['name'], $_POST['fair'], $_POST['trip_id'], $_FILES['avatar'], $_POST['years_used'],)) {

    $name = $_POST['name'];
    $fair = $_POST['fair']; 
    $trip_id = $_POST['trip_id'];
    $avatar = $_FILES['avatar'];
    $years_used = $_POST['years_used'];
    $avatar_name = $avatar['name'];
    $avatar_tmp_name = $avatar['tmp_name'];
    $avatar_size = $avatar['size'];
    $agency_Id = getUserId($token);

    $ext = pathinfo($avatar_name, PATHINFO_EXTENSION);

    if ($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "webp") {
        echo json_encode([
            "success" => false,
            "message" => "Only image files are allowed!"
        ]);
        die();
    }

    if ($avatar_size > 1000000) {
        echo json_encode([
            "success" => false,
            "message" => "Image size should be less than 1MB!"
        ]);
        die();
    }

    $avatar_name = uniqid() . "." . $ext;

    if (!move_uploaded_file($avatar_tmp_name, "./images/" . $avatar_name)) {
        echo json_encode([
            "success" => false,
            "message" => "Image upload failed!"
        ]);
        die();
    }





    $sql = "INSERT INTO buses (name, fair, agency_id, trip_id, avatar, years_used) VALUES ('$name', '$fair', '$agency_Id', '$trip_id', 'images/$avatar_name', '$years_used')";

    $result = mysqli_query($CON, $sql);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "Bus not added!"
        ]);
        die();
    } else {
        echo json_encode([
            "success" => true,
            "message" => "Bus added successfully!"
        ]);
        die();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "name, fair, agency_id, trip_id, avatar, years_used are required!"
    ]);
    die();
}