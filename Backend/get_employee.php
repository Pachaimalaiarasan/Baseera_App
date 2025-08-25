<?php
// header('Content-Type: application/json');
// error_reporting(E_ALL);
// ini_set('display_errors', 0); // Disable HTML error output

require_once("database.php");

try {
    // Updated SQL query with a subquery to compute the average rating for each employee.
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

    if (!$result) {
        throw new Exception($conn->error);
    }

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = [
            'employee_id'    => $row['employee_id'],
            'employee_name'  => $row['employee_name'],
            'employee_phone' => $row['employee_phone'],
            'employee_email' => $row['employee_email'],
            'employee_image' => $row['employee_image'] ? $row['employee_image'] : null,
            // average_rating might be null if there are no ratings; you can choose to default to 0 if needed.
            'average_rating' => $row['average_rating'] !== null ? (float)$row['average_rating'] : null
        ];
    }

    echo json_encode([
        'status' => 'success',
        'employees' => $employees
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
