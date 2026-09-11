<?php
$pageTitle = "Ville d'Angoulême - Carte Postale";
$css = "article";
include 'header.php';
?>
    <main>
        <section class="principal"> <!--Ici on met en avant le produit princiapl avec une image, une description (contenant le titre un prix et un texte) et un bouton pour "acheter le produit"-->
            <div class="image">
                <img src="../images/boutique/cartepostale.png" alt="Carte postale d'Angoulême">
            </div>
            <div class="description">
                <h1 class="titre">Carte Postale d'Angoulême</h1>
                <h2 class="prix">5€</h2>
                <p class="texte">
                    Envoyez un peu d'Angoulême dans chaque courrier avec cette carte postale illustrée ! Capturant les vues emblématiques de la ville, elle est parfaite pour partager un souvenir ou ajouter une touche artistique à votre collection.
                </p>
                <p class="texte">
                    Imprimée sur un papier de haute qualité avec des couleurs vives et durables, cette carte postale reflète le charme unique d'Angoulême. Un souvenir intemporel à offrir ou à conserver !
                </p>
                <button class="bouton">Ajouter au panier</button>
            </div>
        </section>

        <h2>D'autres produits locaux d'Angoulême :</h2>
        <section class="container"><!--Ici on liste les autres produits disponibles avec des liens pour accéder aux produits-->
            <article>
                <a href="portecle.php">
                    <img src="../images/boutique/portecle.png" alt="Porte-clé d'Angoulême">
                    <p>Porte-clé d'Angoulême</p>
                </a>
                <h3>2,50€</h3>
            </article>
            <article>
                <a href="magnet.php">
                    <img src="../images/boutique/magnet.png" alt="Magnet d'Angoulême">
                    <p>Magnet d'Angoulême</p>
                </a>
                <h3>3,50€</h3>
            </article>
            <article>
                <a href="bracelet.php">
                    <img src="../images/boutique/bracelet.png" alt="Bracelet d'Angoulême">
                    <p>Bracelet d'Angoulême</p>
                </a>
                <h3>50€</h3>
            </article>
            <article>
                <a href="tasse.php">
                    <img src="../images/boutique/tasse.png" alt="Tasse d'Angoulême">
                    <p>Tasse d'Angoulême</p>
                </a>
                <h3>10€</h3>
            </article>
        </section>

        <aside class="avis"> <!--Ici on liste les avis, chaque avis se présente avec une note en étoile et un texte-->
            <h4>Avis des clients</h4>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Marie:</strong> Très belle carte, les couleurs sont magnifiques. Parfaite pour envoyer à des amis !</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★☆</div>
                <p><strong>Lucas:</strong> Belle impression, mais j'aurais aimé un choix de designs différents.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Emma:</strong> Un souvenir authentique d'Angoulême, très satisfait de la qualité.</p>
            </div>
        </aside>
    </main>

    <!-- Pied de page -->
<?php
include 'footer.php';
?>
