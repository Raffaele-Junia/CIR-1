<?php
$pageTitle = "Ville d'Angoulême - Tasse";
$css = "article";
include 'header.php';
?>
    <main>
        <section class="principal"> <!--Ici on met en avant le produit princiapl avec une image, une description (contenant le titre un prix et un texte) et un bouton pour "acheter le produit"-->
            <div class="image">
                <img src="../images/boutique/tasse.png" alt="Tasse d'Angoulême">
            </div>
            <div class="description">
                <h1 class="titre">Tasse d'Angoulême</h1>
                <h2 class="prix">10€</h2>
                <p class="texte">
                    Découvrez notre tasse exclusive d'Angoulême, une pièce qui célèbre l'essence et le charme de cette ville historique ! 
                    Avec ses motifs finement illustrés représentant les emblématiques remparts, les rues pavées et le patrimoine culturel d'Angoulême, 
                    cette tasse est bien plus qu'un simple objet du quotidien : c'est un clin d'œil à l'art et à l'histoire de la région.
                </p>
                <p class="texte">
                    Fabriquée en céramique de haute qualité, elle est parfaite pour vos boissons chaudes et froides, alliant résistance et élégance. 
                    Offrez un cadeau unique ou ajoutez une touche locale et authentique à votre collection. 
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
                <a href="magnet.php">
                    <img src="../images/boutique/magnet.png" alt="Magnet d'Angoulême">
                    <p>Magnet d'Angoulême</p>
                </a>
                <h3>3,50€</h3>
            </article>
        </section>

        <aside class="avis"><!--Ici on liste les avis, chaque avis se présente avec une note en étoile et un texte-->
            <h4>Avis des clients</h4>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Marie:</strong> J'adore cette tasse ! Elle est magnifique et me rappelle mes vacances à Angoulême.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★☆</div>
                <p><strong>Julien:</strong> Belle qualité, mais j'aurais aimé un choix de couleurs différentes.</p>
            </div>
            <div class="avis-contenu">
                <div class="etoile">★★★★★</div>
                <p><strong>Claire:</strong> Parfait pour offrir en cadeau, mon ami a adoré !</p>
            </div>
        </aside>
    </main>

    <!-- Pied de page -->
<?php
include 'footer.php';
?>