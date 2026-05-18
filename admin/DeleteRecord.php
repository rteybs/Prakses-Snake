<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    $query = "DELETE FROM records WHERE Record_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $record_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: AdminRecords.php?msg=deleted");
        exit();
    } else {
        echo "Kļūda dzēšot: " . mysqli_error($con);
    }
} else {
    header("Location: AdminRecords.php");
    exit();
}
?>