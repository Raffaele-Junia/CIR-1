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

    function updateLevelData($userId, $level, $gameTime, $deaths, $coins, $completed) {
        global $servername, $username, $password, $dbname;
        try {
            $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $db->prepare("SELECT * FROM niveau WHERE idJoueur = ? AND numNiveau = ?");
            $stmt->execute([$userId, $level]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                $newTotalGameTime = $existing['duréeDeJeu'] + $gameTime;
                $newDeaths = $existing['nbrMorts'] + $deaths;
                $currentBestTime = $existing['bestTime'];
                $newBestTime = $currentBestTime;

                if ($completed == 1) {
                    if ($currentBestTime == 0 || $gameTime < $currentBestTime) {
                        $newBestTime = $gameTime;
                    }
                }
                $finalNiveauFini = $completed == 1 ? 1 : $existing['niveauFini'];
                $stmt = $db->prepare("UPDATE niveau 
                                    SET duréeDeJeu = ?, 
                                        nbrMorts = ?, 
                                        nbrCoin = ?, 
                                        niveauFini = ?,
                                        bestTime = ? 
                                    WHERE idJoueur = ? AND numNiveau = ?");
                $stmt->execute([$newTotalGameTime, $newDeaths, $coins, $finalNiveauFini, $newBestTime, $userId, $level]);

            } else {
                $newBestTimeForInsert = 0;
                if ($completed == 1) {
                    $newBestTimeForInsert = $gameTime;
                }
                $stmt = $db->prepare("INSERT INTO niveau (idJoueur, numNiveau, duréeDeJeu, nbrMorts, nbrCoin, niveauFini, bestTime) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $level, $gameTime, $deaths, $coins, $completed, $newBestTimeForInsert]);
            }
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            error_log("Database Error: " . $e->getMessage()); 
        }
    }
    function handleDeath($userId, $level, $gameTime) {
        updateLevelData($userId, $level, $gameTime, 1, 0, 0);
    }
    function handleVictory($userId, $level, $gameTime, $coins) {
        updateLevelData($userId, $level, $gameTime, 0, $coins, 1);
    }  
      

$level = isset($_GET['level']) ? intval($_GET['level']) : 1;

$_SESSION['level_start_time'] = time();

$soundFile = '';
if ($level == 1) {
    header("Location: didacticiel.php");
}

if ($level >= 1 && $level <= 2) {
    $soundFile = '../son/son5.mp3';
} elseif ($level >= 3 && $level <= 4) {
    $soundFile = '../son/son6.mp3';
} elseif ($level >= 5 && $level <= 6) {
    $soundFile = '../son/son7.mp3';
} elseif ($level >= 7 && $level <= 9) {
    $soundFile = '../son/son8.mp3';
} elseif ($level == 10) {
    $soundFile = '../son/son18.mp3';
}

$maps = [
    1 => [
        "#O#####DDDDDDDD|DDDDDDDD#####Q#",
        "#BBBBB#DDDDDDDD|DDDDDDDD#BBBBB#",
        "#####B#DDDDDDDD|DDDDDDDD#BBBBB#",
        "#####B#DDDDDDDD|DDDDDDDD#B###B#",
        " BBBBB##DDDDDDD|DDDDDDD##BBBBB ",
        " B     ########|########     B ",
        " BBBBBBBBB##BBS|SBB## BBB    B ",
        " BBBBBB#BBBBB##|##BBBBB#BBBBBB ",
        "       #######D|D##############",
        "########DDDDDDD|DDDDDDDDDDDDDDD",
        "DDDDDDDDDDDDDDD|DDDDDDDDDDDDDDD",
        "DDDDDDDDDDDDDDD|DDDDDDDDDDDDDDD",
        "DDDDDDDDDDDDDDD|DDDDDDDDDDDDDDD",
        "DDDDDDDDDDDDDDD|DDDDDDDDDDDDDDD",
        "DDDDDDDDDDDDDDD|DDDDDDDDDDDDDDD"
    ],
    2 => [
        "BBBBB#BBB##BBBS|SBBBBBBBB#DDDDD", 
        "B     BB####BB#|#B#####B      B", 
        "BBBBBBBBBBB##B#|#B##BBBBBBBBBBB", 
        "B     BBBBB#BB#|#BB#BBB B     #", 
        "B BBB#BB#B  B #|#BBBBB#BB#BBB B", 
        "B  B #BB#B##BB#|# B##B#BB# B  B", 
        "OBBBB#BB B  BB#|#BB  B BB#BBBBQ", 
        "    B BB BBBB##|##BBBB BB B    ", 
        " BBBBB B   B##D|D##B   B BBBBB ", 
        " BBBBBBB BBB#DD|DD#BBB B     B ", 
        " BBBBBBB BB##DD|DD## B BBBBBBB ", 
        "         BB##DD|DD##BB         ", 
        "#BBBBBB##BBB#DD|DD# B ##BBBBBB#", 
        "#BBB  BBBBBB#DD|DD# BBB B B  B#", 
        "#€####BBBB  #DD|DD# BBBBB####€#" 
    ],
    3 => [
        "D### ##D#BBB###|###BBB#DD#BB###", 
        "##BBT€#D#B#BB S|S BB#B#D#BBBBB#",
        "  BBTB#D#BTBB##|##BBBB#D#BTBB  ",
        "BBB####D#BBBB##|##BBTB#D####BBB",
        "B###D######BBB#|#BBB######D###B",
        "B#DDD#BBBBB##B#|#B##BBB  #DDD#B",
        "B#####BBBBBBBB#|#BBBBBB  #####B",
        "BBBBBBBBB##BB##|##BB##BBBBBB  B",
        "B #B##B#B#####D|D##B##B  ##B# B",
        "BBBBBBBBB#####D|DD#B #BBBBBBBBB",
        "######BBBBB  #D|D##BB#BBB######",
        "#BBBB#B#B#B###D|D## BB##B BBBB#",
        "#O  B#B##BB #DD|D#BBBB##B B##Q#",
        "####BBB#BB ###D|D##  BB#BBB####",
        "DDD###BBBBBBB#D|DD#  BBBB  #DDD"
    ],
    4 => [
        "#  BBBB##DD###D|####DDD#BBBBBB#", 
        "#OBB##B ####B##|##€#D###B #BBQ#",
        "##BB# BBB##BBB#|#BB#D#BBBB# BB#",
        "D#  B TTBB##TB#|#BB#D#BBBBB BB#",
        "D#B BBBBBB##B #|#BB#D#BTBBBBB##",
        "D#€#B#######BB#|#BB#D#####B B##",
        "D###BBB #T###B#|#B########BBBB#",
        "DD#BB##BBBBBBB#|#BBBTBBB##BB###",
        "DD#BB##B#BBB#B#|#B#BBB#B## ##DD",
        "DD# BBBB#####B#|#B#####BBBBB#DD",
        "DD######BB###B#|#B###BB######DD",
        "DD######BBBBBB#|#BTBBBB####DDDD",
        "DD#  BBBB######|##BBBBBBB #DDDD",
        "DD#  BBBB#B BBS|SBB B#  #B####D",
        "DD#BBBBBBBBBBB#|#BBBBBBBBBBB€#D"
    ],

    5 => [
        "###############|###########B###", 
        "#BBBB#BBBBB BB#|##BBBBBBB#BT €#",
        "#B BB#B  BBBBB#|## #BBB B#TTBB#",
        "#BBBBBBBBBB##BS|SB##BB  BBB##B#",
        "# B#B### BBPPB#|#BBBB###BBB#€B#",
        "##B#BBBB####B##|##B BBBBBBB####",
        "#O#####B B  B##|###T   B#####Q#",
        "#TB#D##BBBBBBB#|#B TTTTB####CB#",
        "#BB###BBBBBB B#|#C#BBBBBB####B#",
        "#BBBB B##  BBB#|#  BBBB#B BBTB#",
        "#B#P##BBB# B ##|##BBB#BBB##B###",
        "#C#BBCBBBBBB#€#|#BB   B BBBB###",
        "####B   B###B #|BBT#B#B B#BB###",
        "####BBBBBPTBBB#|#TBBBCBBBBB  ##",
        "############BB#|#BB############"
    ],
    6 => [
        "D#####BB#######|#######BB#####D",
        "D#BBBTBB#BT  BS|SBBBPB#BBBBBB#D",
        "##B# BBB#B#BBB#|#BBB#B#TBB #B##",
        "BB  ##  BB#####|#####BB B##  BB",
        "CBT ##########D|D########## BTB",
        "# TBBB BBBBBB#D|D#BBBBB  BBBT #",
        "# BB TBBB###B#D|D#B###BBBB#BB##",
        "D####B#####BB#D|D#BB#####T####D",
        "D#BB#P#BBBBBB#D|D#BBBBBB# #BB#D",
        "D#BT#B#BB## B#D|D#B ##BB#P#TB#D",
        "D#BB#€## ###B#D|D#B### ##€#BB#D",
        "D#B ###BT#D# #D|D# #D#T ### B#D",
        "##TTBBBBB#D#C#D|D#C#D#BBBBBTT##",
        "#BBT ##BB#D###D|D###D#BBB# TBB#",
        "#OBBBBBB##DDDDD|DDDDD## BBBB#Q#"
    ],
    7 => [
            "###############|###############",
            "#BBBT BBBBTBTB#|#BBBBBBBBBB€TB#",
            "#BBBTBBBBBBBBB#|#BBBTBBB  BBTB#",
            "#BBBTBBBTT#   #|#   #BBBBBBBTB#",
            "####BBBTBB#   #|#   #TBTBBB####",
            "####B€BBBB#   #|#   #TBBTBT####",
            "OBB#BBTBBB#   #|#   #BBBBBB#BBQ",
            "##€####B####B##|##B####B####B##",
            "##BBBB B####B##|##B####B  B€B##",
            "#€B BB BBB€BB##|##BBBB BB BB B#",
            "#B BB  B BBB ##|##B  BBBBBB BB#",
            "#B€BB BBBB BBPS|SBBB€B B  BBBB#",
            "#B  B BBBBBBB##|## BBB BBBB  B#",
            "#BBBBBBBB BBB##|#CBB    BBBBBB#",
            "###############|###############"
    ],
    8 => [
        "#€#BB##########|##########BB#€#",
        "# #BBBTBBB#BBPS|SPB #BBBBBBB# #",
        "#T#B##BB#B   B#|#BB BB#BB##B#T#",
        "#B  ##B##BBB B#|#BBBBB##B##B B#",
        "#BBB##B####BBB#|#BBB####B##BBB#",
        "#####   T######|######T   #####",
        "BBBB BTB   ####|####   BTB CB##",
        "#### BBBBBBBBB#|#BBBBBBBBB #BB#",
        "####  B    BT #|# BB    B   #B#",
        "######BBBBBBBB#|#BBBBBBTBBB##B#",
        "#BBBT BB#TB##B#|#P##BT#BBBBBBB#",
        "#B#####T#BB##P#|#T##B #T#####B#",
        "#O#BPCBBBBB## #|# ##BTBB BBB#Q#",
        "###€##########€|€##########C###",
        "###############|###############"
    ],
    9 => [
        "##€  BBBBBBBBB  BBB#|#B  BB   BBBBBBBBC##",
        "# BBBBB B BB BBBB B#|#BB BBBBBB  B BBBBB#",
        "#BBB BBBBBBBBBBBBBB#|#BBBB BBB  B BBB BB#",
        "#B B BBB  B B BB BB#|#BB BBBB  BBBB   BB#",
        "#BBBBB BBBBBBBB BTB#|# B  B   BBBB  BBC #",
        "## BBBBB  BBBBBPPPPS|SPPPPB BBBBBBBBBBB##",
        "##BB    BB  BB   BB#|#B BBBBBBBB    B  ##",
        "##BBBBBBBBBBB B BB #|#BBBBBBB##BBBBBBBB##",
        "#BBBBBB  BBBBBB BBB#|#B B  BBBBBBBBBB BB#",
        "#CB BBBBBB B    BBB#|#BBB  BBT  B BBBBBC#",
        "#  BB B BBBBBBBBBBB#|#BB BBBBBBB B BB   #",
        "#CBBBB   BB  BB  BB#|#B BB B   B BBBB BB#",
        "#BB BB  BBBB BBCBBB#|#BBBBBBBBBBBBBB  B #",
        "#BBBBBBBB  BB  B BB#|#   BBBB   BBBB BBB#",
        "OBB B  B B B BBB  B#|#B BB  B BBB BBBBBBQ",
        "#BB BBBBBBBBB BBB B#|#BBBBBBBBBB  BBBB B#",
        "#BBBBBBB B##BB  BBB#|#BBBBBBB##BBBBBBBBB#",
        "#BBB  B  B B BBBBBB#|#B BBCB  BBBBBB B B#",
        "# BBBBBBBBBBBB   B #|#BBBB B  BBB BBB CB#",
        "##BBB BBB BBBBBBBBB#|#CBBBBBBBB BBBBBBB##"

    ],
    10 => [
        "DDDD#C ##BBTBTBTBTTB|TTBBTTBBBTB €B#DDDDD",
        "DD####T##BTTBBBBBTPS|STBTTTBTBBT##P#####D",
        "D##BBBBBBBTBTBBTBTTB|BTBBBTTTTBBBBBBBBB#D",
        "D#BB###B#BTTBBTBBBTT|TBTTBBBBBBT#BTTTBB#D",
        "D#BB#D#B#BBBBTBTBBBT|BBBCBBTBBTB#BT#TB #D",
        "D# B###B############|############BTTTBB#D",
        "##BBBB#B#BBBBBBBB #D|D#BBBBBBBBB#B BBTB##",
        "#BPB#B B#B   B BBB##|##B BBB  BB#B B#BBTB",
        "###B#B BBB B B  BBBS|SBB BBBB BBBBBB# #T#",
        "OB##BB#B#BB BBBBB ##|## B BB  B #B#BB##BQ",
        "#B##B##B#  BBB  BB#D|D#BBBB BBBB#B##B##B#",
        "#BBBB##B#BBBBBBBBB#D|D#BBBBBBB  #B##BBBB#",
        "#####BBB############|############BTB#####",
        "D### BBB#DDDDDD#BBB#|#######DDDD#BTB#DDDD",    
        "D#CB BBB#DDDDDD#TTT#|# BBB #DDDD#BTB#DDDD",
        "D### BBB#D######B B#|# BBBB######BTB#DDDD",
        "DDD####B###BBB#BBBBS|SPB  BBBB### #B#DDDD",
        "DDDDD#BBB  B B#B  B#|#B   BBBB   BB##DDDD",
        "DDDDD#BTBBB  TTBBBBB|BBTBBBB# BBBBB#DDDDD",
        "DDDDD#######BB€#####|###BBB#########DDDDD"
    ]
];

$currentMap = $maps[$level] ?? $maps[1];
function formatTime($seconds) {
    if ($seconds === null) return "--:--";
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d:%02d", $minutes, $seconds);
}

$bestTime = null;
$nbrMorts = null;
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->prepare("SELECT bestTime as best_time, nbrMorts FROM niveau WHERE idJoueur = ? AND numNiveau = ? AND niveauFini = 1");
    $stmt->execute([$_SESSION['user_id'], $level]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        if ($result['best_time'] !== null) {
            $bestTime = $result['best_time'];
        }
        if ($result['nbrMorts'] !== null) {
            $nbrMorts = $result['nbrMorts'];
        }
    }
} catch (PDOException $e) {
}
$barClass = 'red-bar'; 

if ($level >= 1 && $level <= 2) {
    $barClass .= ' level-green';
} elseif ($level >= 3 && $level <= 4) {
    $barClass .= ' level-blue';
} elseif ($level >= 5 && $level <= 6) {
    $barClass .= ' level-yellow';
} elseif ($level >= 7 && $level <= 9) {
    $barClass .= ' level-red';
} elseif ($level == 10) {
    $barClass .= ' level-fire';
}
$textClass = 'text-block'; 

if ($level >= 1 && $level <= 2) {
    $textClass .= ' text-green';
} elseif ($level >= 3 && $level <= 4) {
    $textClass .= ' text-blue';
} elseif ($level >= 5 && $level <= 6) {
    $textClass .= ' text-yellow';
} elseif ($level >= 7 && $level <= 9) {
    $textClass .= ' text-red';
} elseif ($level == 10) {
    $textClass .= ' text-fire';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <link rel="icon" href="../pictures/logojeu.png" type="image/png">

<link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">

    <title>BlockMirror - Level <?= htmlspecialchars($level) ?></title>
    <style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    color: white;
    font-family: monospace;
}
#black-fade {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 1s ease-in-out;
}
#bottom-bar{
    position:fixed;
    bottom:0; left:0;
    width:97%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px 20px;
    z-index:50;
}

