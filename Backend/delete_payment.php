<?php
// delete_payment.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_POST['payment_id'])) {
    $payment_id = intval($_POST['payment_id']);
    
    $stmt = $conn->prepare("DELETE FROM payments WHERE payment_id = ?");
    $stmt->bind_param("i", $payment_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Payment deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Payment not found"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete payment: " . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing payment_id parameter"]);
}

$conn->close();
?>
