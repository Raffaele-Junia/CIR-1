<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>BlockMirror - Selection</title>
  <link rel="icon" href="../pictures/logojeu.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Bungee&display=swap');
.difficulty-arrows {
  position: absolute;
  top: 82%;
  left:75%;
  width: 25%;
  display: none;
  justify-content: space-between;
  padding: 0 5vw;
  transform: translateY(-50%);
  z-index: 10;
}
.sound-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  font-size: 2.5vw;
  color: white;
  cursor: pointer;
  z-index: 100;
  transition: all 0.3s ease;
  text-shadow: 0 0 5px white;
}

.sound-toggle:hover {
  transform: scale(1.1);
}

.sound-toggle.muted {
  opacity: 0.5;
}
.difficulty-arrow {
  font-size: 3vw;
  color: white;
  cursor: pointer;
  user-select: none;
  text-shadow: 0 0 10px white;
  transition: transform 0.2s;
}

.difficulty-arrow:hover {
  transform: scale(1.2);
}

.difficulty-indicator {
  position: absolute;
  top: 80%;
  left: 87.5%;
  transform: translateX(-50%);
  font-size: 2.5vw;
  color: white;
  opacity: 0;
  transition: opacity 0.3s;
  white-space: nowrap;
}

.difficulty-indicator.easy {
  color: #0000ff;
  text-shadow: 0 0 2px #0000ff;
}

.difficulty-indicator.medium {
  color: orange;
  text-shadow: 0 0 3px orange;
}

.difficulty-indicator.hard {
  color: red;
  text-shadow: 0 0 6px red;
}

body.random-selected .difficulty-arrows,
body.random-selected .difficulty-indicator {
  display: flex;
  opacity: 1;
}

body.random-selected .nav-arrow {
  opacity: 0.3;
}
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      background-color: black;
      font-family: 'Bangers', cursive;
      color: white;
      overflow: hidden;
    }

    header {
      text-align: center;
      padding: 20px 0;
      font-size: 4vw;
    }

    .mirror {
      display: inline-block;
      transform: scaleX(-1);
    }

    .preview-container {
      position: relative;
      width: 100%;
      height: 50vh;
      overflow: hidden;
      border-top: 2px solid white;
      border-bottom: 2px solid white;
      transition: all 1s ease;
    }

    .preview-image {
      position: absolute;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      transition: transform 0.5s cubic-bezier(0.25, 0.1, 0.25, 1), opacity 0.3s ease;
    }

    .preview-image.next {
      transform: translateY(100%);
      opacity: 0;
    }

    .preview-image.prev {
      transform: translateY(-100%);
      opacity: 0;
    }

    .preview-image.active {
      transform: translateY(0);
      opacity: 1;
    }

    .menu-container {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 35vh;
      overflow: hidden;
      transition: all 1s ease;
    }

    .menu-list {
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease-in-out;
    }

    .menu-item {
      height: 35vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 5vw;
      color: white;
      user-select: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .menu-item::selection {
      background: none;
    }

    .footer {
      position: absolute;
      bottom: 10px;
      width: 100%;
      display: flex;
      justify-content: space-between;
      padding: 0 20px;
      font-size: 2vw;
      transition: all 1s ease;
    }

    .footer a {
      color: white;
      text-decoration: none;
      opacity: 0.7;
      cursor: pointer;
    }

    .footer a:hover {
      opacity: 1;
    }
    
    #page-fade {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index: 9999;
        opacity: 1;
        transition: opacity 1s ease-in-out;
        pointer-events: none;
    }
.menu-item.easy {
  color: #0000ff;
  font-size: 4vw;
  text-shadow: 0 0 2px #0000ff;
}

.menu-item.tuto{
  color: #00ff00;
  font-size: 3.5vw;
  text-shadow: 0 0 2px #00ff00;
}


.menu-item.medium {
  color: orange;
  font-size: 4.5vw;
  text-shadow: 0 0 3px orange;
}