#btn-home{
    font-family:'Bangers',cursive;
    font-size:3rem;
    color:white;
    background:transparent;
    border:none;
    cursor:pointer;
    user-select:none;
    transition:transform .2s,color .2s;
}
#btn-home:hover{
    color:yellow;
    transform:scale(1.15);
}

#btn-replay{
    width:70px;height:70px;
    border-radius:50%;
    border:none;
    background:#ffd700;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;
    box-shadow:0 0 12px #ffd700;
    transition:transform .2s,box-shadow .2s;
}
#btn-replay:hover{
    transform:scale(1.12);
    box-shadow:0 0 20px #ffd700;
}
#btn-replay svg{
    width:34px;height:34px;
    fill:black;
    pointer-events:none;
}


body {
    display: flex;
    flex-direction: column;
    justify-content: center; 
    align-items: center;     
    height: 100vh;           
    background-color: #000;
    background-image: url('../pictures/fond.png');
}
#game {
    white-space: pre;
    font-size: 40px; 
    line-height: 0.8; 
    user-select: none;
    padding: 10px 20px;
    max-width: 100%;
    overflow: hidden;
}
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        
        .fade-out {
            animation: fadeOut 1s ease-in-out;
        }
        .dehors-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background: transparent;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .exit-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/sortie.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .porte-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/porte_fermee_verticale.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .cle-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/cle.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }

        .space-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/trou.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }

        .wall-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/mur.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }

        .piece-img{
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/piece.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }

        .player-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/personnage.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        
        .player-img.up { 
            background-image: url('../pictures/personnageDroite.png');
        }
        
        .player-img.down { 
            background-image: url('../pictures/personnageGauche.png');
        }
        
        .player-img.left {
            background-image: url('../pictures/personnageBas.png');
        }
        
        .player-img.right {
            background-image: url('../pictures/personnage.png');
        }
        
        .ghost-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/ombre.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }
        .tonneau-img {
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/tonneau.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
        }

        .miroir-img{
            display: inline-block;
            width: 1em;
            height: 1em;
            background-image: url('../pictures/miroir.png');
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
            transition: transform 0.2s;
            display: inline-block;
            background-size: 80% 100%;
            margin: 0 -0.4em;
        }
        .ghost-img.up {
            background-image: url('../pictures/ombreDroite.png');
        }
        
        .ghost-img.down {
            background-image: url('../pictures/ombreGauche.png');
        }
        
        .ghost-img.left {
            background-image: url('../pictures/ombre.png');
        }
        
        .ghost-img.right {
            background-image: url('../pictures/ombreBas.png');
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .floor-img{
            display:inline-block;
            width:1em;
            height:1em;
            background:url("../pictures/plancher.jpg") center/contain no-repeat;
            vertical-align:middle;      
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        body {
            opacity: 0;
            transition: opacity 1s;
        }
        
        body.loaded {
            opacity: 1;
        }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      width: 100%;
      overflow: hidden; 
    }

@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&display=swap'); 

    @keyframes move-up {
      to { transform: translateY(-100vh); }
    }

    @keyframes move-left {
      to { transform: translateX(-100vw); }
    }

    @keyframes move-up-right {
      to { transform: translate(100vw, -100vh); }
    }

    @keyframes move-right {
      to { transform: translateX(100vw); }
    }

    @keyframes move-down {
      to { transform: translateY(100vh); }
    }
@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&display=swap');

.intro-wrapper {
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  pointer-events: none;
  z-index: 2000;
  overflow: hidden;
}

.red-bar, .black-bar, .beige-bar {
  position: absolute;
  width: 100vw;
  height: 100vh;
}

.red-bar {
  background-color: #e30613;
  transform: translateX(0);
  right:80%;
  z-index: 3;
  transform: translateX(0) rotate(-3deg); 
  height:4000px;
  animation: slideLeft 1.2s ease-in forwards;
  animation-delay: 0.6s; 
}

.black-bar {
  background-color: #171717;
  clip-path: polygon(0 0, 100% 0, 100% 80%, 0 100%);
  transform: translate(0, 0);
  animation: slideDiag 1.6s ease-out forwards, fadeOut 1.2s ease-out forwards;
  animation-delay: 0.5s, 0.8s;
  z-index: 2;
}

.beige-bar.initial {
  background-color: #f5f0e1;
  z-index: 1;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  padding-top: 10px;
  transform: rotate(-7deg) translateY(90%);
}

.beige-bar.animate {
  animation: slideDown 1s cubic-bezier(0.6, 0.05, 1, 1) forwards;
}

.timer {
    position: fixed;
    top: 20px;
    right: 20px;
    font-family: 'Bangers', cursive;
    font-size: 2rem;
    color: white;
    background: rgba(0, 0, 0, 0.7);
    padding: 10px 20px;
    border-radius: 10px;
    border: 2px solid #ffcc00;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 100;
}

.timer .label {
    color: #ffcc00;
}

.timer .time {
    font-family: 'Orbitron', monospace;
    letter-spacing: 2px;
}

@keyframes slideLeft {
  0% {
    transform: translateX(0) rotate(-3deg);
  }
  100% {
    transform: translateX(-100vw) rotate(-3deg); 
  }
}

@keyframes slideDiag {
  to { transform: translate(100px, -80px); }
}

@keyframes slideDown {
  from {
    transform: rotate(-7deg) translateY(90%);
  }
  to {
    transform: rotate(-7deg) translateY(152%);
  }
}

@keyframes slideUp {
  from { transform: translateY(100%); }
  to { transform: translateY(-100%); }
}

.text-block {
  position: absolute;
  top: 30%;
  left: 20%;
  font-family: 'Rajdhani', sans-serif;
  text-transform: uppercase;
  z-index: 10;
  animation: fadeOut 0.6s ease-out forwards;
  animation-delay: 0.3s;
  transform: skewX(4deg); 
  
}

.text-block.text-green {
  color: #28a745;
  text-shadow: 2px 2px 4px rgba(0, 64, 0, 0.7), 0 0 10px rgba(40, 167, 69, 0.5);
}

.text-block.text-blue {
  color: #007bff;
  text-shadow: 2px 2px 4px rgba(0, 38, 77, 0.7), 0 0 10px rgba(0, 123, 255, 0.5);
}

.text-block.text-yellow {
  color: #ffa500;
  text-shadow: 2px 2px 4px rgba(77, 64, 0, 0.7), 0 0 10px rgba(255, 193, 7, 0.5);
}

.text-block.text-red {
  color: #e30613;
  text-shadow: 8px 8px 4px rgba(0, 0, 0, 0.7), 0 0 10px rgba(255, 0, 0, 0.5);
}

.text-block.text-fire {
  color: orange;
  background: linear-gradient(45deg, orange, red, yellow);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: firePulseText 3s ease infinite;
  text-shadow: 0 0 8px orange, 0 0 20px red, 0 0 40px yellow;
}

@keyframes firePulseText {
  0%, 100% {
    text-shadow: 0 0 8px orange, 0 0 20px red, 0 0 40px yellow;
  }
  50% {
    text-shadow: 0 0 12px yellow, 0 0 30px orange, 0 0 50px red;
  }
}

.text-block .stage {
  font-weight: bold;
  font-size: 4.8rem;
}
.text-block .zone {
  font-size: 3.6rem;
}

.text-block .title {
  font-size: 4.8rem;
  font-weight: bold;
}

.best-time {
  position: absolute;
  bottom: 1%;
  left: 70%;
  font-family: 'Rajdhani', sans-serif;
  font-size: 4.8rem;
  font-weight: bold;
  color: #171717;
  text-transform: uppercase;
  text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8);
  z-index: 4;
  display: flex;
  align-items: center;
  gap: 1rem;
  transform: rotate(-4deg);
  
  animation: moveRightAccelerate 1s forwards;
  animation-delay: 2s; 
}
@keyframes moveRightAccelerate {
  0% {
    transform: rotate(-4deg) translateX(0);
    letter-spacing: normal;
    opacity: 1;
  }
  50% {
    letter-spacing: 0.15em; 
  }
  100% {
    transform: rotate(-4deg) translateX(160%); 
    letter-spacing: 0.2em; 
    opacity: 0; 
  }
}

