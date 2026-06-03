<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
mysqli_report(MYSQLI_REPORT_OFF);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $errors = [];
    $Username = test_input($_POST['Username'] ?? '');
    $Email = test_input($_POST['Email'] ?? '');
    $Password = $_POST['Password'] ?? '';
    $ConfirmPassword = $_POST['ConfirmPassword'] ?? '';
    $avatarPath = null;

    if (empty($Username)) {
        $errors['Username'] = "Lietotājvārds ir tukšs";
    } elseif (strlen($Username) < 4) {
        $errors['Username'] = "Lietotājvārdam jābūt vismaz 4 rakstzīmes garam";
    }

    if (empty($Email)) {
        $errors['Email'] = "E-pasts ir tukšs";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = "Nederīgs e-pasta formāts";
    }

    if (empty($Password)) {
        $errors['Password'] = "Parole ir tukša";
    } elseif (strlen($Password) < 4) {
        $errors['Password'] = "Parolei jābūt vismaz 4 rakstzīmes garai";
    }

    if (empty($ConfirmPassword)) {
        $errors['ConfirmPassword'] = "Lūdzu apstipriniet paroli";
    } elseif ($ConfirmPassword !== $Password) {
        $errors['ConfirmPassword'] = "Parolēm jābūt vienādām";
    }

    if (isset($_FILES['Avatar']) && $_FILES['Avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadOk = true;
        $file = $_FILES['Avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['Avatar'] = "Kļūda augšupielādējot attēlu.";
            $uploadOk = false;
        }

        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);

        if (!in_array($mime, $allowedTypes)) {
            $errors['Avatar'] = "Atļauti tikai PNG un JPEG attēli.";
            $uploadOk = false;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            $errors['Avatar'] = "Attēla izmērs nedrīkst pārsniegt 2 MB.";
            $uploadOk = false;
        }

        if ($uploadOk) {
            $uploadDir = __DIR__ . '/../uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $avatarPath = 'uploads/avatars/' . $filename;
            } else {
                $errors['Avatar'] = "Neizdevās saglabāt attēlu.";
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_old'] = ['Username' => $Username, 'Email' => $Email];
        header("Location: ../Register.php");
        exit();
    }

    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (Username, Email, Password, Avatar_url) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $Username, $Email, $hashed_password, $avatarPath);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../Login.php");
        exit();
    } else {
        if (mysqli_errno($con) == 1062) {
            $errors['general'] = "Lietotājvārds vai e-pasts jau eksistē.";
        } else {
            $errors['general'] = "Datubāzes kļūda: " . mysqli_error($con);
        }
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_old'] = ['Username' => $Username, 'Email' => $Email];
        header("Location: ../Register.php");
        exit();
    }
} else {
    header("Location: ../Register.php");
    exit();
}
?>