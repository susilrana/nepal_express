<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the required parameters are present
    if (isset($_POST['token'], $_POST['notification_id'])) {
        $token = $_POST['token'];
        $notification_id = $_POST['notification_id'];
        
        // Verify token or authentication here if needed

        // Include your database connection file
        include "./database/connection.php";

        // Update the 'isClicked' field in the database
        $update_sql = "UPDATE notifications SET isClicked = '1' WHERE notification_id = '$notification_id'";
        $update_result = mysqli_query($CON, $update_sql);

        if ($update_result) {
            echo json_encode([
                "success" => true,
                "message" => "Notification updated successfully!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to update notification!"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Token and notification ID are required!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method!"
    ]);
}
?>
