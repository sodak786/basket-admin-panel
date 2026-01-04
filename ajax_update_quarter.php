<?php
global $conn;
require "db.php";
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    http_response_code(403);
    echo "Nejste přihlášen";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Špatná metoda";
    exit;
}

$gameId  = isset($_POST["game_id"]) ? (int)$_POST["game_id"] : 0;
$side    = $_POST["side"]    ?? '';
$quarter = isset($_POST["quarter"]) ? (int)$_POST["quarter"] : 0;
$value   = isset($_POST["value"])   ? (int)$_POST["value"]   : 0;

if (!$gameId || $quarter < 1 || $quarter > 4 || !in_array($side, ["home", "away"])) {
    http_response_code(400);
    echo "Špatná data";
    exit;
}

$column = ($side === "home" ? "home_score_" : "away_score_") . $quarter;

$sql = "UPDATE games SET $column = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $value, $gameId);
$stmt->execute();

if ($stmt->affected_rows >= 0) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Chyba při ukládání";
}
