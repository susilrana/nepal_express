<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


if (!isset($_POST['token'])) {
    echo json_encode([
        "success" => false,
        "message" => "Token not found!"
    ]);
    die();
}
include "./database/connection.php";

if (
    isset(
        $_POST['email'],
        $_POST['new_password']
    )
) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    global $CON;

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $encrypted_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_sql = "UPDATE users SET password = '$encrypted_password' WHERE email = '$email'";
            $update_result = mysqli_query($CON, $update_sql);

            if ($update_result) {
                echo json_encode([
                    "success" => true,
                    "message" => "Password updated successfully!"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update password!"
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
            "message" => "Something went wrong!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Email and new password are required fields!"
    ]);
}
?>
