<?php
global $conn;
include "db.php";
session_start();

$isLoggedin=false;

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true){
    $isLoggedin=true;
}


$team_id = $_GET["id"];

$team = $conn->query("SELECT * FROM teams WHERE id = $team_id")->fetch_assoc();

$players = $conn->query("SELECT * FROM players WHERE team_id = $team_id");
?>
<?php
global $conn;
include "db.php";

$sql = "SELECT 
            t.id,
            t.name,
            SUM(CASE WHEN 
                    (g.home_team_id = t.id AND g.home_score > g.away_score) OR 
                    (g.away_team_id = t.id AND g.away_score > g.home_score)
                THEN 1 ELSE 0 END) AS wins,
            SUM(CASE WHEN 
                    (g.home_team_id = t.id AND g.home_score < g.away_score) OR 
                    (g.away_team_id = t.id AND g.away_score < g.home_score)
                THEN 1 ELSE 0 END) AS losses
        FROM teams t
        LEFT JOIN games g
            ON g.home_team_id = t.id OR g.away_team_id = t.id
        GROUP BY t.id
        ORDER BY wins DESC";

$result = $conn->query($sql);
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
<body id="TEAM">
<header>
    <a href="/index.php" class="index-button"><img src="/images/sport-basketball-svgrepo-com(1).svg" alt="" class="micek"></a>
    <div class="header-pages">
        <a href="/vysledky.php">Výsledky</a>
        <a href="/kalendar.php">Kalendář</a>
        <a href="/index.php">Domů</a>
    </div>
</header>
<main>
    <h1><?= htmlspecialchars($team['name']) ?></h1>
    <div class="header-doplnky">
        <?php if ($isLoggedin): ?>
            <p>Přidat hráče</p>
            <button class="add-result-button" onclick="togglePanel()">+</button>

            <div id="add-result-panel" style="display:none;">
                <form action="pridat_hrace.php" method="POST">
                    <input type="hidden" name="team_id" value="<?= $team['id'] ?>">

                    <label>Jméno:</label>
                    <input type="text" name="first_name" required>

                    <label>Příjmení:</label>
                    <input type="text" name="last_name" required>

                    <label>Pozice:</label>
                    <input type="text" name="position" required>
                    <select name="home-team" >
                        <option value="<?= $tym['id'] ?>"><?= htmlspecialchars($tym['name']) ?></option>
                    </select>

                    <label>Výška (cm):</label>
                    <input type="number" name="height_cm" required>

                    <label>Váha (kg):</label>
                    <input type="number" name="weight_kg" required>

                    <label>Datum narození:</label>
                    <input type="date" name="birth_date" required>

                    <button type="submit">Přidat hráče</button>
                </form>
            </div>
        <?php endif; ?>

    </div>
    <p>Coach: <?= htmlspecialchars($team['coach']) ?></p>

    <h2>Hráči</h2>
    <ul>
        <?php while($p = $players->fetch_assoc()): ?>
            <li onclick="window.location.href='player.php?id=<?= $p['id'] ?>'">
                <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?>
            </li>
        <?php endwhile; ?>
    </ul>
</main>
<footer></footer>
</body>
</html>

