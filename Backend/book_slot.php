<?php
header('Content-Type: application/json');
include 'database.php';
if (isset($_POST['employee_id']) && isset($_POST['slot_id']) && isset($_POST['date'])) {
    $employee_id = intval($_POST['employee_id']);
    $slot_id = intval($_POST['slot_id']);
    $date = $_POST['date'];

    $conn->begin_transaction();

    // Check if the slot is already booked
    $stmt = $conn->prepare("SELECT booked_slot_id FROM booked_slots WHERE employee_id = ? AND slot_id = ? AND date = ?");
    $stmt->bind_param("iis", $employee_id, $slot_id, $date);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Slot already booked"]);
        exit;
    }
    $stmt->close();

    // Insert the booking
    $stmt = $conn->prepare("INSERT INTO booked_slots (employee_id, slot_id, date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $employee_id, $slot_id, $date);
    if ($stmt->execute()) {
        $newBookedSlotId = $stmt->insert_id; // Capture the auto-incremented ID
        $conn->commit();
        echo json_encode([
            "status" => "success",
            "message" => "Slot booked successfully",
            "booked_slot_id" => $newBookedSlotId
        ]);
    } else {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Failed to book slot"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
}
$conn->close();
?>