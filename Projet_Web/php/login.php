<?php
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (!isset($_SESSION['user_id']) && isset($_COOKIE['session_user'])) {
    try {
        // Rechercher l'utilisateur à partir du cookie
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE identifiant = :identifiant");
        $stmt->execute([
            'identifiant' => htmlspecialchars($_COOKIE['session_user'])
        ]);

        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            // Reconnecter automatiquement
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['username'] = $utilisateur['identifiant'];
        } else {
            // Cookie invalide => supprimer le cookie
            setcookie('session_user', '', time() - 3600, "/");
        }
    } catch (Exception $e) {
        // En cas d'erreur, on supprime aussi le cookie
        setcookie('session_user', '', time() - 3600, "/");
    }
}

// Connexion à la base de données
require_once 'login_bdd.php';

// Vérifie que la requête vient du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Fonction pour éviter les failles XSS
    function nettoyer($donnee) {
        return htmlspecialchars(trim($donnee));
    }

    // Récupération et nettoyage des données
    $identifiant = nettoyer($_POST['identifiant'] ?? '');
    $mdp = nettoyer($_POST['mdp'] ?? '');

    $erreurs = [];

    // Vérifications des champs
    if (empty($identifiant)) {
        $erreurs[] = "Veuillez entrer votre identifiant ou e-mail.";
    }

    if (empty($mdp)) {
        $erreurs[] = "Veuillez entrer votre mot de passe.";
    }

    // S'il n'y a pas d'erreurs
    if (empty($erreurs)) {
        try {
            // Recherche utilisateur par identifiant ou mail
            $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE mail = :identifiant OR identifiant = :identifiant");
            $stmt->execute([
                'identifiant' => $identifiant
            ]);

            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe (sans hash, comparaison directe)
            if ($utilisateur && $mdp == $utilisateur['mdp']) {
                // Connexion réussie, gestion de la session
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['username'] = $utilisateur['identifiant'];

                // Création d'un cookie de session personnalisé
                setcookie('session_user', $utilisateur['identifiant'], time() + 3600, "/"); // 1h de durée, disponible sur tout le site

                header("Location: ../index.php");
                exit();
            } else {
                $erreurs[] = "Identifiant ou mot de passe incorrect.";
            }
        } catch (Exception $e) {
            $erreurs[] = "Erreur lors de la connexion : " . htmlspecialchars($e->getMessage());
        }
    }

    // Affichage des erreurs s'il y en a
    if (!empty($erreurs)) {
        echo "<h2>Erreurs :</h2><ul>";
        foreach ($erreurs as $e) {
            echo "<li>" . htmlspecialchars($e) . "</li>";
        }
        echo "</ul>";
        echo "<a href='../php/connexion.php'>Retour à la page de connexion</a>";
    }
} else {
    // Si la méthode n'est pas POST, redirige
    header("Location: ../php/connexion.php");
    exit();
}
?>
