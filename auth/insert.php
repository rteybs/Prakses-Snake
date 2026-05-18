<?php 
require_once __DIR__ . '/../includes/connection.php';

if(isset($_POST['submit'])) {
    $errors = [];
    
    if(empty($_POST['Username']) || empty($_POST['Email']) || empty($_POST['Password'])) {
        $errors[] = 'Lūdzu aizpildiet visus laukus!';
    }
    
    $Username = trim($_POST['Username']);
    $Email = trim($_POST['Email']);
    $Password = $_POST['Password'];
    $avatarPath = null;
    
    if(isset($_FILES['Avatar']) && $_FILES['Avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadOk = true;
        $file = $_FILES['Avatar'];
        
        if($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Kļūda augšupielādējot attēlu.';
            $uploadOk = false;
        }
        
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        
        if(!in_array($mime, $allowedTypes)) {
            $errors[] = 'Atļauti tikai PNG un JPEG attēli.';
            $uploadOk = false;
        }
        
        if($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Attēla izmērs nedrīkst pārsniegt 2 MB.';
            $uploadOk = false;
        }
        
        if($uploadOk) {
            $uploadDir = __DIR__ . '/../uploads/avatars/';
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $destination = $uploadDir . $filename;
            
            if(move_uploaded_file($file['tmp_name'], $destination)) {
                $avatarPath = 'uploads/avatars/' . $filename; // relative to project root
            } else {
                $errors[] = 'Neizdevās saglabāt attēlu.';
            }
        }
    }
    
    if(!empty($errors)) {
        echo '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
        echo '<a href="../Register.php">Atpakaļ</a>';
        exit();
    }
    
    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (Username, Email, Password, Avatar_url) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $Username, $Email, $hashed_password, $avatarPath);
    
    if(mysqli_stmt_execute($stmt)) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Kļūda: " . mysqli_error($con);
    }
    
    mysqli_stmt_close($stmt);
} else {
    header("Location: ../Register.php");
    exit();
}
?>