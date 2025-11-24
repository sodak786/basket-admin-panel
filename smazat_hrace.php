<?php
global $conn;
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id"];
    $team_id = $_POST["team_id"];

    $conn->query("DELETE FROM player_stats WHERE player_id = $id");

    $conn->query("DELETE FROM players WHERE id = $id");

    header("Location: team.php?id=$team_id&deleted_player=1");
    exit;
}
