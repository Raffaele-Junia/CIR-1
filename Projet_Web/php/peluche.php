<?php
$pageTitle = "Ville d'Angoulême - Peluche";
$css = "article";
include 'header.php';
?>
    <main>
        <section class="principal"> <!--Ici on met en avant le produit princiapl avec une image, une description (contenant le titre un prix et un texte) et un bouton pour "acheter le produit"-->
            <div class="image">
                <img src="../images/boutique/peluche.png" alt="Peluche d'Angoulême">
            </div>
            <div class="description">
                <h1 class="titre">Peluche d'Angoulême</h1>
                <h2 class="prix">800€</h2>
                <p class="texte">
                    Apportez une touche de douceur et de charme local avec cette adorable peluche d'Angoulême. Conçue avec des matériaux doux et sûrs, elle est parfaite pour tous les âges.
                </p>
                <p class="texte">
                    Inspirée par l'esprit chaleureux de la ville, cette peluche est un cadeau idéal pour les enfants ou une pièce de collection unique pour les amateurs de souvenirs. À câliner sans modération !
                </p>
                <button class="bouton">Ajouter au panier</button>
            </div>
        </section>

        <h2>D'autres produits locaux d'Angoulême :</h2>
        <section class="container"><!--Ici on liste les autres produits disponibles avec des liens pour accéder aux produits-->
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

        <aside class="avis"><!--Ici on liste les avis, chaque avis se présente avec une note en étoile et un texte-->
            <h4>Avis des clients</h4>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Julie:</strong> Une peluche adorable et de grande qualité, parfaite pour ma fille !</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★☆</div>
                <p><strong>Mathieu:</strong> Très douce, mais un peu plus grande aurait été encore mieux.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Chloé:</strong> Excellent souvenir d'Angoulême, je l'adore !</p>
            </div>
        </aside>
    </main>

    <!-- Pied de page -->
<?php
include 'footer.php';
?>