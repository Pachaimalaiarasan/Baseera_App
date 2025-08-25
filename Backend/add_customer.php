<?php
header("Content-Type: application/json");

// Include database connection
require_once("database.php");

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    is_null($data) || 
    !isset($data['username']) || 
    !isset($data['email']) || 
    !isset($data['phone']) || 
    !isset($data['password'])
) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$username = $data['username'];
$email = $data['email'];
$phone = $data['phone'];
$password = $data['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Check if username, email, or phone already exists in the login table
$sql = "SELECT * FROM login WHERE username = ? OR email = ? OR phone = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "User already exists"]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Begin transaction
$conn->autocommit(FALSE);

try {
    // Insert into customers table
    $sql = "INSERT INTO customers (customer_name, customer_email, customer_phone) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $phone);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    // Get the generated customer_id
    $customer_id = $conn->insert_id;
    $stmt->close();

    // Insert into login table with the customer_id
    $role = 'customer'; // Default role
    $sql = "INSERT INTO login (username, email, phone, password, role, customer_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssi", $username, $email, $phone, $hashedPassword, $role, $customer_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Commit the transaction if both inserts succeeded
    $conn->commit();
    echo json_encode(["status" => "success", "message" => "User registered successfully"]);
} catch (Exception $e) {
    // Roll back the transaction if any error occurs
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => "Failed to register user: " . $e->getMessage()]);
}

// Close the connection
$conn->close();
?>
