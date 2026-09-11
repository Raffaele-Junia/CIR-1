<?php
$pageTitle = "Ville d'Angoulême";
$css = "loisirs";
include 'header.php';
?>
        <main>
          <div class="container"> 
            <section class="entete"> <!--Partie "entete" de la page avec les liens de navigations pour naviguer sur cette page-->
              <h2>La ville d'Angoulême présente de nombreuses activités et loisirs, vous pourrez sur cette page retrouvez certaines de ces activités:</h2>
              <a href="#event">Événements annuels</a>
              <br>
              <a href="#tourisme">Tourisme et activités culturelles</a>
              <br>
              <a href="#sport">Activités sportives</a>
              <br>
              <a href="#boutique">Notre boutique</a>
              <br>
            </section>
            <section>
              <div class="entete"> <!--A partir d'ici le code se ressemble jusqu'à la fin du main, on fait une entête contenant un titre et une description, puis on fait des articles de classe "article-container" (on utilisera ces classes pour le css) ces articles contiennent à chaque fois un titre, du texte, un lien vers un site et une image-->
                <h2 id="event">Les grands événements d'Angoulême</h2>
                <p>
                  Angoulême, la capitale de la Charente, est une ville dynamique qui accueille chaque année plusieurs événements de renommée internationale, attirant des visiteurs du monde entier. Voici un aperçu des principaux rendez-vous à ne pas manquer :
                </p>
              </div>
              <div class="boite"> 
                <article class="contenu"> 
                  <h3>Festival International de la Bande Dessinée (FIBD)</h3>
                  <p> <!--Balise strong pour mettre en avant le texte-->
                    Le <strong>Festival International de la Bande Dessinée d'Angoulême</strong>, créé en 1974, est l'un des plus grands événements mondiaux dédiés au 9ᵉ art. Chaque année, à la fin janvier, auteurs, illustrateurs, éditeurs et amateurs de bande dessinée se retrouvent pour célébrer la créativité et l'innovation dans ce domaine. Expositions, rencontres, dédicaces et remise de prix (notamment le Fauve d'Or) rythment ces quelques jours festifs.
                  </p>
                  <a href="https://www.bdangouleme.com/" target="_blank">Plus d'informations ici sur le site du FIBD <img src="../images/loisir/bd.jpg" alt="Image BD Angoulême"/></a>
                </article>
            
                <article class="contenu"> 
                  <h3>Festival du Film Francophone d'Angoulême (FFA)</h3>
                  <p>
                    Le <strong>Festival du Film Francophone</strong>, organisé chaque été depuis 2008, met à l'honneur le cinéma francophone. Ce festival convivial permet de découvrir des films inédits, de rencontrer des acteurs et des réalisateurs et de célébrer la diversité culturelle de l'espace francophone.
                  </p>
                  <a href="https://filmfrancophone.fr/fr" target="_blank">Plus d'informations ici sur le site du FFA<img src="../images/loisir/ffa.jpeg" alt="Image FFA Angoulême"/></a>
                </article>
            
                <article class="contenu"> 
                  <h3>Les Musiques Métisses</h3>
                  <p>
                    Ce festival est un véritable pont entre les cultures. Depuis plus de 40 ans, <strong>Musiques Métisses</strong> propose en mai ou juin une programmation éclectique, mêlant musiques du monde, jazz, reggae et sons contemporains. C'est une occasion unique de voyager à travers les sons et les rythmes du monde entier.
                  </p>
                  <a href="https://www.musiques-metisses.com/" target="_blank">Plus d'informations ici sur le site des Musiques Métisses<img src="../images/loisir/musique.jpg" alt="Image Musiques métisses Angoulême"/></a>
                </article>
                <article class="contenu"> 
                  <h3>Le Circuit des Remparts</h3>
                  <p>
                    Pour les amateurs de voitures anciennes et de sports mécaniques, le <strong>Circuit des Remparts</strong>, organisé chaque septembre, est un rendez-vous incontournable. Depuis 1939, cet événement transforme les rues d'Angoulême en un circuit automobile où défilent des bolides de légende. Courses, expositions et ambiance rétro ravissent petits et grands.
                  </p>
                  <a href="https://www.circuitdesremparts.com/" target="_blank">Plus d'informations ici sur le site du Circuit des Remparts<img src="../images/loisir/circuit.jpg" alt="Image circuit des remparts Angoulême"/></a>
                </article>
              </div>
            </section>

            <section> <!--On crée une nouvelle section à chaque changement de thème et pour mieux structurer la page-->
              <div class="entete"> 
                <h2 id="tourisme">Tourisme et activités culturelles à Angoulême</h2>
                <p>
                  Angoulême, perchée sur une colline et entourée de remparts, offre une richesse culturelle et historique à découvrir. Voici quelques incontournables pour les visiteurs :
                </p>
              </div>
              <div class="boite"> 
                <article class="contenu"> 
                  <h3>La Cathédrale Saint-Pierre</h3>
                  <p>
                    Un chef-d'œuvre de l'art roman, la <strong>Cathédrale Saint-Pierre</strong> est un symbole emblématique d'Angoulême. Sa façade sculptée et ses détails architecturaux en font une visite incontournable pour les amateurs d'histoire et de patrimoine.
                  </p>
                  <a href="https://charente.catholique.fr/grand-angouleme/paroisses/saints-apotres/" target="_blank">En savoir plus sur la cathédrale <img src="../images/loisir/cathedrale.jpg" alt="Image Cathédrale d'Angoulême" /></a>
                </article>
                <article class="contenu"> 
                  <h3>Le Musée d'Angoulême</h3>
                  <p>
                    Ce musée abrite une collection impressionnante d'art et d'archéologie, allant de l'art primitif aux œuvres contemporaines. Une immersion fascinante dans l'histoire et la culture de la région.
                  </p>
                  <a href="https://www.angouleme-tourisme.com/patrimoine-culturel/musee-dangouleme/" target="_blank">Découvrez le Musée d'Angoulême <img src="../images/loisir/musee.jpg" alt="Image Musée d'Angoulême" /></a>
                </article>
                <article class="contenu"> 
                  <h3>Les Murs Peints</h3>
                  <p>
                    Capitale de la bande dessinée, Angoulême est célèbre pour ses <strong>murs peints</strong>, qui rendent hommage au 9ᵉ art. En flânant dans les rues, laissez-vous surprendre par ces fresques colorées qui parsèment la ville.
                  </p>
                  <a href="https://www.angouleme-tourisme.com/equipement/parcours-des-murs-peints-coeur-de-ville/" target="_blank">En savoir plus sur les murs peints <img src="../images/loisir/murs.jpg" alt="Image Murs Peints Angoulême" /></a>
                </article>
              
            </section>

            <section>
              <div class="entete">
                <h2 id="sport">Activités sportives à Angoulême</h2>
                <p>
                  Angoulême et ses environs offrent de nombreuses opportunités pour les amateurs de sport et de plein air. Que vous soyez à la recherche d'activités nautiques, terrestres ou d'aventure, la région saura vous séduire.
                </p>
              </div>
              <div class="boite"> 
                <article class="contenu"> 
                  <h3>Canoë-kayak sur la Charente</h3>
                  <p>
                    Profitez d'une balade paisible ou d'une aventure plus sportive sur la <strong>Charente</strong>. Le canoë-kayak est une excellente façon de découvrir la nature et les paysages pittoresques autour d'Angoulême, tout en profitant de l'eau.
                  </p>
                  <a href="https://www.angouleme-tourisme.com/equipement/angouleme-canoe-kayak/" target="_blank">Plus d'informations sur le canoë <img src="../images/loisir/canoe.jpg" alt="Image canoë sur la Charente" /></a>
                </article>
                <article class="contenu"> 
                  <h3>Randonnée et balades</h3>
                  <p>
                    Les sentiers de randonnée autour d'Angoulême permettent de découvrir la richesse naturelle et culturelle de la région. Entre collines, vallées et petits villages, une activité idéale pour les amateurs de marche et de grands espaces.
                  </p>
                  <a href="https://www.angouleme-tourisme.com/visiter/bouger/les-randonnees-2/" target="_blank">En savoir plus sur les randonnées <img src="../images/loisir/randonnee.jpg" alt="Image randonnée Angoulême" /></a>
                </article>
                <article class="contenu"> 
                  <h3>Vélo et cyclotourisme</h3>
                  <p>
                    Avec ses itinéraires balisés et ses routes pittoresques, Angoulême est une destination idéale pour les cyclistes. Louez un vélo et partez explorer les environs, notamment les berges de la Charente.
                  </p>
                  <a href="https://www.angouleme-tourisme.com/visiter/bouger/les-randonnees-2/a-velo/" target="_blank">Plus d'informations sur le cyclotourisme <img src="../images/loisir/velo.jpg" alt="Image cyclotourisme Angoulême" /></a>
                </article>
                <article class="contenu"> 
                  <h3>Escalade et accrobranche</h3>
                  <p>
                    Pour les amateurs de sensations fortes, les parcs d'accrobranche et les sites d'escalade proches d'Angoulême offrent des activités pour tous les âges et tous les niveaux.
                  </p>
                  <a href="https://www.angouleme-tourisme.com/equipement/accrocamp-16/" target="_blank">Découvrez l'escalade et l'accrobranche <img src="../images/loisir/escalade.jpg" alt="Image accrobranche Angoulême" /></a>
                </article>
              </div>
            </section>
            <article class="pied1"> <!--Avant la fin du main on rajoute des liens vers le site de l'office de tourisme et vers notre page boutique-->
              <h3>Retrouvez toutes ces activités et les informations nécessaires sur le site de l'office de tourisme d'Angoulême !</h3>
              <a href="https://www.angouleme-tourisme.com/" target="_blank"><img src="../images/loisir/office.jpg" alt="Image Office de Tourisme" /></a>
            </article>

            <article class="pied2">
              <a href="https://www.angouleme-tourisme.com/" target="_blank">Visitez le site de l'Office de Tourisme </a><br>
              <a id="boutique" href="boutique.php">Retrouvez tout nos  goodies liés au tourisme dans notre boutique en cliquant ici !</a>
            </article>
          </div>
        </main>
<?php
include 'footer.php';
?>