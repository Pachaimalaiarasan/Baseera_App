<?php
require_once("database.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_name'])) {
    $branchName = $_POST['branch_name'];
    $branchCity = isset($_POST['branch_city']) ? $_POST['branch_city'] : null;
    $branchPhone = isset($_POST['branch_phone']) ? $_POST['branch_phone'] : null;

    // Prepare and execute the INSERT statement.
    $stmt = $conn->prepare("INSERT INTO branch (branch_name, branch_city, branch_phone) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
        exit();
    }
    $stmt->bind_param("sss", $branchName, $branchCity, $branchPhone);
    
    if ($stmt->execute()) {
        $branchId = $conn->insert_id;
        $stmt->close();
        $stmt = null; // Prevent further closing

        // Prepare branch data to return.
        $branchData = array(
            "branch_id"   => $branchId,
            "branch_name" => $branchName,
            "branch_city" => $branchCity,
            "branch_phone"=> $branchPhone,
            "created_at"  => null,
            "updated_at"  => null
        );
        echo json_encode([
            'status' => 'success', 
            'message' => 'Branch added successfully',
            'branch' => $branchData
        ]);
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        $stmt = null;
        echo json_encode(['status' => 'error', 'message' => $errorMsg]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
