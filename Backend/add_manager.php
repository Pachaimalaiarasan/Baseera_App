<?php
require_once("database.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['managerName']) &&
    isset($_POST['managerPhone']) &&
    isset($_POST['managerEmail'])) {

    $name  = $_POST['managerName'];
    $phone = $_POST['managerPhone'];
    $email = $_POST['managerEmail'];

    // Begin transaction
    $conn->autocommit(FALSE);

    try {
        // Insert manager details into the managers table.
        $sql = "INSERT INTO managers (manager_name, manager_phone, manager_email, branch_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        // Note: branch_id can be null if not provided.
        $branch_id = isset($_POST['branch_id']) ? (int)$_POST['branch_id'] : null;
        $stmt->bind_param("sssi", $name, $phone, $email, $branch_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $managerId = $stmt->insert_id;
        $stmt->close();

        // Insert a record into the login table with default password set as phone.
        $defaultPassword = $phone;
        $hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);
        $role = "manager";
        $sqlLogin = "INSERT INTO login (username, email, phone, password, role, manager_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtLogin = $conn->prepare($sqlLogin);
        if (!$stmtLogin) {
            throw new Exception("Prepare failed (login): " . $conn->error);
        }
        $stmtLogin->bind_param("sssssi", $name, $email, $phone, $hashedPassword, $role, $managerId);
        if (!$stmtLogin->execute()) {
            throw new Exception("Execute failed (login): " . $stmtLogin->error);
        }
        $stmtLogin->close();

        $conn->commit();

        $managerData = array(
            "manager_id"    => $managerId,
            "manager_name"  => $name,
            "manager_phone" => $phone,
            "manager_email" => $email,
            "branch_id"     => $branch_id
        );
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Manager added successfully',
            'manager' => $managerData
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}

$conn->close();
?>
