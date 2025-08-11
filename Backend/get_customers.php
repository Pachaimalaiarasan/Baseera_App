<?php
header('Content-Type: application/json');
require_once("database.php");

$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

if ($result) {
    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
    echo json_encode(["status" => "success", "customers" => $customers]);
} else {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]);
}
$conn->close();
?>
