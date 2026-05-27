<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

$topQuery = "SELECT Username, Points, Played_at, Avatar_url
             FROM records
             JOIN user ON user.User_ID = records.User_ID
             ORDER BY Points DESC
             LIMIT 5";
$topResult = mysqli_query($con, $topQuery);

$recentQuery = "SELECT Username, Points, Played_at, Avatar_url
                FROM records 
                JOIN user ON user.User_ID = records.User_ID 
                ORDER BY Played_at DESC 
                LIMIT 5";
$recentResult = mysqli_query($con, $recentQuery);

$userBest = null;
$userRank = null;
$lastScore = null;
$lastDate = null;
$totalGames = null;

if (isset($_SESSION['User_ID'])) {
    $userId = $_SESSION['User_ID'];
    
    $bestQuery = "SELECT MAX(Points) as highscore FROM records WHERE User_ID = $userId";
    $bestRes = mysqli_query($con, $bestQuery);
    $userBest = mysqli_fetch_assoc($bestRes)['highscore'] ?? 0;
    
    $rankQuery = "SELECT COUNT(DISTINCT Points) + 1 as rank
                  FROM records
                  WHERE Points > (SELECT IFNULL(MAX(Points), 0) FROM records WHERE User_ID = $userId)";
    $rankRes = mysqli_query($con, $rankQuery);
    $userRank = mysqli_fetch_assoc($rankRes)['rank'] ?? '—';
    
    $lastQuery = "SELECT Points, Played_at FROM records WHERE User_ID = $userId ORDER BY Played_at DESC LIMIT 1";
    $lastRes = mysqli_query($con, $lastQuery);
    if ($lastData = mysqli_fetch_assoc($lastRes)) {
        $lastScore = $lastData['Points'];
        $lastDate = $lastData['Played_at'];
    }

    $gamesQuery = "SELECT COUNT(*) as total FROM records WHERE User_ID = $userId";
    $gamesRes = mysqli_query($con, $gamesQuery);
    $totalGames = mysqli_fetch_assoc($gamesRes)['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Čūsku spēle</title>
    <link rel="stylesheet" href="css/MainStyle.css">
</head>
<body>
<div class="container">
    <div class="NavBox">
        <h3>Čūsku mājaslapa</h3>
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
        <h2>Čūsku spēle</h2>
        
        <div class="snake-preview">
            <div class="mini-snake large">
                <div class="snake-cell"></div>
                <div class="snake-cell body"></div>
                <div class="snake-cell body"></div>
                <div class="snake-cell head"></div>
                <div class="snake-cell food"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
                <div class="snake-cell"></div>
            </div>
        </div>

        <div class="info-cube">
            <p><strong>Vadība:</strong> bulttaustiņi vai WASD</p>
            <p><strong>Mērķis:</strong> Apēst dzelteno ēdienu, kļūt garākam, neietriekties sienā vai sevī.</p>
        </div>

        <?php if (isset($_SESSION['Username'])): ?>
            <div class="user-game-stats">
                <p>🏆 Personīgais rekords: <strong><?php echo htmlspecialchars($userBest); ?></strong> punkti</p>
                <p>📊 Kopā nospēlēts: <strong><?php echo $totalGames; ?></strong> spēles</p>
                <?php if ($lastScore !== null): ?>
                <p>🕒 Pēdējais rezultāts: <strong><?php echo $lastScore; ?></strong> punkti (<?php echo date('d.m.Y H:i', strtotime($lastDate)); ?>)</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <p>Ielogojies, lai saglabātu savus rekordus un redzētu savu statistiku!</p>
            </div>
        <?php endif; ?>

        <a href="snake.php" class="play-btn">Spēlēt tūlīt</a>
    </div>

   
    <div class="PointsBox">
        <h3>Top 5 rekordi</h3>
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th>Avatar</th>
                        <th>Lietotājs</th>
                        <th>Punkti</th>
                        <th>Datums</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $rank = 1;
                mysqli_data_seek($topResult, 0);
                while($top = mysqli_fetch_assoc($topResult)): ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td>
                            <?php if(!empty($top['Avatar_url'])): ?>
                                <img src="<?php echo htmlspecialchars($top['Avatar_url']); ?>" class="table-avatar" alt="avatar">
                            <?php else: ?>
                                has no avatar
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($top['Username']); ?></td>
                        <td><?php echo htmlspecialchars($top['Points']); ?></td>
                        <td><?php echo date('d.m.Y', strtotime($top['Played_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (isset($_SESSION['Username'])): ?>
        <div class="info-cube">
            <p>Jūsu vieta: <strong>#<?php echo $userRank; ?></strong></p>
        </div>
        <a href="AllResults.php" class="more-link">Skatīt visus rezultātus →</a>
        <?php endif; ?>
        
    </div>

    <div class="RecentBox">
        <h3>Pēdējās spēles</h3>
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Lietotājs</th>
                        <th>Punkti</th>
                        <th>Laiks</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = mysqli_fetch_assoc($recentResult)): ?>
                    <tr>
                        <td>
                            <?php if(!empty($row['Avatar_url'])): ?>
                                <img src="<?php echo htmlspecialchars($row['Avatar_url']); ?>" class="table-avatar" alt="avatar">
                            <?php else: ?>
                                has no avatar
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['Username']); ?></td>
                        <td><?php echo $row['Points']; ?></td>
                        <td><?php echo date('H:i, d.m.Y', strtotime($row['Played_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>