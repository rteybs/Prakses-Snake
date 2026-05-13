<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

// If not logged in, redirect to login page
if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php");
    exit();
}

$User_ID = $_SESSION['User_ID'];

// Get all records for this user, most recent first
$query = "SELECT Points, Duration_sec, Played_at FROM records WHERE User_ID = ? ORDER BY Played_at DESC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $User_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch all rows into an array
$records = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mani rezultāti</title>
    <link rel="stylesheet" href="css/MainStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <h1>Mani rezultāti</h1>
        <div class="button-group">
            <a href="index.php">Sākumlapa</a>
            <a href="snake.php">Spēlēt vēlreiz</a>
        </div>
    </div>

    <div class="SnakeBox">
        <?php if (count($records) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Punkti (Points)</th>
                        <th>Ilgums (sekundēs)</th>
                        <th>Spēlēts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $rec): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rec['Points']); ?></td>
                            <td><?php echo htmlspecialchars($rec['Duration_sec']); ?></td>
                            <td><?php echo htmlspecialchars($rec['Played_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Jums vēl nav neviena rezultāta. <a href="snake.php">Spēlēt tagad</a></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>