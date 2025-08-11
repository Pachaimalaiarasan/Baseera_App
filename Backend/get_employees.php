<?php
header('Content-Type: application/json');
require_once("database.php");

$sql = "SELECT 
            e.employee_id, 
            e.employee_name, 
            e.employee_phone, 
            e.employee_email, 
            e.employee_image,
            (
                SELECT AVG(r.rating) 
                FROM employee_ratings r 
                WHERE r.employee_id = e.employee_id
            ) AS average_rating
        FROM employees e";
$result = $conn->query($sql);

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = [
        'employee_id'    => $row['employee_id'],
        'employee_name'  => $row['employee_name'],
        'employee_phone' => $row['employee_phone'],
        'employee_email' => $row['employee_email'],
        'employee_image' => $row['employee_image'] ? $row['employee_image'] : null,
        'average_rating' => $row['average_rating'] !== null ? (float)$row['average_rating'] : null
    ];
}
echo json_encode([
    'status' => 'success',
    'employees' => $employees
]);
$conn->close();
?>
