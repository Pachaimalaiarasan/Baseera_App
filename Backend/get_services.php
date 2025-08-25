<?php
// Enable error reporting for debugging during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("database.php");

// Define the base URL to prepend to the image path
$base_url = "https://app.baseeragarden.in/mohamedapp";

// Prepare the SQL query to fetch all services
$sql = "SELECT service_id, name, description, image FROM services";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $services = [];
        while ($row = $result->fetch_assoc()) {
            // Prepend the base URL to the image path
            if (!empty($row['image'])) {
                $row['image'] = $base_url . $row['image'];
            } else {
                $row['image'] = null; // If no image, set it to null
            }

            $services[] = $row;
        }
        echo json_encode([
            'status' => 'success',
            'services' => $services
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No services found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
