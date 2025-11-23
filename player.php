<?php
global $conn;
include "db.php";
function clean($num) {
    return rtrim(rtrim($num, '0'), '.');
}

$id = $_GET["id"];

$player = $conn->query("SELECT p.*, t.name AS team_name 
                        FROM players p 
                        LEFT JOIN teams t ON p.team_id = t.id
                        WHERE p.id = $id")->fetch_assoc();

$player_stats = $conn->query("
    SELECT 
        AVG(points) AS avg_points,
        AVG(rebounds) AS avg_rebounds,
        AVG(assists) AS avg_assists,
        AVG(steals) AS avg_steals,
        AVG(blocks) AS avg_blocks,
        AVG(fouls) AS avg_fouls
    FROM player_stats
    WHERE player_id = $id
")->fetch_assoc();
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="index.scss">
    <script src="script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
</head>
<body id="PLAYER">
<header>
    <a href="/index.php" class="index-button"><img src="/images/sport-basketball-svgrepo-com(1).svg" alt="" class="micek"></a>
    <div class="header-pages">
        <a href="/vysledky.php">Výsledky</a>
        <a href="/kalendar.php">Kalendář</a>
        <a href="/index.php">Domů</a>
    </div>
</header>
<main>
    <h1><?= htmlspecialchars($player['first_name'] . ' ' . $player['last_name']) ?></h1>

    <p>Tým: <?= htmlspecialchars($player['team_name']) ?></p>
    <p>Pozice: <?= htmlspecialchars($player['position']) ?></p>
    <p>Výška: <?= $player['height_cm'] ?> cm</p>
    <p>Váha: <?= $player['weight_kg'] ?> kg</p>
    <p>Datum narození: <?= $player['birth_date'] ?></p>

    <h1>Průměrné statistiky hráče</h1>

    <p>Body: <?= clean($player_stats['avg_points']) ?></p>
    <p>Doskoky: <?= clean($player_stats['avg_rebounds']) ?></p>
    <p>Asistence: <?= clean($player_stats['avg_assists']) ?></p>
    <p>Stealy: <?= clean($player_stats['avg_steals']) ?></p>
    <p>Bloky: <?= clean($player_stats['avg_blocks']) ?></p>
    <p>Fauly: <?= clean($player_stats['avg_fouls']) ?></p>
</main>
<footer></footer>
</body>
</html>

