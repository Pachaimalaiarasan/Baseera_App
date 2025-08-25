<?php
header('Content-Type: application/json');
require_once 'database.php';

if (!isset($_GET['service_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Service ID is required']);
    exit;
}

$serviceId = intval($_GET['service_id']);
$stmt = $conn->prepare("SELECT * FROM serviceplans WHERE service_id = ?");
$stmt->bind_param("i", $serviceId);
$stmt->execute();
$result = $stmt->get_result();

$plans = [];
while ($row = $result->fetch_assoc()) {
    $plans[] = [
        'plan_id' => $row['plan_id'],
        'service_id' => $row['service_id'],
        'plan_name' => $row['plan_name'],
        'plan_price' => $row['plan_price']
    ];
}

echo json_encode(['status' => 'success', 'plans' => $plans]);
exit;
?>