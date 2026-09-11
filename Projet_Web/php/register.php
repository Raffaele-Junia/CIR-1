<?php
session_start();
require_once 'login_bdd.php';

function nettoyer($donnee) {
    return htmlspecialchars(trim($donnee));
}

$erreurs = [];
$success = false;
$prenom = $nom = $identifiant = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $genre = isset($_POST['genre']) ? nettoyer($_POST['genre']) : "";
    $nom = nettoyer($_POST['nom']);
    $prenom = nettoyer($_POST['prenom']);
    $mail = nettoyer($_POST['mail']);
    $identifiant = nettoyer($_POST['identifiant']);
    $mdp = nettoyer($_POST['mdp']);
    $mdp2 = nettoyer($_POST['mdp2']);
    $date_naissance = htmlspecialchars($_POST['date_naissance'] ?? null);

    if (empty($nom) || empty($prenom) || empty($mail) || empty($identifiant) || empty($mdp) || empty($mdp2)) {
        $erreurs[] = "Tous les champs sont obligatoires.";
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Adresse email invalide.";
    }

    if ($mdp !== $mdp2) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE mail = :mail OR identifiant = :identifiant");
    $stmt->execute([
        'mail' => $mail,
        'identifiant' => $identifiant
    ]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        $erreurs[] = "Un compte avec cet e-mail ou identifiant existe déjà.";
    }

    if (empty($erreurs)) {
        $mdp_clair = $mdp;
        $stmt = $conn->prepare("INSERT INTO utilisateurs (genre, nom, prenom, mail, identifiant, mdp, date_naissance) 
                                VALUES (:genre, :nom, :prenom, :mail, :identifiant, :mdp, :date_naissance)");
        $stmt->execute([
            'genre' => $genre,
            'nom' => $nom,
            'prenom' => $prenom,
            'mail' => $mail,
            'identifiant' => $identifiant,
            'mdp' => $mdp_clair,
            'date_naissance' => $date_naissance
        ]);
        $success = true;
    }
} else {
    header("Location: ../php/compte.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Création de compte</title>
        <link rel="stylesheet" href="../css/style_message.css">
    </head>
    <body>

        <div class="message-container">
            <?php if ($success): ?>
            <div class="success">
                <h2>Compte créé avec succès !</h2>
                <p>Bienvenue, <?= $prenom ?> <?= $nom ?> (<?= $identifiant ?>)</p>
                <a href="connexion.php">Se connecter</a>
            </div>
            <?php else: ?>
            <div class="error">
                <h2>Erreurs :</h2>
                <ul>
                    <?php foreach ($erreurs as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="../php/compte.php">Retour</a>
            </div>
            <?php endif; ?>
        </div>
    </body>
</html>
