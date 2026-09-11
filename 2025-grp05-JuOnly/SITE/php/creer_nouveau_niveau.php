<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$level_name = isset($_GET['level_name']) ? trim(strip_tags($_GET['level_name'])) : "Nouveau Niveau";
if (empty($level_name)) $level_name = "Nouveau Niveau";
$level_name = substr($level_name, 0, 50);

$side_size = isset($_GET['side_size']) ? intval($_GET['side_size']) : 15;
$side_size = max(12, min(19, $side_size)); 

$map_height = $side_size;                   
$drawable_width_per_side = $side_size;     
$map_width_total = ($drawable_width_per_side * 2) + 1; 
$mirror_column_index = $drawable_width_per_side; 

$initial_map_data_js = [];
for ($h = 0; $h < $map_height; $h+=1) {
    $initial_map_data_js[] = array_fill(0, $drawable_width_per_side * 2, 'B');
}

$feedback_message = "";
$feedback_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['level_data_to_save']) && isset($_POST['level_name_to_save'])) {
    $level_map_string_to_save = trim($_POST['level_data_to_save']);
    $level_name_to_save = trim(strip_tags($_POST['level_name_to_save']));
    if (empty($level_name_to_save)) $level_name_to_save = "Niveau Sans Titre Sauvegardé";
    $level_name_to_save = substr($level_name_to_save, 0, 50);

    if (!empty($level_map_string_to_save)) {
        $levels_file_path = __DIR__ . '/../file/creer.txt';
        $current_content = "";
        if (file_exists($levels_file_path)) {
            $current_content = trim(file_get_contents($levels_file_path));
        }
        $new_level_entry = "";
        if (!empty($current_content)) { $new_level_entry .= "\n\n"; }
        $new_level_entry .= "NOM:" . $level_name_to_save . "\n";
        $new_level_entry .= $level_map_string_to_save;

        if (file_put_contents($levels_file_path, $new_level_entry, FILE_APPEND | LOCK_EX) !== false) {
            $feedback_message = "Niveau \"".htmlspecialchars($level_name_to_save)."\" sauvegardé ! Redirection...";
            $feedback_type = "success";
        } else {
            $feedback_message = "Erreur : Impossible de sauvegarder. Vérifiez les permissions.";
            $feedback_type = "error";
            error_log("Erreur d'écriture : " . (error_get_last()['message'] ?? 'Inconnue'));
        }
    } else {
        $feedback_message = "Erreur : Aucune donnée de carte à sauvegarder.";
        $feedback_type = "error";
    }
}

