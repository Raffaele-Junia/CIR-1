<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$total_time_seconds = null;
$rank_letter = 'F';
$rank_color = '#888888';

if (isset($_SESSION['random_series_start_time'])) {
    $total_time_seconds = time() - $_SESSION['random_series_start_time'];
    unset($_SESSION['random_series_start_time']);

    if ($total_time_seconds <= 90) { // 1m30s
        $rank_letter = 'S';
        $rank_color = '#FF0000';
    } elseif ($total_time_seconds <= 120) { // 2m
        $rank_letter = 'A';
        $rank_color = '#00008B';
    } elseif ($total_time_seconds <= 140) { // 2m20s
        $rank_letter = 'B';
        $rank_color = '#32CD32';
    } elseif ($total_time_seconds <= 160) { // 2m40s
        $rank_letter = 'C';
        $rank_color = '#FFD700';
    } elseif ($total_time_seconds <= 180) { // 3m
        $rank_letter = 'D';
        $rank_color = '#00BFFF';
    } else { // Plus de 3m
        $rank_letter = 'F';
        $rank_color = '#888888';
    }
}

function format_display_time($seconds) {
    if ($seconds === null) return '--:--';
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    return sprintf("%02d:%02d", $minutes, $secs);
}

$display_time = format_display_time($total_time_seconds);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>BlockMirror - Victoire Aléatoire!</title>
  <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet" />
  <link rel="icon" href="../pictures/logojeu.png" type="image/png">
  <style>
    body { margin: 0; 
           background: #9E01DF; 
           height: 100vh; 
           overflow: hidden; 
           font-family: 'Bangers', cursive; 
    }
    .black-bar { 
      position: absolute; 
      top: 0; 
      left: 0; 
      width: 100%; 
      height: 210px;
      background: black; 
      transform: skewY(-3deg); 
      transform-origin: top left; 
      z-index: 1; 
      box-shadow: 0 5px 15px rgba(0,0,0,0.3); }

    .result-container { 
      position: absolute; 
      top: 5%; width: 100%; 
      display: flex; 
      justify-content: center; 
      z-index: 2; 
      gap: 10px; }

    .letter { 
      font-size: 120px; 
      display: inline-block; 
      position: relative; opacity: 0; 
      animation: slideIn 0.6s forwards, bounce 0.3s 0.5s forwards; 
      transform: translateX(-1000px); 
      text-shadow: 2px 2px 0 rgba(0,0,0,0.3); }
      
    .letter:nth-child(1) { color: #9E01DF; } 
    .letter:nth-child(2) { color: #A316E0; } 
    .letter:nth-child(3) { color: #A92BE2; } 
    .letter:nth-child(4) { color: #AE40E3; } 
    .letter:nth-child(5) { color: #B455E5; } 
    .letter:nth-child(6) { color: #B96AE6; } 
    .letter:nth-child(7) { color: #BF7FE8; } 
    .letter:nth-child(8) { color: #C494E9; }

    .mirror-img { 
      position: absolute; 
      bottom: 0; 
      left: 25%; 
      transform: translateX(-50%) scaleY(0.6) scaleX(1.4); 
      width: 430px; 
      z-index: 0; 
      animation: fallStretch 0.3s ease-out forwards; 
      animation-delay: 0.6s; }

    #rank-container { 
      position: absolute; 
      bottom: 155px; 
      left: 25%; 
      transform: translateX(-50%); 
      z-index: 5; 
      text-align: center; 
      color: white; 
      font-family: 'Bangers', cursive; 
      pointer-events: none; 
      text-shadow: 2px 2px 4px rgba(0,0,0,0.8); 
    }
    #rank-container small { 
      display: block; 
      font-size: 32px; 
      margin-bottom: 6px; 
    }
    #rank-value { 
      font-size: 300px; 
      font-weight: bold; 
      line-height: 1; 
      color: <?php echo $rank_color; ?>; 
    }
    @keyframes fallStretch { 
      0% { top: -300px; transform: translateX(-50%) scaleY(0.6) scaleX(1.4); } 
      70% { top: 230px; transform: translateX(-50%) scaleY(1.25) scaleX(0.85); } 
      85% { top: 180px; transform: translateX(-50%) scaleY(0.95) scaleX(1.05); } 
      100% { top: 200px; transform: translateX(-50%) scaleY(1) scaleX(1); } 
    }
    @keyframes slideIn { 
      0% { transform: translateX(-1000px) scaleX(3) scaleY(0.6); opacity: 0; } 
      60% { transform: translateX(30px) scaleX(0.7) scaleY(1.4); opacity: 1; } 
      100% { transform: translateX(0) scaleX(1) scaleY(1); opacity: 1; } 
    }
    @keyframes bounce { 
      0%, 100% { transform: translateX(0); } 
      50% { transform: translateX(-15px); } 
    }
    #overlay { 
      position: fixed; 
      top: 0; 
      left: 0; 
      width: 100%; 
      height: 100%; 
      background: black; 
      z-index: 9999; 
      animation: fadeOutOverlay 2s ease forwards; 
      animation-delay: 0.5s; 
    }
    @keyframes fadeOutOverlay { 
      0% { opacity: 1; } 
      100% { opacity: 0; visibility: hidden; } 
    }
#scoreboard { 
    position: absolute; 
    top: 60%; 
    left: 65%; 
    transform: translate(-50%, -50%); 
    color: white; 
    padding: 20px; 
    text-align: center; 
    z-index: 2; 
    line-height: 1.8; 
    text-shadow: 2px 2px 6px rgba(0,0,0,0.9), -1px -1px 2px rgba(255,255,255,0.15); 
}
    #win-scroll-container { 
      position: fixed; 
      top: 50%; 
      left: 50%; 
      width: 100vw; 
      height: 100vh; 
      transform: translate(-50%, -50%) rotate(-3deg); 
      overflow: hidden; 
      pointer-events: none; 
      z-index: 0; 
    }
    .win-line { 
      white-space: nowrap; 
      color: rgba(255, 255, 255, 0.3); 
      font-family: 'Bangers', cursive; 
      font-size: 60px; 
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3); 
      opacity: 0.4; 
      animation: scrollLeft 35s linear infinite; 
      padding: 0 10px; 
      position: absolute; 
      width: 200%; 
    }
    @keyframes scrollLeft { 
      0% { transform: translateX(0%); } 
      100% { transform: translateX(-50%); } 
    }
    #new-win-btn { 
      position: fixed; 
      bottom: 20px; 
      right: 20px; 
      z-index: 10; 
      font-family: 'Bangers', cursive; 
      font-size: 30px; 
      padding: 12px 20px; 
      background-color: #9E01DF; 
      color: white; 
      border: none; 
      border-radius: 10px; 
      cursor: pointer; 
      box-shadow: 2px 2px 10px rgba(0,0,0,0.4); 
      transition: background-color 0.3s ease; 
    }
    #new-win-btn:hover { 
      background-color: #B455E5; 
    }
    #close-overlay { 
      position: fixed; 
      top: 0; 
      left: 0; 
      width: 100%; 
      height: 100%; 
      background: black; 
      z-index: 99999; 
      opacity: 0; 
      pointer-events: none; 
      transition: opacity 0.8s ease; 
    }
    /* Animation de rotation pour le rang */
    @keyframes spin {
      0% { transform: translateX(-50%) rotate(0deg); }
      100% { transform: translateX(-50%) rotate(360deg); }
    }
    .spinning {
      animation: spin 0.5s linear infinite;
    }
    /* Styles pour les confettis */
    .confetti {
      position: absolute;
      width: 10px;
      height: 10px;
      background-color: #f00;
      opacity: 0;
      z-index: 10;
    }
  </style>
