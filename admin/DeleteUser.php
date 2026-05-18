<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {           
    $User_ID = $_GET['id'];

    if ($User_ID == $_SESSION['User_ID']) {
        echo "Nevar dzēst savu kontu!";
        exit();
    }

    $delRecords = mysqli_prepare($con, "DELETE FROM records WHERE User_ID = ?");
    mysqli_stmt_bind_param($delRecords, "i", $User_ID);
    mysqli_stmt_execute($delRecords);
    mysqli_stmt_close($delRecords);

    $query = "DELETE FROM User WHERE User_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $User_ID);  
    if (mysqli_stmt_execute($stmt)) {
        header("Location: AdminUsers.php?msg=deleted");
        exit();
    } else {
        echo "Kļūda dzēšot: " . mysqli_error($con);
    }
} else {
    header("Location: AdminUsers.php");
    exit();
}
?>