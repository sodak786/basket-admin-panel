<?php
global $conn;
include "db.php";
session_start();

$id = $_GET["id"];

$game = $conn->query("
    SELECT g.*, 
           t1.name AS home_team_name,
           t2.name AS away_team_name
    FROM games g
    JOIN teams t1 ON g.home_team_id = t1.id
    JOIN teams t2 ON g.away_team_id = t2.id
    WHERE g.id = $id
")->fetch_assoc();


?>
<!doctype html>
<html>
<head>
    <title>Upravit zápas</title>
    <link rel="stylesheet" href="index.scss">
</head>

<body id="EDIT">
<h1>Upravit zápas</h1>

<form action="ulozit_upravu.php" method="POST">

    <input type="hidden" name="id" value="<?= $id ?>">

    <p><?= $game['game_date'] ?> |  <?= htmlspecialchars($game['home_team_name']) ?> vs <?= htmlspecialchars($game['away_team_name']) ?>

    <label>Skóre domácí:</label>
    <input type="number" name="home_score">

    <label>Skóre hosté:</label>
    <input type="number" name="away_score">

    <h3>Čtvrtiny</h3>
    <input type="number" name="h1">
    <input type="number" name="h2">
    <input type="number" name="h3">
    <input type="number" name="h4">

    <input type="number" name="a1">
    <input type="number" name="a2">
    <input type="number" name="a3">
    <input type="number" name="a4">

    <button type="submit">Uložit výsledek</button>
</form>
</body>
</html>
