<?php
// get_booked_slots_for_an_employee.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']);

    // Prepare a statement to select all relevant fields from the booked_slots table for the given employee.
    $stmt = $conn->prepare("SELECT booked_slot_id, slot_id, date, employee_id FROM booked_slots WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_slots = array();
    while ($row = $result->fetch_assoc()) {
        $booked_slots[] = $row;
    }
    
    echo json_encode(array("status" => "success", "booked_slots" => $booked_slots));
    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Missing parameters"));
}

$conn->close();
?>
