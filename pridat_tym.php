<?php
global $conn;
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $city = $_POST["city"];
    $coach = $_POST["coach"];

    $sql = "INSERT INTO teams (name, city, coach) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $city, $coach);
    $stmt->execute();

    header("Location: statistiky.php?added=1");
    exit;
}
