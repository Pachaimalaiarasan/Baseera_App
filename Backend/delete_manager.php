<?php
require_once("database.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['manager_id'])) {
    $managerId = $data['manager_id'];

    $conn->autocommit(FALSE);

    try {
        // Delete from managers table.
        $stmt = $conn->prepare("DELETE FROM managers WHERE manager_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed (managers): " . $conn->error);
        }
        $stmt->bind_param("i", $managerId);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed (managers): " . $stmt->error);
        }
        $stmt->close();

        // Delete from login table where manager_id matches.
        $stmt2 = $conn->prepare("DELETE FROM login WHERE manager_id = ?");
        if (!$stmt2) {
            throw new Exception("Prepare failed (login): " . $conn->error);
        }
        $stmt2->bind_param("i", $managerId);
        if (!$stmt2->execute()) {
            throw new Exception("Execute failed (login): " . $stmt2->error);
        }
        $stmt2->close();

        // Commit the transaction.
        $conn->commit();
        echo json_encode([
            "status" => "success", 
            "message" => "Manager and associated login record deleted successfully"
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            "status" => "error", 
            "message" => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Missing manager_id"
    ]);
}

$conn->close();
?>
