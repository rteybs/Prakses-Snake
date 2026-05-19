<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reģistrācija</title>
    <link rel="stylesheet" href="css/AuthStyle.css">
</head>
<body>
<div class="auth-container">
    <div class="card-title">
        <h3>Sign in</h3>
    </div>

    <?php if (isset($_SESSION['register_errors']['general'])): ?>
        <div class="error" ><?= htmlspecialchars($_SESSION['register_errors']['general']) ?></div>
        <?php unset($_SESSION['register_errors']['general']); ?>
    <?php endif; ?>

    <form action="auth/insert.php" method="post" enctype="multipart/form-data">
        <?php if (isset($_SESSION['register_errors']['Username'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['register_errors']['Username']) ?></div>
            <?php unset($_SESSION['register_errors']['Username']); ?>
        <?php endif; ?>
        <input type="text" placeholder="username" name="Username"
               value="<?= htmlspecialchars($_SESSION['register_old']['Username'] ?? '') ?>">

        <?php if (isset($_SESSION['register_errors']['Email'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['register_errors']['Email']) ?></div>
            <?php unset($_SESSION['register_errors']['Email']); ?>
        <?php endif; ?>
        <input type="email" placeholder="email" name="Email"
               value="<?= htmlspecialchars($_SESSION['register_old']['Email'] ?? '') ?>">

        <?php if (isset($_SESSION['register_errors']['Password'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['register_errors']['Password']) ?></div>
            <?php unset($_SESSION['register_errors']['Password']); ?>
        <?php endif; ?>
        <input type="password" placeholder="password" name="Password">

        <?php if (isset($_SESSION['register_errors']['ConfirmPassword'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['register_errors']['ConfirmPassword']) ?></div>
            <?php unset($_SESSION['register_errors']['ConfirmPassword']); ?>
        <?php endif; ?>
        <input type="password" placeholder="confirm password" name="ConfirmPassword">

        <?php if (isset($_SESSION['register_errors']['Avatar'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['register_errors']['Avatar']) ?></div>
            <?php unset($_SESSION['register_errors']['Avatar']); ?>
        <?php endif; ?>
        <input type="file" name="Avatar" accept="image/png, image/jpeg">

        <button class="btn btn-primary" name="submit">Submit</button>
    </form>
</div>
</body>
</html>
