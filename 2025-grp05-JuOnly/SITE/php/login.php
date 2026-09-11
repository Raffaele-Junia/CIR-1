<?php
session_start();

$servername = 'localhost'; 
$username = 'root'; 
$password = 'root'; 
$dbname = 'blockmirrordata';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch(Exception $e) {
    die('Error: ' . $e->getMessage());
}

if (isset($_SESSION['user_id'])) {
    header("Location: accueil.php");
    exit();
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_login_attempt'] = 0;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Security error. Please try again.";
    } else {

        $current_time = time();
        if ($_SESSION['login_attempts'] >= 5) {
            if ($current_time - $_SESSION['last_login_attempt'] < 300) {
                $error = "Too many login attempts. Please wait 5 minutes.";
            } else {
                $_SESSION['login_attempts'] = 0;
            }
        }

        if (empty($error)) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            if (empty($email) || empty($password)) {
                $error = "Please fill in all fields.";
            } else {
                try {
                    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
                    $stmt->execute([$email, $password]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        $_SESSION['login_attempts'] = 0;
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['user_type'] = $user['user_type'];
                        
                        header("Location: accueil.php");
                        exit();
                    } else {
                        $_SESSION['login_attempts']++;
                        $_SESSION['last_login_attempt'] = $current_time;
                        $error = "Incorrect email or password. Remaining attempts: " . (5 - $_SESSION['login_attempts']);
                    }
                } catch (PDOException $e) {
                    $error = "Connection error. Please try again later.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Log in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <style>
        :root {
            --primary-blue: #3a3e52;
            --dark-blue: #1b1b26;
            --light-blue: #aeb6d3;
            --mirror-effect: rgba(255, 255, 255, 0.05);
            --accent-orange: #d97b2a;
            --magic-violet: #7c6aaf;
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
        
        .navbar {
            background-color: rgba(58, 62, 82, 0.5);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
        }
        
        .navbar-brand img {
            height: 40px;
            transition: transform 0.3s;
        }
        
        .navbar-brand:hover img {
            transform: scaleX(-1);
        }
        
        .hero-header {
            background-color: rgba(58, 62, 82, 0.5);
            color: white;
            padding: 2rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
        }
        
        .hero-header::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 L0,100 Z" transform="scale(-1,1) translate(-100,0)"/></svg>');
            background-size: 100% 100%;
            opacity: 0.3;
        }
        
        .game-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
        }
        
        .mirror-text {
            display: inline-block;
            transform: scaleX(-1);
            color: var(--light-blue);
            opacity: 0.7;
        }
        
        .auth-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border-top: 5px solid var(--primary-blue);
            backdrop-filter: blur(5px);
            position: relative;
            z-index: 1;
            color: #f0f0f0;
        }
        
        .auth-container::after {
            content: "";
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid var(--light-blue);
            border-radius: 10px;
            pointer-events: none;
            z-index: -1;
            opacity: 0.3;
        }
        
        .tabs {
            display: flex;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--light-blue);
        }
        
        .tab {
            padding: 0.8rem 2rem;
            cursor: pointer;
            color: var(--light-blue);
            font-weight: 600;
            position: relative;
            transition: all 0.3s;
        }
        
        .tab.active {
            color: var(--accent-orange);
        }
        
        .tab.active::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--accent-orange);
        }
        
        .tab:not(.active):hover {
            color: var(--magic-violet);
            background-color: var(--mirror-effect);
        }
        
        .form-group {
            margin-bottom: 1.8rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.7rem;
            font-weight: 600;
            color: var(--light-blue);
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid var(--light-blue);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            color: white;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 3px rgba(217, 123, 42, 0.2);
            outline: none;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            color: white;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.7s;
        }

        .btn-primary:hover::after {
            transform: translateX(100%);
        }

        .error-message {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }
        
        .btn-mirror {
            position: relative;
            overflow: hidden;
        }

        .btn-primary:active, .btn-mirror:active {
            background-color: var(--dark-blue) !important;
        }
        
        .btn-mirror::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.7s;
        }
        
        .btn-mirror:hover::after {
            transform: translateX(100%);
        }
        
        .alert {
            padding: 1.2rem;
            margin-bottom: 2rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--light-blue);
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .auth-footer a {
            color: var(--accent-orange);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .auth-footer a::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary-blue);
            transform: scaleX(0);
            transition: transform 0.3s;
        }
        
        .auth-footer a:hover::after {
            transform: scaleX(1);
        }
        
        .logo-container {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .logo-container img {
            height: 80px;
            transition: transform 0.5s;
        }
        
        .logo-container img:hover {
            transform: scaleX(-1);
        }
    </style>
</head>
<body>
    <header class="hero-header">
        <div class="container">
            <div class="logo-container">
                <a href="../index.html"><img src="../pictures/logojeu.png" alt="BlockMirror Logo"></a>
            </div>
            <h1 class="game-title">Block<span class="mirror-text">Mirror</span></h1>
            <p>The puzzle game where every action has its reflection</p>
        </div>
    </header>

    <div class="container">
        <div class="auth-container">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="tabs">
                <div class="tab active">Log in</div>
                <div class="tab" onclick="window.location.href='inscription.php'">Registration</div>
                <div class="tab" onclick="window.location.href='../index.html'">Menu</div>
            </div>
            
            <form action="login.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" placeholder="Entrez votre email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-mirror">Log in</button>
            </form>
            
            <div class="auth-footer">
                No account yet? <a href="inscription.php">Sign up</a>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 mt-auto" style="background-color: var(--dark-blue); color: white;">
        <div class="container">
            <p>BlockMirror - An innovative puzzle game © 2025</p>
            <p class="text-muted">All rights reserved. The connections in the mirror may be reversed.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>