.best-time .label {
  font-size: 2rem; 
  font-weight: bold;
}

    .best-time .time {
  font-size: 4.8rem;
  font-weight: bold;
}

.best-time .time,
.best-time .rank {
  font-size: inherit; 
  font-weight: inherit;
}
.nbrMorts {
    position: absolute;
    bottom: 1%;
    left: 40%;
    font-family: 'Rajdhani', sans-serif;
    font-size: 4.8rem;
    font-weight: bold;
    color: #171717;
    text-transform: uppercase;
    text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8);
    z-index: 4;
    display: flex;
    align-items: center;
    gap: 1rem;
    transform: rotate(-4deg);
    animation: moveRightAccelerate 1s forwards;
    animation-delay: 2s;
}

.nbrMorts .label {
    font-size: 2rem;
    font-weight: bold;
}

.nbrMorts .morts {
    font-size: 4.8rem;
    font-weight: bold;
}
.red-bar.level-green {
  background-color: #28a745;
}

.red-bar.level-blue {
  background-color: #007bff;
}

.red-bar.level-yellow {
  background-color: #ffa500;
}

.red-bar.level-red {
  background-color: #e30613;
}

.red-bar.level-fire {
  background: linear-gradient(135deg, orange, red, yellow);
  background-size: 400% 400%;
}

