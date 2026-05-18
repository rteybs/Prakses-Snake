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
    <link rel="stylesheet" href="../css/MainStyle.css">
</head>
<body>
<form class="container">
    <div class="nav-box">
        <div class="nav">
            <h1>Lietotāji</h1>
            <div class="button-group">
                <a href="admin.php">Atpakaļ</a>
            </div>
        </div>
    </div>
    <div class="o-race-box">
        <table class="race-table">
            <tr class="column-names">
                <td>ID</td>
                <td>Username</td>
                <td>Email</td>
            </tr>
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
        </table>
    </div>
</form>
</body>
</html>