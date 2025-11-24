<?php
global $conn;
include "db.php";
session_start();

$id     = $_POST["id"];
$hs     = $_POST["home_score"];
$as     = $_POST["away_score"];
$h1     = $_POST["h1"];
$h2     = $_POST["h2"];
$h3     = $_POST["h3"];
$h4     = $_POST["h4"];
$a1     = $_POST["a1"];
$a2     = $_POST["a2"];
$a3     = $_POST["a3"];
$a4     = $_POST["a4"];

$sql = "UPDATE games SET 
            home_score = ?,
            away_score = ?,
            home_score_1 = ?, home_score_2 = ?, home_score_3 = ?, home_score_4 = ?,
            away_score_1 = ?, away_score_2 = ?, away_score_3 = ?, away_score_4 = ?,
            status = 'finished'
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiiiiiiii",
    $hs, $as,
    $h1, $h2, $h3, $h4,
    $a1, $a2, $a3, $a4,
    $id
);
$stmt->execute();

header("Location: vysledky.php?updated=1");
exit;
