<?php
global $conn;
include "db.php";
session_start();

$season = $_GET["season"] ?? $_SESSION["season"] ?? null;

if ($season) {
    $_SESSION["season"] = $season;
}

$tymy = [];
$resTeams = $conn->query("SELECT id, name FROM teams ORDER BY id ASC");
while ($row = $resTeams->fetch_assoc()) {
    $tymy[] = $row;
}

$sql = "SELECT 
            g.id,
            t1.name AS home_team,
            t2.name AS away_team,
            g.game_date,
            g.status
        FROM games g
        JOIN teams t1 ON g.home_team_id = t1.id
        JOIN teams t2 ON g.away_team_id = t2.id
        WHERE g.status = 'scheduled'
        " . ($season ? " AND g.season = ?" : "") . "
        ORDER BY g.game_date ASC";

$stmt = $conn->prepare($sql);

if ($season) {
    $stmt->bind_param("s", $season);
}

$stmt->execute();
$games = $stmt->get_result();

$isLoggedin = isset($_SESSION["loggedIn"]);
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
            <h1>Kalendář zápasů</h1>
            <div class="header-doplnky">
                <label>Sezona:</label>
                <select onchange="window.location.href='kalendar.php?season=' + this.value">
                    <option value="25/26" <?= $season === "25/26" ? "selected" : "" ?>>25/26</option>
                    <option value="24/25" <?= $season === "24/25" ? "selected" : "" ?>>24/25</option>
                    <option value="23/24" <?= $season === "23/24" ? "selected" : "" ?>>23/24</option>
                </select>
                <?php if ($isLoggedin): ?>
                    <p>Přidat nový plánovaný zápas</p>
                    <button onclick="togglePanel()">+</button>

                    <div id="add-result-panel" style="display:none;">
                        <form action="pridat_plan_zapas.php" method="POST">
                            <label>Datum:</label>
                            <input type="date" name="datum" required>

                            <label>Domácí tým:</label>
                            <select name="home-team">
                                <?php foreach ($tymy as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <label>Hosté:</label>
                            <select name="away-team">
                                <?php foreach ($tymy as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <label>Sezona:</label>
                            <select name="sezona">
                                <option value="25/26">25/26</option>
                                <option value="24/25">24/25</option>
                                <option value="23/24">23/24</option>
                            </select>

                            <button type="submit">Přidat</button>
                        </form>
                    </div>
                <?php endif; ?>
                <h2>Nadcházející zápasy</h2>
            </div>
            <table>
                <tr>
                    <th>Datum</th>
                    <th>Domácí</th>
                    <th>Hosté</th>
                    <th>Status</th>
                </tr>

                <?php while ($g = $games->fetch_assoc()): ?>
                    <tr>
                        <td><?= $g['game_date'] ?></td>
                        <td><?= $g['home_team'] ?></td>
                        <td><?= $g['away_team'] ?></td>
                        <td><?= $g['status'] ?></td>

                        <?php if ($isLoggedin): ?>
                            <td>
                                <a href="upravit_zapas.php?id=<?= $g['id'] ?>" class="edit-btn">✎</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>

            </table>
        </main>
    </body>
</html>
