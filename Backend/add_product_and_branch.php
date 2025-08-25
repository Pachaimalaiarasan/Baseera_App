<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_id']) && isset($_POST['product_id'])) {
    $branchId = $_POST['branch_id'];
    $productId = $_POST['product_id'];

    $sql = "INSERT INTO product_and_branch (branch_id, product_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
        exit();
    }
    $stmt->bind_param("ii", $branchId, $productId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mapping added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
