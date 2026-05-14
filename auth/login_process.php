<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

if(isset($_POST['login'])) {
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    $query = "SELECT * FROM user WHERE Email = ? AND Username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $Email, $Username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)) {
        if(password_verify($Password, $row['Password'])) {
            $_SESSION['User_ID'] = $row['User_ID'];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['Email'] = $row['Email'];
            $_SESSION['is_admin'] = $row['is_admin'];   
            header("Location: ../index.php");
            exit();
        } else {
            echo "Nepareiza parole!";
        }
    } else {
        echo "Lietotājs ar šādu Username un e-pastu nav atrasts!";
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>