<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug incoming data
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    $employeeId = $_POST['employeeId'] ?? null; // Match the field name from your Dart code
    $name = $_POST['employeeName'] ?? null; // Match the field name from your Dart code
    $phone = $_POST['employeePhone'] ?? null; // Match the field name from your Dart code
    $email = $_POST['employeeEmail'] ?? null; // Match the field name from your Dart code

    if (!$employeeId || !$name || !$phone || !$email) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Update basic employee information
        $sql = "UPDATE employees SET employee_name = ?, employee_phone = ?, employee_email = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $phone, $email, $employeeId);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update employee details: " . $conn->error);
        }

        // Handle image upload if present
        if (isset($_FILES['employeeImage']) && $_FILES['employeeImage']['error'] == 0) {
            $uploadDir = "uploads/employees/{$employeeId}/";
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception("Failed to create upload directory");
                }
            }

            // Generate unique filename
            $imageExtension = pathinfo($_FILES['employeeImage']['name'], PATHINFO_EXTENSION);
            $imagePath = $uploadDir . "profile." . $imageExtension;

            // Remove old image if exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Upload new image
            if (!move_uploaded_file($_FILES['employeeImage']['tmp_name'], $imagePath)) {
                throw new Exception("Failed to move uploaded file");
            }

            // Update image path in database
            $sqlImage = "UPDATE employees SET employee_image = ? WHERE employee_id = ?";
            $stmtImage = $conn->prepare($sqlImage);
            $stmtImage->bind_param("si", $imagePath, $employeeId);
            
            if (!$stmtImage->execute()) {
                throw new Exception("Failed to update image path: " . $conn->error);
            }
            
            $stmtImage->close();
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    } finally {
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>