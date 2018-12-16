<?php

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("DELETE FROM Oprava WHERE ID_Oprava = :id");
$stmt->bindParam("id", $_GET["id"]);
$stmt->execute();

echo "Oprava smazána ";
