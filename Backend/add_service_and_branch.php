<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_id']) && isset($_POST['service_id'])) {
    $branchId = $_POST['branch_id'];
    $serviceId = $_POST['service_id'];

    $sql = "INSERT INTO service_and_branch (branch_id, service_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $branchId, $serviceId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Mapping added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
