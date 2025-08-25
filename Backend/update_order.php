<?php
// update_order.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    
    // Optional fields to update:
    $order_status = isset($_POST['order_status']) ? $_POST['order_status'] : null;
    $payment_id = isset($_POST['payment_id']) && $_POST['payment_id'] !== "" ? intval($_POST['payment_id']) : null;
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : null;
    $address_id = isset($_POST['address_id']) && $_POST['address_id'] !== "" ? intval($_POST['address_id']) : null;
    $branch_id = isset($_POST['branch_id']) && $_POST['branch_id'] !== "" ? intval($_POST['branch_id']) : null;
    
    // Build the update query dynamically based on provided fields.
    $fields = [];
    $params = [];
    $types = "";
    
    if ($order_status !== null) {
        $fields[] = "order_status = ?";
        $params[] = $order_status;
        $types .= "s";
    }
    if ($payment_id !== null) {
        $fields[] = "payment_id = ?";
        $params[] = $payment_id;
        $types .= "i";
    }
    if ($total_amount !== null) {
        $fields[] = "total_amount = ?";
        $params[] = $total_amount;
        $types .= "d";
    }
    if ($address_id !== null) {
        $fields[] = "address_id = ?";
        $params[] = $address_id;
        $types .= "i";
    }
    if ($branch_id !== null) {
        $fields[] = "branch_id = ?";
        $params[] = $branch_id;
        $types .= "i";
    }
    
    if (count($fields) == 0) {
        echo json_encode(["status" => "error", "message" => "No fields to update"]);
        exit;
    }
    
    $sql = "UPDATE orders SET " . implode(", ", $fields) . " WHERE order_id = ?";
    $params[] = $order_id;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Order updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update order: " . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing order_id parameter"]);
}

$conn->close();
?>
