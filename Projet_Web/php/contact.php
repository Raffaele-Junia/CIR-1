<?php
$pageTitle = "Ville d'Angoulême";
$css = "contact";
include 'header.php';
?>
        <main> <!-- Balise principale contenant le contenu principal de la page -->
            <fieldset class="encadre"> <!-- <fieldset> crée un encadré pour structurer le formulaire -->
                <legend class="contact">Contact</legend> <!-- Légende pour le bloc de formulaire, avec un titre "Contact" -->
                <form method="post" action="contact-process.php"> <!-- Le formulaire envoie ses données via la méthode POST -->
                    <fieldset class="encadre1">
                        <!-- Un autre encadré pour regrouper les informations sur l'utilisateur -->
                        <legend class="infos">Informations sur vous</legend> <!-- Légende "Informations sur vous" -->
                        <div class="active">
                            <label>Genre:</label> <!-- Label pour sélectionner le genre -->
                        </div>
                        <div class="alignement"> <!-- Conteneur pour organiser les boutons radio -->
                            <div class="madame">
                                <input type="radio" name="genre" id="mme" class="grossir"> <!-- Bouton radio pour "Madame" -->
                                <label for="mme">Madame</label> <!-- Label associé au bouton radio -->
                            </div>
                            <div class="monsieur">
                                <input type="radio" name="genre" id="mr" class="grossir"> <!-- Bouton radio pour "Monsieur" -->
                                <label for="mr">Monsieur</label> <!-- Label associé au bouton radio -->
                            </div>
                        </div>
                        <br><br>
                        <div class="active">
                            <label for="nom">Nom :</label> <!-- Label pour le champ "Nom" -->
                        </div>
                        <input type="text" name="nom" id="nom" placeholder="ex : Montagne" class="grossir"> <!-- Champ de saisie pour le nom -->
                        <br><br>
                        <div class="active">
                            <label for="prenom">Prénom :</label> <!-- Label pour le champ "Prénom" -->
                        </div>
                        <input type="text" name="prenom" id="prenom" placeholder="ex : Rosalie" class="grossir"> <!-- Champ de saisie pour le prénom -->
                        <br><br>
                        <div class="active">
                            <label for="mail">Mail :</label> <!-- Label pour le champ "Email" -->
                        </div>
                        <input type="email" name="mail" id="mail" placeholder="sfryahoo@gmail.com" class="grossir"> <!-- Champ de saisie pour l'email -->
                        <br><br>
                        <div class="active">
                            <label for="telephone">Téléphone :</label> <!-- Label pour le champ "Téléphone" -->
                        </div>
                        <input type="tel" name="telephone" id="telephone" placeholder="ex : +33 6 12 34 56 78" class="grossir"> <!-- Champ de saisie pour le téléphone -->
                    </fieldset>
                    <fieldset class="encadre2"> <!-- Un autre encadré pour les détails de la demande -->
                        <legend class="demande">Votre demande</legend> <!-- Légende "Votre demande" -->
                        <div class="active">
                            <label for="objet">Objet du message :</label> <!-- Label pour le champ "Objet" -->
                        </div>
                        <select id="objet" name="objet" class="grossir"> <!-- Liste déroulante pour sélectionner l'objet du message -->
                            <option value="0">- Séléctionner -</option>
                            <option value="Question sur le logement">Question sur le logement</option> <!-- Option pour "Logement" -->
                            <option value="Question sur la propreté de la ville">Question sur la propreté de la ville</option> <!-- Option pour "Propreté" -->
                            <option value="Prendre rendez-vous avec municipalité">Prendre rendez-vous avec municipalité</option> <!-- Option pour "Rendez-vous municipalité" -->
                            <option value="Demande d'emploi">Demande d'emploi</option> <!-- Option pour "Demande d'emploi" -->
                            <option value="Autres demandes (à préciser)">Autres demandes (à préciser)</option> <!-- Option pour "Autres demandes" -->
                        </select>
                        <br><br>
                        <div class="active">
                            <label for="precision_demande">Si autres demandes, préciser:</label> <!-- Label pour le champ "Précision" -->
                        </div>
                        <input type="text" name="precision_demande" id="precision_demande" class="grossir"> <!-- Champ de saisie pour préciser l'objet -->
                        <br><br>
                        <div class="active">
                            <label for="description">Message :</label> <br> <!-- Label pour le champ "Message" -->
                        </div>
                        <textarea rows="10" cols="50" name="description" id="description" maxlength="200" class="message-box" class="grossir"></textarea> <!-- Zone de texte pour le message -->
                        <br><br>
                        <input type="submit" name="Envoyer" id="soumission" class="grossir"> <!-- Bouton pour soumettre le formulaire -->
                        <input type="reset" class="grossir"> <!-- Bouton pour réinitialiser le formulaire -->
                    </fieldset>
                </form>
            </fieldset>
        </main>
<?php
include 'footer.php';
?>