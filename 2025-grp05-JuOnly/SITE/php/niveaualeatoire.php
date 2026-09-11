<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$level = isset($_GET['level']) ? (int)$_GET['level'] : 1;
if (!in_array($level, [1, 2, 3])) {
    header("Location: niveaualeatoire.php?level=1");
    exit();
}

if ($level == 1) {
    $_SESSION['random_series_start_time'] = time();
} elseif (!isset($_SESSION['random_series_start_time'])) {
    $_SESSION['random_series_start_time'] = time();
}
$series_start_time_js = $_SESSION['random_series_start_time'];

$executable = match($level) {
    1 => 'monjeu.exe',
    2 => 'monjeumoyen.exe',
    3 => 'monjeudure.exe'
};

$filename = match($level) {
    1 => '../file/aleatoire.txt',
    2 => '../file/aleatoiredeux.txt',
    3 => '../file/aleatoiretrois.txt'
};

$output = shell_exec('cd ../../JEU && '.$executable.' 2>&1');

$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$currentMap = $lines ?: [];

if (!$lines) {
    die("Erreur : Impossible de lire le fichier $filename");
}

$soundFile = "../son/sonaleatoire.mp3";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <title>BlockMirror - Random</title>
    <style>
        #btn-hint {
            font-family: 'Bangers', cursive;
            font-size: 3rem;
            color: white;
            background: transparent;
            border: none;
            cursor: pointer;
            user-select: none;
            transition: transform .2s, color .2s;
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }
        #btn-hint:hover {
            color: #9e01df;
            transform: translateX(-50%) scale(1.15);
        }
        #black-fade {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: black;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 1s ease-in-out;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            color: white;
            font-family: monospace;
        }
        #bottom-bar{
            position:fixed;
            bottom:0; left:0;
            width:97%;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 30px 20px;
            z-index:50;
        }
        #btn-home{
            font-family:'Bangers',cursive;
            font-size:3rem;
            color:white;
            background:transparent;
            border:none;
            cursor:pointer;
            user-select:none;
            transition:transform .2s,color .2s;
        }
        #btn-home:hover{
            color:yellow;
            transform:scale(1.15);
        }
        #btn-replay{
            width:70px;height:70px;
            border-radius:50%;
            border:none;
            background:#ffd700;
            display:flex;align-items:center;justify-content:center;
            cursor:pointer;
            box-shadow:0 0 12px #ffd700;
            transition:transform .2s,box-shadow .2s;
        }
        #btn-replay:hover{
            transform:scale(1.12);
            box-shadow:0 0 20px #ffd700;
        }
        #btn-replay svg{
            width:34px;height:34px;
            fill:black;
            pointer-events:none;
        }
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #000;
            background-image: url('../pictures/fond.png');
        }
        #game {
            white-space: pre;
            font-size: 40px;
            line-height: 0.8;
            user-select: none;
            padding: 10px 20px;
            max-width: 100%;
            overflow: hidden;
        }
        .wall { color: #888; }
        .coin { color: #ffcc00; }
        .player { color: #e63946; }
        .playerRight { color: #f1faee; }
        .floor { color: #a8dadc; }
        .space { color: #111; }
        .exit { color: #06d6a0; }
        .fade-in { animation: fadeIn 1s ease-in-out; }
        .fade-out { animation: fadeOut 1s ease-in-out; }
        .dehors-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background: transparent;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .exit-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/sortie.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .porte-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/porte_fermee_verticale.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .cle-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/cle.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .space-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/trou.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .wall-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/mur.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .piece-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/piece.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .player-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/personnage.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .player-img.up {
            background-image: url('../pictures/personnageDroite.png');
        }
        .player-img.down {
            background-image: url('../pictures/personnageGauche.png');
        }
        .player-img.left {
            background-image: url('../pictures/personnageBas.png');
        }
        .player-img.right {
            background-image: url('../pictures/personnage.png');
        }
        .ghost-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/ombre.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .tonneau-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/tonneau.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .miroir-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/miroir.png');
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
            display: inline-block;
            background-size: 80% 100%;
            margin: 0 -0.4em;
        }
        .ghost-img.up {
            background-image: url('../pictures/ombreDroite.png');
        }
        .ghost-img.down {
            background-image: url('../pictures/ombreGauche.png');
        }
        .ghost-img.left {
            background-image: url('../pictures/ombre.png');
        }
        .ghost-img.right {
            background-image: url('../pictures/ombreBas.png');
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .floor-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background: url("../pictures/plancher.jpg") center/contain no-repeat;
            vertical-align: middle;
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        body {
            opacity: 0;
            transition: opacity 1s;
        }
        body.loaded {
            opacity: 1;
        }
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&display=swap');
        .intro-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 2000;
            overflow: hidden;
        }
        .red-bar,
        .black-bar,
        .beige-bar {
            position: absolute;
            width: 100vw;
            height: 100vh;
        }
        .red-bar {
            background-color: #9e01df;
            transform: translateX(0);
            right: 80%;
            z-index: 3;
            transform: translateX(0) rotate(-3deg);
            height: 4000px;
            animation: slideLeft 1.2s ease-in forwards;
            animation-delay: 0.6s;
        }
        .black-bar {
            background-color: #171717;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            transform: translate(0, 0);
            animation: slideDiag 1.6s ease-out forwards, fadeOut 1.2s ease-out forwards;
            animation-delay: 0.5s, 0.8s;
            z-index: 2;
        }
        .beige-bar.initial {
            background-color: #f5f0e1;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-top: 10px;
            transform: rotate(-7deg) translateY(90%);
        }
        @keyframes slideLeft {
            0% { transform: translateX(0) rotate(-3deg); }
            100% { transform: translateX(-100vw) rotate(-3deg); }
        }
        @keyframes slideDiag {
            to { transform: translate(100px, -80px); }
        }
        @keyframes slideDown {
            to { transform: translateY(100%); }
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(-100%); }
        }
        .text-block {
            position: absolute;
            top: 30%;
            left: 20%;
            color: #9e01df;
            text-shadow: 2px 2px 4px rgba(0, 64, 0, 0.7), 0 0 10px rgba(40, 167, 69, 0.5);
            font-family: 'Rajdhani', sans-serif;
            text-transform: uppercase;
            z-index: 10;
            animation: fadeOut 0.6s ease-out forwards;
            animation-delay: 0.6s;
            transform: skewX(4deg);
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7), 0 0 10px rgba(255, 0, 0, 0.5);
        }
        .text-block .stage {
            font-weight: bold;
            font-size: 4.8rem;
        }
        .text-block .zone {
            font-size: 3.6rem;
        }
        .text-block .title {
            font-size: 4.8rem;
            font-weight: bold;
        }
        .beige-bar.animate {
            animation: slideDown 0.3s cubic-bezier(0.6, 0.05, 1, 1) forwards;
        }
        .best-time {
            position: absolute;
            bottom: 1%;
            left: 70%;
            font-family: 'Rajdhani', sans-serif;
            font-size: 4.8rem;
            font-weight: bold;
            color: #171717;
            text-transform: uppercase;
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8);
            z-index: 4;
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: rotate(-4deg);
            animation: moveRightAccelerate 1s forwards;
            animation-delay: 2s;
        }
        @keyframes moveRightAccelerate {
            0% {
                transform: rotate(-4deg) translateX(0);
                letter-spacing: normal;
                opacity: 1;
            }
            50% {
                letter-spacing: 0.15em;
            }
            100% {
                transform: rotate(-4deg) translateX(160%);
                letter-spacing: 0.2em;
                opacity: 0;
            }
        }
        .best-time .label {
            font-size: 2rem;
            font-weight: bold;
        }
        .best-time .time {
            font-size: 4.8rem;
            font-weight: bold;
        }
        .best-time .time,
        .best-time .rank {
            font-size: inherit;
            font-weight: inherit;
        }
        .nbrMorts {
            position: absolute;
            bottom: 1%;
            left: 40%;
            font-family: 'Rajdhani', sans-serif;
            font-size: 4.8rem;
            font-weight: bold;
            color: #171717;
            text-transform: uppercase;
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8);
            z-index: 4;
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: rotate(-4deg);
            animation: moveRightAccelerate 1s forwards;
            animation-delay: 2s;
        }
        .nbrMorts .label {
            font-size: 2rem;
            font-weight: bold;
        }
        .nbrMorts .morts {
            font-size: 4.8rem;
            font-weight: bold;
        }
        .global-timer {
            position: fixed;
            top: 20px;
            right: 20px;
            font-family: 'Bangers', cursive;
            font-size: 2rem;
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 10px;
            border: 2px solid #ffcc00;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
        }
        .global-timer .label {
            color: #ffcc00;
        }
        .global-timer .time {
            font-family: 'Orbitron', monospace;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div id="black-fade"></div>
    <div class="global-timer">
        <div class="label">Temps Total:</div>
        <div class="time" id="total-game-timer">00:00</div>
    </div>
    <div id="bottom-bar">
        <button id="btn-home" onclick="goHome()">HOME</button>
        <button id="btn-hint" onclick="toggleHint()">HINT</button>
        <form method="get" action="" style="margin:0">
            <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">
            <button id="btn-home" type="submit" title="Rejouer Niveau Actuel">RETRY</button>
        </form>
    </div>
    <div id="inventory" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 10px; border: 2px solid #ffcc00;">
        <div style="display: flex; align-items: center; gap: 5px;">
            <img src="../pictures/cle.png" style="width: 30px; height: 30px;">
            <span id="key-count" style="font-family: 'Bangers', cursive; font-size: 1.5rem; color: #ffcc00;">0</span>
        </div>
    </div>
    <?php if (!empty($soundFile)): ?>
    <audio autoplay loop id="level-audio">
        <source src="<?= htmlspecialchars($soundFile) ?>" type="audio/mpeg">
    </audio>
    <?php endif; ?>
    <div id="game"></div>
    <div class="intro-wrapper">
      <div class="red-bar"></div>
      <div class="black-bar">
        <div class="text-block">
            <div class="stage">STAGE <?= $level ?></div>
            <div class="zone">BlockMirror</div>
            <div class="title">RANDOM LEVEL</div>
        </div>
      </div>
      <div class="best-time">
        <div class="label">Best Time : </div>
        <div class="time">--:--</div>
      </div>
      <div class="nbrMorts">
        <div class="label">Number of deaths: </div>
        <div class="morts">--</div>
      </div>
      <div class="beige-bar initial"></div>
    </div>
    <audio id="home-sound" src="../son/son1.mp3"></audio>
    <audio id="hint-sound" src="../son/son1.mp3"></audio>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const beigeBar = document.querySelector('.beige-bar.initial');
        setTimeout(() => {
          beigeBar.classList.add('animate');
        }, 2000);
        document.body.classList.add('loaded');
        render();
        adjustGameSize();
        startGlobalTimer();
        document.getElementById('black-fade').style.opacity = '0';
    });

    function goHome() {
        const homeSound = document.getElementById('home-sound');
        if (homeSound) {
            homeSound.currentTime = 0;
            homeSound.play();
        }
        fetch('clear_random_session.php', { method: 'POST' })
            .then(() => {
                const blackFade = document.getElementById('black-fade');
                document.querySelectorAll('button, a').forEach(el => {
                    el.style.pointerEvents = 'none';
                });
                blackFade.style.opacity = '1';
                setTimeout(() => {
                    window.location.href = 'niveaumenujeu.php';
                }, 1000);
            });
    }

    let keysCollected = 0;
    const DOOR_SYMBOL = 'P';
    const KEY_SYMBOL = 'C';
    let map = <?= json_encode($currentMap) ?>.map(row => row.split(''));
    let startPos = { x: 0, y: 0 };
    let startPosRight = { x: 0, y: 0 };
    for (let y = 0; y < map.length; y++) {
        for (let x = 0; x < map[y].length; x++) {
            if (map[y][x] === 'O') startPos = { x, y };
            if (map[y][x] === 'Q') startPosRight = { x, y };
        }
    }
    let playerPos = { ...startPos };
    let playerPosRight = { ...startPosRight };
    let playerDirection = 'right';
    let ghostDirection = 'left';
    let hintActive = false;
    const seriesStartTime = <?= $series_start_time_js ?>;
    let globalTimerInterval;
    let totalSecondsElapsed = 0;

    function startGlobalTimer() {
        totalSecondsElapsed = Math.floor(Date.now() / 1000) - seriesStartTime;
        if (totalSecondsElapsed < 0) totalSecondsElapsed = 0;
        updateGlobalTimerDisplay();
        if (globalTimerInterval) {
            clearInterval(globalTimerInterval);
        }
        globalTimerInterval = setInterval(() => {
            totalSecondsElapsed++;
            updateGlobalTimerDisplay();
        }, 1000);
    }

    function updateGlobalTimerDisplay() {
        const minutes = Math.floor(totalSecondsElapsed / 60);
        const seconds = totalSecondsElapsed % 60;
        const timerElement = document.getElementById('total-game-timer');
        if (timerElement) {
            timerElement.textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }

    function stopGlobalTimer() {
        clearInterval(globalTimerInterval);
    }

    function toggleHint() {
        const hintSound = document.getElementById('hint-sound');
        if (hintSound) {
            hintSound.currentTime = 0;
            hintSound.play();
        }
        hintActive = !hintActive;
        const btnHint = document.getElementById('btn-hint');
        btnHint.style.color = hintActive ? '#9e01df' : 'white';
        render();
    }

    function render() {
        const gameDiv = document.getElementById("game");
        let displayStr = "";
        for (let y = 0; y < map.length; y++) {
            for (let x = 0; x < map[y].length; x++) {
                let ch = map[y][x];
                let cls = "";
                let content = ch;
                switch(ch) {
                    case '#': cls="wall"; content = `<span class="wall-img"></span>`; break;
                    case 'O': cls = "player"; content = `<span class="player-img ${playerDirection}"></span>`; break;
                    case 'Q': cls = "ghost"; content = `<span class="ghost-img ${ghostDirection}"></span>`; break;
                    case '$': cls = "coin"; content = '<span class="piece-img"></span>'; break;
                    case 'T': cls = "tonneau"; content = `<span class="tonneau-img"></span>`; break;
                    case 'B': cls = "floor"; content = '<span class="floor-img"></span>'; break;
                    case ' ': cls = "space"; content = '<span class="space-img"></span>'; break;
                    case 'D': cls = "dehors"; content = '<span class="dehors-img"></span>'; break;
                    case 'S': cls = "exit"; content = '<span class="exit-img"></span>'; break;
                    case '|': cls = "miroir"; content = '<span class="miroir-img"></span>'; break;
                    case 'P': cls = "porte"; content = '<span class="porte-img"></span>'; break;
                    case 'C': cls = "cle"; content = '<span class="cle-img"></span>'; break;
                    case 'W': cls = "floor"; content = `<span class="floor-img" style="background-image: url('${hintActive ? '../pictures/indice.jpg' : '../pictures/plancher.jpg'}')"></span>`; break;
                }
                displayStr += cls ? `<span class="${cls}">${content}</span>` : content;
            }
            displayStr += "\n";
        }
        gameDiv.innerHTML = displayStr;
    }

    function canMove(x, y) {
        if (y < 0 || y >= map.length || x < 0 || x >= map[y].length) return false;
        let ch = map[y][x];
        if (ch === DOOR_SYMBOL && keysCollected > 0) return true;
        return ch !== '#' && ch !== 'D' && ch !== DOOR_SYMBOL;
    }

    function tryOpenDoor(x, y) {
        if (map[y][x] === DOOR_SYMBOL && keysCollected > 0) {
            keysCollected--;
            document.getElementById('key-count').textContent = keysCollected;
            map[y][x] = 'B';
            return true;
        }
        return false;
    }

    function tryPushBarrel(x, y, dx, dy) {
        if (map[y][x] !== 'T') return true;
        let nx = x + dx, ny = y + dy;
        let next = map[ny]?.[nx];
        if (!next || next === '#' || next === 'D' || next === 'T') return false;
        if (next === ' ') { map[ny][nx] = 'B'; map[y][x] = 'B'; }
        else if (next === 'B') { map[ny][nx] = 'T'; map[y][x] = 'B';}
        else return false;
        return true;
    }

    function moveBoth(dxLeft, dyLeft) {
        let newLeftX = playerPos.x + dxLeft;
        let newLeftY = playerPos.y + dyLeft;
        let dxRight = -dxLeft;
        let newRightX = playerPosRight.x + dxRight;
        let newRightY = playerPosRight.y + dyLeft;

        if (dxLeft === -1) { playerDirection = 'down'; ghostDirection = 'up'; }
        else if (dxLeft === 1) { playerDirection = 'up'; ghostDirection = 'down'; }
        else if (dyLeft === -1) { playerDirection = 'left'; ghostDirection = 'right'; }
        else if (dyLeft === 1) { playerDirection = 'right'; ghostDirection = 'left'; }

        if (map[newLeftY][newLeftX] === KEY_SYMBOL) {
            keysCollected++;
            document.getElementById('key-count').textContent = keysCollected;
            map[newLeftY][newLeftX] = 'B';
        }
        if (map[newRightY][newRightX] === KEY_SYMBOL) {
            keysCollected++;
            document.getElementById('key-count').textContent = keysCollected;
            map[newRightY][newRightX] = 'B';
        }
        const isDoorLeft = map[newLeftY][newLeftX] === DOOR_SYMBOL;
        const isDoorRight = map[newRightY][newRightX] === DOOR_SYMBOL;
        if ((isDoorLeft || isDoorRight) && keysCollected === 0) return;
        if (isDoorLeft) tryOpenDoor(newLeftX, newLeftY);
        if (isDoorRight) tryOpenDoor(newRightX, newRightY);
        if (!canMove(newLeftX, newLeftY) && map[newLeftY][newLeftX] !== 'T') return;
        if (!canMove(newRightX, newRightY) && map[newRightY][newRightX] !== 'T') return;
        if (!tryPushBarrel(newLeftX, newLeftY, dxLeft, dyLeft)) return;
        if (!tryPushBarrel(newRightX, newRightY, dxRight, dyLeft)) return;

        if (map[newLeftY][newLeftX] === ' ' || map[newRightY][newRightX] === ' ') {
            handledeathJS();
            return;
        }
        if (map[newLeftY][newLeftX] === 'S' || map[newRightY][newRightX] === 'S') {
            goToVictory();
            return;
        }
        map[playerPos.y][playerPos.x] = 'B';
        map[playerPosRight.y][playerPosRight.x] = 'B';
        map[newLeftY][newLeftX] = 'O';
        map[newRightY][newRightX] = 'Q';
        playerPos = { x: newLeftX, y: newLeftY };
        playerPosRight = { x: newRightX, y: newRightY };
        render();
    }

    function handledeathJS() {
        stopGlobalTimer();
        fetch('clear_random_session.php', { method: 'POST' })
            .then(() => {
                window.location.href = 'mortdeux.php';
            });
    }

    function goToVictory() {
        const currentLevel = <?= $level ?>;
        if (currentLevel < 3) {
            const nextLevel = currentLevel + 1;
            document.body.classList.remove('loaded');
            document.body.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = `niveaualeatoire.php?level=${nextLevel}`;
            }, 1000);
        } else {
            document.body.classList.remove('loaded');
            document.body.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = "victoirealeatoire.php";
            }, 1000);
        }
    }

    document.addEventListener("keydown", e => {
        switch (e.key.toLowerCase()) {
            case "arrowup": case "z": moveBoth(0, -1); break;
            case "arrowdown": case "s": moveBoth(0, 1); break;
            case "arrowleft": case "q": moveBoth(-1, 0); break;
            case "arrowright": case "d": moveBoth(1, 0); break;
        }
    });

    function adjustGameSize() {
        const game = document.getElementById('game');
        const rows = map.length;
        const cols = map[0].length;
        const horizontalPadding = 40;
        const verticalPadding = 120;
        const maxWidth = window.innerWidth - horizontalPadding;
        const maxHeight = window.innerHeight - verticalPadding;
        const fontSizeBasedOnWidth = maxWidth / cols;
        const fontSizeBasedOnHeight = maxHeight / rows;
        const newFontSize = Math.max(12, Math.min(fontSizeBasedOnWidth, fontSizeBasedOnHeight));
        game.style.fontSize = `${newFontSize}px`;
    }
    adjustGameSize();
    window.addEventListener('resize', adjustGameSize);
</script>
</body>
</html>