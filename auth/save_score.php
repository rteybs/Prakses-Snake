<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

if (!isset($_SESSION['User_ID'])) {
    http_response_code(403);
    echo "Not logged in";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit();
}

$points = isset($_POST['Points']) ? intval($_POST['Points']) : 0;
$duration = isset($_POST['Duration_sec']) ? intval($_POST['Duration_sec']) : 0;

if ($points < 0 || $duration < 0) {
    http_response_code(400);
    echo "Invalid data";
    exit();
}

$userId = $_SESSION['User_ID'];

$stmt = mysqli_prepare($con, "INSERT INTO records (User_ID, Points, Duration_sec) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iii", $userId, $points, $duration);

if (mysqli_stmt_execute($stmt)) {
    echo "Score saved";
} else {
    http_response_code(500);
    echo "Error saving score";
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>