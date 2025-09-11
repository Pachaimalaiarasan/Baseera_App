<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type to application/json for consistent API responses
header('Content-Type: application/json');

require_once("database.php");

// --- FIX 1: Define the base URL for your images ---
// This should be the path to the folder where your service images are stored.
// $base_url = "https://app.baseeragarden.in/mohamedapp/Uploads/services/"; // Example path
$base_url = "http://localhost/baseera_app/Backend/";

// --- FIX 2: Get the branch_id from the URL parameter sent by the frontend ---
$branch_id = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : 0;

if ($branch_id === 0) {
    // If no branch_id is provided, return an error.
    echo json_encode(['status' => 'error', 'message' => 'Branch ID not provided or invalid.']);
    exit;
}

// --- FIX 3: Modify the SQL query to filter services by branch_id ---
$sql = "SELECT service_id, name, description, image FROM services WHERE branch_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// --- FIX 4: Bind the branch_id parameter to the query ---
$stmt->bind_param("i", $branch_id); // "i" means the parameter is an integer

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $services = []; // Initialize as an empty array

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Prepend the base URL to the image path if an image exists
            if (!empty($row['image'])) {
                $row['image'] = $base_url . $row['image'];
            } else {
                $row['image'] = null; // Set to null if there is no image
            }
            $services[] = $row;
        }
    }
    
    // Always return a success status, even if no services are found (an empty array is valid)
    echo json_encode([
        'status' => 'success',
        'services' => $services
    ]);

} else {
    // If the query execution fails
    echo json_encode(['status' => 'error', 'message' => 'Execution failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>