<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit(); 
}

$levels_file_path = __DIR__ . '/../file/creer.txt';
$levels_data = []; 
$feedback_message = '';

$fileDir = dirname($levels_file_path);
if (!is_dir($fileDir)) {
    if (!mkdir($fileDir, 0775, true)) {
        die("Erreur : Impossible de créer le dossier 'file'.");
    }
}
if (!file_exists($levels_file_path)) {
    if (file_put_contents($levels_file_path, "") === false) {
        die("Erreur : Impossible de créer le fichier 'creer.txt'.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_level') {
    if (isset($_POST['level_index_to_delete'])) {
        $level_index_to_delete = intval($_POST['level_index_to_delete']);
        $response_data = ['status' => 'error', 'message' => 'Une erreur inconnue est survenue.'];

        if (file_exists($levels_file_path)) {
            $content = file_get_contents($levels_file_path);
            $raw_levels_blocks = preg_split("/\R{2,}/", trim($content));

            if (isset($raw_levels_blocks[$level_index_to_delete])) {
                $deleted_level_name_lines = explode("\n", trim($raw_levels_blocks[$level_index_to_delete]));
                $deleted_level_name = "Niveau Sans Nom";
                 if (count($deleted_level_name_lines) > 0 && strpos(trim($deleted_level_name_lines[0]), "NOM:") === 0) {
                    $deleted_level_name = trim(substr(trim($deleted_level_name_lines[0]), 4));
                }

                unset($raw_levels_blocks[$level_index_to_delete]);
                
                $updated_levels_blocks = array_values($raw_levels_blocks);
                
                $updated_content = implode("\n\n", $updated_levels_blocks);
                if (!empty(trim($updated_content))) {
                    $updated_content = trim($updated_content) . "\n"; 
                } else {
                    $updated_content = "";
                }

                if (file_put_contents($levels_file_path, $updated_content, LOCK_EX) !== false) {
                    $_SESSION['feedback_message'] = "Niveau \"".htmlspecialchars($deleted_level_name)."\" supprimé avec succès.";
                    $response_data = ['status' => 'success', 'message' => $_SESSION['feedback_message']];
                } else {
                    $_SESSION['feedback_message'] = "Erreur : Impossible de mettre à jour le fichier des niveaux après suppression.";
                    $response_data['message'] = $_SESSION['feedback_message'];
                }
            } else {
                $_SESSION['feedback_message'] = "Erreur : Niveau à supprimer non trouvé (index invalide).";
                 $response_data['message'] = $_SESSION['feedback_message'];
            }
        } else {
            $_SESSION['feedback_message'] = "Erreur : Fichier des niveaux non trouvé pour la suppression.";
            $response_data['message'] = $_SESSION['feedback_message'];
        }
        header('Content-Type: application/json');
        echo json_encode($response_data); 
        exit();
    }
}

if (isset($_SESSION['feedback_message'])) {
    $feedback_message = $_SESSION['feedback_message'];
    unset($_SESSION['feedback_message']);
}

if (file_exists($levels_file_path)) {
    $content = file_get_contents($levels_file_path);
    $raw_levels_blocks = preg_split("/\R{2,}/", trim($content)); 
    
    foreach ($raw_levels_blocks as $index => $level_block_str) {
        if (!empty(trim($level_block_str))) {
            $lines = explode("\n", trim($level_block_str));
            $level_name_from_file = "Niveau Sans Nom " . ($index + 1); 

            if (count($lines) > 0 && strpos(trim($lines[0]), "NOM:") === 0) {
                $level_name_from_file = trim(substr(trim($lines[0]), 4)); 
            }
            
            $levels_data[] = [
                'index' => $index,
                'name' => $level_name_from_file
            ];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockMirror - Créateur de Niveaux</title>
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-accent-color: #ffcc00;
            --hover-accent-color: #ffe066;
            --secondary-color: #3a3e52; 
            --background-color: #000;
            --text-color: #f0f0f0;
            --card-bg: rgba(27, 27, 38, 0.92);
            --card-border: var(--primary-accent-color);
            --danger-color: #e74c3c;
            --danger-hover-color: #c0392b;
        }
        html, body { 
            height: 100%; margin: 0; padding: 0; color: var(--text-color);
            font-family: 'Orbitron', sans-serif; background-color: var(--background-color);
            background-image: url('../pictures/fond.png'); background-size: cover;
            background-attachment: fixed; line-height: 1.6;
        }
        .page-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .header-title { 
            font-family: 'Bangers', cursive; color: var(--text-color);
            text-align: center; font-size: 3.5rem; padding: 20px 0;
            text-shadow: 2px 2px 5px var(--primary-accent-color), 3px 3px 7px #000;
            background-color: rgba(0,0,0,0.5);
        }
        .container { 
            width: 90%; max-width: 1100px; margin: 20px auto; padding: 25px;
            background-color: var(--card-bg); border-radius: 12px;
            box-shadow: 0 0 25px rgba(0,0,0,0.6); border: 1px solid var(--card-border);
            flex-grow: 1;
        }
        .section-title { 
            font-family: 'Bangers', cursive; color: var(--primary-accent-color);
            text-align: center; font-size: 2.5rem; margin-bottom: 25px;
            border-bottom: 2px solid var(--primary-accent-color);
            display: inline-block; padding-bottom: 5px;
            text-shadow: 1px 1px 2px #000;
        }
        .centered-title-wrapper { text-align: center; margin-bottom: 30px; }

        .action-buttons-bar { text-align: center; margin-bottom: 40px; padding: 10px; }
        .main-action-btn {
            font-family: 'Bangers', cursive; font-size: 2.2rem; 
            color: var(--text-color);
            background-color: transparent; border: none;
            padding: 10px 15px; cursor: pointer; text-decoration: none;
            display: inline-block; transition: color 0.3s, text-shadow 0.3s, transform 0.2s;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7); 
        }
        .main-action-btn:hover {
            color: var(--primary-accent-color); 
            text-shadow: 0 0 10px var(--primary-accent-color), 0 0 15px var(--primary-accent-color); 
            transform: scale(1.05);
        }

        .level-list { 
            display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px; margin-top: 20px;
        }
        .level-card { 
            background-color: rgba(40, 40, 55, 0.85);
            border: 2px solid var(--primary-accent-color); 
            border-radius: 10px;
            padding: 15px; box-shadow: 0 5px 10px rgba(0,0,0,0.5);
            display: flex; flex-direction: column; text-align: center; 
        }
        .level-card h3 {
            font-family: 'Bangers', cursive; color: #fff; margin-top: 0;
            margin-bottom: 15px; font-size: 1.8rem; 
            word-break: break-word; 
        }
        
        .card-btn { 
            font-family: 'Bangers', cursive; font-size: 1.2rem;
            color: #000; background-color: var(--primary-accent-color);
            border: none; padding: 8px 15px; border-radius: 5px;
            cursor: pointer; text-decoration: none; display: inline-block;
            transition: background-color 0.2s, transform 0.1s, box-shadow 0.2s; 
            margin: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .card-btn:hover {
            background-color: var(--hover-accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
        }
        .card-btn.delete {
            background-color: var(--danger-color);
            color: white;
        }
        .card-btn.delete:hover {
            background-color: var(--danger-hover-color);
        }

        .modal { 
            display: none; position: fixed; z-index: 1000; left: 0; top: 0;
            width: 100%; height: 100%; overflow: auto; 
            background-color: rgba(0,0,0,0.75); backdrop-filter: blur(5px);
        }
        .modal-content { 
            font-family: 'Orbitron', sans-serif; background-color: #12121a; 
            color: var(--text-color); margin: 10% auto; padding: 30px;
            border: 3px solid var(--primary-accent-color);
            border-radius: 15px;
            width: 90%; max-width: 480px; box-shadow: 0 8px 25px rgba(0,0,0,0.6);
            text-align: left; position: relative;
        }
        .modal-content h2 { 
            font-family: 'Bangers', cursive; color: var(--primary-accent-color);
            font-size: 2rem; margin-top: 0; margin-bottom: 20px; text-align: center;
            text-shadow: 1px 1px 2px #000;
        }
        .modal-content label {
            display: block; margin-top: 15px; margin-bottom: 5px;
            font-weight: 700; color: #fff; font-size: 1rem;
        }
        .modal-content input[type="text"],
        .modal-content input[type="number"] {
            width: calc(100% - 22px); padding: 10px; margin-bottom: 15px;
            border-radius: 6px; border: 2px solid var(--secondary-color);
            background-color: #1f1f2b; color: white; font-size: 1rem;
            font-family: 'Orbitron', sans-serif;
        }
        .modal-content input[type="text"]:focus,
        .modal-content input[type="number"]:focus {
            border-color: var(--primary-accent-color); outline: none;
            box-shadow: 0 0 8px var(--primary-accent-color);
        }
        .modal-btn-bar { text-align: right; margin-top: 20px;}
        .modal-btn { 
            font-family: 'Bangers', cursive; font-size: 1.3rem;
            color: black; background-color: var(--primary-accent-color);
            border: none; padding: 8px 18px; border-radius: 5px; cursor: pointer;
            transition: background-color 0.2s; letter-spacing: 1px; margin-left: 10px;
        }
        .modal-btn.cancel { background-color: #6c757d; color:white; }
        .modal-btn:hover { background-color: var(--hover-accent-color); }
        .modal-btn.cancel:hover { background-color: #545b62; }

        .close-btn-modal { 
            color: #888; position: absolute; top: 10px; right: 15px;
            font-size: 28px; font-weight: bold; transition: color 0.2s;
            line-height: 1;
        }
        .close-btn-modal:hover, .close-btn-modal:focus {
            color: var(--primary-accent-color); text-decoration: none; cursor: pointer;
        }
        .footer { 
            text-align:center; padding: 20px; color: #aaa; font-size: 0.9em; 
            background-color: rgba(0,0,0,0.5); margin-top:auto;
        }
        .feedback-display { 
            padding: 10px;
            margin: 15px auto;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            max-width: 600px;
        }
        .feedback-display.success { background-color: #2ecc71; color: #145a32; }
        .feedback-display.error   { background-color: var(--danger-color); color: white; }
#page-fade {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    z-index: 9999;
    opacity: 1;
    transition: opacity 1s ease-in-out;
    pointer-events: none;
}
    </style>
</head>
<body>

<div id="page-fade"></div>
<div class="page-wrapper">
    <header class="header-title">BlockMirror Level Creator</header>

    <div class="container">
        <div class="action-buttons-bar">
            <button id="openNewLevelModalBtn" class="main-action-btn">Créer Nouveau Niveau</button>
        </div>

        <?php if (!empty($feedback_message)): ?>
            <div class="feedback-display <?= (strpos(strtolower($feedback_message), 'erreur') === false) ? 'success' : 'error' ?>">
                <?= htmlspecialchars($feedback_message) ?>
            </div>
        <?php endif; ?>
        
        <div class="centered-title-wrapper">
            <h2 class="section-title">Vos Niveaux Personnalisés</h2>
        </div>

        <?php if (empty($levels_data)): ?>
            <p style="text-align: center; font-size: 1.1rem; color: #aaa;">Aucun niveau personnalisé.<br>Cliquez ci-dessus pour en créer un !</p>
        <?php else: ?>
            <div class="level-list">
                <?php foreach ($levels_data as $level): ?>
                    <div class="level-card" id="level-card-<?= $level['index'] ?>">
                        <h3><?= htmlspecialchars($level['name']) ?></h3>
                        <div style="margin-top:15px;"> 
                            <a href="creer_niveau_modifier.php?action=edit&level_index=<?= $level['index'] ?>" class="card-btn">Modifier</a>
                            <a href="jeucreer.php?nom=<?= urlencode($level['name']) ?>" class="card-btn">Jouer</a>
                            <form method="POST" action="creer_niveau.php" style="display: inline-block; margin:0;" class="delete-level-form">
                                <input type="hidden" name="action" value="delete_level">
                                <input type="hidden" name="level_index_to_delete" value="<?= $level['index'] ?>">
                                <button type="submit" class="card-btn delete">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 40px;">
            <a href="niveaumenujeu.php" class="main-action-btn" style="font-size:1.8rem;">« Retour au Menu des Niveaux</a>
        </div>

    </div> 

    <div id="newLevelModal" class="modal">
        <div class="modal-content">
            <span class="close-btn-modal" id="closeNewLevelModalBtn">×</span>
            <h2>Nouveau Niveau Personnalisé</h2>
            <form id="newLevelForm" action="creer_nouveau_niveau.php" method="GET">
                <div>
                    <label for="level_name">Nom du Niveau :</label>
                    <input type="text" id="level_name" name="level_name" placeholder="Ex: Labyrinthe Royal" maxlength="50" required>
                </div>
                <div>
                    <label for="map_side_size">Taille du Côté (carré, 12-19):</label>
                    <input type="number" id="map_side_size" name="side_size" value="15" min="12" max="19" required>
                    <p style="font-size:0.8em; color:#aaa; margin-top: -10px;">Grille de N x N de chaque côté du miroir (Largeur totale: 2N+1).</p>
                </div>
                <div class="modal-btn-bar">
                    <button type="button" class="modal-btn cancel" id="cancelNewLevelModalBtn">Annuler</button>
                    <button type="submit" class="modal-btn">Créer la Grille</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="footer">
        BlockMirror Level Creator © <?= date("Y") ?>
    </footer>
</div> 
<audio id="transitionSound" src="../son/son1.mp3"></audio>
<audio autoplay loop>
    <source src="../son/soncreer.mp3" type="audio/mpeg">
</audio>
    <script>
function redirectWithFade(url) {
    const pageFade = document.getElementById('page-fade');
    const sound = document.getElementById('transitionSound');
    
    if (sound) {
        sound.currentTime = 0;
        sound.play().catch(e => console.error("Erreur de lecture du son pour la transition:", e));
    }
    
    if (pageFade) {
        pageFade.style.display = 'block';
        pageFade.style.opacity = '0';
    }

    document.querySelectorAll('a, button, input').forEach(element => {
        element.style.pointerEvents = 'none';
    });

    setTimeout(() => {
        if (pageFade) pageFade.style.opacity = '1';
        setTimeout(() => {
            window.location.href = url;
        }, (sound && sound.duration > 0.7) ? 700 : 1000); 
    }, 10);
}

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des formulaires de suppression
    const deleteForms = document.querySelectorAll('form.delete-level-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const sound = document.getElementById('transitionSound');
            if (sound) {
                sound.currentTime = 0;
                sound.play().catch(err => console.error("Erreur de lecture du son:", err));
            }
            
            const formData = new FormData(this);
            const formActionUrl = this.getAttribute('action'); // Correction ici

            fetch(formActionUrl, { // Correction ici
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Attendre une réponse JSON
            .then(data => {
                if (data.status === 'success') {
                    redirectWithFade(window.location.href); 
                } else {
                    console.error('Erreur lors de la suppression:', data.message);
                    // Afficher le message d'erreur à l'utilisateur si nécessaire
                    // Par exemple, en mettant à jour le .feedback-display
                    const feedbackDisplay = document.querySelector('.feedback-display');
                    if(feedbackDisplay){
                        feedbackDisplay.textContent = data.message || 'Une erreur est survenue.';
                        feedbackDisplay.className = 'feedback-display error'; // Assurez-vous que la classe est correcte
                        feedbackDisplay.style.display = 'block';
                         setTimeout(() => {
                            feedbackDisplay.style.transition = 'opacity 0.5s ease-out';
                            feedbackDisplay.style.opacity = '0';
                            setTimeout(() => {
                                feedbackDisplay.style.display = 'none';
                                feedbackDisplay.style.opacity = '1'; // Reset for next time
                            }, 500);
                        }, 4000);
                    } else {
                        alert(data.message || 'Une erreur est survenue lors de la suppression du niveau.');
                    }
                }
            }).catch(error => {
                console.error('Erreur fetch:', error);
                alert('Une erreur réseau est survenue. Détails en console.');
            });
        });
    });

    window.addEventListener('load', () => {
        const pageFade = document.getElementById('page-fade');
        if (pageFade) {
            pageFade.style.opacity = '1';
            setTimeout(() => {
                pageFade.style.opacity = '0';
                setTimeout(() => {
                    pageFade.style.display = 'none';
                }, 1000);
            }, 500);
        }
    });

    const backButton = document.querySelector('a[href="niveaumenujeu.php"]');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            redirectWithFade(this.href);
        });
    }

    // Gestion des clics sur les boutons Modifier et Jouer pour inclure la transition
    const cardActionLinks = document.querySelectorAll('.level-card a.card-btn');
    cardActionLinks.forEach(link => {
        // Ne pas appliquer aux boutons de suppression qui sont gérés par le formulaire
        if (!link.classList.contains('delete')) { 
            link.addEventListener('click', function(e) {
                e.preventDefault();
                redirectWithFade(this.href);
            });
        }
    });
    
    const newLevelForm = document.getElementById('newLevelForm');
    if (newLevelForm) {
        // Le bouton submit du formulaire gère déjà la soumission.
        // Si on veut que le clic sur le bouton "Créer la Grille" déclenche la transition avant soumission :
        const createGridSubmitButton = newLevelForm.querySelector('button[type="submit"].modal-btn');
        if(createGridSubmitButton){
            newLevelForm.addEventListener('submit', function(e) {
                 e.preventDefault();
                 if(this.checkValidity()){
                    const formAction = this.action;
                    const formData = new FormData(this);
                    const params = new URLSearchParams(formData);
                    const url = `${formAction}?${params.toString()}`;
                    redirectWithFade(url);
                 } else {
                    this.reportValidity();
                 }
            });
        }
    }

    const modal = document.getElementById("newLevelModal");
    const openModalBtn = document.getElementById("openNewLevelModalBtn");
    const closeModalBtn = document.getElementById("closeNewLevelModalBtn");
    const cancelModalBtn = document.getElementById("cancelNewLevelModalBtn");

    if (openModalBtn && modal) {
        openModalBtn.addEventListener('click', function() { // Ajout de l'écouteur d'événement
            const sound = document.getElementById('transitionSound');
            if (sound) {
                sound.currentTime = 0;
                sound.play().catch(e => console.error("Erreur de lecture du son:", e));
            }
            setTimeout(() => { // Petit délai pour le son
                 modal.style.display = "block"; 
                 const levelNameInput = document.getElementById('level_name');
                 if (levelNameInput) levelNameInput.focus();
            }, 150);
        });
    }
    
    function closeModal() {
        if (modal) {
            modal.style.display = "none";
            const form = document.getElementById('newLevelForm');
            if (form) form.reset(); 
        }
    }

    if (closeModalBtn) closeModalBtn.onclick = closeModal;
    if (cancelModalBtn) cancelModalBtn.onclick = closeModal;
    
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
    
    const feedbackElement = document.querySelector('.feedback-display');
    if (feedbackElement && feedbackElement.textContent.trim() !== '') {
        setTimeout(() => {
            feedbackElement.style.transition = 'opacity 0.5s ease-out';
            feedbackElement.style.opacity = '0';
            setTimeout(() => {
                feedbackElement.style.display = 'none';
                feedbackElement.style.opacity = '1'; // Reset for next time
            }, 500);
        }, 4000);
    }
});
    </script>
</body>
</html>