</head>
<body>
  <div id="overlay"></div>
  <div id="win-scroll-container" aria-hidden="true">
    <div class="win-line" style="top: 15%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 25%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 35%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 45%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 55%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 65%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 75%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 85%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
    <div class="win-line" style="top: 95%;">WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN WIN</div>
  </div>
  <img src="../pictures/miroirVictoire.png" alt="Mirror" class="mirror-img" />
  <div id="rank-container">
    <small>Rank :</small>
    <span id="rank-value" style="opacity: 0;"><?php echo $rank_letter; ?></span>
  </div>
  <audio autoplay loop id="background-music">
    <source src="../son/sonvictoirealeatoire.mp3" type="audio/mpeg" />
  </audio>
  <div class="black-bar"></div>
  <div class="result-container" id="resultContainer"></div>
<div id="scoreboard">
    <div style="font-size: 5rem;">Temps Total:</div>
    <div style="font-size: 7rem; margin-top: 0px;"><span id="completed-time"><?php echo $display_time; ?></span></div>
</div>
  <div id="close-overlay"></div>
<script>
  const backgroundMusic = document.getElementById('background-music');
  const rankValueElement = document.getElementById('rank-value');
  const completedTimeElement = document.getElementById('completed-time');
  const rankContainer = document.getElementById('rank-container');
        const revealSound = new Audio('../son/son1.mp3');
  const musicPaths = {
    F: '../son/sonlooseale.mp3',
    D: '../son/sonlooseale.mp3',
    C: '../son/songood.mp3',
    B: '../son/songood.mp3',
    A: '../son/excellent.mp3',
    S: '../son/perfect.mp3'
  };
  const rankLetterJS = "<?php echo $rank_letter; ?>";
  const audio = new Audio();
  audio.loop = true;

  function fadeOutAudio(audioElement, duration) {
    const fadeOutInterval = 50;
    const steps = duration / fadeOutInterval;
    if (audioElement.volume === 0 || steps === 0) {
      audioElement.pause();
      audioElement.volume = 0;
      return;
    }
    const stepSize = audioElement.volume / steps;
    const fadeInterval = setInterval(() => {
      if (audioElement.volume > stepSize) {
        audioElement.volume -= stepSize;
      } else {
        audioElement.volume = 0;
        clearInterval(fadeInterval);
        audioElement.pause();
      }
    }, fadeOutInterval);
  }

  function createTitle() {
    const container = document.getElementById("resultContainer");
    const word = "VICTOIRE!";
    container.innerHTML = '';
    word.split('').forEach((letter, index) => {
      const span = document.createElement('span');
      span.className = 'letter';
      span.textContent = letter;
      span.style.animationDelay = `${350 + index * 120}ms, ${350 + (index * 120) + 300}ms`;
      container.appendChild(span);
    });
  }

  function createConfetti() {
    const colors = ['#f00', '#0f0', '#00f', '#ff0', '#f0f', '#0ff'];
    const container = document.body;
    
    for (let i = 0; i < 150; i++) {
      const confetti = document.createElement('div');
      confetti.className = 'confetti';
      confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
      confetti.style.left = Math.random() * 100 + 'vw';
      confetti.style.top = -10 + 'px';
      confetti.style.width = Math.random() * 10 + 5 + 'px';
      confetti.style.height = Math.random() * 10 + 5 + 'px';
      confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
      container.appendChild(confetti);
      
      // Animation
      const animationDuration = Math.random() * 3 + 2;
      const animationDelay = Math.random() * 2;
      
      confetti.style.animation = `
        fadeIn 0.5s ${animationDelay}s forwards,
        fall ${animationDuration}s ${animationDelay}s linear forwards
      `;
      
      // Supprimer après l'animation
      setTimeout(() => {
        confetti.remove();
      }, (animationDelay + animationDuration) * 3000);
    }
    
    // Ajouter les keyframes dynamiquement
    const style = document.createElement('style');
    style.innerHTML = `
      @keyframes fadeIn {
        to { opacity: 1; }
      }
      @keyframes fall {
        to { 
          transform: translate(${Math.random() * 200 - 100}px, 100vh) rotate(${Math.random() * 360}deg);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  }

function setupVictoryScreen() {
  createTitle();

  // Jouer le son du compteur pendant 2 secondes
  const counterSound = new Audio('../son/compteur.mp3');
  counterSound.play().catch(e => console.error("Counter sound error:", e));

  // Préparer le rang en rotation avec ?
  rankValueElement.textContent = 'ㅤ';
  rankValueElement.style.opacity = '1';
  rankValueElement.classList.add('spinning');

  // Simuler un défilement aléatoire du temps pendant 2 secondes
  const originalTime = completedTimeElement.textContent;
  let elapsed = 0;
  const intervalDuration = 100;
  const timeShuffleInterval = setInterval(() => {
    const randomMinutes = Math.floor(Math.random() * 5);
    const randomSeconds = Math.floor(Math.random() * 60);
    const fakeTime = `${String(randomMinutes).padStart(2, '0')}:${String(randomSeconds).padStart(2, '0')}`;
    completedTimeElement.textContent = fakeTime;
    elapsed += intervalDuration;
    if (elapsed >= 2000) {
      clearInterval(timeShuffleInterval);
      completedTimeElement.textContent = originalTime;

      // Révéler le vrai rang
      rankValueElement.classList.remove('spinning');
      rankValueElement.textContent = rankLetterJS;

      // Jouer le son du rang révélé
      const rankSound = new Audio(musicPaths[rankLetterJS]);
      rankSound.play().catch(e => console.error("Rank sound error:", e));
      revealSound.play().catch(e => console.error("Erreur lecture son révélation :", e));
      // Ajouter des confettis après révélation
      createConfetti();
    }
  }, intervalDuration);
}

function goMainMenu() {
    const closeOverlay = document.getElementById('close-overlay');
    // Faire partir en fondu la musique de fond ET la musique du rang
    fadeOutAudio(backgroundMusic, 800);
    fadeOutAudio(audio, 800);
    
    closeOverlay.style.opacity = '1';
    closeOverlay.style.pointerEvents = 'auto'; // Permet de cliquer à travers
    
    setTimeout(() => {
        window.location.href = 'niveaumenujeu.php';
    }, 800);
}

  window.onload = function() {
    setupVictoryScreen();
    setTimeout(() => {
        goMainMenu();
    }, 7000);
  };
</script>
</body>
</html>
