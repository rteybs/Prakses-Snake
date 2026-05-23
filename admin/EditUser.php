<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Nav norādīts lietotāja ID!";
    exit();
}
$User_ID = $_GET['id'];

$query = "SELECT * FROM User WHERE User_ID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $User_ID);   
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo "Lietotājs nav atrasts!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Rediģēt lietotāju</title>
    <link rel="stylesheet" href="../css/AuthStyle.css">
</head>
<body>
<div class="auth-container">
    <div class="NavBox">
        <div class="nav">
            <h1>Rediģēt lietotāju #<?= $user['User_ID'] ?></h1>
            <div class="button-group">
                <a href="AdminUsers.php">Atpakaļ</a>
            </div>
        </div>
    </div>
    <div class="SnakeBox">
        <form action="UpdateUsers.php" method="post">
            <input type="hidden" name="User_ID" value="<?= $user['User_ID'] ?>">
            <label>Lietotājvārds: <input type="text" name="Username" value="<?= htmlspecialchars($user['Username']) ?>"></label><br>
            <label>E-pasts: <input type="email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>"></label><br>
            <label>Jauna parole (atstāt tukšu, lai nemainītu): <input type="password" name="Password"></label><br>
            <label>Ir administrators: <input type="checkbox" name="is_admin" value="1" <?= $user['is_admin'] ? 'checked' : '' ?>></label><br>
            <button type="submit" name="update">Saglabāt</button>
        </form>
    </div>
</div>
</body>
</html>