@keyframes firePulse {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

</style>
</head>
<body>
<body>
<div id="black-fade"></div>
<div id="bottom-bar">
<button id="btn-home" onclick="goHome()">
    HOME
</button>

    <form method="get" action="" style="margin:0">
        <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">
        <button id="btn-home" title="Rejouer">
        REPLAY

        </button>
<div id="inventory" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 10px; border: 2px solid #ffcc00;">
    <div style="display: flex; align-items: center; gap: 5px;">
        <img src="../pictures/cléInv.png" style="width: 30px; height: 30px;">
        <span id="key-count" style="font-family: 'Bangers', cursive; font-size: 1.5rem; color: #ffcc00;">0</span>
    </div>
</div>
   </form>
</div>
<div id="game"></div>

<div class="intro-wrapper">
<div class="<?= $barClass ?>"></div>
  <div class="black-bar">
<div class="<?= $textClass ?>">
      <div class="stage">STAGE <?= htmlspecialchars($level) ?></div>
      <div class="zone">BlockMirror</div>
      <div class="title">Level <?= htmlspecialchars($level) ?></div>
    </div>
  </div>
  
  <div class="best-time">
    <div class="label">Best Time : </div>
    <div class="time"><?= $bestTime !== null ? formatTime($bestTime) : "--:--" ?></div>
  </div>

  <div class="nbrMorts">
    <div class="label">Nombre de morts : </div>
    <div class="morts"><?= $nbrMorts !== null ? htmlspecialchars($nbrMorts) : "--" ?></div>
  </div>

  <div class="timer">
    <div class="label">Timer:</div>
    <div class="time" id="game-timer">00:00</div>
  </div>
  
  <div class="beige-bar initial"></div>
</div>

<?php if (!empty($soundFile)): ?>
<audio autoplay loop>
    <source src="<?= htmlspecialchars($soundFile) ?>" type="audio/mpeg">
</audio>
<audio id="home-sound" src="../son/son1.mp3"></audio>
<?php endif; ?>
<div id="game"></div>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const beigeBar = document.querySelector('.beige-bar.initial');
    setTimeout(() => {
      beigeBar.classList.add('animate');
    }, 2000); 
  });

