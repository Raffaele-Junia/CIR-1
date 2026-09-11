<?php
$pageTitle = "Ville d'Angoulême - Porte-clé";
$css = "article";
include 'header.php';
?>
    <main>
        <section class="principal"> <!--Ici on met en avant le produit princiapl avec une image, une description (contenant le titre un prix et un texte) et un bouton pour "acheter le produit"-->
            <div class="image">
                <img src="../images/boutique/portecle.png" alt="Porte-clé d'Angoulême">
            </div>
            <div class="description">
                <h1 class="titre">Porte-clé d'Angoulême</h1>
                <h2 class="prix">2,50€</h2>
                <p class="texte">
                    Emportez un morceau d'Angoulême partout avec vous grâce à notre porte-clés d'exception ! Ce porte-clés unique rend
                    hommage à la ville avec un design inspiré des rues historiques et des monuments emblématiques.
                </p>
                <p class="texte">
                    Fabriqué avec des matériaux robustes et élégants, il est parfait pour retrouver facilement vos clés ou ajouter une touche locale à votre trousseau.
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
                <p><strong>Claire:</strong> Petit et pratique, j'aime son design simple et élégant.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★☆</div>
                <p><strong>Thomas:</strong> Bon produit, dommage qu'il n'existe pas en d'autres couleurs.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Sophie:</strong> Excellent rapport qualité-prix, je recommande à tous les amoureux d'Angoulême !</p>
            </div>
        </aside>
    </main>

    <!-- Pied de page -->
<?php
include 'footer.php';
?>