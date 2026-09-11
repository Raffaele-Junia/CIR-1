<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr"> <!-- Je commence la page html en définissant le langage (ici français)-->
    <head>  <!-- La balise head englobe tout ce qui n'apparaîtra pas dans notre page Web-->
        <title> Ville d'Angoulême </title> <!-- On définit le titre-->
        <link rel="icon" href="images/logo.png" type="image/x-icon"><!-- On donne un logo à notre page-->
        <meta charset="utf-8"> <!-- On utilisera la chaîne de caractère utf-8-->
        <link rel="stylesheet" href="css/accueil.css"><!--On lie les pages css à notre page pour lui donner un style-->
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
    </head>
    <body> <!-- La balise body englobe tout ce qui apparaîtra dans notre page Web-->
    <header>
    <a href="../index.php"><img src="images/logo.png" alt="Angoulême Logo"></a>

    <?php if (isset($_SESSION['id'])): ?>
        <a href="php/infoperso.php" class="btn-connexion">Membres</a>
    <?php else: ?>
        <a href="php/connexion.php" class="btn-connexion">Se connecter</a>
    <?php endif; ?>

    <h1>Ville d'Angoulême</h1>
</header>
        <nav>
            <ol> <!--Liste non ordonnée pour la navigation entre les pages du site-->
                <li class="index"><a class="eltact"href="index.php">Accueil</a></li>
                <li class="loisirs"><a href="php/loisirs.php">Tourisme et Loisirs</a></li>
                <li class="histoire"><a href="php/histoire.php">Histoire</a></li>
                <li class="contact"><a href="php/contact.php">Contact</a></li>
            </ol>
        </nav>
        <main>
            <div id="container">
                <div class="diapo"> <!--Les images pour créer un diaporama-->
                <img src="images/accueil/diapo1.jpg" alt="Image Angoulême 1"/>
                <img src="images/accueil/diapo2.jpg" alt="Image Angoulême 2"/>
                <img src="images/accueil/diapo3.jpg" alt="Image Angoulême 3"/>
                <img src="images/accueil/diapo4.jpg" alt="Image Angoulême 4"/>
                </div>
            </div>
            <section>
                <header> <!--Titre en haut du main-->
                    <h1 class="ville">La ville d'Angoulême en quelques points :</h1>
                </header>
                <div class="conteneur">
                    <article class="conteneur1"> <!--Ici le code se répète, on définit un article avec un classe pour faciliter le css puis on donne un titre à cette article, un texte et un lien vers une autre page de notre site associée avec une image-->
                        <h2>Angoulême, une ville culturelle</h2>
                        <p>Angoulême est la capitale mondiale de la BD, capitale du cinéma français, membre des villes créatives de l'Unesco</p>
                        <a href="php/loisirs.php">Découvrez-en plus sur les activités culturelles d'Angoulême<img src="images/accueil/angouleme_bd.jpg" alt="Image BD Angoulême"/></a>
                    </article>
                    <article class="conteneur2">
                        <h2>Une ville historique</h2>
                        <p>Notre ville a une histoire et une architecture atypique avec notamment ses remparts faisant le tour du centre-ville et sa position en hauteur, dominant la Charente et l'agglomération ou encore sa magnifique cathédrale</p>
                        <a href="php/histoire.php">Découvrez-en plus sur l'histoire d'Angoulême<img src="images/accueil/cathedrale.jpg" alt="Image cathédrale"/></a>
                    </article>
                    <article class="conteneur3">
                        <h2>Des loisirs et activités diverses</h2>
                        <p>Notre ville offre une panoplie de loisirs et d'événements annuels : festival de la BD, festival du film, circuit des remparts... et bien d'autres encore !</p>
                        <a href="php/loisirs.">Découvrez-en plus sur les activités d'Angoulême<img src="images/accueil/circuit.jpg" alt="Image circuit des remparts"/></a>
                    </article>
                </div>
            </section>
        </main>
        <footer> <!-- La section footer contenant les informations de bas de page -->
            <div class="footer-gauche"> <!-- Partie gauche du footer -->
                <img src="images/footer/angouleme.png" alt="Logo d'Angoulême" /> <!-- Logo d'Angoulême -->
                <img src="images/footer/unesco.png" alt="Membre des villes de l'Unesco" /> <!-- Logo de l'Unesco -->
            </div>
            <div class="footer-centre"> <!-- Partie centrale du footer -->
                <!-- Section Carte -->
                <div class="carte">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d44627.94880314658!2d0.10382750595865571!3d45.64586530034062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47fe2d85032bc499%3A0x405d39260eec0f0!2sAngoulême!5e0!3m2!1sfr!2sfr!4v1733300128398!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
       
                <!-- Section Infos  -->
                <div class="infos">
                    <dl class="infos2">
                        <dd>Angoulême 16000</dd> <!-- Adresse de la ville -->
                    </dl>
                </div>
       
                <!-- Section Logos -->
                <div class="logos">
                    <a href="https://www.facebook.com/villeangouleme" target="_blank"><img src="images/footer/facebook.png" alt="Facebook d'Angoulême" /></a> <!-- Lien vers le Facebook d'Angoulême -->
                    <a href="https://www.instagram.com/villeangouleme/" target="_blank"><img src="images/footer/instagram.png" alt="Instagram d'Angoulême" /></a> <!-- Lien vers l'Instagram d'Angoulême -->
                    <a href="https://www.youtube.com/@VilleAngoulemeTV" target="_blank"><img src="images/footer/youtube.png" alt="Youtube d'Angoulême" /></a> <!-- Lien vers la chaîne Youtube d'Angoulême -->
                    <a href="https://twitter.com/villeangouleme" target="_blank"><img src="images/footer/twitter.png" alt="Twitter d'Angoulême" /></a> <!-- Lien vers le Twitter d'Angoulême -->
                    <a href="https://fr.linkedin.com/company/villeangouleme" target="_blank"><img src="images/footer/linkedin.png" alt="Linkedin d'Angoulême" /></a> <!-- Lien vers le LinkedIn d'Angoulême -->
                </div>
            </div>
            <div class="footer-droite"> <!-- Partie droite du footer -->
                <ul class="infos1">
                    <li><p>Téléphone :</p><p>05 45 38 92 89</p></li> <!-- Affiche le numéro de téléphone -->
                    <li><a href="php/contact.php" class="contact1">Nous contacter</a></li> <!-- Lien vers la page de contact -->
                    <li><a href="php/politiquedeconfidentialite.php" class="lien">Politique de confidentialité</a></li> <!-- Lien vers la politique de confidentialité -->
                </ul>
            </div>
        </footer>
    </body>
</html>