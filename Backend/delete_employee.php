<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'])) {
    $employeeId = $_POST['employee_id'];

    // Begin a transaction to ensure both deletions occur safely.
    $conn->autocommit(FALSE);

    try {
        // Step 1: Retrieve the employee's image path before deleting.
        $sqlGetImage = "SELECT employee_image FROM employees WHERE employee_id = ?";
        $stmtGetImage = $conn->prepare($sqlGetImage);
        if (!$stmtGetImage) {
            throw new Exception("Prepare failed (get image): " . $conn->error);
        }
        $stmtGetImage->bind_param("i", $employeeId);
        $stmtGetImage->execute();
        $result = $stmtGetImage->get_result();
        $stmtGetImage->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imagePath = $row['employee_image'];

            // Step 2: Delete the corresponding record from the login table.
            $sqlDeleteLogin = "DELETE FROM login WHERE employee_id = ?";
            $stmtDeleteLogin = $conn->prepare($sqlDeleteLogin);
            if (!$stmtDeleteLogin) {
                throw new Exception("Prepare failed (delete login): " . $conn->error);
            }
            $stmtDeleteLogin->bind_param("i", $employeeId);
            if (!$stmtDeleteLogin->execute()) {
                throw new Exception("Failed to delete login record: " . $stmtDeleteLogin->error);
            }
            $stmtDeleteLogin->close();

            // Step 3: Delete the employee record from the employees table.
            $sqlDeleteEmployee = "DELETE FROM employees WHERE employee_id = ?";
            $stmtDeleteEmployee = $conn->prepare($sqlDeleteEmployee);
            if (!$stmtDeleteEmployee) {
                throw new Exception("Prepare failed (delete employee): " . $conn->error);
            }
            $stmtDeleteEmployee->bind_param("i", $employeeId);
            if (!$stmtDeleteEmployee->execute()) {
                throw new Exception("Failed to delete employee record: " . $stmtDeleteEmployee->error);
            }
            $stmtDeleteEmployee->close();

            // Step 4: Remove the employee image file (if exists) and its directory.
            if ($imagePath && file_exists($imagePath)) {
                unlink($imagePath);
            }
            $uploadDir = "uploads/employees/$employeeId/";
            if (is_dir($uploadDir)) {
                rmdir($uploadDir);
            }

            // Commit the transaction after all steps succeed.
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Employee not found']);
        }
    } catch (Exception $e) {
        // Roll back the transaction if any error occurs.
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
