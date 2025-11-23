<?php
global $conn;
require "db.php";
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    die("Nepovolený přístup");
}

if (!isset($_GET["id"])) {
    die("Chybí ID zápasu");
}

$id = intval($_GET["id"]);

$sql = "DELETE FROM games WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$season = $_SESSION["season"] ?? "25/26";

header("Location: vysledky.php?season=$season&deleted=1");
exit;
