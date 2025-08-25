<?php
header('Content-Type: application/json');
include 'database.php';

// Retrieve the product_id from the POST request
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

if ($product_id === null) {
    echo json_encode(["status" => "error", "message" => "Missing product id"]);
    exit;
}

// Prepare and execute the delete query
$query = "DELETE FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Product deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete product"]);
}

$stmt->close();
$conn->close();
?>
