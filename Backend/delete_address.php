<?php
header('Content-Type: application/json');
include 'database.php';

// Validate required POST data
if (!isset($_POST['address_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required field: address_id'
    ]);
    exit();
}

$address_id = intval($_POST['address_id']);

// Prepare SQL statement to delete the address record
$sql = "DELETE FROM customer_address WHERE address_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare statement: ' . $conn->error
    ]);
    exit();
}

$stmt->bind_param("i", $address_id);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Address deleted successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete address: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
