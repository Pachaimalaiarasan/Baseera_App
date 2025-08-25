<?php
// add_payment.php
header('Content-Type: application/json');
include 'database.php';

// Expected POST parameters: customer_id, payment_amount, payment_method, payment_status, payment_date
if (
    isset($_POST['customer_id']) && isset($_POST['payment_amount']) &&
    isset($_POST['payment_method']) && isset($_POST['payment_status']) &&
    isset($_POST['payment_date'])
) {
    $customer_id    = intval($_POST['customer_id']);
    $payment_amount = floatval($_POST['payment_amount']);
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $payment_date   = $_POST['payment_date']; // Format: 'YYYY-MM-DD HH:MM:SS'

    $stmt = $conn->prepare("INSERT INTO payments (customer_id, payment_amount, payment_method, payment_status, payment_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $customer_id, $payment_amount, $payment_method, $payment_status, $payment_date);

    if ($stmt->execute()) {
        echo json_encode([
          "status" => "success",
          "message" => "Payment added successfully",
          "payment_id" => $stmt->insert_id
        ]);
    } else {
        echo json_encode([
          "status" => "error",
          "message" => "Failed to add payment: " . $stmt->error
        ]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
}

$conn->close();
?>
