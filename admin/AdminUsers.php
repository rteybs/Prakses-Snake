<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT * FROM user ORDER BY User_ID DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Lietotāju pārvaldība</title>
    <link rel="stylesheet" href="../css/OneBoxStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <div class="nav">
            <h1>Lietotāji</h1>
            <div class="button-group">
                <a href="admin.php">Atpakaļ</a>
            </div>
        </div>
    </div>
    <div class="SnakeBox">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Ir admns?</th>
                    <th>opcijas</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $user['User_ID'] ?></td>
                    <td><?= htmlspecialchars($user['Username']) ?></td>
                    <td><?= htmlspecialchars($user['Email']) ?></td>
                    <td><?= $user['is_admin'] ? 'Jā' : 'Nē' ?></td>
                    <td>
                        <a href="EditUser.php?id=<?= $user['User_ID'] ?>">Rediģēt</a> |
                        <a href="DeleteUser.php?id=<?= $user['User_ID'] ?>" onclick="return confirm('Vai tiešām dzēst?')">Dzēst</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>