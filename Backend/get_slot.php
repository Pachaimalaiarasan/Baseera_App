<?php
// get_slot.php
header('Content-Type: application/json');
include 'database.php';

$sql = "SELECT slot_id, start_time, end_time FROM slots ORDER BY slot_id";
$result = $conn->query($sql);

if ($result) {
    $slots = array();
    while ($row = $result->fetch_assoc()) {
        $slots[] = $row;
    }
    echo json_encode(array("status" => "success", "slots" => $slots));
} else {
    echo json_encode(array("status" => "error", "message" => "Could not fetch slots"));
}

$conn->close();
?>
