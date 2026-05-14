<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

$query = "SELECT records.Points, records.Duration_sec, records.Played_at, user.Username 
          FROM records 
          JOIN user ON records.User_ID = user.User_ID
          ORDER BY records.Played_at DESC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$records = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi rezultāti</title>
    <link rel="stylesheet" href="css/MainStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <h3>Visi lietotāju rezultāti</h3>
        <div class="button-group">
            <?php if(isset($_SESSION['Username'])): ?>
                    <span>Labdien, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</span>
                    <a href="index.php">Sākumlapa</a>
                    <a href="MyResults.php">Mani rezultāti</a>
                    <a href="AllResults.php">Visi rezultāti</a>
                    <a href="AllUsers.php">Lietotāju saraksts</a>  
                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true): ?>
                        <a href="admin/admin.php">Admin panelis</a>
                    <?php endif; ?>     
                    <a href="Logout.php">Log out</a> 
                <?php else: ?>
                    <a href="index.php">Sākumlapa</a>
                    <a href="Register.php">sign in</a>
                    <a href="Login.php">Log in</a>
                <?php endif; ?>
        </div>
    </div>

    <div class="SnakeBox">
        <?php if (count($records) > 0): ?>
            <table>
                    <tr>
                        <th>User</th>
                        <th>Points</th>
                        <th>sekundēs</th>
                        <th>Timestamp</th>
                    </tr>
                    <?php foreach ($records as $rec): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rec['Username']); ?></td>
                            <td><?php echo htmlspecialchars($rec['Points']); ?></td>
                            <td><?php echo htmlspecialchars($rec['Duration_sec']); ?></td>
                            <td><?php echo htmlspecialchars($rec['Played_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Vēl nav neviena rezultāta. <a href="snake.php">Spēlēt tagad</a></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>