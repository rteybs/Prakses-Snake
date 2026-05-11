
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Snake Game</title>
    <link rel="stylesheet" href="css/SnakeStyle.css">
</head>
<body>
    <div class="game-container">
        <h1>Snake</h1>
        <div class="info">
            <span>Score: <span id="scoreDisplay">0</span></span>
            <span>Time: <span id="timeDisplay">0</span>s</span>
        </div>
        <canvas id="gameCanvas" width="400" height="400"></canvas>
        <div id="message" class="message"></div>
        <button id="restartButton" class="restart-btn" style="display:none;">Play Again</button>
    </div>

    <script>
    (function() {
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const scoreSpan = document.getElementById('scoreDisplay');
        const timeSpan = document.getElementById('timeDisplay');
        const messageDiv = document.getElementById('message');
        const restartBtn = document.getElementById('restartButton');

        const gridSize = 20;       // px per cell
        const tileCount = canvas.width / gridSize;   // 20x20 grid

        let snake, direction, food, score, gameRunning, startTime;
        let gameLoop, timerLoop;

        function initGame() {
            snake = [{x: 10, y: 10}];
            direction = {x: 0, y: 0};   // not moving until first arrow press
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
            // Make sure food doesn't spawn on the snake
            for (let segment of snake) {
                if (segment.x === food.x && segment.y === food.y) {
                    placeFood();
                    break;
                }
            }
        }

        function update() {
            if (!gameRunning) return;

            // Move head
            const head = {x: snake[0].x + direction.x, y: snake[0].y + direction.y};

            // Wall collision
            if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) {
                endGame();
                return;
            }

            // Self collision (ignore the tail that will be removed)
            for (let i = 0; i < snake.length - 1; i++) {
                if (snake[i].x === head.x && snake[i].y === head.y) {
                    endGame();
                    return;
                }
            }

            snake.unshift(head);

            // Eat food
            if (head.x === food.x && head.y === food.y) {
                score++;
                scoreSpan.textContent = score;
                placeFood();
            } else {
                snake.pop();   // remove tail
            }

            draw();
        }

        function draw() {
            // Clear canvas
            ctx.fillStyle = 'black';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw snake
            ctx.fillStyle = 'green';
            for (let segment of snake) {
                ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
            }

            // Draw food
            ctx.fillStyle = 'red';
            ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize - 2, gridSize - 2);
        }

        function endGame() {
            gameRunning = false;
            clearInterval(gameLoop);
            clearInterval(timerLoop);
            messageDiv.textContent = 'Game Over!';
            restartBtn.style.display = 'inline-block';

            const finalScore = score;
            const finalDuration = Math.floor((Date.now() - startTime) / 1000);

        }

        // Keyboard arrow controls
        document.addEventListener('keydown', e => {
            if (!gameRunning) return;
            const key = e.key;
            // Prevent opposite direction
            if (key === 'ArrowUp'    && direction.y === 0) { direction = {x: 0, y: -1}; }
            if (key === 'ArrowDown'  && direction.y === 0) { direction = {x: 0, y: 1}; }
            if (key === 'ArrowLeft'  && direction.x === 0) { direction = {x: -1, y: 0}; }
            if (key === 'ArrowRight' && direction.x === 0) { direction = {x: 1, y: 0}; }
        });

        // Keyboard wasd controls
        document.addEventListener('keydown', e => {
            if (!gameRunning) return;
            const key = e.key;
            // Prevent opposite direction
            if (key === 'w'    && direction.y === 0) { direction = {x: 0, y: -1}; }
            if (key === 's'  && direction.y === 0) { direction = {x: 0, y: 1}; }
            if (key === 'a'  && direction.x === 0) { direction = {x: -1, y: 0}; }
            if (key === 'd' && direction.x === 0) { direction = {x: 1, y: 0}; }
        });

        restartBtn.addEventListener('click', initGame);

        // Start the first game
        initGame();
    })();
    </script>
</body>
</html>