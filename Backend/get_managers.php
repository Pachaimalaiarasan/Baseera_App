<?php
header("Content-Type: application/json");
require_once("database.php");

$result = $conn->query("SELECT * FROM managers");
$managers = [];
while ($row = $result->fetch_assoc()) {
    $managers[] = $row;
}
echo json_encode([
    "status" => "success",
    "managers" => $managers
]);
$conn->close();
?>
