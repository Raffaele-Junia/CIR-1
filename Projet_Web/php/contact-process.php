<?php
// Démarrer la session
session_start();

// Si ce n'est pas une requête POST, redirige vers le formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération sécurisée des données du formulaire
    $genre = htmlspecialchars($_POST["genre"] ?? '');
    $nom = htmlspecialchars($_POST["nom"] ?? '');
    $prenom = htmlspecialchars($_POST["prenom"] ?? '');
    $mail = htmlspecialchars($_POST["mail"] ?? '');
    $telephone = htmlspecialchars($_POST["telephone"] ?? '');
    $objet = htmlspecialchars($_POST["objet"] ?? '');
    $precision = htmlspecialchars($_POST["precision_demande"] ?? '');
    $description = htmlspecialchars($_POST["description"] ?? '');

    // Vérification que les champs obligatoires sont remplis
    if (empty($nom) || empty($prenom) || empty($mail) || empty($description)) {
        echo "<h1>Erreur : Veuillez remplir tous les champs obligatoires (Nom, Prénom, Mail, Message).</h1>";
        echo '<a href="contact.php">Retourner au formulaire</a>';
        exit;
    }
}

$servername = 'localhost';
$username = 'root';
$password = 'root';
$dataname = 'projet';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dataname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO contact (genre, nom, prenom, mail, telephone, objet, precision_demande, message) 
                            VALUES (:genre, :nom, :prenom, :email, :telephone, :objet, :precision, :description)");

    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $mail);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':objet', $objet);
    $stmt->bindParam(':precision', $precision);
    $stmt->bindParam(':description', $description);

    $stmt->execute();

    // Début de l'affichage stylisé
    echo '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
        }
        .recap {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .recap h1 {
            color: #4CAF50;
            text-align: center;
        }
        .recap p {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
        .recap ul {
            list-style: none;
            padding: 0;
        }
        .recap li {
            background: #f0f0f0;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .recap li strong {
            color: #333;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            font-size: 18px;
        }
    </style>

    <div class="recap">
        <h1>Merci pour votre message !</h1>
        <p>Voici un récapitulatif de votre demande :</p>
        <ul>
            <li><strong>Genre :</strong> ' . ($genre ? $genre : "Non précisé") . '</li>
            <li><strong>Nom :</strong> ' . $nom . '</li>
            <li><strong>Prénom :</strong> ' . $prenom . '</li>
            <li><strong>Email :</strong> ' . $mail . '</li>
            <li><strong>Téléphone :</strong> ' . ($telephone ? $telephone : "Non précisé") . '</li>
            <li><strong>Objet :</strong> ' . ($objet !== "0" ? $objet : "Non précisé") . '</li>
            <li><strong>Précision :</strong> ' . ($precision ? $precision : "Aucune précision") . '</li>
            <li><strong>Message :</strong> ' . $description . '</li>
        </ul>
        <div class="back-link">
            <a href="contact.php">Retourner au formulaire</a>
        </div>
    </div>
    ';
} catch (Exception $e) {
    echo "<h1>Erreur : Impossible d'enregistrer les données.</h1>";
    echo "Erreur : " . $e->getMessage();
    echo '<a href="contact.php">Retourner au formulaire</a>';
}
?>