.menu-item.hard {
  color: red;
  font-size: 5vw;
  text-shadow: 0 0 6px red;
  transform: scale(1.05);
}

.menu-item.extreme {
  color: crimson;
  font-size: 5.3vw;
  text-shadow: 0 0 8px crimson, 0 0 4px red;
  transform: scale(1.07);
}

.menu-item.ultimate {
  color: crimson;
  font-size: 5.6vw;
  text-shadow: 0 0 10px gold, 0 0 5px yellow;
  transform: scale(1.1);
}
.menu-item.random{
  color: #9e01df;
  font-size: 5vw;
  transform: scale(1.1);
text-shadow: 0 0 2px #9e01df, 0 0 2px violet;
}
.nav-arrow {
  position: fixed;
  left: 50%;
  transform: translateX(-50%);
  font-size: 3vw;
  color: white;
  cursor: pointer;
  user-select: none;
  z-index: 10;
  text-shadow: 0 0 10px white;
  transition: transform 0.2s, opacity 1s ease;
}

#arrow-up {
  top: 66.5%;
}

#arrow-down {
  bottom: 2vh;
}

.nav-arrow:hover {
  transform: translateX(-50%) scale(1.2);
}

.nav-arrow.hover {
  transform: translateX(-50%) scale(1.2);
}

