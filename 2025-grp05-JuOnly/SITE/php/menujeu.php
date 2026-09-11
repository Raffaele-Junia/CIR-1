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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Game</title>
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bangers&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000;
            color: #fff;
            font-family: 'Bangers', cursive;
            transition: all 1s ease;
        }

        #warning {
            position: absolute;
            text-align: center;
            cursor: pointer;
            padding: 20px;
            opacity: 1;
            transition: opacity 0.5s ease;
            font-size: 1.5em;
            letter-spacing: 1px;
            z-index: 1000;
        }

        #warning:hover {
            text-shadow: 0 0 10px #fff;
            transform: scale(1.05);
        }

        .message {
            position: absolute;
            text-align: center;
            opacity: 0;
            transition: opacity 1s ease;
            font-size: 2em;
            letter-spacing: 2px;
            text-shadow: 3px 3px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
        }

        #qlf {
            font-size: 5em;
            font-weight: bold;
            letter-spacing: 10px;
        }

        #wallpaper, #wallpaper2 {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 1s ease;
            transform-origin: center;
        }

        #wallpaper2 {
            background-size: 120%;
            background-position: center 50%;
            background-repeat: no-repeat;
            transition: 
                opacity 1.5s ease-in-out, 
                background-position 0.5s ease-out, 
                background-size 0.5s ease-out;
            opacity: 0;
        }
        
        #black-fade {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: black;
            opacity: 0;
            pointer-events: none;
            z-index: 2000;
            transition: opacity 1s ease;
        }
        
        #enter-button {
            position: absolute;
            bottom: 12%;
            left: 50%;
            transform: translateX(-50%);
            padding: 0;
            background: transparent;
            color: white;
            border: none;
            font-family: 'Bangers', cursive;
            font-size: 4em;
            cursor: pointer;
            opacity: 0;
            transition: all 0.5s ease;
            z-index: 1001;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            letter-spacing: 3px;
            text-align: center;
        }

        #enter-button::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease;
        }

        #enter-button:hover {
            color: #ffcc00;
            text-shadow: 0 0 15px rgba(255, 204, 0, 0.7);
            transform: translateX(-50%) scale(1.05);
            background: transparent;
        }

        #enter-button:hover::after {
            width: 80%;
        }

        .visible {
            opacity: 1;
            transform: translateX(0) !important;
        }
        
        .menu-button {
            position: absolute;
            left: 0;
            padding: 5px 15px;
            background: transparent;
            color: white;
            border: none;
            font-family: 'Bangers', cursive;
            font-size: 4em;
            cursor: pointer;
            opacity: 0;
            z-index: 1002;
            text-align: left;
            transform: translateX(-100%);
            transform-origin: left center;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            letter-spacing: 2px;
        }
        
        .menu-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 15px;
            width: 0;
            height: 3px;
            background-color: white;
            transition: width 0.3s ease;
        }
        
        .menu-button.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .menu-button:hover {
            transform: translateX(0) scale(1.3);
            color: #ffcc00;
            text-shadow: 0 0 10px rgba(255,204,0,0.7);
        }
        
        .menu-button:hover::after {
            width: calc(100% - 30px);
        }
        
        #play {
            top: 15%;
            left: 7%;
            transition-delay: 0.1s;
        }
        
        #create-level {
            top: 35%;
            left: 2%;
            transition-delay: 0.2s;
        }
        
        #random-level {
            top: 55%;
            left: 3%;
            transition-delay: 0.3s;
        }
        
        #home {
            top: 75%;
            left: 11.5%;
            transition-delay: 0.4s;
        }

        #credits {
            top: 74%;
            left: 26%;
            transition-delay: 0.5s;
        }

        #black-screen {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: #000;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.8s ease;
            pointer-events: none;
            display: none;
        }

        #wallpaper3 {
            position: fixed;
            width: 100%;
            height: 100%;
            background-image: url("../pictures/wallpaper3.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 0.8s ease;
            z-index: -1;
            display: none;
        }

        .level-container {
            position: absolute;
            width: 80%;
            height: 70%;
            top: 10%;
            left: 10%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 15px;
            opacity: 0;
            z-index: 1000;
            transition: opacity 0.8s ease;
            display: none;
        }

        .level-button {
            width: 280px;
            height: 160px;
            margin: 10px;
            border: 4px solid;
            border-radius: 15px;
            font-size: 2.2em;
            transition: all 0.3s ease, transform 0.2s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            font-family: 'Bangers', cursive;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            z-index: 1001;
        }

        .level-button:hover {
            transform: scale(1.08) translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }

        .level-button:active {
            transform: scale(0.98) translateY(0);
        }

        .level-number {
            font-size: 3em;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .level-difficulty {
            font-size: 1.3em;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .tutorial {
            border-color: #4CAF50;
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.2), rgba(76, 175, 80, 0.1));
        }
        .tutorial:hover {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.3), rgba(76, 175, 80, 0.2));
            box-shadow: 0 0 25px #4CAF50;
        }

        .easy {
            border-color: #2196F3;
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.2), rgba(33, 150, 243, 0.1));
        }
        .easy:hover {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.3), rgba(33, 150, 243, 0.2));
            box-shadow: 0 0 25px #2196F3;
        }

        .medium {
            border-color: #FFC107;
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.2), rgba(255, 193, 7, 0.1));
        }
        .medium:hover {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.3), rgba(255, 193, 7, 0.2));
            box-shadow: 0 0 25px #FFC107;
        }

        .hard {
            border-color: #FF5722;
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.2), rgba(255, 87, 34, 0.1));
        }
        .hard:hover {
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.3), rgba(255, 87, 34, 0.2));
            box-shadow: 0 0 25px #FF5722;
        }

        .extreme {
            border-color: #F44336;
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.2), rgba(244, 67, 54, 0.1));
        }
        .extreme:hover {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.3), rgba(244, 67, 54, 0.2));
            box-shadow: 0 0 30px #F44336;
            animation: pulse 0.5s infinite alternate;
        }

        @keyframes pulse {
            from { box-shadow: 0 0 15px #F44336; }
            to { box-shadow: 0 0 35px #F44336, 0 0 45px #F44336; }
        }

        #back-button {
            position: absolute;
            left: 80%;
            padding: 5px 15px;
            background: transparent;
            color: white;
            border: none;
            font-family: 'Bangers', cursive;
            font-size: 3.5em;
            cursor: pointer;
            opacity: 0;
            z-index: 1002;
            text-align: left;
            transform: translateX(-100%);
            transform-origin: left center;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            letter-spacing: 2px;
            top: 85%;
            display: none;
        }
        
        #back-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 15px;
            width: 0;
            height: 3px;
            background-color: white;
            transition: width 0.3s ease;
        }
        
        #back-button.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        #back-button:hover {
            transform: translateX(0) scale(1.05);
            color: #ffcc00;
            text-shadow: 0 0 10px rgba(255,204,0,0.7);
        }
        
        #back-button:hover::after {
            width: calc(100% - 30px);
        }
        .level-container {
    position: absolute;
    width: 90%;
    height: auto;
    top: 10%;
    left: 5%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding-bottom: 100px;
    display:none;
}

