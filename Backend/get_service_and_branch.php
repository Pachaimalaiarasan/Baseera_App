<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
require_once("database.php");

// --- Correct: Use many-to-many logic ---
// $base_url = "https://app.baseeragarden.in/mohamedapp/Uploads/services/";
$base_url = "http://localhost/baseera_app/Backend/";

$branch_id = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : 0;
if ($branch_id === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Branch ID not provided or invalid.']);
    exit;
}

// JOIN the mapping table and the services table:
$sql = "SELECT s.service_id, s.name, s.description, s.image 
        FROM services s
        INNER JOIN service_and_branch sab ON sab.service_id = s.service_id
        WHERE sab.branch_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $branch_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = !empty($row['image']) ? $base_url . $row['image'] : null;
        $services[] = $row;
    }
    echo json_encode(['status' => 'success', 'services' => $services]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Execution failed: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
?>
