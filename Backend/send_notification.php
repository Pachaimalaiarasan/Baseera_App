<?php
require __DIR__ . '/../vendor/autoload.php';  // ✅ Ensure correct path to autoload.php

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Sends a notification using Firebase Cloud Messaging.
 *
 * @param string $title   The title of the notification.
 * @param string $message The body of the notification.
 * @param string $token   The FCM registration token of the target device.
 *
 * @return string The response body from FCM or an error message.
 */
function sendNotification($title, $message, $token) {
    $jsonKeyFilePath = "C:/xampp/htdocs/config/carwash-firebase-adminsdk.json"; // ✅ Correct path

    // Create credentials with the required scope.
    $credentials = new ServiceAccountCredentials(
        'https://www.googleapis.com/auth/firebase.messaging',
        $jsonKeyFilePath
    );

    // Initialize Guzzle client with authorization headers.
    $client = new Client([
        'headers' => [
            'Authorization' => 'Bearer ' . $credentials->fetchAuthToken()['access_token'],
            'Content-Type'  => 'application/json',
        ]
    ]);

    // Build the payload with both notification and data sections.
    $data = [
        "message" => [
            "token" => $token,
            "notification" => [
                "title" => $title,
                "body"  => $message,
            ],
            "data" => [   // This payload is used to handle notifications in the foreground.
                "title" => $title,
                "body"  => $message,
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",  // Adjust this if needed.
                "orderUpdate" => "true"   // Custom key to trigger a UI refresh.
            ],
            "android" => [
                "priority" => "high",
            ],
            "apns" => [
                "headers" => [
                    "apns-priority" => "10",
                ],
            ],
        ]
    ];

    try {
        // Send the notification via Firebase Cloud Messaging.
        $response = $client->post(
            'https://fcm.googleapis.com/v1/projects/carwash-88008/messages:send',
            ['json' => $data]
        );
        return $response->getBody();
    } catch (RequestException $e) {
        return $e->getMessage();
    }
}

// Validate required POST data.
if (!isset($_POST['role']) || !isset($_POST['title']) || !isset($_POST['message'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

$role    = $_POST['role'];
$title   = $_POST['title'];
$message = $_POST['message'];
$email   = $_POST['email'] ?? null;  // ✅ Use null if 'email' is not provided

include 'database.php';  // ✅ Ensure this file sets up your $conn variable

$tokens = [];

// Determine SQL query based on role or email.
if ($role === 'admin') {
    // When role is 'admin', send notification to the admin(s).
    $sql = "SELECT fcm_token FROM login WHERE role = 'admin'";
} elseif ($role === 'employee' || $role === 'customer') {
    // When role is 'employee' or 'customer', send notification to a specific user.
    $sql = "SELECT fcm_token FROM login WHERE email = ?";
} elseif ($email !== null) {
    // When an email is provided (e.g., from an employee to admin/user).
    $sql = "SELECT fcm_token FROM login WHERE email = ?";
} else {
    echo json_encode(["status" => "error", "message" => "No valid target for notification"]);
    exit();
}

// Prepare and execute the query.
$stmt = $conn->prepare($sql);
if ($email !== null) {
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$result = $stmt->get_result();

// Collect all valid FCM tokens.
while ($row = $result->fetch_assoc()) {
    if (!empty($row['fcm_token'])) {
        $tokens[] = $row['fcm_token'];
    }
}

$stmt->close();
$conn->close();

// Check if any tokens were found.
if (empty($tokens)) {
    echo json_encode(["status" => "error", "message" => "No valid FCM tokens found"]);
    exit();
}

// Send notifications to all collected tokens.
foreach ($tokens as $token) {
    $response = sendNotification($title, $message, $token);
    // You may want to log or handle $response per token if needed.
}

echo json_encode(["status" => "success", "message" => "Notification sent"]);
?>