.level-button {
    background: rgba(0, 0, 0, 0.6);
    aspect-ratio: 3 / 4;
    border: 3px solid white;
    border-radius: 15px;
    color: white;
    font-family: 'Bangers', cursive;
    font-size: 2em;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}


        .tutorial { border-color: #4CAF50; }
        .tutorial:hover {
            background: rgba(76, 175, 80, 0.4);
            box-shadow: 0 0 20px #4CAF50;
            transform: scale(1.05);
        }

        .easy { border-color: #2196F3; }
        .easy:hover {
            background: rgba(33, 150, 243, 0.4);
            box-shadow: 0 0 20px #2196F3;
            transform: scale(1.05);
        }

        .medium { border-color: #FFC107; }
        .medium:hover {
            background: rgba(255, 193, 7, 0.4);
            box-shadow: 0 0 20px #FFC107;
            transform: scale(1.05);
        }

        .hard { border-color: #FF5722; }
        .hard:hover {
            background: rgba(255, 87, 34, 0.4);
            box-shadow: 0 0 20px #FF5722;
            transform: scale(1.05);
        }

        .extreme { border-color: rgb(105, 24, 105); }
        .extreme:hover {
            background: rgb(68, 17, 68);
            box-shadow: 0 0 20px #691869
            transform: scale(1.05);
            animation: pulse 0.3s infinite alternate;
        }
        @keyframes pulse {
            from { box-shadow: 0 0 10px #691869 }
            to { box-shadow: 0 0 30px #691869;
                 transform: scale(1.05); 
            }
        }

        .level-number {
            font-size: 2.5em;
            margin-bottom: 5px;
        }

        .level-difficulty {
            font-size: 1.2em;
            opacity: 0.8;
        }

        #back-button {
            position: absolute;
            left: 45%;
            top: 85%;
            padding: 5px 15px;
            background: transparent;
            color: white;
            border: none;
            font-family: 'Bangers', cursive;
            font-size: 3.5em;
            cursor: pointer;
            z-index: 1002;
            text-align: left;
            letter-spacing: 2px;
            transition: all 0.3s ease;
        }

        #back-button:hover {
            transform: scale(1.05);
            color: #ffcc00;
            text-shadow: 0 0 10px rgba(255,204,0,0.7);
        }

        #random-button {
            position: absolute;
            right: 3%;
            top: 85%;
            padding: 10px 20px;
            font-size: 1.5em;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 10px;
            font-family: 'Bangers', cursive;
            cursor: pointer;
            z-index: 1002;
        }

        #random-button:hover {
            color: #ffcc00;
            border-color: #ffcc00;
            box-shadow: 0 0 15px rgba(255, 204, 0, 0.7);
            transform: scale(1.05);
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
    </style>
</head>
<body>
    <div id="warning" onclick="startExperience()">
        <h1>Warning</h1>
        <p>This game contains sounds and music that may be startling or cause unexpected reactions.</p>
        <p>We recommend playing at a moderate volume and in an appropriate environment.</p>
        <p>Click here to continue</p>
    </div>

    <div id="messages-container"></div>
    <div id="wallpaper"></div>
    <div id="wallpaper2"></div>
    <button id="enter-button" onclick="enterGame()">ENTER THE GAME</button>

    <button id="play" class="menu-button" onclick="goniveaulafamille()">PLAY</button>
    <button id="create-level" class="menu-button" onclick="createLevel()">CREATE LEVEL</button>
    <button id="random-level" class="menu-button" onclick="window.location.href='niveaualeatoire.php'">RANDOM LEVEL</button>
    <button id="home" class="menu-button" onclick="goHome()">HOME</button>
    <button id="credits" class="menu-button" onclick="window.location.href='credits.php'"></button>

    <div id="black-screen"></div>
    <div id="wallpaper3"></div>

    <div class="level-container" id="level-container">
        <button class="level-button tutorial" data-level="1" onclick="launchTutorial(event)">
            <span class="level-number">1</span>
            <span class="level-difficulty">Tutorial</span>
        </button>

        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="2">
            <button class="level-button tutorial" data-level="2">
                <span class="level-number">2</span>
                <span class="level-difficulty">Advanced Tutorial</span>
            </button>
        </form>

        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="3">
            <button class="level-button easy" data-level="3">
                <span class="level-number">3</span>
                <span class="level-difficulty">Easy</span>
            </button>
        </form>

        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="4">
            <button class="level-button easy" data-level="4">
                <span class="level-number">4</span>
                <span class="level-difficulty">Easy</span>
            </button>
        </form>

        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="5">
            <button class="level-button medium" data-level="5">
                <span class="level-number">5</span>
                <span class="level-difficulty">Medium</span>
            </button>
        </form>
        
        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="6">
            <button class="level-button medium" data-level="6">
                <span class="level-number">6</span>
                <span class="level-difficulty">Medium</span>
            </button>
        </form>
        
        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="7">
            <button class="level-button hard" data-level="7">
                <span class="level-number">7</span>
                <span class="level-difficulty">Hard</span>
            </button>
        </form>
        
        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="8">
            <button class="level-button hard" data-level="8">
                <span class="level-number">8</span>
                <span class="level-difficulty">Hard</span>
            </button>
        </form>
        
        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="9">
            <button class="level-button extreme" data-level="9">
                <span class="level-number">9</span>
                <span class="level-difficulty">Extreme</span>
            </button>
        </form>

        <form method="POST" action="menuniveau.php">
            <input type="hidden" name="action" value="10">
            <button class="level-button extreme" data-level="10">
                <span class="level-number">10</span>
                <span class="level-difficulty">Ultimate</span>
            </button>
        </form>
    </div>

    <button id="back-button" onclick="goHome()">HOME</button>

    <audio id="sound1"></audio>
    <audio id="sound2"></audio>
    <audio id="sound3"></audio>
    <audio id="sound4"></audio>
    <div id="black-fade"></div>

    <script>
function launchTutorial(event) {
    event.preventDefault();
    sound1.currentTime = 0;
    sound1.play().catch(console.error);

    const pageFade = document.getElementById('page-fade');
    pageFade.style.display = 'block';
    pageFade.style.opacity = '0';

    setTimeout(() => {
        pageFade.style.opacity = '1';
        setTimeout(() => {
            const form = new FormData();
            form.append("action", "1");

            fetch("didacticiel.php", {
                method: "POST",
                body: form
            }).then(() => {
                window.location.href = "didacticiel.php";
            });
        }, 1000);
    }, 10);
}

        window.addEventListener('load', () => {
            sound1.load();
            sound2.load();
            sound3.load();
            sound4.load();
            const img = new Image();
            img.src = "../pictures/wallpaper2.png";
            const img2 = new Image();
            img2.src = "../pictures/wallpaper3.png";
        });
        
        const warning = document.getElementById('warning');
        const messagesContainer = document.getElementById('messages-container');
        const wallpaper = document.getElementById('wallpaper');
        const wallpaper2 = document.getElementById('wallpaper2');
        const enterButton = document.getElementById('enter-button');
        const createLevelBtn = document.getElementById('create-level');
        const randomLevelBtn = document.getElementById('random-level');
        const playBtn = document.getElementById('play');
        const homeBtn = document.getElementById('home');
        const creditsBtn = document.getElementById('credits');
        const sound1 = document.getElementById('sound1');
        const sound2 = document.getElementById('sound2');
        const sound3 = document.getElementById('sound3');
        const sound4 = document.getElementById('sound4');
        const blackScreen = document.getElementById('black-screen');
        const wallpaper3 = document.getElementById('wallpaper3');
        const levelContainer = document.getElementById('level-container');
        const backButton = document.getElementById('back-button');
        const blackFade = document.getElementById('black-fade');

        sound1.src = "../son/son1.mp3";
        sound2.src = "../son/son2.mp3";
        sound3.src = "../son/son3.mp3";
        sound4.src = "../son/menu-music.mp3";
        
        wallpaper.style.backgroundImage = 'url("../pictures/wallpaper.png")';
        wallpaper2.style.backgroundImage = 'url("../pictures/wallpaper2.png")';

        function playHoverSound() {
            sound3.currentTime = 0; 
            sound3.play().catch(console.error);
        }

        createLevelBtn.addEventListener('mouseenter', playHoverSound);
        randomLevelBtn.addEventListener('mouseenter', playHoverSound);
        playBtn.addEventListener('mouseenter', playHoverSound);
        homeBtn.addEventListener('mouseenter', playHoverSound);
        creditsBtn.addEventListener('mouseenter', playHoverSound);

        function startExperience() {
            warning.style.pointerEvents = 'none';
            warning.style.opacity = '0';
            
            sound1.play().catch(e => console.error("Son 1 non joué:", e));
            
            requestFullscreen().then(() => {
                setTimeout(playSequence, 100); 
            }).catch(playSequence);
        }

        function requestFullscreen() {
            const elem = document.documentElement;
            return Promise.resolve();
        }

        function playSequence() {
            sound1.play().catch(console.error);
            
            setTimeout(() => {
                sound2.play().catch(console.error);
                showMessages();
            }, 2000);
        }

        function showMessages() {
            const messages = [
"Created by Group 5, Junia Only",
"This game is a non-commercial work for educational purposes only"
            ];
            
            let delay = 0;
            
            messages.forEach(msg => {
                setTimeout(() => {
                    clearMessages();
                    const msgElement = createMessage(msg);
                    fadeIn(msgElement);
                }, delay);
                delay += 3000;
            });
            
            setTimeout(() => {
                clearMessages();
                const qlfElement = createMessage('', 'qlf');
                fadeIn(qlfElement);
                
                setTimeout(() => qlfElement.textContent = 'J', 150);
                setTimeout(() => qlfElement.textContent = 'JU', 200);
                setTimeout(() => qlfElement.textContent = 'JUN', 250);
                setTimeout(() => qlfElement.textContent = 'JUNI', 300);
                setTimeout(() => qlfElement.textContent = 'JUNIA', 350);                
                setTimeout(() => qlfElement.textContent = 'JUNIA ', 400);
                setTimeout(() => qlfElement.textContent = 'JUNIA O', 450);
                setTimeout(() => qlfElement.textContent = 'JUNIA ON', 500);
                setTimeout(() => qlfElement.textContent = 'JUNIA ONL', 550);
                setTimeout(() => qlfElement.textContent = 'JUNIA ONLY', 600);
                setTimeout(() => qlfElement.textContent = 'JUNIA ONLY ', 1610);
                setTimeout(() => {
                    setTimeout(showFirstWallpaper, 2000);
                }, 100);
            }, delay);
        }

        function createMessage(text, id = '') {
            const element = document.createElement('div');
            element.className = 'message';
            element.textContent = text;
            if (id) element.id = id;
            messagesContainer.appendChild(element);
            return element;
        }

        function fadeIn(element) {
            setTimeout(() => element.style.opacity = '1', 50);
        }

        function clearMessages() {
            document.querySelectorAll('.message').forEach(msg => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 1000);
            });
        }

        function showFirstWallpaper() {
            clearMessages();
            wallpaper.style.opacity = '1';
            
            setTimeout(() => {
                enterButton.style.opacity = '1';
            }, 1000);
        }

        function enterGame() {
            sound1.play().catch(console.error);
            
            enterButton.style.opacity = '0';
            enterButton.style.pointerEvents = 'none';
            wallpaper.style.opacity = '0';
            
            wallpaper2.style.display = 'block';
            wallpaper2.style.opacity = '0';
            
            let opacity = 0;
            const fadeInterval = setInterval(() => {
                opacity += 0.05;
                wallpaper2.style.opacity = opacity;
                
                if (opacity >= 1) {
                    clearInterval(fadeInterval);
                    showMenuButtons();
                    setupWallpaperMovement();
                }
            }, 50); 
        }

        function showMenuButtons() {
            setTimeout(() => {
                playBtn.classList.add('visible');
            }, 200);
            
            setTimeout(() => {
                createLevelBtn.classList.add('visible');
            }, 400);
            
            setTimeout(() => {
                randomLevelBtn.classList.add('visible');
            }, 600);
            
            setTimeout(() => {
                homeBtn.classList.add('visible');
            }, 800);
            
            setTimeout(() => {
                creditsBtn.classList.add('visible');
            }, 1000);
        }

        const wallpaperMove = {
            createLevel: { target: 60 },
            randomLevel: { target: 75 },
            home: { target: 90 },
            play: { target: 10 },
            credits: { target: 50 },
            default: { target: 30 }     
        };
        let currentTarget = wallpaperMove.default.target;

        function setupWallpaperMovement() {
            function animate() {
                const currentY = parseFloat(wallpaper2.style.backgroundPosition.split(' ')[1] || '50%');
                const newY = currentY + (currentTarget - currentY) * 0.1;
                wallpaper2.style.backgroundPosition = `center ${newY}%`;
                requestAnimationFrame(animate);
            }
            animate();
        }

        createLevelBtn.addEventListener('mouseenter', () => {
            currentTarget = wallpaperMove.createLevel.target;
            playHoverSound();
        });

        randomLevelBtn.addEventListener('mouseenter', () => {
            currentTarget = wallpaperMove.randomLevel.target;
            playHoverSound();
        });

        playBtn.addEventListener('mouseenter', () => {
            currentTarget = wallpaperMove.play.target;
            playHoverSound();
        });

        homeBtn.addEventListener('mouseenter', () => {
            currentTarget = wallpaperMove.home.target;
            playHoverSound();
        });

        creditsBtn.addEventListener('mouseenter', () => {
            currentTarget = wallpaperMove.credits.target;
            playHoverSound();
        });

        [createLevelBtn, randomLevelBtn, playBtn, homeBtn, creditsBtn].forEach(btn => {
            btn.addEventListener('mouseleave', () => {
                currentTarget = wallpaperMove.default.target;
            });
        });

        function createLevel() {
            sound1.play().catch(console.error);
            const fadeAudio = setInterval(() => {
                if(sound1.volume > 0.1) {
                    sound1.volume -= 0.1;
                } else {
                    sound1.volume = 0;
                    clearInterval(fadeAudio);
                    sound1.pause();
                }
            }, 100);

            blackFade.style.opacity = '1';
            
            document.querySelectorAll('.menu-button').forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
            
            setTimeout(() => {
                window.location.href = "creer_niveau.php";
            }, 1000);
        }


        function showLevelSelection() {
            sound1.play().catch(console.error);
            const fadeAudio = setInterval(() => {
                if(sound1.volume > 0.1) {
                    sound1.volume -= 0.1;
                } else {
                    sound1.volume = 0;
                    clearInterval(fadeAudio);
                    sound1.pause();
                }
            }, 100);

            blackFade.style.opacity = '1';
            
            document.querySelectorAll('.menu-button').forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
            
            setTimeout(() => {
                wallpaper2.style.display = 'none';
                
                blackScreen.style.display = 'block';
                wallpaper3.style.display = 'block';
                levelContainer.style.display = 'grid';
                backButton.style.display = 'block';
                
                blackFade.style.opacity = '0';
                
                setTimeout(() => {
                    blackScreen.style.opacity = '0';
                    
                    setTimeout(() => {
                        wallpaper3.style.opacity = '1';
                        
                        setTimeout(() => {
                            levelContainer.style.opacity = '1';
                            backButton.classList.add('visible');
                            
                            sound4.volume = 0;
                            sound4.play().catch(console.error);
                            
                            let volume = 0;
                            const musicFadeIn = setInterval(() => {
                                volume += 0.05;
                                sound4.volume = Math.min(volume, 0.5);
                                if (volume >= 0.5) clearInterval(musicFadeIn);
                            }, 50);
                        }, 300);
                    }, 300);
                }, 300);
            }, 1000);
        }

        document.querySelectorAll('.level-button').forEach(button => {
            button.addEventListener('mouseenter', () => {
                sound3.currentTime = 0;
                sound3.play().catch(console.error);
            });
            
            button.addEventListener('click', function() {
                sound1.currentTime = 0;
                sound1.play().catch(console.error);
                startLevel(parseInt(this.getAttribute('data-level')));
            });
        });

        backButton.addEventListener('mouseenter', () => {
            sound3.currentTime = 0;
            sound3.play().catch(console.error);
        });

        backButton.addEventListener('click', () => {
            sound1.currentTime = 0;
            sound1.play().catch(console.error);
            backToMenu();
        });

        function startLevel(levelNumber) {
            sound1.currentTime = 0; 
            sound1.volume = 1; 
            sound1.play().catch(console.error);
            
            document.querySelectorAll('.level-button').forEach(btn => {
                btn.style.pointerEvents = 'none';
            });
            backButton.style.pointerEvents = 'none';
            
            let volume = 0.5;
            const musicFadeOut = setInterval(() => {
                volume -= 0.1;
                sound4.volume = Math.max(volume, 0);
                if (volume <= 0) clearInterval(musicFadeOut);
            }, 50);
            
            blackFade.style.opacity = '1';
            
            setTimeout(() => {
                window.location.href = `jeu.php?level=${levelNumber}`;
            }, 1250); 
        }

        function backToMenu() {
            document.querySelectorAll('.level-button').forEach(btn => {
                btn.style.pointerEvents = 'none';
            });
            backButton.style.pointerEvents = 'none';
            
            let volume = 0.5;
            const musicFadeOut = setInterval(() => {
                volume -= 0.1;
                sound4.volume = Math.max(volume, 0);
                if (volume <= 0) clearInterval(musicFadeOut);
            }, 50);
            
            blackFade.style.opacity = '1';
            
            setTimeout(() => {
                blackScreen.style.opacity = '1';
                wallpaper3.style.opacity = '0';
                levelContainer.style.opacity = '0';
                backButton.classList.remove('visible');
                
                setTimeout(() => {
                    blackScreen.style.display = 'none';
                    wallpaper3.style.display = 'none';
                    levelContainer.style.display = 'none';
                    backButton.style.display = 'none';
                    
                    wallpaper2.style.display = 'block';
                    
                    blackFade.style.opacity = '0';
                    
                    setTimeout(() => {
                        showMenuButtons();
                    }, 300);
                }, 500);
            }, 500);
        }

        function goHome() {
            sound1.play().catch(console.error);
            const fadeAudio = setInterval(() => {
                if(sound1.volume > 0.1) {
                    sound1.volume -= 0.1;
                } else {
                    sound1.volume = 0;
                    clearInterval(fadeAudio);
                    sound1.pause();
                }
            }, 100);

            blackFade.style.opacity = '1';
            
            document.querySelectorAll('.menu-button').forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
            
            setTimeout(() => {
                window.location.href = "accueil.php";
            }, 1000);
        }
        function goniveaulafamille() {
            sound1.play().catch(console.error);
            const fadeAudio = setInterval(() => {
                if(sound1.volume > 0.1) {
                    sound1.volume -= 0.1;
                } else {
                    sound1.volume = 0;
                    clearInterval(fadeAudio);
                    sound1.pause();
                }
            }, 100);

            blackFade.style.opacity = '1';
            
            document.querySelectorAll('.menu-button').forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
            
            setTimeout(() => {
                window.location.href = "niveaumenujeu.php";
            }, 1000);
        }

    </script>
</body>
</html>