@keyframes beat-once {
  0%   { transform: scale(1); }
  50%  { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.beat-once {
  animation: beat-once 0.6s ease forwards;
}

.clicked {
  pointer-events: none;
}
.nav-arrow.disabled {
  opacity: 0.3;
  pointer-events: none;
  cursor: default;
}

    body.random-mode header,
    body.random-mode .menu-container,
    body.random-mode .footer,
    body.random-mode .nav-arrow,
    body.random-mode .difficulty-arrows,
    body.random-mode .difficulty-indicator {
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.5s ease;
    }

    body.random-mode .preview-container {
      height: 100vh;
      border: none;
      transform: scale(1.3); 
      transition: all 1s ease;
    }

.loading-text {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 3vw;
  color: white;
  z-index: 100;
  opacity: 0;
  transition: opacity 0.5s ease;
  text-align: center;
}

body.random-mode .loading-text {
  opacity: 1;
}

.loading-text {
  position: fixed;
  top: 95%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 3.5vw;
  color: white;
  z-index: 100;
  opacity: 0;
  transition: opacity 0.4s 1s ease;
  text-align: center;
  white-space: nowrap;
  text-shadow: 
    -1px -1px 0 black,
     1px -1px 0 black,
    -1px  1px 0 black,
     1px  1px 0 black;
}

.loading-dots::after {
  content: '';
  animation: dots 1.5s steps(5, end) infinite;
}

@keyframes dots {
  0%, 20% {
    content: '.';
  }
  40% {
    content: '..';
  }
  60% {
    content: '...';
  }
  80%, 100% {
    content: '';
  }
}
  </style>
</head>
<body>
<div class="sound-toggle" id="soundToggle">🔊</div>
  <div id="page-fade"></div>
  
  <header>
    BLOCK <span class="mirror">MIRROR</span>
  </header>

  <div class="preview-container" id="previewContainer">
  </div>

  <div class="menu-container">
    <div class="menu-list" id="menuList">
<div class="menu-item tuto" data-level="1">LEVEL 1 - Tutorial</div>
<div class="menu-item tuto" data-level="2">LEVEL 2 - Advanced Tutorial</div>
<div class="menu-item easy" data-level="3">LEVEL 3 - Easy</div>
<div class="menu-item easy" data-level="4">LEVEL 4 - Easy</div>
<div class="menu-item medium" data-level="5">LEVEL 5 - Medium</div>
<div class="menu-item medium" data-level="6">LEVEL 6 - Medium</div>
<div class="menu-item hard" data-level="7">LEVEL 7 - Hard</div>
<div class="menu-item hard" data-level="8">LEVEL 8 - Hard</div>
<div class="menu-item extreme" data-level="9">LEVEL 9 - Extreme</div>
<div class="menu-item ultimate" data-level="10">LEVEL 10 - Ultimate</div>
<div class="menu-item random" data-level="random">RANDOM LEVEL</div>
    </div>
  </div>

  <div class="footer">
    <a href="#" onclick="playSoundAndRedirect('accueil.php')">Home</a>
    <a href="#" onclick="playSoundAndRedirect('creer_niveau.php')">Create a level</a>
  </div>

<div class="loading-text">
  Chargement<span class="loading-dots"></span>
</div>

  <audio id="sound1"></audio>
  <audio id="sound2"></audio>
  <audio id="sound3"></audio>
<div id="arrow-up" class="nav-arrow">&#9650;</div>
<div id="arrow-down" class="nav-arrow">&#9660;</div>
<div class="difficulty-arrows">
  <div class="difficulty-arrow" id="arrow-left">&#9664;</div>
  <div class="difficulty-arrow" id="arrow-right">&#9654;</div>
</div>

<div class="difficulty-indicator easy" id="difficultyIndicator">EASY</div>

<script>
const sound1 = document.getElementById('sound1');
const sound2 = document.getElementById('sound2');
const sound3 = document.getElementById('sound3');

sound1.src = "../son/son1.mp3";
sound2.src = "../son/sonniveau.mp3";
sound3.src = "../son/son3.mp3";

window.addEventListener('load', () => {
    const pageFade = document.getElementById('page-fade');
    pageFade.style.opacity = '1';

    sound1.load();
    sound2.load();
    sound3.load();

    sound2.currentTime = 0;
    sound2.play().catch(console.error);

    setTimeout(() => {
        pageFade.style.opacity = '0';
        setTimeout(() => {
            pageFade.style.display = 'none';
        }, 1000);
    }, 500);

    initImages();
    updateMenu();
});

const levels = [
    "NIVEAU 1 - Tutorial", "NIVEAU 2 - Advanced Tutorial", "NIVEAU 3 - Easy",
    "NIVEAU 4 - Easy", "NIVEAU 5 - Medium", "NIVEAU 6 - Medium",
    "NIVEAU 7 - Hard", "NIVEAU 8 - Hard", "NIVEAU 9 - Extreme",
    "NIVEAU 10 - Ultimate", "RANDOM LEVEL"
];

const menuList = document.getElementById('menuList');
let currentIndex = 0;
let previousIndex = 0;
let images = [];
let currentDifficulty = 'easy';
let isRandomSelected = false;

function initImages() {
    const previewContainer = document.getElementById('previewContainer');

    for (let i = 0; i < levels.length; i++) {
        const img = document.createElement('div');
        img.className = 'preview-image';
        if (i === 0) {
            img.classList.add('active');
        } else {
            img.classList.add('next');
        }

        const imagePath = i < 10
            ? `../pictures/niveau/niveau${i + 1}.png`
            : `../pictures/niveau/random.png`;
        img.style.backgroundImage = `url('${imagePath}')`;

        previewContainer.appendChild(img);
        images.push(img);
    }
}

function updateMenu() {
    const itemHeight = window.innerHeight * 0.35;
    menuList.style.transform = `translateY(-${currentIndex * itemHeight}px)`;

    images[previousIndex].classList.remove('active');
    if (currentIndex > previousIndex) {
        images[previousIndex].classList.add('prev');
        images[currentIndex].classList.remove('next');
    } else {
        images[previousIndex].classList.add('next');
        images[currentIndex].classList.remove('prev');
    }
    images[currentIndex].classList.add('active');

    previousIndex = currentIndex;
    checkRandomSelected();
    
    // Mettre à jour l'état des flèches
    const arrowUp = document.getElementById('arrow-up');
    const arrowDown = document.getElementById('arrow-down');
    
    arrowUp.classList.toggle('disabled', currentIndex === 0);
    arrowDown.classList.toggle('disabled', currentIndex === levels.length - 1);
}

function flashArrow(arrowId) {
    const arrow = document.getElementById(arrowId);
    arrow.classList.add('hover');
    setTimeout(() => {
        arrow.classList.remove('hover');
    }, 500);
}

function updateDifficultyIndicator() {
    const indicator = document.getElementById('difficultyIndicator');
    indicator.className = 'difficulty-indicator ' + currentDifficulty;
    indicator.textContent = currentDifficulty.toUpperCase();
}

function checkRandomSelected() {
    const randomItem = document.querySelector('.menu-item[data-level="random"]');
    isRandomSelected = currentIndex === Array.from(menuList.children).indexOf(randomItem);
    
    if (isRandomSelected) {
        document.body.classList.add('random-selected');
        updateDifficultyIndicator();
    } else {
        document.body.classList.remove('random-selected');
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowDown') {
        // Ne pas descendre si déjà en bas
        if (currentIndex === levels.length - 1) return;
        
        flashArrow('arrow-down');
        sound3.currentTime = 0;
        sound3.play().catch(console.error);
        previousIndex = currentIndex;
        currentIndex = (currentIndex + 1) % levels.length;
        updateMenu();
    } else if (e.key === 'ArrowUp') {
        // Ne pas monter si déjà en haut
        if (currentIndex === 0) return;
        
        flashArrow('arrow-up');
        sound3.currentTime = 0;
        sound3.play().catch(console.error);
        previousIndex = currentIndex;
        currentIndex = (currentIndex - 1 + levels.length) % levels.length;
        updateMenu();
    } else if (e.key === 'Enter') {
        const activeItem = document.querySelectorAll('.menu-item')[currentIndex];
        if (activeItem) {
            activeItem.classList.add('clicked');
            setTimeout(() => activeItem.classList.remove('clicked'), 1000);
        }
        sound1.currentTime = 0;
        sound1.play().catch(console.error);
        launchLevel(currentIndex);
    } else if (isRandomSelected && (e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
        const difficulties = ['easy', 'medium', 'hard'];
        const currentDiffIndex = difficulties.indexOf(currentDifficulty);
        let newIndex;
        
        if (e.key === 'ArrowLeft') {
            newIndex = (currentDiffIndex - 1 + difficulties.length) % difficulties.length;
        } else {
            newIndex = (currentDiffIndex + 1) % difficulties.length;
        }
        
        currentDifficulty = difficulties[newIndex];
        updateDifficultyIndicator();
        sound3.currentTime = 0;
        sound3.play().catch(console.error);
    }
});

document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function () {
        if (this.getAttribute('data-level') === 'random') {
            prepareRandomLevelTransition();
        } else {
            playSoundAndRedirect(`jeu.php?level=${this.getAttribute('data-level')}`);
        }
    });

    item.addEventListener('mouseenter', function () {
        const index = Array.from(this.parentNode.children).indexOf(this);
        if (index !== currentIndex) {
            sound3.currentTime = 0;
            sound3.play().catch(console.error);
            previousIndex = currentIndex;
            currentIndex = index;
            updateMenu();
        }
    });
});

