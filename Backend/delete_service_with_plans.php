<?php
header('Content-Type: application/json');
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['service_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$serviceId = $_POST['service_id'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Step 0: Check if any order has been placed for the service
    $orderCheck = $conn->prepare("SELECT COUNT(*) as orderCount FROM orders WHERE service_id = ?");
    $orderCheck->bind_param("i", $serviceId);
    if (!$orderCheck->execute()) {
        throw new Exception($conn->error);
    }
    $orderResult = $orderCheck->get_result();
    $orderData = $orderResult->fetch_assoc();
    if ($orderData['orderCount'] > 0) {
        throw new Exception("Cannot delete service because orders have already been placed for this service.");
    }
    $orderCheck->close();

    // Step 1: Get the service's image path
    $selectService = $conn->prepare("SELECT image FROM services WHERE service_id = ?");
    $selectService->bind_param("i", $serviceId);
    if (!$selectService->execute()) {
        throw new Exception($conn->error);
    }
    $serviceResult = $selectService->get_result();
    $serviceData = $serviceResult->fetch_assoc();
    $imagePath = $serviceData['image'];
    $selectService->close();

    // Step 2: Delete associated service plans
    $deletePlans = $conn->prepare("DELETE FROM serviceplans WHERE service_id = ?");
    $deletePlans->bind_param("i", $serviceId);
    if (!$deletePlans->execute()) {
        throw new Exception($conn->error);
    }
    $deletePlans->close();

    // Step 3: Delete the mapping from service_and_branch table
    $deleteMapping = $conn->prepare("DELETE FROM service_and_branch WHERE service_id = ?");
    $deleteMapping->bind_param("i", $serviceId);
    if (!$deleteMapping->execute()) {
        throw new Exception($conn->error);
    }
    $deleteMapping->close();

    // Step 4: Delete the service record
    $deleteService = $conn->prepare("DELETE FROM services WHERE service_id = ?");
    $deleteService->bind_param("i", $serviceId);
    if (!$deleteService->execute()) {
        throw new Exception($conn->error);
    }
    $deleteService->close();

    // Commit transaction if all queries succeed
    $conn->commit();

    // Step 5: Delete the service image file from the filesystem if it exists
    if (!empty($imagePath) && file_exists($imagePath)) {
        unlink($imagePath);
    }

    echo json_encode([
        'status' => 'success', 
        'message' => 'Service, associated plans, and mappings deleted successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on any error
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => 'Deletion failed: ' . $e->getMessage()
    ]);
} finally {
    $conn->close();
}
exit;
?>
