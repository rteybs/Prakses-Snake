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
        <form action="auth/login_process.php" method="post">
            <input type="text" name="Username" placeholder="Username" required>
            <input type="email" name="Email" placeholder="Email" required>
            <input type="password" name="Password" placeholder="Password" required>
            

            <button type="submit" name="login">Pieslēgties</button>
        </form>
        <p>Nav konta? <a href="Register.php">Reģistrējies</a></p>
    </div>
</body>
</html>