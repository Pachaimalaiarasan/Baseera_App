<?php
// get_all_orders.php
header('Content-Type: application/json');
include 'database.php';

// Check if branch_id parameter is provided
if (isset($_GET['branch_id']) && $_GET['branch_id'] !== "") {
    $branch_id = intval($_GET['branch_id']);
    $stmt = $conn->prepare("SELECT * FROM orders WHERE branch_id = ? ORDER BY order_id DESC");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode(["status" => "success", "orders" => $orders]);
    $stmt->close();
} else {
    // No branch filter; return all orders.
    $sql = "SELECT * FROM orders ORDER BY order_id DESC";
    $result = $conn->query($sql);
    if ($result) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode(["status" => "success", "orders" => $orders]);
    } else {
        echo json_encode(["status" => "error", "message" => "Could not fetch orders"]);
    }
}

$conn->close();
?>