let keysCollected = 0;
const DOOR_SYMBOL = 'P';
const KEY_SYMBOL = 'C'; 

let map = <?= json_encode($currentMap) ?>.map(row => row.split(''));

let startPos = { x: 0, y: 0 };
let startPosRight = { x: 0, y: 0 };
let coinsCollected = 0;
for (let y = 0; y < map.length; y++) {
    for (let x = 0; x < map[y].length; x++) {
        if (map[y][x] === 'O') startPos = { x, y };
        if (map[y][x] === 'Q') startPosRight = { x, y };
    }
}

let playerPos = { ...startPos };
let playerPosRight = { ...startPosRight };
let playerDirection = 'right'; 
let ghostDirection = 'left'; 

function render() {
    const gameDiv = document.getElementById("game");
    let displayStr = "";

    for (let y = 0; y < map.length; y++) {
        for (let x = 0; x < map[y].length; x++) {
            let ch = map[y][x];
            let cls = "";
            let content = ch;

            switch(ch) {
                case '#': 
                    cls="wall";
                    content = `<span class="wall-img"></span>`;
                    break;
                case 'O': 
                    cls = "player";
                    content = `<span class="player-img ${playerDirection}"></span>`;
                    break;
                case 'Q': 
                    cls = "ghost";
                    content = `<span class="ghost-img ${ghostDirection}"></span>`;
                    break;
                case '€': 
                    cls = "coin";
                    content = '<span class="piece-img"></span>';
                    break;
                case 'T':
                    cls = "tonneau";
                    content = `<span class="tonneau-img"></span>`;
                    break;
                case 'B':   
                    cls = "floor";
                    content = '<span class="floor-img"></span>';
                    break;
                case ' ': 
                    cls = "space";
                    content = '<span class="space-img"></span>';
                    break;
                case 'D': 
                    cls = "dehors";
                    content = '<span class="dehors-img"></span>';
                    break;
                case 'S': 
                    cls = "exit"; 
                    content = '<span class="exit-img"></span>';
                    break;
                case '|':
                    cls = "miroir";
                    content = '<span class="miroir-img"></span>';
                    break;
                case 'P':
                    cls = "porte";
                    content = '<span class="porte-img"></span>';
                    break;
                case 'C':
                    cls = "cle";
                    content = '<span class="cle-img"></span>';
                    break;
            }

            displayStr += cls ? `<span class="${cls}">${content}</span>` : content;
        }
        displayStr += "\n";
    }

    gameDiv.innerHTML = displayStr;
}

