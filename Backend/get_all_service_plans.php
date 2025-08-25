<?php
// get_all_service_plans.php
header('Content-Type: application/json');
include 'database.php';

// Run the query
$sql = "SELECT * FROM serviceplans ORDER BY plan_id DESC";
$result = $conn->query($sql);

// Check if query executed successfully
if (!$result) {
    die(json_encode(["status" => "error", "message" => "SQL Query Failed: " . $conn->error]));
}

// Fetch results
$plans = [];
while ($row = $result->fetch_assoc()) {
    $plans[] = $row;
}

// Return JSON response
if (!empty($plans)) {
    echo json_encode(["status" => "success", "plans" => $plans]);
} else {
    echo json_encode(["status" => "error", "message" => "No service plans found."]);
}
$conn->close();
?>
