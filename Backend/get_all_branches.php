<?php
require_once("database.php");

$query = "SELECT * FROM branch ORDER BY branch_id ASC";
$result = $conn->query($query);

$branches = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $branches[] = $row;
    }
}

echo json_encode(['status' => 'success', 'branches' => $branches]);

$conn->close();
?>
