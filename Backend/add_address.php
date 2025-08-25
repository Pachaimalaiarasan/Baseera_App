<?php
header('Content-Type: application/json');
include 'database.php';

// Validate required POST data
if (
    !isset($_POST['customer_id']) ||
    !isset($_POST['address_line1']) ||
    !isset($_POST['state']) ||
    !isset($_POST['city'])
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields: customer_id, address_line1, state, and city are required.'
    ]);
    exit();
}

$customer_id   = intval($_POST['customer_id']);
$address_line1 = trim($_POST['address_line1']);
$address_line2 = isset($_POST['address_line2']) ? trim($_POST['address_line2']) : null;
$state         = trim($_POST['state']);
$city          = trim($_POST['city']);
$pin_code      = isset($_POST['pin_code']) ? trim($_POST['pin_code']) : null;

// Prepare the SQL statement to insert a new address
$sql = "INSERT INTO customer_address (customer_id, address_line1, address_line2, state, city, pin_code) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare statement: ' . $conn->error
    ]);
    exit();
}

$stmt->bind_param("isssss", $customer_id, $address_line1, $address_line2, $state, $city, $pin_code);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Address added successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to add address: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
