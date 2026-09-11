<?php
session_start();

// Vérifie s'il y a un cookie 'session_user' et que la session 'id' n'est pas encore définie
if (!isset($_SESSION['id']) && isset($_COOKIE['session_user'])) {
    // Restauration de la session à partir du cookie
    $_SESSION['id'] = $_COOKIE['session_user'];
    // Optionnel : tu peux charger d'autres infos de l'utilisateur ici depuis la base
}
?>
<!DOCTYPE html>
<html lang="fr"> <!-- Je commence pas page html en définissant le langage (ici français)-->
    <head>  <!-- La balise head anglobe tout ce qui n'apparaîtra pas dans notre page Web-->
        <title><?php echo htmlspecialchars($pageTitle); ?></title>
        <link rel="icon" href="../images/logo.png" type="image/x-icon">
        <meta charset="utf-8"> <!-- On utilisera la chaîne de caractère utf-8-->
        <link rel="stylesheet" href="../css/<?php echo htmlspecialchars($css); ?>.css"> <!-- On link la page html avec la page css afin d'ajouter de l'esthétique à notre page WEB -->
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/header.css">
    </head>
    <header>
    <a href="../index.php"><img src="../images/logo.png" alt="Angoulême Logo"></a>

    <?php if (isset($_SESSION['id'])): ?>
        <a href="infoperso.php" class="btn-connexion">Membres</a>
    <?php else: ?>
        <a href="connexion.php" class="btn-connexion">Se connecter</a>
    <?php endif; ?>

    <h1>Ville d'Angoulême</h1>
</header>
        <nav>
            <ol><!--Liste non ordonnée pour la navigation entre les pages-->
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="loisirs.php">Tourisme et loisirs</a></li>
                <li><a href="histoire.php">Histoire</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ol>
        </nav>