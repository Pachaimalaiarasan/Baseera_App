<?php
header('Content-Type: application/json');
require_once 'database.php';

$required = ['plan_id', 'service_id', 'plan_name', 'plan_price'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "$field is required"]);
        exit;
    }
}

$stmt = $conn->prepare("UPDATE serviceplans SET service_id = ?, plan_name = ?, plan_price = ? WHERE plan_id = ?");
$stmt->bind_param("issi", $_POST['service_id'], $_POST['plan_name'], $_POST['plan_price'], $_POST['plan_id']);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

exit;
?>