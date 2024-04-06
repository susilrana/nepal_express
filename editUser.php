<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the token is present
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


if (!isAdmin($token)) {
    echo json_encode([
        "success" => false,
        "message" => "You are not authorized!"
    ]);
    die();
}


if (
    isset(
        $_POST['user_id'],
        $_POST['email'],
        $_POST['fullname'],
    )
) {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];


    global $CON;

    // Check if the user with the given user_id exists
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            // Update user details
            $sql = "UPDATE users SET email='$email', full_name='$fullname', address='$address' WHERE user_id='$user_id'";

            $update_result = mysqli_query($CON, $sql);

            if ($update_result) {
                echo json_encode([
                    "success" => true,
                    "message" => "User details updated successfully!"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update user details!"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "User not found!"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong while checking user!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "user_id, email, fullname, and role are required fields"
    ]);
}
