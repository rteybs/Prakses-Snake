<?php
session_start();
require_once __DIR__ . '/includes/connection.php';
require_once __DIR__ . '/includes/functions.php';

$User_ID = $_SESSION['User_ID'];

$where = ["User_ID = ?"];
$params = [$User_ID];
$types = "i";

if (isset($_GET['points_min']) && $_GET['points_min'] !== '' && is_numeric($_GET['points_min'])) {
    $where[] = "Points >= ?";
    $params[] = (int)$_GET['points_min'];
    $types .= "i";
}

if (isset($_GET['points_max']) && $_GET['points_max'] !== '' && is_numeric($_GET['points_max'])) {
    $where[] = "Points <= ?";
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

$sql = "SELECT Points, Duration_sec, Played_at FROM records";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY Played_at DESC";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$records = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mani rezultāti</title>
    <link rel="stylesheet" href="css/OneBoxStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <h3>Mani rezultāti</h3>
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
        <form method="GET" class="filter-form">
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
                <a href="MyResults.php" class="reset-btn">Notīrīt</a>
            </div>
        </form>

        <?php if (count($records) > 0): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Points</th>
                        <th>seconds played</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $rec): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rec['Points']); ?></td>
                            <td><?php echo htmlspecialchars(formatduration($rec['Duration_sec'])); ?></td>
                            <td><?php echo htmlspecialchars($rec['Played_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nav rezultātu ar šādiem filtriem. <a href="snake.php">Spēlēt tagad</a></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>