<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'blockmirrordata';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT numNiveau, duréeDeJeu, nbrMorts, nbrCoin, niveauFini, bestTime FROM niveau WHERE idJoueur = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['numNiveau']] = $row;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
function formatBestTime($seconds) {
    if ($seconds <= 0) return "--:--";
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d:%02d", $minutes, $seconds);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <style>
        :root {
            --primary-blue: #3a3e52;
            --dark-blue: #1b1b26;
            --light-blue: #aeb6d3;
            --mirror-effect: rgba(255, 255, 255, 0.05);
            --accent-orange: #d97b2a;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../pictures/dungeon-bg.png') no-repeat center center fixed;
            background-size: cover;
            color: #f0f0f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero-header {
            background-color: rgba(58, 62, 82, 0.5);
            color: white;
            padding: 2rem 0;
            text-align: center;
            position: relative;
        }

        .hero-header::before {
            content: "";
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: rgba(255,255,255,0.03);
            background-size: 100% 100%;
            opacity: 0.1;
        }

        .game-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .mirror-text {
            display: inline-block;
            transform: scaleX(-1);
            color: var(--light-blue);
            opacity: 0.7;
        }

        .logo-container img {
            height: 80px;
            transition: transform 0.5s;
        }

        .logo-container img:hover {
            transform: scaleX(-1);
        }

        .container.my-5 {
            margin-top: 3rem;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        h2 {
            color: white;
        }

        .card-container {
            margin-bottom: 2rem;
        }

        .level-card {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
            transition: transform 0.3s;
        }

        .level-card:hover {
            transform: translateY(-5px);
        }

        .level-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border-radius: 15px 15px 0 0;
        }

        .card-body {
            padding: 1rem 1.5rem;
        }

        .stats-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem 0;
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-label {
            font-weight: 600;
            color: var(--light-blue);
        }

        .stats-value {
            color: #f0f0f0;
        }

        .custom-cols {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .card-container {
            flex: 0 1 calc(31% - 2rem);
            max-width: calc(31% - 2rem);
        }

        .btn-mirror {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-mirror:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .btn-mirror::after {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.7s;
        }

        .btn-mirror:hover::after {
            transform: translateX(100%);
        }

        footer {
            background-color: rgba(58, 62, 82, 0.5);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
            text-align: center;
            backdrop-filter: blur(3px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <header class="hero-header">
        <div class="container">
            <div class="logo-container">
                <img src="../pictures/logojeu.png" alt="BlockMirror Logo">
            </div>
            <h1 class="game-title">Block<span class="mirror-text">Mirror</span></h1>
            <p>The puzzle game where every action has its reflection</p>
        </div>
    </header>

    <div class="container my-5">
        <div class="top-bar">
            <h2>Your Level Statistics</h2>
            <a href="accueil.php" class="btn-mirror">← Back to Home</a>
        </div>

        <div class="custom-cols">
            <?php
            for ($i = 2; $i <= 10; $i++) {
                $niveau = $stats[$i] ?? ['duréeDeJeu' => 0, 'nbrMorts' => 0, 'nbrCoin' => 0, 'niveauFini' => false, 'bestTime' => 0];
                $niveauFini = $niveau['niveauFini'] ? 'Oui' : 'Non';  
                $formattedTime = gmdate("H:i:s", $niveau['duréeDeJeu']);
                $bestTimeDisplay = $niveau['niveauFini'] ? formatBestTime($niveau['bestTime']) : '--:--';
                echo '
                <div class="card-container">
                    <div class="card level-card h-100">
                        <div class="level-header">Level ' . htmlspecialchars((string)$i) . '</div>
                        <div class="card-body">
                            <div class="stats-item">
                                <span class="stats-label">Playtime:</span>
                                <span class="stats-value">' . htmlspecialchars($formattedTime) . '</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label">Deaths:</span>
                                <span class="stats-value">' . htmlspecialchars((string)$niveau['nbrMorts']) . '</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label">Coins:</span>
                                <span class="stats-value">' . htmlspecialchars((string)$niveau['nbrCoin']) . '</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label">Level Completed:</span>
                                <span class="stats-value">' . htmlspecialchars($niveauFini) . '</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label">Best time:</span>
                                <span class="stats-value">' . htmlspecialchars($bestTimeDisplay) . '</span>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>BlockMirror - An innovative puzzle game © 2025</p>
            <p class="text-muted">All rights reserved. Actions in the mirror may be reversed.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
