<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_POST['name'])) {
    $name = $_POST['name'];
    $image = $_FILES['image'];

    // Step 1: Insert the service name into the database to get the service ID
    $sql = "INSERT INTO services (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        $serviceId = $stmt->insert_id; // Get the newly inserted service's ID

        // Step 2: Create a directory for the service
        $uploadDir = "uploads/services/$serviceId/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Step 3: Save the image to the new directory
        $imagePath = $uploadDir . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Step 4: Update the service record with the image path
            $sqlUpdate = "UPDATE services SET image = ? WHERE service_id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("si", $imagePath, $serviceId);
            if ($stmtUpdate->execute()) {
                // Return the new service data in JSON format
                $serviceData = array(
                    "service_id" => $serviceId,
                    "name" => $name,
                    "imagePath" => $imagePath
                );
                echo json_encode(['status' => 'success', 'service' => $serviceData]);
            } else {
                echo json_encode(['status' => 'error', 'message' => $conn->error]);
            }
            $stmtUpdate->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
