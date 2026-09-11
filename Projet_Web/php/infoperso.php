<?php
session_start();
$pageTitle = "Ville d'Angoulême";
$css = "infoperso";
include 'header.php';


$host = 'localhost';
$dbname = 'projet';
$username = 'root'; 
$password = 'root';    

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$sql = "SELECT id, genre, nom, prenom, date_naissance, mail, identifiant FROM utilisateurs";
$stmt = $pdo->query($sql);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
    <div class="container">
        <h2>Utilisateurs enregistrés</h2>

        <?php if (count($utilisateurs) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Genre</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?= htmlspecialchars($utilisateur['genre']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['mail']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
