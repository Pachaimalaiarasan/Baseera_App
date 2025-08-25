<?php
header('Content-Type: application/json');
include 'database.php';

$sql = "SELECT * FROM payments ORDER BY payment_date DESC";
$result = $conn->query($sql);

$payments = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    
    if (count($payments) > 0) {
        echo json_encode([
            "status" => "success",
            "payments" => $payments
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No payments found"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Query failed: " . $conn->error
    ]);
}

$conn->close();
?>
