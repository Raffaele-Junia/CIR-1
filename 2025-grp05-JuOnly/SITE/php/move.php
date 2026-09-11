<?php */
/*     session_start(); */
/*     if (!isset($_SESSION['user_id'])) { */
/*         header("Location: login.php"); */
/*         exit(); */
/*     }     */
/*  */
/* if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) { */
/*     $filePath = '../file/input.json'; */
/*     $newAction = ['action' => $_POST['action'], 'timestamp' => time()]; */
/*  */
/*     $data = []; */
/*     if (file_exists($filePath)) { */
/*         $content = file_get_contents($filePath); */
/*         $data = json_decode($content, true); */
/*         if (!is_array($data)) { */
/*             $data = [];  */
/*         } */
/*     } */
/*  */
/*     $data[] = $newAction; */
/*  */
/*     file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); */
/*     /* */
/*     $son = 'pas'; // valeur par défaut */
/*     if ($_POST['action'] === 'pousser') { */
/*         $son = 'tonneau'; */
/*     } elseif ($_POST['action'] === 'ramasser') { */
/*         $son = 'piece'; */
/*     } */
/*  */
/*     header('Content-Type: application/json'); */
/*     echo json_encode(['etat' => 'ok', 'son' => $son]); */
/*     */ */
/*     header('Location: ' . $_SERVER['HTTP_REFERER']); */
/*     exit; */
/* } */
/* ?> */