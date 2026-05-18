<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['update'])) {
    $User_ID = $_POST['User_ID'];
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (!empty($_POST['Password'])) {
        $hashed = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        $query = "UPDATE User SET Username=?, Email=?, Password=?, is_admin=? WHERE User_ID=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sssii", $Username, $Email, $hashed, $is_admin, $User_ID);
    } else {
        $query = "UPDATE User SET Username=?, Email=?, is_admin=? WHERE User_ID=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssii", $Username, $Email, $is_admin, $User_ID);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: AdminUsers.php?msg=updated");
        exit();
    } else {
        echo "Kļūda: " . mysqli_error($con);
    }
}

?>