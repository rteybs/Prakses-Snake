<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrācija</title>
    <link rel="stylesheet" href="../css/AdminStyle.css">
</head>
<body>
<form class="container">
    <div class="NavBox">
        <div>
            <h1>Administrācijas panelis</h1>
            <div class="button-group">
                <?php if(isset($_SESSION['Username'])): ?>
                    <span>Labdien, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</span>
                    <a href="../index.php">Sākumlapa</a>
                    <a href="../MyResults.php">Mani rezultāti</a>
                    <a href="../AllResults.php">Visi rezultāti</a>
                    <a href="../AllUsers.php">Lietotāju saraksts</a>  
                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true): ?>
                        <a href="admin.php">Admin panelis</a>
                    <?php endif; ?>     
                    <a href="../Logout.php">Log out</a> 
                <?php endif; ?>   
            </div>
        </div>
    </div>

    <div class="SnakeBox">
        <h2>Administratora funkcijas</h2>
        <ul>
            <li><a href="AdminUsers.php">Lietotāju pārvaldība</a></li>
            <li><a href="AdminRecords.php">Rekordu pārvaldība</a></li>
        </ul>
    </div>
</form>
</body>
</html>