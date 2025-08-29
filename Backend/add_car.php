<?php
require_once("database.php"); // Make sure $conn is set here!

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['carNumber']) && isset($_POST['carName']) && isset($_POST['customerId'])) {
    $carNumber = $_POST['carNumber'];
    $carName = $_POST['carName'];
    $customerId = $_POST['customerId'];
    $image = isset($_FILES['carImage']) && $_FILES['carImage']['error'] === UPLOAD_ERR_OK ? $_FILES['carImage'] : null;

    // Step 1: Insert car details into the database (without image)
    $sql = "INSERT INTO cars (car_number, car_name, customer_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $carNumber, $carName, $customerId);

    if ($stmt->execute()) {
        $carId = $stmt->insert_id; // Get the newly inserted car's ID

        // Step 2: Handle car image upload (if provided)
        if ($image) {
            $uploadDir = __DIR__ . "/uploads/cars/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create folder recursively
            }
            $imageFileName = uniqid() . '_' . basename($image['name']);
            $imagePath = "uploads/cars/" . $imageFileName;
            $fullPath = $uploadDir . $imageFileName;
            if (move_uploaded_file($image['tmp_name'], $fullPath)) {
                // Step 3: Update car record with image path (store relative path)
                $sqlUpdate = "UPDATE cars SET car_image = ? WHERE car_id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("si", $imagePath, $carId);
                if (!$stmtUpdate->execute()) {
                    echo json_encode(['status' => 'error', 'message' => $conn->error]);
                    exit();
                }
                $stmtUpdate->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
                exit();
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Car added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
