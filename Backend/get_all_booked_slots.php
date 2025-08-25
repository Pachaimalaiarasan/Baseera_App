<?php
// get_all_booked_slots.php
header('Content-Type: application/json');
include 'database.php';

$stmt = $conn->prepare("SELECT booked_slot_id, employee_id, slot_id, date FROM booked_slots");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_slots = array();
    while ($row = $result->fetch_assoc()) {
        $booked_slots[] = $row;
    }

    echo json_encode(array("status" => "success", "booked_slots" => $booked_slots));
    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Failed to prepare statement"));
}

$conn->close();
?>
