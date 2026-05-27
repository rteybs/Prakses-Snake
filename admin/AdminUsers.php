<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
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
    $where[] = "Username LIKE ?";
    $params[] = "%" . trim($_GET['search_username']) . "%";
    $types .= "s";
}

if (isset($_GET['search_email']) && trim($_GET['search_email']) !== '') {
    $where[] = "Email LIKE ?";
    $params[] = "%" . trim($_GET['search_email']) . "%";
    $types .= "s";
}

if (isset($_GET['admin_filter']) && $_GET['admin_filter'] !== '') {
    $admin_val = (int)$_GET['admin_filter'];
    $where[] = "is_admin = ?";
    $params[] = $admin_val;
    $types .= "i";
}

$sql = "SELECT * FROM user";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY User_ID DESC";

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
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>Lietotājvārds</label>
                <input type="text" name="search_username" value="<?php echo htmlspecialchars($_GET['search_username'] ?? ''); ?>" placeholder="Meklēt pēc vārda...">
            </div>
            <div class="filter-group">
                <label>E-pasts</label>
                <input type="text" name="search_email" value="<?php echo htmlspecialchars($_GET['search_email'] ?? ''); ?>" placeholder="Meklēt pēc e-pasta...">
            </div>
            <div class="filter-group">
                <label>Administrators</label>
                <select name="admin_filter">
                    <option value="">Visi</option>
                    <option value="1" <?php echo (isset($_GET['admin_filter']) && $_GET['admin_filter'] == '1') ? 'selected' : ''; ?>>Jā</option>
                    <option value="0" <?php echo (isset($_GET['admin_filter']) && $_GET['admin_filter'] == '0') ? 'selected' : ''; ?>>Nē</option>
                </select>
            </div>
            <div class="filter-group">
                <button type="submit">Filtrēt</button>
            </div>
            <div class="filter-group">
                <a href="AdminUsers.php" class="reset-btn">Notīrīt</a>
            </div>
        </form>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
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
                    <td>
                        <?php 
                        $avatarUrl = adminAvatarUrl($user['Avatar_url']);
                        if ($avatarUrl): ?>
                            <img src="<?= htmlspecialchars($avatarUrl) ?>" class="table-avatar" alt="avatar">
                        <?php else: ?>
                            has no avatar
                        <?php endif; ?>
                    </td>
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