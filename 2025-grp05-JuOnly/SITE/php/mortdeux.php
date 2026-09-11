<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    unset($_SESSION['level_start_time']);    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>BlockMirror - Dead</title>
<link rel="icon" href="../pictures/logojeu.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet" />

<style>
  html, body {
    margin: 0; padding: 0; height: 100%; width: 100%; overflow: hidden;
    background: black;
  }

  #video-bg {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    object-fit: cover;
    z-index: 1;
  }
.glow-init {
  color: yellow;
  text-shadow: 0 0 30px yellow, 0 0 50px yellow, 0 0 80px yellow;
}

@keyframes glow-fade-out {
  0% {
    color: yellow;
    text-shadow: 0 0 20px yellow, 0 0 30px yellow, 0 0 40px yellow;
  }
  100% {
    color: white;
    text-shadow: none;
  }
}

.glow-fade-out {
  animation: glow-fade-out 5s ease forwards;
}
  #retry-btn {
    position: fixed;
    top: 85%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1);
    font-family: 'Bangers', cursive;
    font-size: 4rem;
    color: white;
    background: transparent;
    border: none;
    cursor: pointer;
    z-index: 10;
    display: none; 
    user-select: none;
    transform-origin: center;
    transition: transform 0.2s ease, color 0.2s ease;
  }

  #retry-btn:not(.clicked):hover {
    color: yellow;
    transform: translate(-50%, -50%) scale(1.15);
  }

  @keyframes breathing {
    0%, 100% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.1); }
  }

  .breathing {
    animation: breathing 4s ease-in-out infinite;
  }

  @keyframes beat-once {
    0%   { transform: translate(-50%, -50%) scale(1); }
    50%  { transform: translate(-50%, -50%) scale(1.2); }
    100% { transform: translate(-50%, -50%) scale(1); }
  }

  .beat-once {
    animation: beat-once 0.6s ease forwards;
  }

  #fade-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: black;
    opacity: 0;
    pointer-events: none;
    transition: opacity 1s ease;
    z-index: 9999;
  }

  #fade-overlay.active {
    opacity: 1;
    pointer-events: auto;
  }
</style>
</head>
<body>

<video id="video-bg" autoplay muted playsinline>
  <source src="../videos/mort.mp4" type="video/mp4" />
  Your browser does not support the video.
</video>

<audio id="audio-son13" src="../son/son13.mp3"></audio>
<audio id="audio-music" src="../son/son11.mp3" loop></audio>
<audio id="audio-son12" src="../son/son12.mp3" loop></audio>

<button id="retry-btn">RETRY</button>

<div id="fade-overlay"></div>

<script>
  const audio13 = document.getElementById('audio-son13');
  const audio11 = document.getElementById('audio-music');
  const audio12 = document.getElementById('audio-son12');
  const retryBtn = document.getElementById('retry-btn');
  const fadeOverlay = document.getElementById('fade-overlay');

  window.addEventListener('load', () => {
    audio13.play().catch(() => {
      console.log('Autoplay blocked by browser, waiting for user interaction.');
    });
  });

  setTimeout(() => {
    audio11.play();
    retryBtn.style.display = 'block';

    let lastTime = 0;
    const bpm = 120;
    const beatInterval = 60000 / bpm;
    let pulse = 1;

    function pulseAnimation(time) {
      if (!lastTime) lastTime = time;
      const elapsed = time - lastTime;

      if (elapsed > beatInterval && !retryBtn.classList.contains('clicked')) {
        pulse = pulse === 1 ? 1.2 : 1;
        retryBtn.style.transform = `translate(-50%, -50%) scale(${pulse})`;
        lastTime = time;
      }
      requestAnimationFrame(pulseAnimation);
    }
    requestAnimationFrame(pulseAnimation);
  }, 1050);

retryBtn.addEventListener('click', () => {
  audio11.pause();
  audio11.currentTime = 0;

  retryBtn.classList.add('clicked');

  retryBtn.classList.add('glow-init');
  setTimeout(() => {
    retryBtn.classList.add('glow-fade-out');
  }, 100);

  retryBtn.classList.remove('breathing');
  retryBtn.classList.add('beat-once');

  audio12.play();

  retryBtn.addEventListener(
    'animationend',
    (e) => {
      if (e.animationName === 'beat-once') {
        retryBtn.classList.remove('beat-once');
        retryBtn.classList.add('breathing');
      }
    },
    { once: true }
  );

  retryBtn.addEventListener(
    'animationend',
    (e) => {
      if (e.animationName === 'glow-fade-out') {
        retryBtn.classList.remove('glow-init', 'glow-fade-out');
      }
    },
    { once: true }
  );

  setTimeout(() => {
    fadeOverlay.classList.add('active');
  }, 1000);

  setTimeout(() => {
    window.location.href = 'niveaumenujeu.php';
  }, 3000);
});
</script>

</body>
</html>