<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['carId']) && isset($_POST['carNumber']) && isset($_POST['carName']) && isset($_POST['customerId'])) {
    $carId = $_POST['carId'];
    $carNumber = $_POST['carNumber'];
    $carName = $_POST['carName'];
    $customerId = $_POST['customerId'];
    $image = isset($_FILES['carImage']) ? $_FILES['carImage'] : null;

    // Step 1: Update car details (without image)
    $sql = "UPDATE cars SET car_number = ?, car_name = ?, customer_id = ? WHERE car_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $carNumber, $carName, $customerId, $carId);

    if ($stmt->execute()) {
        // Step 2: If image provided, handle image upload and update image path
        if ($image) {
            $uploadDir = "uploads/cars/$carId/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . basename($image['name']);
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
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
        echo json_encode(['status' => 'success', 'message' => 'Car updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