function canMove(x, y) {
    if (y < 0 || y >= map.length || x < 0 || x >= map[y].length) return false;
    let ch = map[y][x];
    if (ch === DOOR_SYMBOL && keysCollected > 0) return true;
    return ch !== '#' && ch !== 'D' && ch !== DOOR_SYMBOL;
}
function tryOpenDoor(x, y) {
    if (map[y][x] === DOOR_SYMBOL && keysCollected > 0) {
        keysCollected--;
        document.getElementById('key-count').textContent = keysCollected;
        map[y][x] = 'B'; 
        return true;
    }
    return false;
}

function tryPushBarrel(x, y, dx, dy) {
    if (map[y][x] !== 'T') return true;
    let nx = x + dx, ny = y + dy;
    let next = map[ny]?.[nx];
    if (!next || next === '#' || next === 'D' || next === 'T') return false;
    if (next === ' ') {
        map[ny][nx] = 'B';
        map[y][x] = 'B';
    } else if (next === 'B') {
        map[ny][nx] = 'T';
        map[y][x] = 'B';
    } else return false;
    return true;
}

function goHome() {
    const homeSound = document.getElementById('home-sound');
    if (homeSound) {
        homeSound.currentTime = 0;
        homeSound.play();
    }
    
    const blackFade = document.getElementById('black-fade');
    
    document.querySelectorAll('button, a').forEach(el => {
        el.style.pointerEvents = 'none';
    });
    
    blackFade.style.opacity = '1';
    
    setTimeout(() => {
        window.location.href = 'niveaumenujeu.php';
    }, 1000); 
}

