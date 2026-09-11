<?php
$pageTitle = "Ville d'Angoulême - Magnet";
$css = "article";
include 'header.php';
?>
    <main>
        <section class="principal"> <!--Ici on met en avant le produit princiapl avec une image, une description (contenant le titre un prix et un texte) et un bouton pour "acheter le produit"-->
            <div class="image">
                <img src="../images/boutique/magnet.png" alt="Magnet d'Angoulême">
            </div>
            <div class="description">
                <h1 class="titre">Magnet d'Angoulême</h1>
                <h2 class="prix">3,50€</h2>
                <p class="texte">
                    Ajoutez un morceau d'Angoulême à votre quotidien avec notre magnet exclusif ! Ce magnet unique célèbre le charme intemporel
                    d'Angoulême, avec des détails soignés qui rappellent ses paysages historiques et son atmosphère artistique.
                </p>
                <p class="texte">
                    Fabriqué avec des matériaux de haute qualité, il est parfait pour décorer votre réfrigérateur ou tout autre espace métallique.
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
                <a href="bracelet.php">
                    <img src="../images/boutique/bracelet.png" alt="Bracelet d'Angoulême">
                    <p>Bracelet d'Angoulême</p>
                </a>
                <h3>50€</h3>
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

        <aside class="avis"><!--Ici on liste les avis, chaque avis se présente avec une note en étoile et un texte-->
            <h4>Avis des clients</h4>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Camille:</strong> Magnifique magnet, un excellent souvenir d'Angoulême !</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★☆</div>
                <p><strong>Lucas:</strong> Bonne qualité, mais j'aurais aimé un design un peu plus coloré.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Emma:</strong> Parfait pour décorer mon réfrigérateur, je l'adore !</p>
            </div>
        </aside>
    </main>

    <!-- Pied de page -->
<?php
include 'footer.php';
?>