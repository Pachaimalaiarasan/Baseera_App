<?php
header('Content-Type: application/json');
require_once 'database.php';

$required = ['service_id', 'plan_name', 'plan_price'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "$field is required"]);
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO serviceplans (service_id, plan_name, plan_price) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $_POST['service_id'], $_POST['plan_name'], $_POST['plan_price']);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

exit;
?>