function moveBoth(dxLeft, dyLeft) {
    let newLeftX = playerPos.x + dxLeft;
    let newLeftY = playerPos.y + dyLeft;
    let dxRight = -dxLeft;
    let newRightX = playerPosRight.x + dxRight;
    let newRightY = playerPosRight.y + dyLeft;

    if (dxLeft === -1) {
        playerDirection = 'down';
        ghostDirection = 'up';
    } else if (dxLeft === 1) {
        playerDirection = 'up';
        ghostDirection = 'down';
    } else if (dyLeft === -1) {
        playerDirection = 'left';
        ghostDirection = 'right';
    } else if (dyLeft === 1) {
        playerDirection = 'right';
        ghostDirection = 'left';
    }
    if (map[newLeftY][newLeftX] === '€') {
        map[newLeftY][newLeftX] = 'B';
        coinsCollected++;
        document.getElementById('coin-count').textContent = coinsCollected;
    }
    if (map[newRightY][newRightX] === '€') {
        map[newRightY][newRightX] = 'B';
        coinsCollected++;
        document.getElementById('coin-count').textContent = coinsCollected;
    }
if (map[newLeftY][newLeftX] === KEY_SYMBOL) {
    keysCollected++;
    document.getElementById('key-count').textContent = keysCollected;
    map[newLeftY][newLeftX] = 'B';
}
if (map[newRightY][newRightX] === KEY_SYMBOL) {
    keysCollected++;
    document.getElementById('key-count').textContent = keysCollected;
    map[newRightY][newRightX] = 'B';
}
    const isDoorLeft = map[newLeftY][newLeftX] === DOOR_SYMBOL;
    const isDoorRight = map[newRightY][newRightX] === DOOR_SYMBOL;
    
    if ((isDoorLeft || isDoorRight) && keysCollected === 0) {
        return; 
    }
    if (isDoorLeft) tryOpenDoor(newLeftX, newLeftY);
    if (isDoorRight) tryOpenDoor(newRightX, newRightY);
    if (!canMove(newLeftX, newLeftY) && map[newLeftY][newLeftX] !== 'T' && map[newLeftY][newLeftX] !== '€') return;
    if (!canMove(newRightX, newRightY) && map[newRightY][newRightX] !== 'T' && map[newRightY][newRightX] !== '€') return;

    if (!tryPushBarrel(newLeftX, newLeftY, dxLeft, dyLeft)) return;
    if (!tryPushBarrel(newRightX, newRightY, dxRight, dyLeft)) return;

    if (map[newLeftY][newLeftX] === '€') map[newLeftY][newLeftX] = 'B';
    if (map[newRightY][newRightX] === '€') map[newRightY][newRightX] = 'B';

    if (map[newLeftY][newLeftX] === ' ' || map[newRightY][newRightX] === ' ') {
        handledeathJS();
        return;
    }

    if (map[newLeftY][newLeftX] === 'S' || map[newRightY][newRightX] === 'S') {
        goToVictory();
        return;
    }

    map[playerPos.y][playerPos.x] = 'B';
    map[playerPosRight.y][playerPosRight.x] = 'B';
    map[newLeftY][newLeftX] = 'O';
    map[newRightY][newRightX] = 'Q';
    playerPos = { x: newLeftX, y: newLeftY };
    playerPosRight = { x: newRightX, y: newRightY };
    render();
}
function getPlayTime() {
    return Math.floor((Date.now() / 1000) - <?= $_SESSION['level_start_time'] ?>);
}
function handledeathJS() {
    stopTimer();
    const playTime = getPlayTime();
    fetch('updateLevelBD.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=death&level=<?= $level ?>&time=${playTime}`
    }).then(() => {
        window.location.href = 'mort.php';
    });
}
function goToVictory() {
    stopTimer();
    const playTime = getPlayTime();
    fetch('updateLevelBD.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=victory&level=<?= $level ?>&time=${playTime}&coins=${coinsCollected}`
    }).then(() => {
        document.body.classList.remove('loaded');
        document.body.classList.add('fade-out');
        setTimeout(() => window.location.href = "victoire.php", 1000);
    });
}


