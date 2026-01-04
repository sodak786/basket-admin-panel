<?php
global $conn;
include "db.php";
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    die("Nemáš oprávnění");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $player_id = (int)$_POST["player_id"];
    $game_id   = (int)$_POST["game_id"];

    $points    = (int)$_POST["points"];
    $rebounds  = (int)$_POST["rebounds"];
    $assists   = (int)$_POST["assists"];
    $steals    = (int)$_POST["steals"];
    $blocks    = (int)$_POST["blocks"];
    $fouls     = (int)$_POST["fouls"];


    $sql = "INSERT INTO player_stats 
            (player_id, game_id, points, rebounds, assists, steals, blocks, fouls)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iiiiiiii",
        $player_id,
        $game_id,
        $points,
        $rebounds,
        $assists,
        $steals,
        $blocks,
        $fouls
    );
    $stmt->execute();

    header("Location: player.php?id=" . $player_id);
    exit;
}
