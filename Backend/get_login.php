<?php
header("Content-Type: application/json");

// Include database connection
require_once("database.php");

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input: require a password and at least one of email, phone, or username.
if (
    is_null($data) || 
    !isset($data['password']) || 
    (!isset($data['username']) && !isset($data['email']) && !isset($data['phone']))
) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// Determine which field is provided. We use email first, then phone, then username.
if (isset($data['email'])) {
    $loginField = "email";
    $loginValue = $data['email'];
} elseif (isset($data['phone'])) {
    $loginField = "phone";
    $loginValue = $data['phone'];
} else {
    $loginField = "username";
    $loginValue = $data['username'];
}

$password = $data['password'];

// (Optional) Ensure that the loginField is one of the allowed columns.
$allowedFields = ['username', 'email', 'phone'];
if (!in_array($loginField, $allowedFields)) {
    echo json_encode(["status" => "error", "message" => "Invalid login field"]);
    exit;
}

// Build and prepare the SQL query.
// Since $loginField comes from our controlled list, it is safe to insert directly.
$sql = "SELECT * FROM login WHERE $loginField = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loginValue);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $storedPassword = $user['password'];

    // Check if the provided password matches the stored password.
    if (password_verify($password, $storedPassword) || md5($password) === $storedPassword) {
        // Generate a token.
        $token = base64_encode(random_bytes(32));

        // Prepare the response with role and token.
        $response = [
            "status" => "success",
            "role" => $user['role'],
            "token" => $token,
            "email" => $user['email']
        ];

        // If the user is a customer, include the customer_id.
        if ($user['role'] === 'customer' && isset($user['customer_id'])) {
            $response['customer_id'] = $user['customer_id'];
        }

        // If the user is a customer, include the customer_id.
        if ($user['role'] === 'employee' && isset($user['employee_id'])) {
            $response['employee_id'] = $user['employee_id'];
        }

        // If the user is a manager, include the manager_id.
        if ($user['role'] === 'manager' && isset($user['manager_id'])) {
            $response['manager_id'] = $user['manager_id'];
        }

        echo json_encode($response);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

// Close the statement and connection.
$stmt->close();
$conn->close();
?>
