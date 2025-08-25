<?php
require_once("database.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$stmt = null;
$updateStmt = null;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Method not allowed", 405);
    }

    // Validate required fields
    $requiredFields = [
        'product_name' => 'Product name',
        'product_buy_price' => 'Buy price',
        'product_sell_price' => 'Sell price',
        'product_quantity' => 'Quantity'
    ];
    foreach ($requiredFields as $field => $name) {
        if (empty($_POST[$field])) {
            throw new Exception("$name is required", 400);
        }
    }

    // Prepare variables
    $product_name = trim($_POST['product_name']);
    $buy_price = (float)$_POST['product_buy_price'];
    $sell_price = (float)$_POST['product_sell_price'];
    $quantity = (int)$_POST['product_quantity'];
    $percentage_discount = null;
    $product_desc = null;

    if (!empty($_POST['product_percentage_discount'])) {
        $percentage_discount = (float)$_POST['product_percentage_discount'];
        if ($percentage_discount < 0 || $percentage_discount > 100) {
            throw new Exception("Discount must be between 0 and 100", 400);
        }
    }

    if (!empty($_POST['product_desc'])) {
        $product_desc = trim($_POST['product_desc']);
    }

    $conn->begin_transaction();

    // Insert main product data
    $stmt = $conn->prepare("
        INSERT INTO products (
            product_name,
            product_buy_price,
            product_sell_price,
            product_quantity,
            product_percentage_discount,
            product_desc
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error, 500);
    }

    $stmt->bind_param(
        "sddids",
        $product_name,
        $buy_price,
        $sell_price,
        $quantity,
        $percentage_discount,
        $product_desc
    );

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error, 500);
    }
    $productId = $conn->insert_id;
    $stmt->close();
    $stmt = null; // Mark as closed

    // Handle image upload
    $targetPath = null;
    if (!empty($_FILES['product_image']['tmp_name'])) {
        $file = $_FILES['product_image'];
        
        // Validate image
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        if (!array_key_exists($mime, $allowed_types)) {
            throw new Exception("Only JPG/PNG images allowed", 400);
        }

        // Create upload directory
        $uploadDir = "uploads/products/$productId/";
        if (!file_exists($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create upload directory", 500);
        }

        // Generate safe filename
        $extension = $allowed_types[$mime];
        $filename = uniqid() . ".$extension";
        $targetPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Failed to move uploaded file", 500);
        }

        // Update product with image path
        $updateStmt = $conn->prepare("UPDATE products SET product_image = ? WHERE product_id = ?");
        if (!$updateStmt) {
            throw new Exception("Prepare failed: " . $conn->error, 500);
        }
        $updateStmt->bind_param("si", $targetPath, $productId);
        if (!$updateStmt->execute()) {
            throw new Exception("Image update failed: " . $updateStmt->error, 500);
        }
        $updateStmt->close();
        $updateStmt = null;
    }

    $conn->commit();

    // Prepare complete product data for the response.
    $productData = array(
        "product_id" => $productId,
        "product_name" => $product_name,
        "product_buy_price" => $buy_price,
        "product_sell_price" => $sell_price,
        "product_quantity" => $quantity,
        "product_percentage_discount" => $percentage_discount,
        "product_desc" => $product_desc,
        "product_image" => $targetPath
    );
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Product added successfully',
        'product' => $productData
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->rollback();
    }
} finally {
    if (isset($stmt) && $stmt !== null && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    if (isset($updateStmt) && $updateStmt !== null && $updateStmt instanceof mysqli_stmt) {
        $updateStmt->close();
    }
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
