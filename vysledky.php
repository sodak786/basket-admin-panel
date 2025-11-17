<?php
global $conn;
include "db.php";
session_start();


$season = $_GET["season"] ?? $_SESSION["season"] ?? null;

if ($season) {
    $_SESSION["season"] = $season;
}

if ($season) {
    $sql = "SELECT 
                g.id,
                t1.name AS home_team,
                t2.name AS away_team,
                g.home_score,
                g.home_score_1,
                g.home_score_2,
                g.home_score_3,
                g.home_score_4,
                g.away_score,
                g.away_score_1,
                g.away_score_2,
                g.away_score_3,
                g.away_score_4,
                g.game_date,
                g.status
            FROM games g
            JOIN teams t1 ON g.home_team_id = t1.id
            JOIN teams t2 ON g.away_team_id = t2.id
            WHERE g.season = ?
            ORDER BY g.game_date DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $season);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    // no season selected → show all games
    $sql = "SELECT 
                g.id,
                t1.name AS home_team,
                t2.name AS away_team,
                g.home_score,
                g.home_score_1,
                g.home_score_2,
                g.home_score_3,
                g.home_score_4,
                g.away_score,
                g.away_score_1,
                g.away_score_2,
                g.away_score_3,
                g.away_score_4,
                g.game_date,
                g.status
            FROM games g
            JOIN teams t1 ON g.home_team_id = t1.id
            JOIN teams t2 ON g.away_team_id = t2.id
            ORDER BY g.game_date DESC";

    $result = $conn->query($sql);
}



$isLoggedin=false;

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true){
    $isLoggedin=true;
}


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
<body id="VYSLEDKY">
<header>
    <a href="/index.php" class="index-button"><img src="/images/sport-basketball-svgrepo-com(1).svg" alt="" class="micek"></a>
    <div class="header-pages">
        <a href="/index.php">Domů</a>
        <a href="/kalendar.php">Kalendář</a>
        <a href="/statistiky.php">Statistika</a>
    </div>
</header>
<main>
    <h1 class="header-text">Vysledky</h1>
    <div class="header-doplnky">
        <label for="season">Sezona:</label>
        <select name="season" id="season" onchange="window.location.href='vysledky.php?season=' + this.value;">
            <option value="25/26" <?= ($season === "25/26") ? "selected" : "" ?>>25/26</option>
            <option value="24/25" <?= ($season === "24/25") ? "selected" : "" ?>>24/25</option>
            <option value="23/24" <?= ($season === "23/24") ? "selected" : "" ?>>23/24</option>
        </select>
    <?php if(!empty($season)): ?>
        <p>vybral sis sezonu <?= htmlspecialchars($season, ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p>Vyber si sezónu z nabídky</p>
    <?php endif; ?>

    <?php if(!$isLoggedin): ?>
    <?php else: ?>
        <p>Přidat výsledek</p>
        <button class="add-result-button" onclick="pridatVysledek()">+</button>
        <div id="add-result-panel">
            <form action="" method="POST">
                <label for="datum">Datum zápasu: </label>
                <input type="date" name="datum" placeholder="Datum">
                <br>
                <label for="home-team">Domaci tym:</label>
                <select name="home-team" id="home-team">
                    <option value="Lakers">Lakers</option>
                    <option value="Warriors">Warriors</option>
                    <option value="Bulls">Bulls</option>
                    <option value="Celtics">Celtics</option>
                    <option value="Cavaliers">Cavaliers</option>
                    <option value="Mavericks">Mavericks</option>
                    <option value="Nuggets">Nuggets</option>
                    <option value="Heat">Heat</option>
                </select>
                <br>
                <label for="away-team">Hostujici tym:</label>
                <select name="away-team" id="away-team">
                    <option value="Lakers">Lakers</option>
                    <option value="Warriors">Warriors</option>
                    <option value="Bulls">Bulls</option>
                    <option value="Celtics">Celtics</option>
                    <option value="Cavaliers">Cavaliers</option>
                    <option value="Mavericks">Mavericks</option>
                    <option value="Nuggets">Nuggets</option>
                    <option value="Heat">Heat</option>
                </select>
                <br><br>
                <h2>Skóre</h2>
                <label for="domaci-celkem">Domácí (celkem):</label>
                <input type="number" name="domaci-celkem" class="skore">
                <br>
                <label>Čtvrtiny: </label>
                <input type="number" name="domaci-1" class="skore">
                <input type="number" name="domaci-2" class="skore">
                <input type="number" name="domaci-3" class="skore">
                <input type="number" name="domaci-4" class="skore">
                <br>
                <label for="domaci-celkem">Hostující (celkem):</label>
                <input type="number" name="domaci-celkem" class="skore">
                <br>
                <label>Čtvrtiny: </label>
                <input type="number" name="domaci-1" class="skore">
                <input type="number" name="domaci-2" class="skore">
                <input type="number" name="domaci-3" class="skore">
                <input type="number" name="domaci-4" class="skore">

                <button type="submit">Přidat</button>
            </form>
        </div>
    <?php endif; ?>
    </div>
    <table>
        <tr>
            <th>Datum</th>
            <th>Tým</th>
            <th>Skóre</th>
            <th>1.</th>
            <th>2.</th>
            <th>3.</th>
            <th>4.</th>
            <th>Status</th>
        </tr>

        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td rowspan="2"><?= htmlspecialchars($row["game_date"])?></td>
                    <td><?= htmlspecialchars($row["home_team"])?></td>
                    <td><?= htmlspecialchars($row["home_score"])?></td>
                    <td><?= htmlspecialchars($row["home_score_1"])?></td>
                    <td><?= htmlspecialchars($row["home_score_2"])?></td>
                    <td><?= htmlspecialchars($row["home_score_3"])?></td>
                    <td><?= htmlspecialchars($row["home_score_4"])?></td>
                    <td rowspan="2"><?= htmlspecialchars($row["status"])?></td>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row["away_team"])?></td>
                    <td><?= htmlspecialchars($row["away_score"])?></td>
                    <td><?= htmlspecialchars($row["away_score_1"])?></td>
                    <td><?= htmlspecialchars($row["away_score_2"])?></td>
                    <td><?= htmlspecialchars($row["away_score_3"])?></td>
                    <td><?= htmlspecialchars($row["away_score_4"])?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Žádné zápasy nenalezeny</td></tr>
        <?php endif; ?>
    </table>
</main>
<footer></footer>
</body>
</html>

