<?php
$pageTitle = "Ville d'Angoulême";
$css = "compte";
include 'header.php';
?>
        <main> <!-- Balise principale contenant le contenu principal de la page -->
            <h1 class="creercompte">Créer un compte</h1>
            <form method="post" action="register.php"> <!-- On définit notre formulaire qui, une fois rempli, renverra vers une page de transition -->
                <fieldset class="encadre"> <!-- Encadré pour regrouper les champs du formulaire -->
                    <br>
                    <label>Genre</label> <!-- Label pour le choix du genre -->
                    <div class="alignement"> <!-- L'utilisation de la balis div pour organiser les boutons radio sur une même ligne -->
                        <input type="radio" name="genre" id="mme" value="Femme" class="grossir"> <!-- Bouton radio pour "Madame" -->
                        <label for="mme">Madame</label> <!-- Label pour le bouton radio "Madame" -->
                        <input type="radio" name="genre" id="mr" value="Homme" class="grossir"> <!-- Bouton radio pour "Monsieur" -->
                        <label for="mr">Monsieur</label> <!-- Label pour le bouton radio "Monsieur" -->
                    </div>
                    <br><br> <!--Les balises br permettent de revenir à la ligne-->
                    <label for="nom">Nom :</label> <!-- Label pour le champ "Nom" -->
                    <input type="text" name="nom" id="nom" placeholder="ex : Montagne" class="grossir" required> <!-- Champ de texte pour le nom -->
                    <br><br>              
                    <label for="prenom">Prénom :</label> <!-- Label pour le champ "Prénom" -->
                    <input type="text" name="prenom" id="prenom" placeholder="ex : Rosalie" class="grossir" required> <!-- Champ de texte pour le prénom -->
                    <br><br>
                    <label for="date_naissance">Date de naissance :</label> <!-- Label pour la date de naissance -->
                    <input type="date" name="date_naissance" id="date_naissance" class="grossir"/> <!-- Champ pour entrer la date de naissance -->
                    <br><br>
                    <label for="mail">Mail : </label> <!-- Label pour l'email -->
                    <input type="email" name="mail" id="mail" placeholder="ex : abcd@gmail.com" class="grossir" required> <!-- Champ pour l'adresse email -->
                    <br><br>
                    <label for="identifiant">Identifiant : </label> <!-- Label pour l'identifiant -->
                    <input type="text" name="identifiant" id="identifiant" placeholder="ex : TheodortleBison" class="grossir" required> <!-- Champ pour l'identifiant -->
                    <br><br>
                    <label for="mdp">Mot de passe : </label> <!-- Label pour le mot de passe -->
                    <input type="password" name="mdp" id="mdp" placeholder="•••••••••••" class="grossir" required> <!-- Champ pour le mot de passe -->
                    <br><br>
                    <label for="mdp2">Confirmer le mot de passe : </label> <!-- Label pour confirmer le mot de passe -->
                    <input type="password" name="mdp2" id="mdp2" placeholder="•••••••••••" class="grossir" required> <!-- Champ pour la confirmation du mot de passe -->
                    <br><br>
                    <input type="submit" name="connexion" id="connexion" value="Créer un compte"> <!-- Bouton pour envoyer le formulaire -->
                    <input type="reset"> <!-- Bouton pour réinitialiser le formulaire -->
                </fieldset>
                <fieldset class="encadre2"> <!-- Un autre encadré pour les liens en bas du formulaire -->
                    <a href="connexion.php">Vous avez déjà un compte ?</a> <!-- Lien vers la page de connexion si l'utilisateur a déjà un compte -->
                    <a href="../index.php">Retourner à l'accueil</a> <!-- Lien pour retourner à la page d'accueil -->
                </fieldset>
            </form>
        </main>
<?php
include 'footer.php';
?>
