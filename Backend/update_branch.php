<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_id']) && isset($_POST['branch_name'])) {
    $branchId = $_POST['branch_id'];
    $branchName = $_POST['branch_name'];
    $branchCity = isset($_POST['branch_city']) ? $_POST['branch_city'] : null;
    $branchPhone = isset($_POST['branch_phone']) ? $_POST['branch_phone'] : null;

    $sql = "UPDATE branch SET branch_name = ?, branch_city = ?, branch_phone = ? WHERE branch_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $branchName, $branchCity, $branchPhone, $branchId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Branch updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
