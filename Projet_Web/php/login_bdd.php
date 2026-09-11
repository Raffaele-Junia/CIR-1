<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà active
}
$servername = 'localhost'; // Serveur de base de données
$username = 'root'; // Nom d'utilisateur
$password = 'root'; // Mot de passe
$dataname = 'projet'; // Nom de la base de donnée
// On essaie de se connecter
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dataname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
// En cas d'erreur (exception)
catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>