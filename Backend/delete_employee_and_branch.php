<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_id']) && isset($_POST['employee_id'])) {
    $branchId = $_POST['branch_id'];
    $employeeId = $_POST['employee_id'];

    $sql = "DELETE FROM emp_and_branch WHERE branch_id = ? AND employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $branchId, $employeeId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mapping deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
