<?php
global $conn;
include "db.php";
session_start();

$season = $_GET["season"] ?? $_SESSION["season"] ?? null;

if ($season) {
    $_SESSION["season"] = $season;
}

$isLoggedin=false;

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true){
    $isLoggedin=true;
}

$sql = "SELECT 
            t.id,
            t.name,
            SUM(CASE WHEN 
                    g.season = ? AND
                    ((g.home_team_id = t.id AND g.home_score > g.away_score) OR 
                     (g.away_team_id = t.id AND g.away_score > g.home_score))
                THEN 1 ELSE 0 END) AS wins,

            SUM(CASE WHEN 
                    g.season = ? AND
                    ((g.home_team_id = t.id AND g.home_score < g.away_score) OR 
                     (g.away_team_id = t.id AND g.away_score < g.home_score))
                THEN 1 ELSE 0 END) AS losses
        FROM teams t
        LEFT JOIN games g
            ON g.home_team_id = t.id OR g.away_team_id = t.id
        GROUP BY t.id
        ORDER BY wins DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $season, $season);
$stmt->execute();
$result = $stmt->get_result();

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
<body id="KALENDAR">
<header>
    <a href="/index.php" class="index-button"><img src="/images/sport-basketball-svgrepo-com(1).svg" alt="" class="micek"></a>
    <div class="header-pages">
        <a href="/vysledky.php">Výsledky</a>
        <a href="/index.php">Domů</a>
        <a href="/statistiky.php">Statistika</a>
    </div>
</header>
<main>
    <h1>Kalendář</h1>
    <div class="header-doplnky">
        <label for="season">Sezona:</label>
        <select name="season" id="season" onchange="window.location.href='statistiky.php?season=' + this.value;">
            <option value="25/26" <?= ($season === "25/26") ? "selected" : "" ?>>25/26</option>
            <option value="24/25" <?= ($season === "24/25") ? "selected" : "" ?>>24/25</option>
            <option value="23/24" <?= ($season === "23/24") ? "selected" : "" ?>>23/24</option>
        </select>
        <?php if(!empty($season)): ?>
            <p>Kalendář za sezónu <?= htmlspecialchars($season, ENT_QUOTES, 'UTF-8') ?></p>
        <?php else: ?>
            <p>Vyber si sezónu z nabídky</p>
        <?php endif; ?>

        <?php if(!$isLoggedin): ?>
        <?php else: ?>
        <?php endif; ?>
    </div>

</main>
<footer></footer>
</body>
</html>

