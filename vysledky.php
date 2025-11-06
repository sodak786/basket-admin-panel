<?php
include "db.php";

session_start();

$isLoggedin=false;

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true){
    $isLoggedin=true;
}

$season = $_GET["season"] ?? null;
if(isset($season)){
    $_SESSION["season"]=$season;
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
        <p>Vyber si sezónu z nabídky</p
    <?php endif; ?>

    <?php if(!$isLoggedin): ?>
    <?php else: ?>
        <p>Admin je přihlášen</p>
    </div>
    <table>
        <tr>
            <th>Datum</th>
            <th>Tým</th>
            <th>1.</th>
            <th>2.</th>
            <th>3.</th>
            <th>4.</th>
            <th>Dohromady</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <?php endif; ?>


</main>
<footer></footer>
</body>
</html>

