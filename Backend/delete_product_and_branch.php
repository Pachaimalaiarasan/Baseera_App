<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_id']) && isset($_POST['product_id'])) {
    $branchId = $_POST['branch_id'];
    $productId = $_POST['product_id'];

    $sql = "DELETE FROM product_and_branch WHERE branch_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
        exit();
    }
    $stmt->bind_param("ii", $branchId, $productId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mapping deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
