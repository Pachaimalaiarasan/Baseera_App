<?php
// place_order.php
header('Content-Type: application/json');
include 'database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);


// Start a transaction
$conn->begin_transaction();

try {
    // ===============================
    // 1. Insert Payment Record
    // ===============================
    if (
        !isset($_POST['customer_id'], $_POST['payment_amount'], 
               $_POST['payment_method'], $_POST['payment_status'], 
               $_POST['payment_date'])
    ) {
        throw new Exception("Missing payment parameters");
    }
    $customer_id    = intval($_POST['customer_id']);
    $payment_amount = floatval($_POST['payment_amount']);
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $payment_date   = $_POST['payment_date'];  // Expected format: "YYYY-MM-DD HH:MM:SS"

    $stmt = $conn->prepare("INSERT INTO payments (customer_id, payment_amount, payment_method, payment_status, payment_date) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Payment prepare failed: " . $conn->error);
    }
    $stmt->bind_param("idsss", $customer_id, $payment_amount, $payment_method, $payment_status, $payment_date);
    if (!$stmt->execute()) {
        throw new Exception("Payment insertion failed: " . $stmt->error);
    }
    $payment_id = $stmt->insert_id;
    $stmt->close();

    // ===============================
    // 2. Book the Slot
    // ===============================
    if (!isset($_POST['employee_id'], $_POST['slot_id'], $_POST['date'])) {
        throw new Exception("Missing slot booking parameters");
    }
    $employee_id = intval($_POST['employee_id']);
    $slot_id     = intval($_POST['slot_id']);
    $date        = $_POST['date']; // Expected format: "YYYY-MM-DD" or similar

    // Check if the slot is already booked
    $stmt = $conn->prepare("SELECT booked_slot_id FROM booked_slots WHERE employee_id = ? AND slot_id = ? AND date = ?");
    if (!$stmt) {
        throw new Exception("Slot check prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iis", $employee_id, $slot_id, $date);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        throw new Exception("Slot already booked");
    }
    $stmt->close();

    // Insert the booking
    $stmt = $conn->prepare("INSERT INTO booked_slots (employee_id, slot_id, date) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Slot booking prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iis", $employee_id, $slot_id, $date);
    if (!$stmt->execute()) {
        throw new Exception("Slot booking failed: " . $stmt->error);
    }
    $booked_slot_id = $stmt->insert_id;
    $stmt->close();

    // ===============================
    // 3. Insert Order Record
    // ===============================
    if (!isset($_POST['car_id'], $_POST['service_id'], $_POST['plan_id'], $_POST['branch_id'])) {
        throw new Exception("Missing order parameters");
    }
    $car_id       = intval($_POST['car_id']);
    $service_id   = intval($_POST['service_id']);
    $plan_id      = intval($_POST['plan_id']);
    $branch_id    = intval($_POST['branch_id']); // New branch parameter
    $order_status = isset($_POST['order_status']) ? $_POST['order_status'] : 'pending';
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0.00;
    // Optional address id
    $address_id   = (isset($_POST['address_id']) && $_POST['address_id'] !== "") ? intval($_POST['address_id']) : null;

    // Updated SQL to include branch_id.
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, employee_id, booked_slot_id, car_id, service_id, plan_id, payment_id, order_status, total_amount, address_id, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Order prepare failed: " . $conn->error);
    }
    // Binding string: "iiiiiiisdii" corresponds to:
    // i: customer_id, i: employee_id, i: booked_slot_id, i: car_id, i: service_id, i: plan_id,
    // i: payment_id, s: order_status, d: total_amount, i: address_id, i: branch_id.
    $stmt->bind_param("iiiiiiisdii", $customer_id, $employee_id, $booked_slot_id, $car_id, $service_id, $plan_id, $payment_id, $order_status, $total_amount, $address_id, $branch_id);
    if (!$stmt->execute()) {
        throw new Exception("Order insertion failed: " . $stmt->error);
    }
    $order_id = $stmt->insert_id;
    $stmt->close();

    // If all steps succeeded, commit the transaction.
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Order placed successfully",
        "order_id" => $order_id
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        "status" => "error",
        "message" => "Failed to place order: " . $e->getMessage()
    ]);
}

$conn->close();
?>
