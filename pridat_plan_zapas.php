<?php
global $conn;
require "db.php";
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    die("Nepovolený přístup.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datum = $_POST["datum"];
    $domaci = $_POST["home-team"];
    $hoste = $_POST["away-team"];
    $season = $_POST["sezona"];

    if ($domaci == $hoste) {
        die("Domácí a hosté musí být různí.");
    }

    $sql = "INSERT INTO games 
            (home_team_id, away_team_id, game_date, status, season)
            VALUES (?, ?, ?, 'scheduled', ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $domaci, $hoste, $datum, $season);
    $stmt->execute();

    header("Location: kalendar.php?season=$season&added=1");
    exit;
}
?>
