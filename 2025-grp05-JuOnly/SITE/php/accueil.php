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
} catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <style>
    :root {
        --primary-blue: #3a3e52;
        --dark-blue: #1b1b26;
        --light-blue: #aeb6d3;
        --mirror-effect: rgba(255, 255, 255, 0.05);
        --accent-orange: #d97b2a;
        --magic-violet: #7c6aaf;
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
            overflow: hidden;
        }

        .hero-header::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 L0,100 Z" transform="scale(-1,1) translate(-100,0)"/></svg>');
            background-size: 100% 100%;
            opacity: 0.3;
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

        .dashboard-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border-top: 5px solid var(--primary-blue);
            backdrop-filter: blur(5px);
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.2rem;
            color: var(--light-blue);
        }

        .action-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
            border-left: 4px solid var(--primary-blue);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .action-title {
            color: var(--light-blue);
            margin-bottom: 1rem;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .action-description {
            color: #e0e0e0;
            margin-bottom: 1.5rem;
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.7s;
        }

        .btn-mirror:hover::after {
            transform: translateX(100%);
        }

        .user-info {
            text-align: right;
            margin-bottom: 1rem;
            color: var(--light-blue);
        }

        .logout-link {
            color: var(--light-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .logout-link:hover {
            text-decoration: underline;
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


        .logo-container img {
            height: 80px;
            transition: transform 0.5s;
        }

        .logo-container img:hover {
            transform: scaleX(-1);
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

    <div class="container">
        <div class="user-info">
            <a href="../index.html" class="logout-link">Do you want to go to the Home page? Click here...</a>
        </div>

        <div class="user-info">
            Hello, <?php echo htmlspecialchars($_SESSION['first_name']); ?> | 
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
        
        <div class="dashboard-container">
            <div class="welcome-message">
                <h2>Welcome to your BlockMirror space</h2>
                <p>What do you want to do today?</p>
            </div>
            
            <div class="action-card">
                <h3 class="action-title">Play BlockMirror</h3>
                <p class="action-description">Dive into the world of mirrors and solve captivating puzzles.</p>
                <a href="menujeu.php" class="btn-mirror">Start the game</a>
            </div>
            <div class="action-card">
                <h3 class="action-title">Leaderboard</h3>
                <p class="action-description">View the overall leaderboard</p>
                <a href="classement.php" class="btn-mirror">View the leaderboard</a>
            </div>
            <div class="action-card">
                <h3 class="action-title">My Profile</h3>
                <p class="action-description">View your game statistics and performance</p>
                <a href="profil.php" class="btn-mirror">View my profile</a>
            </div>
            <div class="action-card">
                <h3 class="action-title">My Account</h3>
                <p class="action-description">Manage your personal information and account settings</p>
                <a href="compte.php" class="btn-mirror">Access my account</a>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>BlockMirror - An innovative puzzle game © 2025</p>
            <p>All rights reserved. Actions in the mirror may be reversed.</p>
        </div>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.setItem('audioTime', 0);
        localStorage.setItem('audioPlaying', 'false');
        if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
