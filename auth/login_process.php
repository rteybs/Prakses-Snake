<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['login'])) {
    $errors = [];
    $Username = test_input($_POST['Username']);
    $Email = test_input($_POST['Email']);
    $Password = $_POST['Password'];

    if (empty($Username)) {
        $errors['Username'] = "Lietotājvārds ir tukšs";
    }
    if (empty($Email)) {
        $errors['Email'] = "E-pasts ir tukšs";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = "Nederīgs e-pasta formāts";
    }
    if (empty($Password)) {
        $errors['Password'] = "Parole ir tukša";
    }

    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_old'] = ['Username' => $Username, 'Email' => $Email];
        header("Location: ../Login.php");
        exit();
    }

    $query = "SELECT User_ID, Username, Email, Password, is_admin, Avatar_url FROM user WHERE Email = ? AND Username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $Email, $Username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($Password, $row['Password'])) {
        $_SESSION['User_ID'] = $row['User_ID'];
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['Email'] = $row['Email'];
        $_SESSION['is_admin'] = (bool)$row['is_admin'];
        $_SESSION['Avatar_url'] = $row['Avatar_url'];
        header("Location: ../index.php");
        exit();
    } else {
        $errors['Password'] = "Nepareiza parole!";
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_old'] = ['Username' => $Username, 'Email' => $Email];
        header("Location: ../Login.php");
        exit();
    }
} else {
    $errors['general'] = "Lietotājs ar šādu lietotājvārdu vai e-pastu nav atrasts!";
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_old'] = ['Username' => $Username, 'Email' => $Email];
    header("Location: ../Login.php");
    exit();
}

}
?>