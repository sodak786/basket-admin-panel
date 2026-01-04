<?php
global $conn;
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datum = $_POST["datum"];
    $domaci = $_POST["home-team"];
    $hoste = $_POST["away-team"];
    $skore_domaci = $_POST["domaci-celkem"];
    $skore_hoste = $_POST["hostujici-celkem"];
    $domaci_1 = $_POST["domaci-1"];
    $domaci_2 = $_POST["domaci-2"];
    $domaci_3 = $_POST["domaci-3"];
    $domaci_4 = $_POST["domaci-4"];
    $hoste_1= $_POST["hoste-1"];
    $hoste_2= $_POST["hoste-2"];
    $hoste_3= $_POST["hoste-3"];
    $hoste_4= $_POST["hoste-4"];
    $season = $_POST["sezona"];

    if ($domaci == $hoste) {
        die("Domácí a hosté musí být různí");
    }

    $sql = "INSERT INTO games 
        (home_team_id, away_team_id, game_date, home_score, away_score, status,
         home_score_1, home_score_2, home_score_3, home_score_4,
         away_score_1, away_score_2, away_score_3, away_score_4,
         season)
        VALUES (?, ?, ?, ?, ?, 'finished', ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iisiiiiiiiiiis",
        $domaci,
        $hoste,
        $datum,
        $skore_domaci,
        $skore_hoste,
        $domaci_1, $domaci_2, $domaci_3, $domaci_4,
        $hoste_1, $hoste_2, $hoste_3, $hoste_4,
        $season
    );


    $stmt->execute();

    header("Location: vysledky.php?season=$season&added=1");
    exit;
}
