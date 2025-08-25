<?php
// update_payment.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_POST['payment_id'])) {
    $payment_id = intval($_POST['payment_id']);
    
    // Optional fields
    $payment_amount = isset($_POST['payment_amount']) ? floatval($_POST['payment_amount']) : null;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
    $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : null;
    
    $fields = [];
    $params = [];
    $types = "";
    
    if ($payment_amount !== null) {
        $fields[] = "payment_amount = ?";
        $params[] = $payment_amount;
        $types .= "d";
    }
    if ($payment_method !== null) {
        $fields[] = "payment_method = ?";
        $params[] = $payment_method;
        $types .= "s";
    }
    if ($payment_status !== null) {
        $fields[] = "payment_status = ?";
        $params[] = $payment_status;
        $types .= "s";
    }
    
    if (count($fields) === 0) {
        echo json_encode(["status" => "error", "message" => "No fields to update"]);
        exit;
    }
    
    $sql = "UPDATE payments SET " . implode(", ", $fields) . " WHERE payment_id = ?";
    $params[] = $payment_id;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Payment updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update payment: " . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing payment_id parameter"]);
}

$conn->close();
?>
