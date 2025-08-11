<?php
require_once("database.php");
header("Content-Type: application/json");

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("
        SELECT 
            product_id,
            product_name,
            product_buy_price,
            product_sell_price,
            product_quantity,
            product_image,
            product_percentage_discount,
            product_desc
        FROM products
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'product_id' => (int)$row['product_id'],
            'product_name' => $row['product_name'],
            'product_buy_price' => (float)$row['product_buy_price'],
            'product_sell_price' => (float)$row['product_sell_price'],
            'product_quantity' => (int)$row['product_quantity'],
            'product_image' => $row['product_image'],
            'product_percentage_discount' => $row['product_percentage_discount'] !== null 
                ? (float)$row['product_percentage_discount'] 
                : null,
            'product_desc' => $row['product_desc']
        ];
    }

    echo json_encode([
        'status' => 'success',
        'products' => $products
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>