document.addEventListener("keydown", e => {
    switch (e.key) {
        case "ArrowUp":
        case "z": moveBoth(0, -1); break;
        case "ArrowDown":
        case "s": moveBoth(0, 1); break;
        case "ArrowLeft":
        case "q": moveBoth(-1, 0); break;
        case "ArrowRight":
        case "d": moveBoth(1, 0); break;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('loaded');
    render();
    adjustGameSize();
    startTimer();
    
    document.getElementById('black-fade').style.opacity = '0';
});

function adjustGameSize() {
    const game = document.getElementById('game');
    const rows = map.length;
    const cols = map[0].length;
    
    
    const horizontalPadding = 40;
    const verticalPadding = 120;
    
    const maxWidth = window.innerWidth - horizontalPadding;
    const maxHeight = window.innerHeight - verticalPadding;
    
    const fontSizeBasedOnWidth = maxWidth / cols;
    const fontSizeBasedOnHeight = maxHeight / rows;
    
    const newFontSize = Math.max(12, Math.min(fontSizeBasedOnWidth, fontSizeBasedOnHeight));
    
    game.style.fontSize = `${newFontSize}px`;
}

adjustGameSize();
window.addEventListener('resize', adjustGameSize);


let gameTimer;
let secondsElapsed = 0;

function startTimer() {
    secondsElapsed = Math.floor((Date.now() / 1000) - <?= $_SESSION['level_start_time'] ?>);
    if (secondsElapsed < 0) secondsElapsed = 0;
    
    updateTimerDisplay();

    if (gameTimer) {
        clearInterval(gameTimer);
    }

    gameTimer = setInterval(() => {
        secondsElapsed++;
        updateTimerDisplay();
    }, 1000);
}
function updateTimerDisplay() {
    const minutes = Math.floor(secondsElapsed / 60);
    const seconds = secondsElapsed % 60;
    const timerElement = document.getElementById('game-timer');
    if (timerElement) {
        timerElement.textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}

function stopTimer() {
    clearInterval(gameTimer);
}
/*function jouerSon(id) {
    const son = document.getElementById(id);
    if (son) {
        son.currentTime = 0;
        son.play();
    }

function deplacer(action) {
    fetch('../php/move.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=' + encodeURIComponent(action)
    })
    .then(res => res.json())
    .then(data => {
        if (data.son) {
            jouerSon(data.son);
        }
        // ici, tu peux ajouter le rafraîchissement de la grille ou autre
    });
}

document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowUp") deplacer("haut");
    else if (e.key === "ArrowDown") deplacer("bas");
    else if (e.key === "ArrowLeft") deplacer("gauche");
    else if (e.key === "ArrowRight") deplacer("droite");
});
}*/

</script>
</body>
</html>