document.getElementById('arrow-up').addEventListener('click', () => {
if (this.classList.contains('disabled')) return;
    if (currentIndex === 0) return;
    
    sound3.currentTime = 0;
    sound3.play().catch(console.error);
    previousIndex = currentIndex;
    currentIndex = (currentIndex - 1 + levels.length) % levels.length;
    updateMenu();
});

document.getElementById('arrow-down').addEventListener('click', () => {
if (this.classList.contains('disabled')) return;
    if (currentIndex === levels.length - 1) return;
    
    sound3.currentTime = 0;
    sound3.play().catch(console.error);
    previousIndex = currentIndex;
    currentIndex = (currentIndex + 1) % levels.length;
    updateMenu();
});

document.getElementById('arrow-left').addEventListener('click', () => {
    if (!isRandomSelected) return;
    
    const difficulties = ['easy', 'medium', 'hard'];
    const currentDiffIndex = difficulties.indexOf(currentDifficulty);
    const newIndex = (currentDiffIndex - 1 + difficulties.length) % difficulties.length;
    
    currentDifficulty = difficulties[newIndex];
    updateDifficultyIndicator();
    sound3.currentTime = 0;
    sound3.play().catch(console.error);
});

document.getElementById('arrow-right').addEventListener('click', () => {
    if (!isRandomSelected) return;
    
    const difficulties = ['easy', 'medium', 'hard'];
    const currentDiffIndex = difficulties.indexOf(currentDifficulty);
    const newIndex = (currentDiffIndex + 1) % difficulties.length;
    
    currentDifficulty = difficulties[newIndex];
    updateDifficultyIndicator();
    sound3.currentTime = 0;
    sound3.play().catch(console.error);
});

