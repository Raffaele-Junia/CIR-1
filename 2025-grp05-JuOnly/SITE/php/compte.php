<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'blockmirrordata';

$errors = [];
$success = false;
$user = null;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        $checkEmailStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $checkEmailStmt->execute([$email, $_SESSION['user_id']]);
        if ($checkEmailStmt->fetch()) {
            $errors[] = "Cette adresse email est déjà utilisée par un autre compte.";
        }

        if (empty($firstName) || empty($lastName)) {
            $errors[] = "Le prénom et le nom sont obligatoires.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email n'est pas valide.";
        }

        if (!empty($newPassword)) {
            if ($currentPassword !== $user['password']) {
                $errors[] = "Le mot de passe actuel est incorrect.";
            } elseif (strlen($newPassword) < 8) {
                $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
            }
        }

        if (empty($errors)) {
            try {
                if (!empty($newPassword)) {
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE user_id = ?");
                    $stmt->execute([$firstName, $lastName, $email, $newPassword, $_SESSION['user_id']]);
                } else {
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
                    $stmt->execute([$firstName, $lastName, $email, $_SESSION['user_id']]);
                }

                $_SESSION['first_name'] = $firstName;
                $_SESSION['last_name'] = $lastName;
                $_SESSION['email'] = $email;

                $success = true;
                $user = array_merge($user, [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ]);
                if (!empty($newPassword)) {
                    $user['password'] = $newPassword;
                }
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de la mise à jour : " . $e->getMessage();
            }
        }
    }

} catch(Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <style>
        :root {
            --primary-blue: #3a3e52;
            --dark-blue: #1b1b26;
            --light-blue: #aeb6d3;
            --mirror-effect: rgba(255, 255, 255, 0.05);
            --accent-orange: #d97b2a;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../pictures/dungeon-bg.png') no-repeat center center fixed;
            background-size: cover;
            color: #f0f0f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero-header {
            background-color: rgba(58, 62, 82, 0.5);
            color: white;
            padding: 2rem 0;
            text-align: center;
            position: relative;
        }

        .hero-header::before {
            content: "";
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: rgba(255,255,255,0.03);
            background-size: 100% 100%;
            opacity: 0.1;
        }

        .game-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .mirror-text {
            display: inline-block;
            transform: scaleX(-1);
            color: var(--light-blue);
            opacity: 0.7;
        }

        .account-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border-top: 5px solid var(--primary-blue);
            backdrop-filter: blur(6px);
        }

        .account-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .account-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--dark-blue);
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--light-blue);
            font-weight: bold;
            box-shadow: 0 0 10px rgba(255,255,255,0.2);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--light-blue);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
            font-size: 1rem;
        }

        input::placeholder {
            color: #ccc;
        }

        .btn-mirror {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-mirror:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-mirror::after {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.7s;
        }

        .btn-mirror:hover::after {
            transform: translateX(100%);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            color: white;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
        }

        .password-section {
            background-color: var(--mirror-effect);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }

        .back-link {
            display: inline-block;
            margin: 1rem 0;
            color: var(--light-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        footer {
            background-color: rgba(58, 62, 82, 0.5);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
            text-align: center;
            backdrop-filter: blur(3px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <header class="hero-header">
        <div class="container">
            <h1 class="game-title">Block<span class="mirror-text">Mirror</span></h1>
            <p>Account management</p>
        </div>
    </header>

    <div class="container">
        <a href="accueil.php" class="back-link">← Return to home</a>
        <div class="account-container">
            <div class="account-header">
                <div class="account-avatar">
                    <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                </div>
                <h2>My Account</h2>
                <p>Manage your personal information</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    Your account has been successfully updated!
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <?php echo htmlspecialchars($error); ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="first_name">First name</label>
                    <input type="text" id="first_name" name="first_name"
                           value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Name</label>
                    <input type="text" id="last_name" name="last_name"
                           value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="password-section">
                    <h4>Change the password</h4>
                    <p class="text-muted">Fill this out only if you wish to change your password.</p>

                    <div class="form-group">
                        <label for="current_password">Current password</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">New password</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm the new password</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>

                <button type="submit" class="btn-mirror">Update</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>BlockMirror - An innovative puzzle game © 2025</p>
            <p class="text-muted">All rights reserved. Changes in the mirror may be reversed.</p>
        </div>
    </footer>
</body>
</html>
