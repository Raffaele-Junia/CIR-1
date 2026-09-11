<?php
$pageTitle = "Ville d'Angoulême - Bracelet";
$css = "article";
include 'header.php';
?>
    <main>
        <section class="principal"> <!--Ici on met en avant le produit princiapl avec une image, une description (contenant le titre un prix et un texte) et un bouton pour "acheter le produit"-->
            <div class="image">
                <img src="../images/boutique/bracelet.png" alt="Bracelet d'Angoulême">
            </div>
            <div class="description">
                <h1 class="titre">Bracelet d'Angoulême</h1>
                <h2 class="prix">50€</h2>
                <p class="texte">
                    Ajoutez une touche d'élégance locale à votre style avec ce bracelet inspiré de l'histoire et de la culture d'Angoulême. Fabriqué avec des matériaux de qualité, il est à la fois raffiné et confortable.
                </p>
                <p class="texte">
                    Disponible en cuir ou en métal argenté, ce bracelet est parfait pour toute occasion. Portez fièrement l'esprit d'Angoulême à votre poignet et laissez-vous séduire par son design intemporel.
                </p>
                <button class="bouton">Ajouter au panier</button>
            </div>
        </section>

        <h2>D'autres produits locaux d'Angoulême :</h2>
        <section class="container"> <!--Ici on liste les autres produits disponibles avec des liens pour accéder aux produits-->
            <article>
                <a href="cartepostale.php">
                    <img src="../images/boutique/cartepostale.png" alt="Carte postale d'Angoulême">
                    <p>Carte postale d'Angoulême</p>
                </a>
                <h3>5€</h3>
            </article>
            <article>
                <a href="magnet.php">
                    <img src="../images/boutique/magnet.png" alt="Magnet d'Angoulême">
                    <p>Magnet d'Angoulême</p>
                </a>
                <h3>3,50€</h3>
            </article>
            <article>
                <a href="portecle.php">
                    <img src="../images/boutique/portecle.png" alt="Porte-clé d'Angoulême">
                    <p>Porte-clé d'Angoulême</p>
                </a>
                <h3>2,50€</h3>
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
                <p><strong>Emma:</strong> Un bracelet élégant et bien conçu, je l'adore !</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★☆</div>
                <p><strong>Paul:</strong> Très beau design, mais le cuir aurait pu être un peu plus épais.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Sophie:</strong> Parfait en cadeau, très apprécié par mes amis.</p>
            </div>
        </aside>
    </main>

<?php
include 'footer.php';
?>