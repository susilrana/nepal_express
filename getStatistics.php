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

$token = $_POST['token'];

include "./database/connection.php";
include "./helpers/auth.php";

$is_agency = isAgency($token);

if (!$is_agency) {
    echo json_encode([
        "success" => false,
        "message" => "You are not authorized"
    ]);
    die();
}

$agencyId = getUserId($token);

$isAdmin = isAdmin($token);

$no_of_buses;

$sql = '';

if ($isAdmin) {
    $sql = "select count(*) as totalBuses from buses";
} else {
    $sql = "select count(*) as totalBuses from buses where agency_id='$agencyId'";
}

global $CON;

$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $no_of_buses = $row['totalBuses'];
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!"
    ]);
    die();
}

$totalMonthlyIncome = 0;

$sql = '';

if ($isAdmin) {
    $sql = "select sum(amount) as totalIncome from payments where MONTH(payment_at) = MONTH(CURRENT_DATE()) AND YEAR(payment_at) = YEAR(CURRENT_DATE())";
} else {
    $sql = "select sum(amount) as totalIncome from payments join bookings on payments.booking_id=bookings.booking_id join buses on bookings.bus_id=buses.id where buses.agency_id='$agencyId' AND MONTH(payment_at) = MONTH(CURRENT_DATE()) AND YEAR(payment_at) = YEAR(CURRENT_DATE())";
}

$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalMonthlyIncome = $row['totalIncome'];
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!00"
    ]);
    die();
}

$totalIncome = 0;

$sql = '';

if ($isAdmin) {
    $sql = "select sum(amount) as totalIncome from payments";
} else {
    $sql = "select sum(amount) as totalIncome from payments join bookings on payments.booking_id=bookings.booking_id join buses on bookings.bus_id=buses.id where buses.agency_id='$agencyId'";
}

$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalIncome = $row['totalIncome'];
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!8888"
    ]);
    die();
}

$totalBookings = 0;

$sql = '';

if ($isAdmin) {
    $sql = "select count(*) as totalBookings from bookings where status='paid'";
} else {
    $sql = "select count(*) as totalBookings from bookings join buses on bookings.bus_id=buses.id where buses.agency_id='$agencyId' AND status='paid'";
}

$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalBookings = $row['totalBookings'];
} else {
    echo json_encode([
        "success" => false,
        "message" => "Something went wrong!999"
    ]);
    die();
}

if ($isAdmin) {
    $totalUsers = 0;

    $sql = '';

    if ($isAdmin) {
        $sql = "select count(*) as totalUsers from users where role='user'";
    }

    $result = mysqli_query($CON, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalUsers = $row['totalUsers'];
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Something went wrong!!!"
        ]);
        die();
    }

    echo json_encode([
        "success" => true,
        "message" => "Stats fetched successfully!",
        "statistics" => [
            "no_of_buses" => $no_of_buses,
            "totalIncome" => $totalIncome,
            "totalMonthlyIncome" => $totalMonthlyIncome,
            "totalBookings" => $totalBookings,
            "totalUsers" => $totalUsers
        ]
    ]);
} else {
    echo json_encode([
        "success" => true,
        "message" => "Stats fetched successfully!",
        "statistics" => [
            "no_of_buses" => $no_of_buses,
            "totalIncome" => $totalIncome,
            "totalMonthlyIncome" => $totalMonthlyIncome,
            "totalBookings" => $totalBookings,
        ]
    ]);
}