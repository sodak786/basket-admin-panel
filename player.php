<?php
global $conn;
include "db.php";
session_start();
$isLoggedin=false;

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true){
    $isLoggedin=true;
}
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

$playerTeamId = (int)$player['team_id'];

$gamesStmt = $conn->prepare("
    SELECT g.id, g.game_date, 
           t1.name AS home_team, 
           t2.name AS away_team
    FROM games g
    JOIN teams t1 ON g.home_team_id = t1.id
    JOIN teams t2 ON g.away_team_id = t2.id
    WHERE (g.home_team_id = ? OR g.away_team_id = ?)
      AND g.status = 'finished'
    ORDER BY g.game_date DESC
");
$gamesStmt->bind_param("ii", $playerTeamId, $playerTeamId);
$gamesStmt->execute();
$games = $gamesStmt->get_result();

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
    <div class="header-doplnky">
        <p>Tým: <?= htmlspecialchars($player['team_name']) ?></p>
        <p>Pozice: <?= htmlspecialchars($player['position']) ?></p>
        <p>Výška: <?= $player['height_cm'] ?> cm</p>
        <p>Váha: <?= $player['weight_kg'] ?> kg</p>
        <p>Datum narození: <?= $player['birth_date'] ?></p>
    </div>

    <h2>Průměrné statistiky hráče</h2>

    <?php if ($player_stats): ?>
        <p>Body: <?= clean($player_stats['avg_points']) ?></p>
        <p>Doskoky: <?= clean($player_stats['avg_rebounds']) ?></p>
        <p>Asistence: <?= clean($player_stats['avg_assists']) ?></p>
        <p>Stealy: <?= clean($player_stats['avg_steals']) ?></p>
        <p>Bloky: <?= clean($player_stats['avg_blocks']) ?></p>
        <p>Fauly: <?= clean($player_stats['avg_fouls']) ?></p>
    <?php else: ?>
        <p>Hráč zatím nemá žádné statistiky.</p>
    <?php endif; ?>

    <?php if ($isLoggedin): ?>
        <button class="add-result-button" onclick="togglePanel()">Přidat statistiky z konkrétního zápasu</button>
        <div id="add-result-panel">
            <form action="pridat_stat_hrace.php" method="POST">
                <input type="hidden" name="player_id" value="<?= (int)$id ?>">

                <label for="game_id">Zápas:</label>
                <select name="game_id" id="game_id" required>
                    <?php while ($g = $games->fetch_assoc()): ?>
                        <option value="<?= $g['id'] ?>">
                            <?= htmlspecialchars($g['game_date']) ?>
                            - <?= htmlspecialchars($g['home_team']) ?>
                            vs <?= htmlspecialchars($g['away_team']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <br>
                <label>Body:</label>
                <input type="number" name="points" min="0" required>

                <label>Doskoky:</label>
                <input type="number" name="rebounds" min="0" required>

                <label>Asistence:</label>
                <input type="number" name="assists" min="0" required>

                <label>Stealy:</label>
                <input type="number" name="steals" min="0" required>

                <label>Bloky:</label>
                <input type="number" name="blocks" min="0" required>

                <label>Fauly:</label>
                <input type="number" name="fouls" min="0" required>

                <button type="submit">Uložit statistiky</button>
            </form>
        </div>
    <?php endif; ?>
</main>
<footer></footer>
</body>
</html>

