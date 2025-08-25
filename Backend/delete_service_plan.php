<?php
header('Content-Type: application/json');
require_once 'database.php';

if (!isset($_POST['plan_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Plan ID is required']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM serviceplans WHERE plan_id = ?");
$stmt->bind_param("i", $_POST['plan_id']);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

exit;
?>