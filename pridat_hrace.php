<?php
global $conn;
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $team_id = $_POST["team_id"];
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $pos = $_POST["position"];
    $height = $_POST["height_cm"];
    $weight = $_POST["weight_kg"];
    $birth = $_POST["birth_date"];

    $sql = "INSERT INTO players (team_id, first_name, last_name, position, height_cm, weight_kg, birth_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiis",
        $team_id, $first, $last, $pos, $height, $weight, $birth
    );
    $stmt->execute();

    header("Location: team.php?id=$team_id&added=1");
    exit;
}
?>
