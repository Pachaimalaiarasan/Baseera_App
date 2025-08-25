<?php
require_once("database.php");

$query = "SELECT * FROM service_and_branch";
$result = $conn->query($query);

$mappings = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $mappings[] = $row;
    }
}

echo json_encode(['status' => 'success', 'mappings' => $mappings]);

$conn->close();
?>
