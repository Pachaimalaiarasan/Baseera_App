<?php
require_once("database.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['manager_id'])) {
    $stmt = $conn->prepare("UPDATE managers SET manager_name = ?, manager_email = ?, manager_phone = ?, branch_id = ? WHERE manager_id = ?");
    $stmt->bind_param("sssii", $data['manager_name'], $data['manager_email'], $data['manager_phone'], $data['branch_id'], $data['manager_id']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Manager updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update manager"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing manager_id"]);
}
?>
