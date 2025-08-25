<?php
header('Content-Type: application/json');

// Error Logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/htdocs/car_service_api/logs/php_error.log');

require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['branch_id'])) {
    $branchId = $_POST['branch_id'];
    $conn->begin_transaction();

    try {
        // Step 0: Check for active orders
        $sql = "SELECT COUNT(*) as orderCount FROM orders WHERE branch_id = ? AND order_status NOT IN ('Completed', 'Rejected')";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['orderCount'] > 0) {
            $conn->rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Cannot delete branch: active orders exist for this branch'
            ]);
            exit;
        }

        // Step 1: Delete Completed/Rejected orders
        $sql = "DELETE FROM orders WHERE branch_id = ? AND order_status IN ('Completed', 'Rejected')";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (orders): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $stmt->close();

        // Step 2: Delete employees and their booked slots
        $sql = "SELECT employee_id FROM emp_and_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (fetch employees): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $employeeIds = [];
        while ($row = $result->fetch_assoc()) {
            $employeeIds[] = $row['employee_id'];
        }
        $stmt->close();

        if (!empty($employeeIds)) {
            $in = implode(',', array_fill(0, count($employeeIds), '?'));
            $types = str_repeat('i', count($employeeIds));

            // 2.1 Delete booked slots of these employees
            $sql = "DELETE FROM booked_slots WHERE employee_id IN ($in)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed (delete booked_slots): " . $conn->error);
            $stmt->bind_param($types, ...$employeeIds);
            $stmt->execute();
            $stmt->close();

            // 2.2 Delete from employees
            $sql = "DELETE FROM employees WHERE employee_id IN ($in)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed (delete employees): " . $conn->error);
            $stmt->bind_param($types, ...$employeeIds);
            $stmt->execute();
            $stmt->close();
        }

        // 2.3 Finally delete from emp_and_branch
        $sql = "DELETE FROM emp_and_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (emp_and_branch): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $stmt->close();


        // Step 3: Delete products linked via product_and_branch, then from products
        $sql = "SELECT product_id FROM product_and_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (fetch products): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $productIds = [];
        while ($row = $result->fetch_assoc()) {
            $productIds[] = $row['product_id'];
        }
        $stmt->close();

        if (!empty($productIds)) {
            $in = implode(',', array_fill(0, count($productIds), '?'));
            $types = str_repeat('i', count($productIds));
            $sql = "DELETE FROM products WHERE product_id IN ($in)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed (delete products): " . $conn->error);
            $stmt->bind_param($types, ...$productIds);
            $stmt->execute();
            $stmt->close();
        }

        $sql = "DELETE FROM product_and_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (product_and_branch): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $stmt->close();

        // Step 4: Delete services and their plans
        $sql = "SELECT service_id FROM service_and_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (fetch services): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $serviceIds = [];
        while ($row = $result->fetch_assoc()) {
            $serviceIds[] = $row['service_id'];
        }
        $stmt->close();

        if (!empty($serviceIds)) {
            $in = implode(',', array_fill(0, count($serviceIds), '?'));
            $types = str_repeat('i', count($serviceIds));

            // Delete service plans
            $sql = "DELETE FROM serviceplans WHERE service_id IN ($in)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed (delete serviceplans): " . $conn->error);
            $stmt->bind_param($types, ...$serviceIds);
            $stmt->execute();
            $stmt->close();

            // Delete services
            $sql = "DELETE FROM services WHERE service_id IN ($in)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed (delete services): " . $conn->error);
            $stmt->bind_param($types, ...$serviceIds);
            $stmt->execute();
            $stmt->close();
        }

        $sql = "DELETE FROM service_and_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (service_and_branch): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $stmt->close();

        // Step 5: Delete managers
        $sql = "DELETE FROM managers WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (managers): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $stmt->close();

        // Step 6: Delete the branch
        $sql = "DELETE FROM branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed (branch): " . $conn->error);
        $stmt->bind_param("i", $branchId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $conn->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Branch and all related data deleted successfully'
            ]);
        } else {
            $conn->rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Branch not found or already deleted'
            ]);
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Deletion failed: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Deletion failed: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}

$conn->close();
?>
