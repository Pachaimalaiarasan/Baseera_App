<?php
header('Content-Type: application/json');
include 'database.php';

$conn = openConnection();
if (!$conn) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

// Retrieve the customer_id from the POST request
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;

if ($customer_id === null) {
    echo json_encode(["status" => "error", "message" => "Missing customer id."]);
    exit;
}

// Prepare and execute the delete query
$query = "DELETE FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$customer_id = (int)$customer_id;
$stmt->bind_param("i", $customer_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Customer deleted successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete customer: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