function launchLevel(index) {
    if (index < 10) {
        playSoundAndRedirect(`jeu.php?level=${index + 1}`);
    } else {
        prepareRandomLevelTransition();
    }
}

function prepareRandomLevelTransition() {
    sound1.currentTime = 0;
    sound1.play().catch(console.error);
    
    document.body.classList.add('random-mode');
    
    document.querySelector('.difficulty-arrows').style.display = 'none';
    document.querySelector('.difficulty-indicator').style.display = 'none';
    
    document.querySelectorAll('.menu-item, .footer a, .nav-arrow, .difficulty-arrow').forEach(el => {
        el.style.pointerEvents = 'none';
    });
    
    setTimeout(() => {
        const levelMap = { easy: 1, medium: 2, hard: 3 };
        window.location.href = `niveaualeatoire.php?level=${levelMap[currentDifficulty]}`;
    }, 2000);
}

function playSoundAndRedirect(url) {
    sound1.currentTime = 0;
    sound1.play().catch(console.error);

    const activeItem = document.querySelector(`.menu-item[data-level="${url.split('=')[1] || 'random'}"]`) || 
                       document.querySelector('.menu-item.tuto');

    if (activeItem) {
        activeItem.classList.add('glow-init', 'clicked');
        
        setTimeout(() => {
            activeItem.classList.add('glow-fade-out');
            activeItem.classList.add('beat-once');
        }, 100);

        activeItem.addEventListener('animationend', (e) => {
            if (e.animationName === 'glow-fade-out') {
                activeItem.classList.remove('glow-init', 'glow-fade-out');
            }
            if (e.animationName === 'beat-once') {
                activeItem.classList.remove('beat-once');
            }
        }, { once: true });
    }

    const pageFade = document.getElementById('page-fade');
    pageFade.style.display = 'block';
    pageFade.style.opacity = '0';

    document.querySelectorAll('.menu-item, .footer a').forEach(element => {
        element.style.pointerEvents = 'none';
    });

    setTimeout(() => {
        pageFade.style.opacity = '1';
        setTimeout(() => {
            window.location.href = url;
        }, 1000);
    }, 10);
}

updateDifficultyIndicator();
checkRandomSelected();
// Gestion du son
const soundToggle = document.getElementById('soundToggle');
const backgroundMusic = sound2;

// Vérifier l'état initial du son
let isMuted = localStorage.getItem('soundMuted') === 'true';
updateSoundState();

soundToggle.addEventListener('click', () => {
    isMuted = !isMuted;
    localStorage.setItem('soundMuted', isMuted);
    updateSoundState();
});

function updateSoundState() {
    if (isMuted) {
        backgroundMusic.pause();
        soundToggle.classList.add('muted');
        soundToggle.textContent = '🔇'; // Icône sourdine
    } else {
        backgroundMusic.play().catch(e => console.log("Lecture automatique bloquée:", e));
        soundToggle.classList.remove('muted');
        soundToggle.textContent = '🔊'; // Icône son
    }
}

// Gérer la lecture automatique bloquée par le navigateur
document.addEventListener('click', () => {
    if (!isMuted && backgroundMusic.paused) {
        backgroundMusic.play().catch(e => console.log("Lecture après interaction:", e));
    }
}, { once: true });
</script>
</body>
</html>
