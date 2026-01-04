<?php
global $conn;
require "db.php";
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    die("Nejsi přihlášen");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = intval($_POST["id"]);

    $stmt = $conn->prepare("DELETE FROM players WHERE team_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM games WHERE home_team_id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $stmt3 = $conn->prepare("DELETE FROM games WHERE away_team_id = ?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();

    $stmt4 = $conn->prepare("DELETE FROM teams WHERE id = ?");
    $stmt4->bind_param("i", $id);
    $stmt4->execute();

    header("Location: statistiky.php?team_deleted=1");
    exit;
}