$tile_definitions_js = [
    '#' => ['name' => 'Mur', 'class' => 'wall-img', 'image' => '../pictures/mur.png'],
    'B' => ['name' => 'Sol', 'class' => 'floor-img', 'image' => '../pictures/plancher.jpg'],
    ' ' => ['name' => 'Vide', 'class' => 'space-img', 'image' => '../pictures/trou.png'],
    'O' => ['name' => 'Joueur', 'class' => 'player-img right', 'image' => '../pictures/personnage.png'],
    'S' => ['name' => 'Sortie', 'class' => 'exit-img', 'image' => '../pictures/sortie.png'],
    'T' => ['name' => 'Tonneau', 'class' => 'tonneau-img', 'image' => '../pictures/tonneau.png'],
    'P' => ['name' => 'Porte', 'class' => 'porte-img', 'image' => '../pictures/porte_fermee_verticale.png'],
    'C' => ['name' => 'Clé', 'class' => 'cle-img', 'image' => '../pictures/cle.png'],
    'D' => ['name' => 'Hors Lim.', 'class' => 'dehors-img', 'image' => '../pictures/dehors.png']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <title>Éditeur: <?= htmlspecialchars($level_name) ?></title>
    <style>
        :root {
            --primary-accent-color: #ffcc00;
            --hover-accent-color: #ffe066;
            --editor-border-color: #2a2e3b; 
        }
        html, body {
            height: 100%; margin: 0; padding: 0; color: white;
            font-family: 'Orbitron', sans-serif; overflow: hidden;
        }
        body {
            display: flex; flex-direction: column; justify-content: center; 
            align-items: center; background-color: #000;
            background-image: url('../pictures/fond.png'); 
            background-size: cover; background-position: center;
        }
        #editor-title {
            font-family: 'Bangers', cursive; font-size: 2.5rem;
            color: var(--primary-accent-color); text-shadow: 2px 2px 4px #000;
            margin-bottom: 15px; position: absolute; top: 15px; 
            left: 50%; transform: translateX(-50%);
            background-color: rgba(0,0,0,0.6); padding: 5px 15px; border-radius: 8px;
        }
        #editor-area {
            white-space: pre; font-size: 30px; 
            line-height: 1; 
            user-select: none; padding: 8px; 
            border: 2px solid var(--editor-border-color); 
            border-radius: 10px; background-color: rgba(0,0,0,0.6); 
        }
        .grid-cell-editor { 
            display: inline-block; width: 1em; height: 1em; box-sizing: border-box;
            border: 0; 
            vertical-align: top; position: relative; cursor: pointer;
        }
        .grid-cell-editor.mirror-cell-editor {
            background-image: url('../pictures/miroir.png'); background-size: 70% 90%; 
            background-repeat: no-repeat; background-position: center;
            cursor: default;
            border-left: 1px solid rgba(255, 255, 255, 0.15); 
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }
        .grid-cell-editor:not(.mirror-cell-editor).highlight-hover::after { 
            content: ''; position: absolute; 
            top: 0; left: 0; right: 0; bottom: 0; 
            border: 2px solid var(--hover-accent-color); 
            box-sizing: border-box; pointer-events: none; z-index: 1;
            border-radius: 2px; 
        }

        .tile-img-editor { display: inline-block; width: 100%; height: 100%; background-size: contain; background-repeat: no-repeat; background-position: center; }
        .dehors-img { background-image: url('../pictures/dehors.png'); } 
        .exit-img { background-image: url('../pictures/sortie.png'); }
        .porte-img { background-image: url('../pictures/porte_fermee_verticale.png'); }
        .cle-img { background-image: url('../pictures/cle.png'); }
        .space-img { background-image: url('../pictures/trou.png'); }
        .wall-img { background-image: url('../pictures/mur.png'); }
        .player-img { background-image: url('../pictures/personnage.png'); }
        .ghost-img { background-image: url('../pictures/ombre.png'); } 
        .tonneau-img { background-image: url('../pictures/tonneau.png'); }
        .floor-img { background-image: url('../pictures/plancher.jpg'); }

        #editor-controls-bar {
            position: fixed; bottom: 10px; left: 50%; transform: translateX(-50%); 
            width: auto; max-width: 90%; display: flex;
            justify-content: center; align-items: center;
            padding: 10px 15px; background-color: rgba(10,10,20,0.85); 
            border: 1px solid var(--editor-border-color); 
            border-radius: 12px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.7);
            z-index: 50; box-sizing: border-box;
        }
        #palette-container {
            display: flex; gap: 8px; align-items: center;
            overflow-x: auto; padding: 5px 0; 
        }
        .palette-item-editor {
            display: flex; flex-direction: column; align-items: center;
            padding: 4px; border: 2px solid transparent; border-radius: 6px;
            cursor: pointer; background-color: rgba(255,255,255,0.05);
            transition: border-color 0.2s, background-color 0.2s;
            min-width: 45px; 
        }
        .palette-item-editor:hover { border-color: var(--hover-accent-color); }
        .palette-item-editor.selected {
            border-color: var(--primary-accent-color); background-color: #3a3e52; 
        }
        .palette-item-editor .tile-preview-palette {
            width: 28px; height: 28px; 
            background-size: contain; background-repeat: no-repeat;
            background-position: center; margin-bottom: 2px;
        }
         .palette-item-editor span { font-size: 0.65em; text-align: center; color: #bbb; }

        .editor-actions { display: flex; gap: 12px; margin-left: 20px; }
        .editor-btn {
            font-family: 'Bangers', cursive; font-size: 1.6rem; 
            color: black; background-color: var(--primary-accent-color);
            border: none; padding: 8px 18px; 
            border-radius: 8px; cursor: pointer; text-decoration: none;
            transition: background-color 0.2s, transform 0.1s;
        }
        .editor-btn:hover { background-color: var(--hover-accent-color); transform: translateY(-1px); }
        .editor-btn.cancel { background-color: #c04050; color: white; }
        .editor-btn.cancel:hover { background-color: #a03040; }
        
        #feedback-container {
            position: fixed; top: 15px; left: 50%; transform: translateX(-50%);
            padding: 12px 25px; border-radius: 8px; font-size: 1.2rem;
            z-index: 100; display: none; box-shadow: 0 3px 8px rgba(0,0,0,0.4);
            font-family: 'Orbitron', sans-serif; text-align: center;
        }
        #feedback-container.success { background-color: #206030; color: white; border: 1px solid #308040;}
        #feedback-container.error { background-color: #702020; color: white; border: 1px solid #903030;}
    </style>
</head>
<body>
    <div id="editor-title">Édition: <?= htmlspecialchars($level_name) ?></div>
    <div id="editor-area"></div>

    <div id="editor-controls-bar">
        <div id="palette-container"></div>
        <div class="editor-actions">
            <form id="saveLevelForm" method="POST" action="creer_nouveau_niveau.php?side_size=<?= $side_size ?>&level_name=<?= urlencode($level_name) ?>">
                <input type="hidden" name="level_data_to_save" id="levelDataInput">
                <input type="hidden" name="level_name_to_save" value="<?= htmlspecialchars($level_name) ?>">
                <button type="submit" class="editor-btn save">Sauvegarder</button>
            </form>
            <a href="creer_niveau.php" class="editor-btn cancel">Annuler</a>
        </div>
    </div>

    <div id="feedback-container" class="<?= htmlspecialchars($feedback_type) ?>">
        <?= htmlspecialchars($feedback_message) ?>
    </div>

    <script>
        const editorArea = document.getElementById('editor-area');
        const paletteContainer = document.getElementById('palette-container');
        const saveLevelForm = document.getElementById('saveLevelForm');
        const levelDataInput = document.getElementById('levelDataInput');
        const feedbackContainer = document.getElementById('feedback-container');

        const TILE_DEFINITIONS = <?= json_encode($tile_definitions_js) ?>;
        const SIDE_SIZE = <?= $side_size ?>; 
        const MAP_HEIGHT = <?= $map_height ?>; 
        const MAP_WIDTH_TOTAL = <?= $map_width_total ?>; 
        const MIRROR_COLUMN_INDEX = <?= $mirror_column_index ?>; 
        let mapData = <?= json_encode($initial_map_data_js) ?>;

        let currentSelectedTileChar = 'B';
        let isMouseDown = false;
        let lastHoveredCell = null;
        let playerPlacedY = -1, playerPlacedXMapData = -1;
        let exitPlacedY = -1, exitPlacedXMapDataLeft = -1;

        function adjustEditorSize() {
            const titleElem = document.getElementById('editor-title');
            const controlsElem = document.getElementById('editor-controls-bar');
            let titleHeight = titleElem ? titleElem.offsetHeight + 30 : 70;
            let controlsHeight = controlsElem ? controlsElem.offsetHeight + 20 : 80;
            
            const availableWidth = window.innerWidth * 0.95; 
            const availableHeight = window.innerHeight - titleHeight - controlsHeight;

            let cellSizeByWidth = Math.floor(availableWidth / MAP_WIDTH_TOTAL);
            let cellSizeByHeight = Math.floor(availableHeight / MAP_HEIGHT);
            
            let optimalCellSize = Math.min(cellSizeByWidth, cellSizeByHeight);
            optimalCellSize = Math.max(10, Math.min(optimalCellSize, 45)); 

            editorArea.style.fontSize = optimalCellSize + 'px';
        }

        function renderPalette() {
            paletteContainer.innerHTML = '';
            Object.keys(TILE_DEFINITIONS).forEach(char => {
                const def = TILE_DEFINITIONS[char];
                const item = document.createElement('div');
                item.classList.add('palette-item-editor');
                item.dataset.tile = char;
                item.title = def.name;

                const preview = document.createElement('div');
                preview.classList.add('tile-preview-palette');
                preview.style.backgroundImage = `url('${def.image}')`;
                
                const nameSpan = document.createElement('span');
                nameSpan.textContent = def.name.substring(0,8);

                item.appendChild(preview);
                item.appendChild(nameSpan);
                
                if (char === currentSelectedTileChar) { item.classList.add('selected'); }

                item.addEventListener('click', () => {
                    currentSelectedTileChar = char;
                    document.querySelectorAll('.palette-item-editor.selected').forEach(el => el.classList.remove('selected'));
                    item.classList.add('selected');
                });
                paletteContainer.appendChild(item);
            });
        }
        
        function getCellElementByGridCoords(y, xGrid) {
            const cellDivs = Array.from(editorArea.childNodes).filter(node => node.nodeName === 'DIV');
            const index = y * MAP_WIDTH_TOTAL + xGrid;
            return cellDivs[index];
        }

        function updateCellAppearance(cellElement, tileChar) {
            if (!cellElement) return; 
            const imgSpan = cellElement.firstChild;
            if (imgSpan) {
                const tileDef = TILE_DEFINITIONS[tileChar] || TILE_DEFINITIONS['B']; 
                imgSpan.className = 'tile-img-editor ' + (tileDef.class || TILE_DEFINITIONS['B'].class);
                if (tileChar === 'Q') {  imgSpan.className = 'tile-img-editor ghost-img'; }
            }
        }

        function renderEditorGrid() {
            editorArea.innerHTML = '';
            playerPlacedY = -1; playerPlacedXMapData = -1;
            exitPlacedY = -1; exitPlacedXMapDataLeft = -1;


            for (let y = 0; y < MAP_HEIGHT; y+=1) {
                for (let xGrid = 0; xGrid < MAP_WIDTH_TOTAL; xGrid+=1) {
                    const cell = document.createElement('div');
                    cell.classList.add('grid-cell-editor');
                    cell.dataset.y = y;
                    cell.dataset.xGrid = xGrid;

                    const imgSpan = document.createElement('span'); 
                    imgSpan.classList.add('tile-img-editor'); 

                    if (xGrid === MIRROR_COLUMN_INDEX) {
                        cell.classList.add('mirror-cell-editor');
                    } else {
                        const xMapData = xGrid < MIRROR_COLUMN_INDEX ? xGrid : xGrid - 1;
                        cell.dataset.xMapData = xMapData;

                        const tileChar = mapData[y][xMapData];
                        const tileDefForInitialRender = TILE_DEFINITIONS[tileChar] || TILE_DEFINITIONS['B'];
                        imgSpan.classList.add(tileDefForInitialRender.class); 

                        if (tileChar === 'O') {
                            playerPlacedY = y; playerPlacedXMapData = xMapData;
                        } else if (tileChar === 'S') {
                            if (xMapData === SIDE_SIZE -1) {
                                exitPlacedY = y; exitPlacedXMapDataLeft = xMapData;
                            }
                        }
                        
                        cell.addEventListener('mousedown', (e) => {
                            if (e.button === 0) { isMouseDown = true; handlePlacement(y, xMapData); }
                        });
                        cell.addEventListener('mouseenter', () => {
                            if (lastHoveredCell) lastHoveredCell.classList.remove('highlight-hover');
                            if (!cell.classList.contains('mirror-cell-editor')) {
                                cell.classList.add('highlight-hover');
                                lastHoveredCell = cell;
                            }
                            if (isMouseDown) { handlePlacement(y, xMapData); }
                        });
                        cell.addEventListener('mouseleave', () => {
                            cell.classList.remove('highlight-hover');
                            if (lastHoveredCell === cell) lastHoveredCell = null;
                        });
                    }
                    cell.appendChild(imgSpan); 
                    editorArea.appendChild(cell);
                }
                editorArea.appendChild(document.createElement('br'));
            }
            if (playerPlacedY !== -1) { placeShadowForPlayer(playerPlacedY, playerPlacedXMapData, false); }
            if (exitPlacedY !== -1) { placePairedExit(exitPlacedY, exitPlacedXMapDataLeft, false); }

        }

        function handlePlacement(y, xMapDataClicked) {
            if (mapData[y][xMapDataClicked] === currentSelectedTileChar && currentSelectedTileChar !== 'O' && currentSelectedTileChar !== 'S') return;

            const originalTileAtClick = mapData[y][xMapDataClicked];
            if (playerPlacedY !== -1 && (currentSelectedTileChar === 'O' || originalTileAtClick === 'O')) {
                clearTile(playerPlacedY, playerPlacedXMapData); 
                clearTile(playerPlacedY, (2 * SIDE_SIZE - 1) - playerPlacedXMapData); 
                playerPlacedY = -1; playerPlacedXMapData = -1;
            }
            if (exitPlacedY !== -1 && (currentSelectedTileChar === 'S' || originalTileAtClick === 'S')) {
                clearTile(exitPlacedY, exitPlacedXMapDataLeft);
                clearTile(exitPlacedY, exitPlacedXMapDataLeft + 1);
                exitPlacedY = -1; exitPlacedXMapDataLeft = -1;
            }
            if (originalTileAtClick === 'Q') {
                const correspondingPlayerXMapData = (2 * SIDE_SIZE - 1) - xMapDataClicked;
                if (mapData[y][correspondingPlayerXMapData] === 'O') {
                    clearTile(y, correspondingPlayerXMapData);
                    playerPlacedY = -1; playerPlacedXMapData = -1; 
                }
            }
            if (currentSelectedTileChar === 'O') {
                placeSingleTile(y, xMapDataClicked, 'O');
                playerPlacedY = y; playerPlacedXMapData = xMapDataClicked;
                placeShadowForPlayer(y, xMapDataClicked, true);
            } else if (currentSelectedTileChar === 'S') {
                const xGridClicked = xMapDataClicked < MIRROR_COLUMN_INDEX ? xMapDataClicked : xMapDataClicked + 1;
                if (xGridClicked === MIRROR_COLUMN_INDEX - 1) {
                    placeSingleTile(y, xMapDataClicked, 'S');
                    exitPlacedY = y; exitPlacedXMapDataLeft = xMapDataClicked;
                    placePairedExit(y, xMapDataClicked, true);
                } else if (xGridClicked === MIRROR_COLUMN_INDEX + 1) {
                    placeSingleTile(y, xMapDataClicked, 'S');
                    exitPlacedY = y; exitPlacedXMapDataLeft = SIDE_SIZE - 1;
                    placePairedExit(y, SIDE_SIZE -1, true);
                } else {
                    alert("La sortie 'S' doit être placée dans une colonne adjacente au miroir.");
                }
            } else {
                placeSingleTile(y, xMapDataClicked, currentSelectedTileChar);
            }
        }
        
        function placeSingleTile(y, xMapData, tileChar) {
            mapData[y][xMapData] = tileChar;
            const xGrid = xMapData < MIRROR_COLUMN_INDEX ? xMapData : xMapData + 1;
            updateCellAppearance(getCellElementByGridCoords(y, xGrid), tileChar);
        }
        
        function clearTile(y, xMapData) {
            placeSingleTile(y, xMapData, 'B');
        }

        function placeShadowForPlayer(playerY, playerXMapData, clearShadowCellContent) {
            const shadowXMapData = (2 * SIDE_SIZE - 1) - playerXMapData; 
            if (mapData[playerY][shadowXMapData] === 'O') return;
            if (clearShadowCellContent || mapData[playerY][shadowXMapData] === 'B' || mapData[playerY][shadowXMapData] === 'Q' ) {
                placeSingleTile(playerY, shadowXMapData, 'Q');
            }
        }

        function placePairedExit(exitY, exitXMapDataLeftClicked, clearPairedCellContent) {
            const exitXMapDataRight = SIDE_SIZE; 

            if(exitXMapDataLeftClicked === SIDE_SIZE -1){
                if (mapData[exitY][exitXMapDataRight] === 'O' || mapData[exitY][exitXMapDataRight] === 'Q') return;
                if(clearPairedCellContent || mapData[exitY][exitXMapDataRight] === 'B' || mapData[exitY][exitXMapDataRight] === 'S'){
                    placeSingleTile(exitY, exitXMapDataRight, 'S');
                }
            }
            else if (exitXMapDataLeftClicked === SIDE_SIZE) { 
                const targetLeftXMapData = SIDE_SIZE -1;
                 if (mapData[exitY][targetLeftXMapData] === 'O' || mapData[exitY][targetLeftXMapData] === 'Q') return;
                 if(clearPairedCellContent || mapData[exitY][targetLeftXMapData] === 'B' || mapData[exitY][targetLeftXMapData] === 'S'){
                    placeSingleTile(exitY, targetLeftXMapData, 'S');
                }
            }
        }
        
        document.addEventListener('mouseup', () => { isMouseDown = false; });
        editorArea.addEventListener('mouseleave', () => {
            isMouseDown = false;
            if (lastHoveredCell) {
                lastHoveredCell.classList.remove('highlight-hover');
                lastHoveredCell = null;
            }
        });

        saveLevelForm.addEventListener('submit', function(event) {
            if (playerPlacedY === -1) {
                alert("Veuillez placer un Joueur ('O') avant de sauvegarder.");
                event.preventDefault(); return;
            }
            if (exitPlacedY === -1) {
                alert("Veuillez placer une Sortie ('S') adjacente au miroir avant de sauvegarder.");
                event.preventDefault(); return;
            }

            let levelString = "";
            for (let y = 0; y < MAP_HEIGHT; y+=1) {
                let lineLeft = ""; let lineRight = "";
                for (let xSide = 0; xSide < SIDE_SIZE; xSide+=1) { lineLeft += mapData[y][xSide]; }
                for (let xSide = 0; xSide < SIDE_SIZE; xSide+=1) { lineRight += mapData[y][SIDE_SIZE + xSide];}
                levelString += lineLeft + "|" + lineRight + (y < MAP_HEIGHT - 1 ? "\n" : "");
            }
            levelDataInput.value = levelString;
        });

        window.addEventListener('resize', adjustEditorSize);
        renderPalette(); 
        adjustEditorSize(); 
        renderEditorGrid(); 

         <?php if (!empty($feedback_message)): ?>
        feedbackContainer.textContent = "<?= addslashes(htmlspecialchars($feedback_message)) ?>";
        feedbackContainer.className = "feedback-display <?= htmlspecialchars($feedback_type) ?>"; 
        feedbackContainer.style.display = 'block';

        const feedbackDuration = (<?= json_encode($feedback_type) ?> === "success" && <?= json_encode(strpos($feedback_message, "Redirection") !== false) ?>) ? 100 : 4000;

        setTimeout(() => {
            feedbackContainer.style.display = 'none';
            <?php if ($feedback_type === "success" && strpos($feedback_message, "Redirection") !== false): ?>
                window.location.href = "creer_niveau.php?save_success=1";
            <?php endif; ?>
        }, feedbackDuration);
        <?php endif; ?>

    </script>
</body>
</html>
