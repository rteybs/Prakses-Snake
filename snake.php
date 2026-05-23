<?php
session_start();
$isLoggedIn = isset($_SESSION['User_ID']);
$userId = $isLoggedIn ? $_SESSION['User_ID'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Game</title>
    <link rel="stylesheet" href="css/SnakeStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <div class="button-group">
            <?php if(isset($_SESSION['Username'])): ?>
                <div class="button-info">
                    <?php if(!empty($_SESSION['Avatar_url'])): 
                        $avatar = $_SESSION['Avatar_url'];
                    ?>
                        <img src="<?php echo htmlspecialchars($avatar); ?>" class="nav-avatar" alt="avatar">
                    <?php endif; ?>
                    <span>Labdien, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</span>
                </div>
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
        <h1>Čūsku spēle</h1>
        <div class="info">
            <span>Punkti: <span id="scoreDisplay">0</span></span>
            <span>Laiks: <span id="timeDisplay">0</span> s</span>
        </div>
        <canvas id="gameCanvas" width="500" height="500"></canvas>
        <div id="message" class="message"></div>
        <button id="restartButton" class="restart-btn" style="display:none;">Spēlēt vēlreiz</button>
        <div class="game-info-text">
            🎮 Vadība: bulttaustiņi vai WASD<br>
            <?php if ($isLoggedIn): ?>
                ✅ Rezultāti tiek saglabāti automātiski
            <?php else: ?>
                🔓 <a href="Login.php" style="color:#FBBF24;">Pieraksties</a>, lai saglabātu savus rezultātus
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    var userId = <?php echo json_encode($userId); ?>;

    (function() {
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const scoreSpan = document.getElementById('scoreDisplay');
        const timeSpan = document.getElementById('timeDisplay');
        const messageDiv = document.getElementById('message');
        const restartBtn = document.getElementById('restartButton');

        const gridSize = 20;      
        const tileCount = canvas.width / gridSize;   

        let snake, direction, food, score, gameRunning, startTime;
        let gameLoop, timerLoop;

        function initGame() {
            snake = [{x: 10, y: 10}];
            direction = {x: 0, y: 0};   
            score = 0;
            gameRunning = true;
            startTime = Date.now();

            placeFood();
            scoreSpan.textContent = score;
            timeSpan.textContent = '0';
            messageDiv.textContent = '';
            restartBtn.style.display = 'none';

            if (gameLoop) clearInterval(gameLoop);
            if (timerLoop) clearInterval(timerLoop);

            gameLoop = setInterval(update, 100);
            timerLoop = setInterval(updateTimer, 1000);
            draw();
        }

        function updateTimer() {
            if (!gameRunning) return;
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            timeSpan.textContent = elapsed;
        }

        function placeFood() {
            food = {
                x: Math.floor(Math.random() * tileCount),
                y: Math.floor(Math.random() * tileCount)
            };
            for (let segment of snake) {
                if (segment.x === food.x && segment.y === food.y) {
                    placeFood();
                    break;
                }
            }
        }

        function update() {
            if (!gameRunning) return;

            const head = {x: snake[0].x + direction.x, y: snake[0].y + direction.y};

            if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) {
                endGame();
                return;
            }

            for (let i = 0; i < snake.length - 1; i++) {
                if (snake[i].x === head.x && snake[i].y === head.y) {
                    endGame();
                    return;
                }
            }

            snake.unshift(head);

            if (head.x === food.x && head.y === food.y) {
                score++;
                scoreSpan.textContent = score;
                placeFood();
            } else {
                snake.pop();
            }

            draw();
        }

        function draw() {
            ctx.fillStyle = '#0F172A';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.fillStyle = '#34D399';
            for (let segment of snake) {
                ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
            }

            ctx.fillStyle = '#FBBF24';
            ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize - 2, gridSize - 2);
        }

        function endGame() {
            gameRunning = false;
            clearInterval(gameLoop);
            clearInterval(timerLoop);
            messageDiv.textContent = 'Spēle beigusies!';
            restartBtn.style.display = 'inline-block';

            const finalScore = score;
            const finalDuration = Math.floor((Date.now() - startTime) / 1000);

            if (isLoggedIn) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "auth/save_score.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log("Score saved:", xhr.responseText);
                    }
                };
                xhr.send("Points=" + finalScore + "&Duration_sec=" + finalDuration);
            }
        }

        document.addEventListener('keydown', e => {
            if (!gameRunning) return;
            const key = e.key;
            if (key === 'ArrowUp'    && direction.y === 0) { direction = {x: 0, y: -1}; }
            if (key === 'ArrowDown'  && direction.y === 0) { direction = {x: 0, y: 1}; }
            if (key === 'ArrowLeft'  && direction.x === 0) { direction = {x: -1, y: 0}; }
            if (key === 'ArrowRight' && direction.x === 0) { direction = {x: 1, y: 0}; }
            if (key === 'w'          && direction.y === 0) { direction = {x: 0, y: -1}; }
            if (key === 's'          && direction.y === 0) { direction = {x: 0, y: 1}; }
            if (key === 'a'          && direction.x === 0) { direction = {x: -1, y: 0}; }
            if (key === 'd'          && direction.x === 0) { direction = {x: 1, y: 0}; }
        });

        restartBtn.addEventListener('click', initGame);

        initGame();
    })();
</script>
</body>
</html>