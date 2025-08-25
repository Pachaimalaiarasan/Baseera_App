<?php
include 'database.php'; // Ensure this file connects to MySQL

$email = $_POST['email'];
$fcm_token = $_POST['fcm_token'];
$action = $_POST['action']; // "save" for login, "remove" for logout

if (!isset($email) || !isset($action)) {
    echo json_encode(["status" => "error", "message" => "Invalid parameters"]);
    exit();
}

if ($action === "save" && isset($fcm_token)) {
    // ✅ Save the FCM token on login
    $sql = "UPDATE login SET fcm_token = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fcm_token, $email);
} elseif ($action === "remove") {
    // ✅ Remove the FCM token on logout
    $sql = "UPDATE login SET fcm_token = NULL WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid action"]);
    exit();
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => ($action === "save" ? "FCM token updated" : "FCM token removed")]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update token"]);
}

$stmt->close();
$conn->close();
?>

