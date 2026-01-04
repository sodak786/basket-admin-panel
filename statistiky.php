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
<body id="STATISTIKY">
<header>
    <a href="/index.php" class="index-button"><img src="/images/sport-basketball-svgrepo-com(1).svg" alt="" class="micek"></a>
    <div class="header-pages">
        <a href="/vysledky.php">Výsledky</a>
        <a href="/kalendar.php">Kalendář</a>
        <a href="/index.php">Domů</a>
    </div>
</header>
<main>
    <h1>Tabulka</h1>
    <div class="header-doplnky">
        <div>
            <label for="season">Sezona:</label>
            <select name="season" id="season" onchange="window.location.href='statistiky.php?season=' + this.value;">
                <option value="25/26" <?= ($season === "25/26") ? "selected" : "" ?>>25/26</option>
                <option value="24/25" <?= ($season === "24/25") ? "selected" : "" ?>>24/25</option>
                <option value="23/24" <?= ($season === "23/24") ? "selected" : "" ?>>23/24</option>
            </select>
        </div>
        <?php if(!empty($season)): ?>
            <p>Výsledky za sezónu <?= htmlspecialchars($season, ENT_QUOTES, 'UTF-8') ?></p>
        <?php else: ?>
            <p>Vyber si sezónu z nabídky</p>
        <?php endif; ?>

        <?php if(!$isLoggedin): ?>
        <?php else: ?>
            <button class="add-result-button" onclick="togglePanel()">Přidat tým</button>

            <div id="add-result-panel" style="display:none;">
                <form action="pridat_tym.php" method="POST">
                    <label>Název týmu:</label>
                    <input type="text" name="name" required>

                    <label>Město:</label>
                    <input type="text" name="city" required>

                    <label>Coach:</label>
                    <input type="text" name="coach" required>

                    <button type="submit" style="margin-left: 110px;">Přidat tým</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <table>
        <tr>
            <th>Tým</th>
            <th>Výhry</th>
            <th>Prohry</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td onclick="window.location.href='team.php?id=<?= $row['id'] ?>'" class="clickable-team">
                    <?= htmlspecialchars($row['name']) ?>
                </td>
                <td><?= $row['wins'] ?></td>
                <td><?= $row['losses'] ?></td>
                <td>
                    <?php if ($isLoggedin): ?>
                        <form action="smazat_tym.php" method="POST" onsubmit="return confirm('Opravdu chceš smazat tento tým?');">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="delete-btn">🗑️</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>
<footer></footer>
</body>
</html>

