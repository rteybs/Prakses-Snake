<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Website</title>
    <link rel="stylesheet" href="css/MainStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
    <h3>Snake website</h3>
    <div class="button-group">
        <?php if(isset($_SESSION['Username'])): ?>
                <span>Labdien, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</span>
                <a href="index.php">Sākumlapa</a>
                <a href="MyResults.php">Mani rezultāti</a>
                <a href="AllResults.php">Visi rezultāti</a>
                <a href="AllUsers.php">Lietotāju saraksts</a>
                <a href="Logout.php">Log out</a>        
            <?php else: ?>
                <a href="index.php">Sākumlapa</a>
                <a href="Register.php">sign in</a>
                <a href="Login.php">Log in</a>
            <?php endif; ?>
    </div>
</div>
    <div class="SnakeBox">
        <a href="snake.php">play snake game</a>
    </div>
    <div class="UserBox"></div>
</div>
</body>
</html>