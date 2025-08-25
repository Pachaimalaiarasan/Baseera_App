<?php
header('Content-Type: application/json');
include 'database.php';


// Retrieve POST parameters
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;
$customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : null;
$customer_email = isset($_POST['customer_email']) ? $_POST['customer_email'] : null;
$customer_phone = isset($_POST['customer_phone']) ? $_POST['customer_phone'] : null;

// Validate required parameters
if ($customer_id === null || $customer_name === null || $customer_email === null || $customer_phone === null) {
    echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
    exit;
}

// Prepare and execute the update query
$query = "UPDATE customer SET customer_name = ?, customer_email = ?, customer_phone = ? WHERE customer_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$customer_id = (int)$customer_id;
$stmt->bind_param("sssi", $customer_name, $customer_email, $customer_phone, $customer_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Customer updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update customer: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
