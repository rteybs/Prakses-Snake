<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Nav norādīts ieraksta ID!";
    exit();
}
$record_id = $_GET['id'];

$query = "SELECT * FROM records WHERE Record_ID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $record_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$record = mysqli_fetch_assoc($result);

if (!$record) {
    echo "Rezultāts nav atrasts!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Rediģēt rezultātu</title>
    <link rel="stylesheet" href="../css/AuthStyle.css">
</head>
<body>
<div class="auth-container">
    <div class="NavBox">
        <div class="nav">
            <h1>Rediģēt rezultātu #<?= $record['Record_ID'] ?></h1>
            <div class="button-group">
                <a href="AdminRecords.php">Atpakaļ uz sarakstu</a>
            </div>
        </div>
    </div>
    <div class="SnakeBox">
        <form action="UpdateRecords.php" method="post">
            <input type="hidden" name="Record_ID" value="<?= $record['Record_ID'] ?>">
            <label>Punkti: 
                <input type="number" name="Points" value="<?= $record['Points'] ?>" required min="0">
            </label><br>
            <label>Ilgums (sekundēs): 
                <input type="number" name="Duration_sec" value="<?= $record['Duration_sec'] ?>" required min="0">
            </label><br>
            <button type="submit" name="update">Saglabāt izmaiņas</button>
        </form>
    </div>
</div>
</body>
</html>