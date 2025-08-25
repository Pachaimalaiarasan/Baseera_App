<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
    isset($_POST['employeeName']) && 
    isset($_POST['employeePhone']) && 
    isset($_POST['employeeEmail'])) {

    $name  = $_POST['employeeName'];
    $phone = $_POST['employeePhone'];
    $email = $_POST['employeeEmail'];
    $image = isset($_FILES['employeeImage']) ? $_FILES['employeeImage'] : null;
    $imagePath = ""; // Initialize image path

    // Begin transaction
    $conn->autocommit(FALSE);

    try {
        // Step 1: Insert employee details into the employees table.
        $sql = "INSERT INTO employees (employee_name, employee_phone, employee_email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $phone, $email);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $employeeId = $stmt->insert_id; // Get the newly inserted employee's ID.
        $stmt->close();

        // Step 2: Handle profile image upload (if provided).
        if ($image) {
            $uploadDir = "uploads/employees/$employeeId/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imagePath = $uploadDir . basename($image['name']);
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                // Update employee record with image path.
                $sqlUpdate = "UPDATE employees SET employee_image = ? WHERE employee_id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                if (!$stmtUpdate) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmtUpdate->bind_param("si", $imagePath, $employeeId);
                if (!$stmtUpdate->execute()) {
                    throw new Exception("Execute failed: " . $stmtUpdate->error);
                }
                $stmtUpdate->close();
            } else {
                throw new Exception("Failed to upload image");
            }
        }

        // Step 3: Insert a record into the login table with a default password.
        $defaultPassword = $phone;
        $hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);
        $role = "employee"; // Default role for employees.
        // Ensure your login table has columns: username, email, phone, password, role, and employee_id.
        $sqlLogin = "INSERT INTO login (username, email, phone, password, role, employee_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtLogin = $conn->prepare($sqlLogin);
        if (!$stmtLogin) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmtLogin->bind_param("sssssi", $name, $email, $phone, $hashedPassword, $role, $employeeId);
        if (!$stmtLogin->execute()) {
            throw new Exception("Execute failed: " . $stmtLogin->error);
        }
        $stmtLogin->close();

        // Commit the transaction if all queries succeeded.
        $conn->commit();

        // Prepare and return the new employee data.
        $employeeData = array(
            "employee_id"    => $employeeId,
            "employee_name"  => $name,
            "employee_phone" => $phone,
            "employee_email" => $email,
            "employee_image" => $imagePath !== "" ? $imagePath : null
        );
        
        echo json_encode(['status' => 'success', 'employee' => $employeeData, 'message' => 'Employee added successfully']);
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
