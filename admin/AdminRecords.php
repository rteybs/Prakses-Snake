<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

function adminAvatarUrl($url) {
    if (empty($url)){
        return null;
    };
    
    return '../' . ltrim($url, '/');
}

$query = "SELECT records.Record_ID, records.Points, records.Duration_sec, records.Played_at, 
                 user.Username, user.User_ID, user.Avatar_url
          FROM records
          JOIN user ON records.User_ID = user.User_ID
          ORDER BY records.Played_at DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezultātu pārvaldība</title>
    <link rel="stylesheet" href="../css/OneBoxStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <div>
            <h1>Spēļu rezultāti</h1>
            <div class="button-group">
                <a href="admin.php">Atpakaļ uz admin paneli</a>
            </div>
        </div>
    </div>

    <div class="SnakeBox">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Lietotājs</th>
                    <th>Punkti</th>
                    <th>Ilgums (sek)</th>
                    <th>Spēles laiks</th>
                    <th>opcijas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['Record_ID'] ?></td>
                    <td>
                        <?php 
                        $avatarUrl = adminAvatarUrl($row['Avatar_url']);
                        if ($avatarUrl): ?>
                            <img src="<?= htmlspecialchars($avatarUrl) ?>" class="table-avatar" alt="avatar">
                        <?php else: ?>
                            has no avatar
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['Username']) ?></td>
                    <td><?= $row['Points'] ?></td>
                    <td><?= formatduration($row['Duration_sec']) ?></td>
                    <td><?= $row['Played_at'] ?></td>
                    <td>
                        <a href="EditRecord.php?id=<?= $row['Record_ID'] ?>">Rediģēt</a> |
                        <a href="DeleteRecord.php?id=<?= $row['Record_ID'] ?>" onclick="return confirm('Dzēst šo rezultātu?')">Dzēst</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>