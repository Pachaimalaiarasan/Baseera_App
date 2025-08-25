<?php
require 'database.php'; // Ensure this file contains your DB connection setup

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if booked_slot_id is provided
    if (isset($_POST['booked_slot_id']) && !empty($_POST['booked_slot_id'])) {
        $booked_slot_id = intval($_POST['booked_slot_id']);

        // Prepare DELETE statement
        $sql = "DELETE FROM booked_slots WHERE booked_slot_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booked_slot_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["status" => "success", "message" => "Booked slot deleted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "No booked slot found with this ID"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid or missing booked_slot_id"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>
