<?php
$pageTitle = "Ville d'Angoulême";
$css = "connexion";
include 'header.php';
?>
        <main> <!-- Balise principale contenant le contenu principal de la page -->
            <form method="post" action="login.php"> <!-- Formulaire pour la connexion, méthode "post" pour envoyer les données -->
                <fieldset class="encadre"> <!-- Encadré contenant les champs de saisie -->
                    <legend class="connexion">Se connecter</legend> <!-- Titre de l'encadré -->
                    <label for="nom" class="grossir2">Identifiant ou e-mail :</label> <!-- Libellé pour le champ "identifiant" -->
                    <input type="text" name="identifiant" id="identifiant" placeholder="ex : jeffdurand@gmail.com" class="grossir"> <!-- Champ de saisie pour l'identifiant ou l'e-mail -->
                    <br><br> <!--Les balises br permettent de revenir à la ligne-->
                    <label for="mdp" class="grossir2">Mot de passe : </label> <!-- Libellé pour le champ "mot de passe" -->
                    <input type="password" name="mdp" id="mdp" placeholder="•••••••••••" class="grossir"> <!-- Champ de saisie pour le mot de passe -->
                    <br><br>
                    <input type="submit" name="connexion" id="connexion" value="Connexion"> <!-- Bouton de soumission du formulaire -->
                    <input type="reset"> <!-- Bouton pour réinitialiser le formulaire -->
                    <div class="ou">
                        <span>ou</span> <!-- Texte "ou" affiché au centre -->
                    </div>
                    <a href="compte.php"><input type="compte" name="compte" class="compte" value="Créer un compte"></a> <!-- Lien vers la page pour créer un compte, sous forme de bouton -->
                </fieldset>
                <fieldset class="encadre2"> <!-- Deuxième encadré pour les options de navigation -->
                    <p><a href="../index.php">Retourner à l'accueil</a></p> <!-- Lien vers la page d'accueil -->
                </fieldset>
            </form>
        </main>
<?php
include 'footer.php';
?>