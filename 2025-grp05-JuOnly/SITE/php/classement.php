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

        $sort = $_GET['sort'] ?? 'best_time';
        $orderDir = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
        $selectedLevel = $_GET['level'] ?? 'all';
        $viewMode = in_array($_GET['view'] ?? '', ['global', 'niveau']) ? $_GET['view'] : 'global';

        $stmt = $conn->query("SELECT DISTINCT numNiveau FROM niveau ORDER BY numNiveau");
        $levels = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $selectFields = "
            u.first_name,
            u.last_name,
            SUM(n.duréeDeJeu) / 3600 AS total_hours,
            SUM(n.nbrCoin) AS total_coins,
            SUM(n.nbrMorts) AS total_morts,
            SUM(n.niveauFini) AS total_fini,
            MIN(n.bestTime) AS best_time
        ";

        $groupBy = "GROUP BY n.idJoueur, u.first_name, u.last_name";
        $where = "";
        $params = [];

        if ($viewMode === 'niveau' && $selectedLevel !== 'all' && in_array($selectedLevel, $levels)) {
            $where = "WHERE n.numNiveau = ?";
            $params[] = $selectedLevel;
        }

        $sortMappings = [
            'best_time' => "CASE WHEN MIN(n.bestTime) = 0 AND SUM(n.niveauFini) = 0 THEN 1 ELSE 0 END, MIN(n.bestTime)",
            'coins' => "SUM(n.nbrCoin)",
            'deaths' => "SUM(n.nbrMorts)",
            'hours' => "SUM(n.duréeDeJeu)",
            'levels' => "SUM(n.niveauFini)"
        ];

        $orderBy = isset($sortMappings[$sort]) ? $sortMappings[$sort] . " $orderDir" : $sortMappings['best_time'] . " ASC";

        $sql = "
            SELECT 
                $selectFields
            FROM niveau n
            JOIN users u ON n.idJoueur = u.user_id
            $where
            $groupBy
            ORDER BY $orderBy
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Leaderboard</title>
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
            background-color: rgba(58, 62, 82, 0.5);            color: white;
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

        .container.mt-5 {
            margin-top: 3rem;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.08);
            padding: 2rem;
            margin-top: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border-top: 5px solid var(--primary-blue);
            backdrop-filter: blur(5px);
        }

        .table-leaderboard {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
            margin-bottom: 60px;
        }

        .table-leaderboard thead {
            background-color: var(--primary-blue);
            color: white;
        }

        .table-leaderboard th, .table-leaderboard td {
            padding: 1rem;
            text-align: center;
            background: transparent;
            color: white;
        }

        .table-leaderboard tbody tr:nth-child(even) {
            background-color: var(--mirror-effect);
        }

        .table-leaderboard tbody tr:hover {
            background-color: rgba(174, 182, 211, 0.2);
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .table-leaderboard td:first-child {
            font-weight: bold;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: white;
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

        .return-home-btn {
            margin-top: 20px;
            text-align: center;
        }

        .sorting-options {
            background: rgba(255, 255, 255, 0.08);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .sorting-options select {
            background: var(--primary-blue);
            color: white;
            border: 1px solid var(--light-blue);
            padding: 0.5rem;
            border-radius: 5px;
            margin-right: 1rem;
        }

        .view-switch {
            margin-bottom: 1.5rem;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
        }

        .view-switch .form-select {
            width: auto;
            min-width: 200px;
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

    <div class="sorting-options">
    <form method="get" class="row g-3 align-items-center">
        <div class="col-auto">
            <select name="view" class="form-select" onchange="this.form.submit()">
                <option value="global" <?= $viewMode === 'global' ? 'selected' : '' ?>>Global</option>
                <option value="niveau" <?= $viewMode === 'niveau' ? 'selected' : '' ?>>Par niveau</option>
            </select>
        </div>

        <?php if ($viewMode === 'niveau'): ?>
        <div class="col-auto">
            <select name="level" class="form-select">
                <?php foreach ($levels as $lvl): ?>
                    <option value="<?= $lvl ?>" <?= $selectedLevel == $lvl ? 'selected' : '' ?>>Niveau <?= $lvl ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <div class="col-auto">
            <select name="sort" class="form-select">
                <option value="best_time" <?= $sort === 'best_time' ? 'selected' : '' ?>>Meilleur temps</option>
                <option value="coins" <?= $sort === 'coins' ? 'selected' : '' ?>>Pièces</option>
                <option value="deaths" <?= $sort === 'deaths' ? 'selected' : '' ?>>Morts</option>
                <option value="hours" <?= $sort === 'hours' ? 'selected' : '' ?>>Temps de jeu</option>
                <option value="levels" <?= $sort === 'levels' ? 'selected' : '' ?>>Réussites</option>
            </select>
        </div>

        <div class="col-auto">
            <select name="order" class="form-select">
                <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Croissant</option>
                <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Décroissant</option>
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn-mirror">Appliquer</button>
        </div>
    </form>
    </div>
    <div class="container mt-5 table-container">
        <h2>Classement des Joueurs</h2>

        <table class="table table-leaderboard">
        <thead>
            <tr>
                <th>#</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Meilleur temps</th>
                <th>Pièces</th>
                <th>Morts</th>
                <th>Temps passé</th>
                <th>Réussites</th>
            </tr>
        </thead>
            <tbody>
                <?php
                $rank = 1;
                foreach ($leaderboard as $player): ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($player['first_name']) ?></td>
                        <td><?= htmlspecialchars($player['last_name']) ?></td>
                        
                        <td><?= gmdate("H:i:s", $player['best_time']) ?></td>
                        <td><?= $player['total_coins'] ?></td>
                        <td><?= $player['total_morts'] ?></td>
                        <td><?= number_format($player['total_hours'], 1) ?>h</td>
                        <td><?= $player['total_fini'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="return-home-btn">
            <a href="accueil.php" class="btn-mirror">Return to Home</a>
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
