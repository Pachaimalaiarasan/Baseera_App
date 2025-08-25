<?php
header('Content-Type: application/json');
include 'database.php';

$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

if ($result) {
    $customers = array();
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
    if (count($customers) > 0) {
        echo json_encode(["status" => "success", "customers" => $customers]);
    } else {
        echo json_encode(["status" => "error", "message" => "No customers found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]);
}

$conn->close();
?>
