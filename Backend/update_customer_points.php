<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['customer_id']) && isset($_POST['redeemable_points'])) {
        $customer_id = intval($_POST['customer_id']);
        $redeemable_points = intval($_POST['redeemable_points']);

        $stmt = $conn->prepare("UPDATE customers SET redeemable_points = ? WHERE customer_id = ?");
        if (!$stmt) {
            echo json_encode([
                "status" => "error",
                "message" => "Prepare failed: " . $conn->error
            ]);
            $conn->close();
            exit;
        }

        $stmt->bind_param("ii", $redeemable_points, $customer_id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Customer points updated successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update points: " . $stmt->error
            ]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Missing required parameters"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method"
    ]);
}
?>
