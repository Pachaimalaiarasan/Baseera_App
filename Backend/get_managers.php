<?php
require_once("database.php");
header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM managers");
$managers = [];

while ($row = $result->fetch_assoc()) {
    $managers[] = $row;
}

echo json_encode($managers);
?>
