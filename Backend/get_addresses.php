<?php
header('Content-Type: application/json');

// Include your database connection file
include 'database.php';

// Check if the customer_id parameter is provided
if (!isset($_GET['customer_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'customer_id is required'
    ]);
    exit();
}

$customerId = intval($_GET['customer_id']);

// Prepare SQL statement to fetch addresses for the given customer_id
$sql = "SELECT address_id, customer_id, address_line1, address_line2, state, city, pin_code FROM customer_address WHERE customer_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare statement: ' . $conn->error
    ]);
    exit();
}

$stmt->bind_param("i", $customerId);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to execute statement: ' . $stmt->error
    ]);
    exit();
}

$result = $stmt->get_result();
$addresses = [];

while ($row = $result->fetch_assoc()) {
    $addresses[] = $row;
}

$stmt->close();
$conn->close();

// Return the addresses as JSON
echo json_encode([
    'status' => 'success',
    'addresses' => $addresses
]);
?>
