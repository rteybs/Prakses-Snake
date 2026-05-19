<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pieslēgties</title>
    <link rel="stylesheet" href="css/AuthStyle.css">
</head>
<body>
<div class="auth-container">
    <h3>Log In</h3>

    <?php if (isset($_SESSION['login_errors']['general'])): ?>
        <div class="error" ><?= htmlspecialchars($_SESSION['login_errors']['general']) ?></div>
        <?php unset($_SESSION['login_errors']['general']); ?>
    <?php endif; ?>

    <form action="auth/login_process.php" method="post">
        <?php if (isset($_SESSION['login_errors']['Username'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['login_errors']['Username']) ?></div>
            <?php unset($_SESSION['login_errors']['Username']); ?>
        <?php endif; ?>
        <input type="text" name="Username" placeholder="Username" required
               value="<?= htmlspecialchars($_SESSION['login_old']['Username'] ?? '') ?>">

        <?php if (isset($_SESSION['login_errors']['Email'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['login_errors']['Email']) ?></div>
            <?php unset($_SESSION['login_errors']['Email']); ?>
        <?php endif; ?>
        <input type="email" name="Email" placeholder="Email" required
               value="<?= htmlspecialchars($_SESSION['login_old']['Email'] ?? '') ?>">

        <?php if (isset($_SESSION['login_errors']['Password'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['login_errors']['Password']) ?></div>
            <?php unset($_SESSION['login_errors']['Password']); ?>
        <?php endif; ?>
        <input type="password" name="Password" placeholder="Password" required>

        <button type="submit" name="login">Pieslēgties</button>
    </form>
    <p>Nav konta? <a href="Register.php">Reģistrējies</a></p>
</div>
</body>
</html>
