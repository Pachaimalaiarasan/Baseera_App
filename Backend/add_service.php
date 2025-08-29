<?php
header('Content-Type: application/json');
require_once("database.php");

// The main requirement is the service name. The image is optional.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $imagePath = null; // Default image path is null

    $conn->begin_transaction(); // Start a transaction

    // Step 1: Insert the service name to get an ID
    $sql = "INSERT INTO services (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        $serviceId = $stmt->insert_id;

        // Step 2: Check if an image was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image'];
            // Use a simple, clean path. The base URL will be added on the frontend or when fetching.
            $uploadDir = "uploads/services/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            // Create a unique filename to avoid overwrites
            $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $uniqueFileName = $serviceId . '_' . time() . '.' . $fileExtension;
            $imagePath = $uploadDir . $uniqueFileName;

            // Step 3: Save the image
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                // Step 4: Update the service with the image path
                $sqlUpdate = "UPDATE services SET image = ? WHERE service_id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("si", $imagePath, $serviceId);
                if (!$stmtUpdate->execute()) {
                    $conn->rollback();
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update image path.']);
                    exit;
                }
            } else {
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
                exit;
            }
        }

        // If we reach here, everything was successful
        $conn->commit();
        $serviceData = [
            "service_id" => $serviceId,
            "name" => $name,
            "imagePath" => $imagePath
        ];
        echo json_encode(['status' => 'success', 'service' => $serviceData]);

    } else {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request: Name is required.']);
}

$conn->close();
?>