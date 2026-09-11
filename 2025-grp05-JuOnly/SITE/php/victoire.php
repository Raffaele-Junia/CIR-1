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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Victory</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
    video {
        transform: scale(1.2);
    }
    </style>
</head>
<body>
    <video id="victoryVideo" autoplay>
        <source src="../videos/victoire.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <script>
        document.getElementById('victoryVideo').addEventListener('ended', function() {
            setTimeout(function() {
                window.location.href = 'niveaumenujeu.php';
            }, 50);
        });
    </script>
</body>
</html>