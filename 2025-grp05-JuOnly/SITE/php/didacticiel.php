<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}    

$level = isset($_GET['level']) ? intval($_GET['level']) : 1;

$soundFile = '';
if ($level >= 1 && $level <= 2) {
    $soundFile = '../son/sondida.mp3';
}

$maps = [
    1 => [
        "#O#########|#########Q#",
        "#BB BBHBB##|##BBHBBBBB#",
        "##BBB#T####|####T#BBB##",
        "###### ####|#### ######",
        " BBBBBB####|####BBBBBB#",
        " C###P#####|#####B###C ",
        " ####BT BMS|SMB TB#### ",
        "###########|###########",
    ]
];


$currentMap = $maps[$level] ?? $maps[1];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <title>BlockMirror - Tutorial</title>
    <style>
        #btn-home {
            position: fixed;
            top: 20px;
            left:47.5%;
            font-family: 'Bangers', cursive;
            font-size: 3rem;
            color: #888;
            background: transparent;
            border: none;
            cursor: pointer;
            user-select: none;
            transition: transform .2s, color .2s;
            z-index: 200;
        }
        
        #btn-home:hover {
            color: white;
            transform: scale(1.15);
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-image: url('../pictures/fond.png');
            color: white;
            font-family: monospace;
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

        .miroir-img{
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

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 90;
            display: none;
        }

        #didac {
            position: fixed;
            top: 5%;
            left: 0;
            width: 100%;
            height: 130vh;
            z-index: 100;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        #didac img {
            max-height: 85%;
            max-width: 95%;
            object-fit: contain;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #000;
        }

        #game {
            white-space: pre;
            font-size: 80px;
            user-select: none;
            padding: 10px 20px;
            line-height: 0.8;
            max-width: 100%;
            overflow: hidden;
        }
        
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        
        .fade-out {
            animation: fadeOut 1s ease-in-out;
        }
        
        .dehors-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/dehors.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
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
            background-size: 80% 80%;
            margin: 0 -0.4em;
            transform: scaleX(0.3);
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
        
        .bulle-container {
            position: relative;
            display: inline-block;
            max-width: 100%;
            max-height: 85%;
        }

        .bulle-img {
            display: block;
            width: 100%;
            height: auto;
        }

        .bulle-text {
            position: absolute;
            top: 64%;
            left: 45%;
            transform: translate(-50%, -50%);
            width: 60%;
            font-family: 'Bangers', cursive;
            font-size: 2em;
            color: black;
            padding: 0.2em 0.5em;
            text-align: left;
        }
        
        body {
            opacity: 0;
            transition: opacity 1s;
        }
        
        body.loaded {
            opacity: 1;
        }
        
        .chibi-img {
            position: absolute;
            top:29%;
            left: 7%;
            height: 200px;
            z-index: 101;
        }

        .chibideux-img {
            position: absolute;
            top:27%;
            left: 75%;
            height: 200px;
            z-index: 101;
        }

        .shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% { transform: translate(1px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(3px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            60% { transform: translate(-3px, 1px) rotate(0deg); }
            70% { transform: translate(3px, 1px) rotate(-1deg); }
            80% { transform: translate(-1px, -1px) rotate(1deg); }
            90% { transform: translate(1px, 2px) rotate(0deg); }
            100% { transform: translate(1px, -2px) rotate(-1deg); }
        }
        * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      width: 100%;
      overflow: hidden;
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

@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&display=swap');

    @keyframes move-up {
      to { transform: translateY(-100vh); }
    }

    @keyframes move-left {
      to { transform: translateX(-100vw); }
    }

    @keyframes move-up-right {
      to { transform: translate(100vw, -100vh); }
    }

    @keyframes move-right {
      to { transform: translateX(100vw); }
    }

    @keyframes move-down {
      to { transform: translateY(100vh); }
    }
@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&display=swap');

.intro-wrapper {
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  pointer-events: none;
  z-index: 2000;
  overflow: hidden;
}

.red-bar, .black-bar, .beige-bar {
  position: absolute;
  width: 100vw;
  height: 100vh;
}

.red-bar {
  background-color: #28a745;
  transform: translateX(0);
  right:80%;
  z-index: 3;
  transform: translateX(0) rotate(-3deg);
  height:4000px;
  animation: slideLeft 1.2s ease-in forwards;
  animation-delay: 0.6s;
}

.black-bar {
  background-color: #171717;
  clip-path: polygon(0 0, 100% 0, 100% 80%, 0 100%);
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
  0% {
    transform: translateX(0) rotate(-3deg);
  }
  100% {
    transform: translateX(-100vw) rotate(-3deg);
  }
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
  color: #28a745;
  text-shadow: 2px 2px 4px rgba(0, 64, 0, 0.7), 0 0 10px rgba(40, 167, 69, 0.5);
  font-family: 'Rajdhani', sans-serif;
  text-transform: uppercase;
  z-index: 10;
  animation: fadeOut 0.6s ease-out forwards;
  animation-delay: 0.6s;
  transform: skewX(4deg);
}

.text-shadow {
  text-shadow:
    2px 2px 4px rgba(0, 0, 0, 0.7),
    0 0 10px rgba(255, 0, 0, 0.5);
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

    </style>
</head>
<body>
    <button id="btn-home" onclick="goHome()">HOME</button>

    <div id="game"></div>

<div class="intro-wrapper">
  <div class="red-bar"></div>
  <div class="black-bar">
    <div class="text-block">
      <div class="stage">STAGE <?= htmlspecialchars($level) ?></div>
      <div class="zone">BlockMirror</div>
      <div class="title">DIDACTICIEL</div>
    </div>
  </div>
  <div class="beige-bar initial"></div>
</div>
<audio id="shake-sound">
    <source src="../son/cognement.mp3" type="audio/mpeg">
</audio>
<audio id="next-sound">
    <source src="../son/sonpourpasser.mp3" type="audio/mpeg">
</audio>
<audio id="voice-sound">
    <source src="../son/sonparler.mp3" type="audio/mpeg">
</audio>
<?php if (!empty($soundFile)): ?>
<audio autoplay loop>
    <source src="<?= htmlspecialchars($soundFile) ?>" type="audio/mpeg">
</audio>
<?php endif; ?>
<div id="game"></div>
<script>
        function goHome() {
            const blackFade = document.createElement('div');
            blackFade.style.position = 'fixed';
            blackFade.style.top = '0';
            blackFade.style.left = '0';
            blackFade.style.width = '100%';
            blackFade.style.height = '100%';
            blackFade.style.backgroundColor = 'black';
            blackFade.style.zIndex = '9999';
            blackFade.style.opacity = '0';
            blackFade.style.transition = 'opacity 1s ease-in-out';
            document.body.appendChild(blackFade);
            
            document.querySelectorAll('button, a').forEach(el => {
                el.style.pointerEvents = 'none';
            });
            
            setTimeout(() => {
                blackFade.style.opacity = '1';
            }, 10);
            
            setTimeout(() => {
                window.location.href = 'niveaumenujeu.php';
            }, 1000);
        }
  window.addEventListener('DOMContentLoaded', () => {
    const beigeBar = document.querySelector('.beige-bar.initial');
    setTimeout(() => {
      beigeBar.classList.add('animate');
    }, 750);
  });


let isBlocked = true;
let chibiImages = [
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/chibichoque.png',
    '../pictures/didacticiel/ombre.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/chibichoque.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/rine.png',
];
let chibiImagesdeux = [
    '../pictures/didacticiel/chibiraff/contentraff.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/chibiraff/malicieuxraff.png',
    '../pictures/didacticiel/chibiraff/antagonisteraff.png',
    '../pictures/didacticiel/chibiraff/malicieuxraff.png',
    '../pictures/didacticiel/rine.png',
    '../pictures/didacticiel/chibiraff/malicieuxraff.png',
    '../pictures/didacticiel/chibiraff/malicieuxraff.png',
    '../pictures/didacticiel/chibiraff/antagonisteraff.png',
    '../pictures/didacticiel/chibiraff/coquinraff.png',
];


let didacImages = [
    '../pictures/didacticiel/bule1.png',
    '../pictures/didacticiel/bule2.png',
    '../pictures/didacticiel/bule3.png'
];

const overlay = document.createElement('div');
overlay.id = 'overlay';
document.body.appendChild(overlay);

const didacContainer = document.createElement('div');
didacContainer.id = 'didac';
document.body.appendChild(didacContainer);

let didacTexts = [
    "Hey ! I'm Raffaele from the JuniaOnly project, one of the creators of the game. Leon, Noel, you are the characters I created !",
    "What ?",
    '...',
    "Let's say I'm doing an experiment: I'm trying to see what happens when you separate a person from their shadow.",
    "And I found this answer, it allows us to control people, given that the body must follow the shadow and vice versa.",
    "We're going to do a little demonstration, move down Noel (The shadow) and Leon (The red character) will follow !",
    "What was that ???",
    "The user moved, he moved your shadow. So, you moved in symmetry with the mirror.",
    "When your shadow moves, so do you. However, if the shadow dies, you die too.",
    "It's your turn to play now !",
];


let didacStep = 0;
let bullePhase = 0;
let pauseAtStep = 5;
let shouldPause = false;
let hasMovedDown = false;

const parlerAudio = new Audio('../son/sonparler.mp3');
parlerAudio.loop = true;
let endTriggered = false;
let triggeredFinalDialogue = false;
let deathStep = 21;
function isNearExit(pos) {
    const directions = [
        { dx: -1, dy: 0 }, { dx: 1, dy: 0 },
        { dx: 0, dy: -1 }, { dx: 0, dy: 1 }
    ];
    for (let d of directions) {
        const nx = pos.x + d.dx;
        const ny = pos.y + d.dy;
        if (map[ny]?.[nx] === 'S') return true;
    }
    return false;
}

function afficherTexteLettreParLettre(element, texte, callback) {
    let i = 0;
    element.innerHTML = "";
    parlerAudio.currentTime = 0;
    parlerAudio.play();

    const interval = setInterval(() => {
        element.textContent += texte.charAt(i);
        i++;
        if (i >= texte.length) {
            clearInterval(interval);
            parlerAudio.pause();
            parlerAudio.currentTime = 0;
            if (callback) callback();
        }
    }, 30);
}

function showDidacStep() {
    if (didacStep >= didacTexts.length) {
        didacContainer.style.display = 'none';
        overlay.style.display = 'none';
        isBlocked = false;
        if (endTriggered && didacStep === deathStep) {
            setTimeout(() => {
                window.location.href = 'mortdeux.php';
            }, 3500);
        }
        return;
    }

setTimeout(() => {
    if (didacStep === pauseAtStep) {
        shouldPause = true;
    }
}, 3500);

    isBlocked = true;
    bullePhase = 0;
    overlay.style.display = 'block';
    didacContainer.style.display = 'flex';
    didacContainer.innerHTML = `
        <div class="bulle-container">
            <img src="${didacImages[bullePhase]}" class="bulle-img">
        </div>
    `;

    setTimeout(() => {
        bullePhase = 1;
        const nextSound = document.getElementById('next-sound');
        nextSound.currentTime = 0;
        nextSound.play();

        didacContainer.innerHTML = `
            <div class="bulle-container">
                <img src="${didacImages[bullePhase]}" class="bulle-img">
            </div>
        `;

        setTimeout(() => {
            bullePhase = 2;
            didacContainer.innerHTML = `
                <div class="bulle-container">
                    <img src="${didacImages[bullePhase]}" class="bulle-img">
                    <img src="${chibiImages[didacStep]}" class="chibi-img" style="position:absolute; bottom:0; right:5%; height:200px;">
                    <img src="${chibiImagesdeux[didacStep]}" class="chibideux-img" style="position:absolute; bottom:0;right:5%; height:200px;">
                    <div class="bulle-text" id="bulleText"></div>
                </div>
            `;

            const bulleText = document.getElementById('bulleText');
            afficherTexteLettreParLettre(bulleText, didacTexts[didacStep], () => {
                if (shouldPause && didacStep === pauseAtStep) {
                    isBlocked = false;
                    overlay.style.display = 'none';
                    didacContainer.style.display = 'none';
                }
            });
        }, 150);
    }, 150);

    if (didacStep === 22) {
        setTimeout(() => {
            window.location.href = "mortdeux.php";
        }, 3500);
    }
}

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
let playerDirection = 'down';
let ghostDirection = 'down';

function render() {
    const gameDiv = document.getElementById("game");
    let displayStr = "";

    for (let y = 0; y < map.length; y++) {
        for (let x = 0; x < map[y].length; x++) {
            let ch = map[y][x];
            let cls = "";
            let content = ch;

            switch(ch) {
                case '#': 
                    cls="wall";
                    content = `<span class="wall-img"></span>`;
                    break;
                case 'T':
                    cls = "tonneau";
                    content = `<span class="tonneau-img"></span>`;
                    break;
                case 'O': 
                    cls = "player";
                    content = `<span class="player-img ${playerDirection}"></span>`;
                    break;
                case 'Q': 
                    cls = "ghost";
                    content = `<span class="ghost-img ${ghostDirection}"></span>`;
                    break;
                case 'B':
                    cls = "floor";
                    content = '<span class="floor-img"></span>';
                    break;
                case ' ': 
                    cls = "space";
                    content = '<span class="space-img"></span>';
                    break;
                case 'D': 
                    cls = "dehors";
                    content = '<span class="dehors-img"></span>';
                    break;
                case 'S': 
                    cls = "exit"; 
                    content = '<span class="exit-img"></span>';
                    break;
                case 'W':
                    cls = "floor";
                    content = '<span class="floor-img"></span>';
                    break;
                case 'H':
                    cls = "floor";
                    content = '<span class="floor-img"></span>';
                    break;
                case 'M':
                    cls = "floor";
                    content = '<span class="floor-img"></span>';
                    break;
                case '|':
                    cls = "miroir";
                    content = '<span class="miroir-img"></span>';
                    break;
                case 'P':
                    cls = "porte";
                    content = '<span class="porte-img"></span>';
                    break;
                case 'C':
                    cls = "cle";
                    content = '<span class="cle-img"></span>';
                    break;
                case '|':
                    cls = "miroir";
                    content = '<span class="miroir-img"></span>';
                    break;

            }

            displayStr += `<span class="${cls}">${content}</span>`;
        }
        displayStr += '\n';
    }

    gameDiv.innerHTML = displayStr;
}

function canMove(x, y) {
    if (y < 0 || y >= map.length || x < 0 || x >= map[y].length) return false;
    let ch = map[y][x];
    return ch !== '#' && ch !== 'D';
}

function tryPushBarrel(x, y, dx, dy) {
    if (map[y][x] !== 'T') return true;
    let nx = x + dx, ny = y + dy;
    let next = map[ny]?.[nx];
    if (!next || next === '#' || next === 'D' || next === 'T') return false;
    if (next === ' ') {
        map[ny][nx] = 'B';
        map[y][x] = 'B';
    } else if (next === 'B') {
        map[ny][nx] = 'T';
        map[y][x] = 'B';
    } else return false;
    return true;
}

function moveBoth(dxLeft, dyLeft) {
    if (isBlocked && !shouldPause) return;
    
    let newLeftX = playerPos.x + dxLeft;
    let newLeftY = playerPos.y + dyLeft;
    let dxRight = -dxLeft;
    let newRightX = playerPosRight.x + dxRight;
    let newRightY = playerPosRight.y + dyLeft;

    if (dxLeft === -1) {
        playerDirection = 'down';
        ghostDirection = 'up';
    } else if (dxLeft === 1) {
        playerDirection = 'up';
        ghostDirection = 'down';
    } else if (dyLeft === -1) {
        playerDirection = 'left';
        ghostDirection = 'right';
    } else if (dyLeft === 1) {
        playerDirection = 'right';
        ghostDirection = 'left';
    }

    if (!canMove(newLeftX, newLeftY) && map[newLeftY][newLeftX] !== 'T' && map[newLeftY][newLeftX] !== '€') return;
    if (!canMove(newRightX, newRightY) && map[newRightY][newRightX] !== 'T' && map[newRightY][newRightX] !== '€') return;

    if (!tryPushBarrel(newLeftX, newLeftY, dxLeft, dyLeft)) return;
    if (!tryPushBarrel(newRightX, newRightY, dxRight, dyLeft)) return;

    if (map[newLeftY][newLeftX] === '€') map[newLeftY][newLeftX] = 'B';
    if (map[newRightY][newRightX] === '€') map[newRightY][newRightX] = 'B';

    if (map[newLeftY][newLeftX] === ' ' || map[newRightY][newRightX] === ' ') {
        window.location.href = 'mortdeux.php';
        return;
    }
    if (map[newLeftY][newLeftX] === 'H' || map[newRightY][newRightX] === 'H') {
        window.location.href = 'didacticieldeux.php';
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
if (!triggeredFinalDialogue && (isNearExit(playerPos) || isNearExit(playerPosRight))) {
    triggeredFinalDialogue = true;
    isBlocked = true;
    didacStep = 20;
    showDidacStep();
    return;
}
    if (shouldPause && dyLeft === 1 && !hasMovedDown) {
    const shakeSound = document.getElementById('shake-sound');
    shakeSound.currentTime = 0;
    shakeSound.play();
        hasMovedDown = true;
        document.body.classList.add('shake');
        setTimeout(() => {
            document.body.classList.remove('shake');
            shouldPause = false;
            didacStep++;
            showDidacStep();
        }, 300);
    }
}

function goToVictory() {
    document.body.classList.remove('loaded');
    document.body.classList.add('fade-out');
    setTimeout(() => window.location.href = "victoire.php", 1000);
}

document.addEventListener('keydown', (e) => {
    if (isBlocked && !shouldPause) {
        if (e.key === 'Enter' || e.key === ' ') {
            if (bullePhase === 2) {
                didacStep++;
                showDidacStep();
            } else {
                showDidacStep();
            }
        }
        return;
    }

    switch (e.key) {
        case "ArrowUp":
        case "z": moveBoth(0, -1); break;
        case "ArrowDown":
        case "s": moveBoth(0, 1); break;
        case "ArrowLeft":
        case "q": moveBoth(-1, 0); break;
        case "ArrowRight":
        case "d": moveBoth(1, 0); break;
    }
});

setTimeout(() => {
    showDidacStep();
}, 2500);

document.querySelectorAll('audio').forEach(audio => {
    audio.volume = 0;
    const interval = setInterval(() => {
        if (audio.volume < 1) {
            audio.volume = Math.min(1, audio.volume + 0.05);
        } else {
            clearInterval(interval);
        }
    }, 100);
});

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('loaded');
    render();
    adjustGameSize();
});

function adjustGameSize() {
    const game = document.getElementById('game');
    if (!game) {
        console.error('Élément #game introuvable');
        return;
    }
    
    const rows = map.length;
    const cols = map[0].length;
    console.log(`Taille calculée - Lignes: ${rows}, Colonnes: ${cols}`);

    console.log(`Nouvelle taille police: ${newFontSize}px`);
    game.style.fontSize = `${newFontSize}px`;
}

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

