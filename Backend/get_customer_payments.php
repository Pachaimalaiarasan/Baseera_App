<?php
// get_customer_payments.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);
    
    $stmt = $conn->prepare("SELECT * FROM payments WHERE customer_id = ? ORDER BY payment_date DESC");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $payments = array();
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    
    echo json_encode(["status" => "success", "payments" => $payments]);
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing customer_id parameter"]);
}

$conn->close();
?>
