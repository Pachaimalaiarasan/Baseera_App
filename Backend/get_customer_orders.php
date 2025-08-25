<?php
// get_customer_orders.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);

    // Check if branch_id parameter is provided
    if (isset($_GET['branch_id']) && $_GET['branch_id'] !== "") {
        $branch_id = intval($_GET['branch_id']);
        $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? AND branch_id = ? ORDER BY order_id DESC");
        $stmt->bind_param("ii", $customer_id, $branch_id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_id DESC");
        $stmt->bind_param("i", $customer_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    
    echo json_encode(["status" => "success", "orders" => $orders]);
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing customer_id parameter"]);
}

$conn->close();
?>
