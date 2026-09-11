<?php
    $servername ='localhost'; 
    $username ='root'; 
    $password ='root'; 
    $dbname='blockmirrordata';

try{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}
catch(Exception $e){
    die('Error : ' . $e->getMessage());
}
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    $errors = [];
    
    if (empty($fullName)) {
        $errors[] = "Full name is required.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "The email address is not valid.";
    }
    
    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "The passwords do not match.";
    }
    
    $nameParts = explode(' ', $fullName, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "This email is already in use.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, user_type) VALUES (?, ?, ?, ?, 'normal')");
                $stmt->execute([$email, $password, $firstName, $lastName]);
                
                $userId = $conn->lastInsertId();
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['first_name'] = $firstName;
                $_SESSION['last_name'] = $lastName;
                
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Registration</title>
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

        #logo {
            height: 60px;
            margin-bottom: 10px;
            transition: transform 0.5s;
        }

        #logo:hover {
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
        
        input[type="text"],
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
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 3px rgba(217, 123, 42, 0.2);
            outline: none;
        }

        input[type="text"]::placeholder,
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
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--light-blue);
        }
        
        .auth-footer a {
            color: var(--accent-orange);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .auth-footer a:hover {
            color: var(--magic-violet);
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
            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="tabs">
                <div class="tab" onclick="window.location.href='login.php'">Log in</div>
                <div class="tab active">Registration</div>
                <div class="tab" onclick="window.location.href='../index.html'">Menu</div>
            </div>
            
            <form action="inscription.php" method="post">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required
                           value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-mirror">Sign up</button>
            </form>
            
            <div class="auth-footer">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 mt-auto">
        <div class="container">
            <p>BlockMirror - An innovative puzzle game © 2025</p>
            <p class="text-muted">All rights reserved. The connections in the mirror may be reversed.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
