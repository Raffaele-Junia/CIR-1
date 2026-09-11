<?php
session_start();
require_once 'jeu.php';

if (!isset($_SESSION['user_id'])) {
    die("Not allowed");
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$level = intval($_POST['level'] ?? 0);
$time = intval($_POST['time'] ?? 0);

switch ($action) {
    case 'death':
        handleDeath($userId, $level, $time);
        break;
    case 'victory':
        $coins = intval($_POST['coins'] ?? 0);
        handleVictory($userId, $level, $time, $coins);
        break;
    default:
        die("Unrecognized action");
}

echo "OK";
?>