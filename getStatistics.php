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
        "message" => "Something went wrong!88"
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
$topUsersQuery = "SELECT
    u.full_name AS user_name,
    SUM(p.amount) AS total_amount_paid
FROM
    users u
JOIN
    payments p ON u.user_id = p.user_id
GROUP BY
    u.full_name
ORDER BY
    total_amount_paid DESC
LIMIT 5";

$topUsersResult = mysqli_query($CON, $topUsersQuery);

if ($topUsersResult) {
    $topUsers = [];
    while ($row = mysqli_fetch_assoc($topUsersResult)) {
        $topUsers[] = [
            "user_name" => $row['user_name'],
            "total_amount_paid" => $row['total_amount_paid']
        ];
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching top users data"
    ]);
    die();
}
$busBookingsQuery = "SELECT
    b.name,
    b.id,
    COUNT(*) AS total_bookings
FROM
    buses b
JOIN
    bookings bk ON b.id = bk.bus_id
GROUP BY
    b.id";

$busBookingsResult = mysqli_query($CON, $busBookingsQuery);

if ($busBookingsResult) {
    $busBookings = [];
    while ($row = mysqli_fetch_assoc($busBookingsResult)) {
        $busBookings[] = [
            "name" => $row['name'],
            "id" => $row['id'],
            "total_bookings" => $row['total_bookings']
        ];
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching bus bookings data"
    ]);
    die();
}


$revenueQuery = "SELECT
    MONTHNAME(p.payment_at) AS month_name,
    YEAR(p.payment_at) AS payment_year,
    SUM(p.amount) AS total_income
FROM
    Payments p
WHERE
    p.payment_at >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
GROUP BY
    YEAR(p.payment_at), MONTH(p.payment_at)
ORDER BY
    YEAR(p.payment_at) DESC, MONTH(p.payment_at) DESC";

$revenueResult = mysqli_query($CON, $revenueQuery);

if ($revenueResult) {
    $revenueData = [];
    while ($row = mysqli_fetch_assoc($revenueResult)) {
        $revenueData[] = [
            "month_name" => $row['month_name'],
            "payment_year" => $row['payment_year'],
            "total_income" => $row['total_income']
        ];
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching revenue data"
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
            "totalUsers" => $totalUsers,
            "top_users" => $topUsers,
            "bus_bookings" => $busBookings,
            "revenue_data" => $revenueData
     
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
            "top_users" => $topUsers,
            "bus_bookings" => $busBookings,
            "revenue_data" => $revenueData
        ]
    ]);
}