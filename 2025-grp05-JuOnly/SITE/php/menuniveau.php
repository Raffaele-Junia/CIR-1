<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }    

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $filePath = '../file/inputniveau.json';
    $newAction = ['action' => $_POST['action'], 'timestamp' => time()];

    $data = [];
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);
        if (!is_array($data)) {
            $data = []; 
        }
    }

    $data[] = $newAction;

    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: jeu.php' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>