<?php
// get_booked_slots.php
header('Content-Type: application/json');
include 'database.php';

if (isset($_GET['employee_id']) && isset($_GET['date'])) {
    $employee_id = intval($_GET['employee_id']);
    $date = $_GET['date'];

    $stmt = $conn->prepare("SELECT slot_id FROM booked_slots WHERE employee_id = ? AND date = ?");
    $stmt->bind_param("is", $employee_id, $date);
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
