<?php
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
</head>
<body id="VYSLEDKY">
<header>
    <div class="index-button">micek</div>
    <div class="header-pages">
        <a href="/index.php">Domů</a>
        <a href="/kalendar.php">Kalendář</a>
        <a href="/statistiky.php">Statistika</a>
    </div>
</header>
<main>
    <h1 class="header-text">Vysledky</h1>
    <form action="">

    </form>
    <label for="season">Sezona:</label>
    <select name="season" id="season" onchange="window.location.href='vysledky.php?season=' + this.value;">
        <option value="25/26" <?= ($season === "25/26") ? "selected" : "" ?>>25/26</option>
        <option value="24/25" <?= ($season === "24/25") ? "selected" : "" ?>>24/25</option>
        <option value="23/24" <?= ($season === "23/24") ? "selected" : "" ?>>23/24</option>
    </select>
    <?php if(!$isLoggedin): ?>
    <?php else: ?>
        <p>Admin je přihlášen</p>
    <?php endif; ?>

    <?php if(!empty($season)): ?>
        <p>vybral sis sezonu <?= htmlspecialchars($season, ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p>Vyber si sezónu z nabídky</p
    <?php endif; ?>
</main>
<footer></footer>
</body>
</html>

