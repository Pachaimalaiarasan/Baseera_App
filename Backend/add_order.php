<?php
// add_order.php
header('Content-Type: application/json');
include 'database.php';

// Expected POST parameters: 
// customer_id, employee_id, booked_slot_id, car_id, service_id, plan_id,
// payment_id (optional), address_id (optional), order_status (optional), total_amount (optional), branch_id (optional)

if (
    isset($_POST['customer_id'], $_POST['employee_id'], $_POST['booked_slot_id'], 
          $_POST['car_id'], $_POST['service_id'], $_POST['plan_id'])
) {
    // Retrieve and sanitize input data
    $customer_id    = intval($_POST['customer_id']);
    $employee_id    = intval($_POST['employee_id']);
    $booked_slot_id = intval($_POST['booked_slot_id']);
    $car_id         = intval($_POST['car_id']);
    $service_id     = intval($_POST['service_id']);
    $plan_id        = intval($_POST['plan_id']);
    $payment_id     = (isset($_POST['payment_id']) && $_POST['payment_id'] !== "") ? intval($_POST['payment_id']) : null;
    $address_id     = (isset($_POST['address_id']) && $_POST['address_id'] !== "") ? intval($_POST['address_id']) : null;
    $order_status   = isset($_POST['order_status']) ? $_POST['order_status'] : 'pending';
    $total_amount   = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0.00;
    $branch_id      = (isset($_POST['branch_id']) && $_POST['branch_id'] !== "") ? intval($_POST['branch_id']) : null;

    // Prepare SQL statement with branch_id as an additional column.
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, employee_id, booked_slot_id, car_id, service_id, plan_id, payment_id, order_status, total_amount, address_id, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters. The binding string "iiiiiiisdii" represents:
    // i: customer_id, i: employee_id, i: booked_slot_id, i: car_id, 
    // i: service_id, i: plan_id, i: payment_id, s: order_status, d: total_amount, 
    // i: address_id, i: branch_id.
    $stmt->bind_param("iiiiiiisdii", $customer_id, $employee_id, $booked_slot_id, $car_id, $service_id, $plan_id, $payment_id, $order_status, $total_amount, $address_id, $branch_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success", 
            "message" => "Order added successfully", 
            "order_id" => $stmt->insert_id
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Failed to add order: " . $stmt->error
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Missing required parameters"
    ]);
}

$conn->close();
?>
