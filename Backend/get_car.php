<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['customer_id'])) {
        $customerId = intval($_GET['customer_id']); // Get customer_id from request

        $sql = "SELECT * FROM cars WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cars = array();
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }

        if (count($cars) > 0) {
            echo json_encode(['status' => 'success', 'cars' => $cars]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No cars found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Customer ID required']);
    }
}

$conn->close();
?>
