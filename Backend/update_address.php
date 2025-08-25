<?php
header('Content-Type: application/json');
include 'database.php';

// Validate required POST data
if (
    !isset($_POST['address_id']) ||
    !isset($_POST['customer_id']) ||
    !isset($_POST['address_line1']) ||
    !isset($_POST['state']) ||
    !isset($_POST['city'])
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields: address_id, customer_id, address_line1, state, and city are required.'
    ]);
    exit();
}

$address_id    = intval($_POST['address_id']);
$customer_id   = intval($_POST['customer_id']);
$address_line1 = trim($_POST['address_line1']);
$address_line2 = isset($_POST['address_line2']) ? trim($_POST['address_line2']) : null;
$state         = trim($_POST['state']);
$city          = trim($_POST['city']);
$pin_code      = isset($_POST['pin_code']) ? trim($_POST['pin_code']) : null;

// Prepare SQL statement to update the address record
$sql = "UPDATE customer_address 
        SET customer_id = ?, address_line1 = ?, address_line2 = ?, state = ?, city = ?, pin_code = ?
        WHERE address_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare statement: ' . $conn->error
    ]);
    exit();
}

$stmt->bind_param("isssssi", $customer_id, $address_line1, $address_line2, $state, $city, $pin_code, $address_id);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Address updated successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update address: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
