<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/functions.php';

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

$where = [];
$params = [];
$types = "";

if (isset($_GET['search_username']) && trim($_GET['search_username']) !== '') {
    $where[] = "user.Username LIKE ?";
    $params[] = "%" . trim($_GET['search_username']) . "%";
    $types .= "s";
}

if (isset($_GET['points_min']) && $_GET['points_min'] !== '' && is_numeric($_GET['points_min'])) {
    $where[] = "records.Points >= ?";
    $params[] = (int)$_GET['points_min'];
    $types .= "i";
}

if (isset($_GET['points_max']) && $_GET['points_max'] !== '' && is_numeric($_GET['points_max'])) {
    $where[] = "records.Points <= ?";
    $params[] = (int)$_GET['points_max'];
    $types .= "i";
}

if (isset($_GET['date_from']) && trim($_GET['date_from']) !== '') {
    $where[] = "records.Played_at >= ?";
    $params[] = trim($_GET['date_from']);
    $types .= "s";
}

if (isset($_GET['date_to']) && trim($_GET['date_to']) !== '') {
    $where[] = "records.Played_at <= ?";
    $params[] = trim($_GET['date_to']) . ' 23:59:59';
    $types .= "s";
}

$sql = "SELECT records.Record_ID, records.Points, records.Duration_sec, records.Played_at, 
                 user.Username, user.User_ID, user.Avatar_url
          FROM records
          JOIN user ON records.User_ID = user.User_ID";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY records.Played_at DESC";

$stmt = mysqli_prepare($con, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>Lietotājvārds</label>
                <input type="text" name="search_username" value="<?php echo htmlspecialchars($_GET['search_username'] ?? ''); ?>" placeholder="Meklēt pēc vārda...">
            </div>
            <div class="filter-group">
                <label>Punkti no</label>
                <input type="number" name="points_min" value="<?php echo htmlspecialchars($_GET['points_min'] ?? ''); ?>" placeholder="Min">
            </div>
            <div class="filter-group">
                <label>Punkti līdz</label>
                <input type="number" name="points_max" value="<?php echo htmlspecialchars($_GET['points_max'] ?? ''); ?>" placeholder="Max">
            </div>
            <div class="filter-group">
                <label>Datums no</label>
                <input type="date" name="date_from" value="<?php echo htmlspecialchars($_GET['date_from'] ?? ''); ?>">
            </div>
            <div class="filter-group">
                <label>Datums līdz</label>
                <input type="date" name="date_to" value="<?php echo htmlspecialchars($_GET['date_to'] ?? ''); ?>">
            </div>
            <div class="filter-group">
                <button type="submit">Filtrēt</button>
            </div>
            <div class="filter-group">
                <a href="AdminRecords.php" class="reset-btn">Notīrīt</a>
            </div>
        </form>

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