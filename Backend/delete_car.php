<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['car_id'])) {
    $carId = $_POST['car_id'];

    // Optionally, you could remove the associated image directory/files here.

    $sql = "DELETE FROM cars WHERE car_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Car deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
