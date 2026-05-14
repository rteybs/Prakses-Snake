<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

$query = "SELECT * FROM records
JOIN user ON user.User_ID = records.User_ID
ORDER BY Points DESC
LIMIT 3";
$result = mysqli_query($con, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Website</title>
    <link rel="stylesheet" href="css/MainStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
    <h3>Snake website</h3>
        <div class="button-group">
            <?php if(isset($_SESSION['Username'])): ?>
                    <span>Labdien, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</span>
                    <a href="index.php">Sākumlapa</a>
                    <a href="MyResults.php">Mani rezultāti</a>
                    <a href="AllResults.php">Visi rezultāti</a>
                    <a href="AllUsers.php">Lietotāju saraksts</a>  
                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] = true): ?>
                        <a href=">Administrācija</a>
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
        <a href="snake.php">play snake game</a>
    </div>

    <div class="PointsBox">
        <h3>Top Punkti</h3>
        <table>
            <tr>
                <td>User</td>
                <td>Points</td>
                <td>Timestamp</td>
            </tr> 
            <?php while($top = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($top['Username']); ?></td>
                <td><?php echo htmlspecialchars($top['Points']); ?></td>
                <td><?php echo htmlspecialchars($top['Played_at']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>