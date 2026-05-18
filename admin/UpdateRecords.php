<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['update'])) {
    $record_id = $_POST['Record_ID'];
    $points = (int)$_POST['Points'];
    $duration = (int)$_POST['Duration_sec'];

    if ($points < 0 || $duration < 0) {
        echo "Punkti un ilgums nedrīkst būt negatīvi!";
        exit();
    }

    $query = "UPDATE records SET Points = ?, Duration_sec = ? WHERE Record_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iii", $points, $duration, $record_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: AdminRecords.php?msg=updated");
        exit();
    } else {
        echo "Kļūda atjauninot: " . mysqli_error($con);
    }
} else {
    header("Location: AdminRecords.php");
    exit();
}
?>