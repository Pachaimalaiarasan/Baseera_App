<?php
header('Content-Type: application/json');
include 'database.php'; // Ensure this file defines openConnection()


// Retrieve POST parameters
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : null;
$product_buy_price = isset($_POST['product_buy_price']) ? $_POST['product_buy_price'] : null;
$product_sell_price = isset($_POST['product_sell_price']) ? $_POST['product_sell_price'] : null;
$product_quantity = isset($_POST['product_quantity']) ? $_POST['product_quantity'] : null;
$product_percentage_discount = isset($_POST['product_percentage_discount']) && $_POST['product_percentage_discount'] !== "" 
    ? $_POST['product_percentage_discount'] 
    : null;
$product_desc = isset($_POST['product_desc']) ? $_POST['product_desc'] : null;

// Validate required parameters
if ($product_id === null || $product_name === null || $product_buy_price === null || $product_sell_price === null || $product_quantity === null) {
    echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
    exit;
}

// Prepare the update SQL statement
// The query updates: product_name, product_buy_price, product_sell_price, product_quantity, product_percentage_discount, product_desc
$query = "UPDATE products SET product_name = ?, product_buy_price = ?, product_sell_price = ?, product_quantity = ?, product_percentage_discount = ?, product_desc = ? WHERE product_id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

// For binding, we need to decide on the parameter types:
// product_name: string (s)
// product_buy_price: double (d)
// product_sell_price: double (d)
// product_quantity: integer (i)
// product_percentage_discount: double (d) – if null, we'll pass null (see below)
// product_desc: string (s)
// product_id: integer (i)
//
// MySQLi's bind_param does not support binding null for a "d" type directly. 
// One workaround is to check if product_percentage_discount is null, and if so, set it to null using mysqli_stmt::bind_param followed by mysqli_stmt::send_long_data, 
// or update the query to use IFNULL. For simplicity, here we assume that if discount is not provided, we set it to NULL via a conditional update.

if ($product_percentage_discount === null) {
    // We'll bind a NULL by using a variable with a null value.
    $discount = null;
    // We must adjust the query if we want to update the column to NULL. One approach is to remove this column from binding and update it separately.
    // For simplicity, we'll assume that an empty discount should be 0.0.
    $discount = 0.0;
} else {
    $discount = (double)$product_percentage_discount;
}

// Cast numeric values appropriately
$product_buy_price = (double)$product_buy_price;
$product_sell_price = (double)$product_sell_price;
$product_quantity = (int)$product_quantity;
$product_id = (int)$product_id;

// Bind parameters. The format string is "sddidsi":
// s: product_name
// d: product_buy_price
// d: product_sell_price
// i: product_quantity
// d: product_percentage_discount
// s: product_desc
// i: product_id
$bind = $stmt->bind_param(
    "sddidsi",
    $product_name,
    $product_buy_price,
    $product_sell_price,
    $product_quantity,
    $discount,
    $product_desc,
    $product_id
);

if (!$bind) {
    echo json_encode(["status" => "error", "message" => "Binding parameters failed: " . $stmt->error]);
    exit;
}

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Product updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Product update failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
