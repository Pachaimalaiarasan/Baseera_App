<?php
header('Content-Type: application/json');
require_once("database.php");

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Prepare the SQL query to select all cars
    $sql = "SELECT * FROM cars";
    $result = $conn->query($sql);

    $cars = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }
    }

    // Return the cars if found; otherwise, an error message
    if (count($cars) > 0) {
        echo json_encode([
            'status' => 'success',
            'cars' => $cars
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No cars found'
        ]);
    }
}

$conn->close();
?>
