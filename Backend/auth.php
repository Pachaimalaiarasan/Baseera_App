<?php
header("Content-Type: application/json");
require_once("database.php"); // DB connection

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['action'])) {
    echo json_encode(["status" => "error", "message" => "No action specified"]);
    exit;
}

$action = $input['action'];

/** =============================================================
    REGISTER - Only customers self-register here
================================================================*/
if ($action === 'register') {

    if (
        empty($input['username']) ||
        empty($input['email']) ||
        empty($input['phone']) ||
        empty($input['password'])
    ) {
        echo json_encode(["status" => "error", "message" => "All fields required"]);
        exit;
    }

    // Normalize inputs (lowercase, trimmed) for consistency
    $username = strtolower(trim($input['username']));
    $email    = strtolower(trim($input['email']));
    $phone    = trim($input['phone']);
    $password = $input['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // -------- Duplicate Check --------
    $where = [];
    $type = '';
    $params = [];
    if (!empty($username)) {
        $where[] = "LOWER(username) = ?";
        $type .= 's';
        $params[] = $username;
    }
    if (!empty($email)) {
        $where[] = "LOWER(email) = ?";
        $type .= 's';
        $params[] = $email;
    }
    if (!empty($phone)) {
        $where[] = "phone = ?";
        $type .= 's';
        $params[] = $phone;
    }

    if (empty($where)) {
        echo json_encode(["status" => "error", "message" => "Invalid registration data"]);
        exit;
    }

    $query = "SELECT * FROM login WHERE " . implode(' OR ', $where);
    $stmt = $conn->prepare($query);
    $stmt->bind_param($type, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "User already exists"]);
        exit;
    }
    $stmt->close();

    // -------- Transaction: Insert into customers + login --------
    $conn->autocommit(FALSE);
    try {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, customer_email, customer_phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $phone);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $customer_id = $conn->insert_id;
        $stmt->close();

        $role = 'customer';
        $stmt = $conn->prepare("INSERT INTO login (username, email, phone, password, role, customer_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $username, $email, $phone, $hashedPassword, $role, $customer_id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $stmt->close();
        $conn->commit();
        
        echo json_encode(["status" => "success", "message" => "User registered successfully"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Registration failed: " . $e->getMessage()]);
    }

    $conn->autocommit(TRUE);
    $conn->close();
    exit;
}

/** =============================================================
    LOGIN - Accepts username | email | phone
================================================================*/
elseif ($action === 'login') {

    if (empty($input['password']) ||
        (empty($input['username']) && empty($input['email']) && empty($input['phone']))) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    // Case-insensitive matching for username/email
    if (!empty($input['email'])) {
        $field = "LOWER(email)";
        $value = strtolower(trim($input['email']));
    } elseif (!empty($input['phone'])) {
        $field = "phone";
        $value = trim($input['phone']);
    } else {
        $field = "LOWER(username)";
        $value = strtolower(trim($input['username']));
    }

    $query = "SELECT * FROM login WHERE $field = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Support bcrypt and md5 fallback
    if (password_verify($input['password'], $user['password']) || md5($input['password']) === $user['password']) {
        $token = base64_encode(random_bytes(32));

        $response = [
            "status" => "success",
            "message" => "Login successful",
            "role"    => $user['role'],
            "token"   => $token,
            "email"   => $user['email']
        ];

        // Attach correct role id
        if ($user['role'] === 'customer' && isset($user['customer_id'])) {
            $response['customer_id'] = $user['customer_id'];
        } elseif ($user['role'] === 'employee' && isset($user['employee_id'])) {
            $response['employee_id'] = $user['employee_id'];
        } elseif ($user['role'] === 'manager' && isset($user['manager_id'])) {
            $response['manager_id'] = $user['manager_id'];
        } elseif ($user['role'] === 'admin') {
            $response['admin_id'] = $user['id']; // assuming id is PK
        }

        echo json_encode($response);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }

    $conn->close();
    exit;
}

/** =============================================================
    INVALID ACTION HANDLER
================================================================ */
else {
    echo json_encode(["status" => "error", "message" => "Invalid action"]);
}
?>
