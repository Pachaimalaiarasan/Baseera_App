<?php
// delete_order.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    
    // Optionally, check for branch_id. If provided, only delete if the branch matches.
    if (isset($_POST['branch_id']) && $_POST['branch_id'] !== "") {
        $branch_id = intval($_POST['branch_id']);
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ? AND branch_id = ?");
        $stmt->bind_param("ii", $order_id, $branch_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
    }
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Order deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Order not found"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete order: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing order_id parameter"]);
}

$conn->close();
?>
