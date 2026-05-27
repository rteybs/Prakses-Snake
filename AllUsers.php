<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

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

$sql = "SELECT * FROM User";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY User_ID ASC";

$stmt = mysqli_prepare($con, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="css/OneBoxStyle.css">
</head>
<body>
<div class="container">
  <div class="NavBox">
    <div class="nav">
        <h3>Visi lietotāji</h3>
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
                <button type="submit">Filtrēt</button>
            </div>
            <div class="filter-group">
                <a href="AllUsers.php" class="reset-btn">Notīrīt</a>
            </div>
        </form>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <?php if(!empty($user['Avatar_url'])): ?>
                            <img src="<?php echo htmlspecialchars($user['Avatar_url']); ?>" class="table-avatar" alt="avatar">
                        <?php else: ?>
                            has no avatar
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($user['Username']); ?></td>
                    <td><?php echo htmlspecialchars($user['Email']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>