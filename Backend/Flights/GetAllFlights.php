<?php

header('Content-Type: application/json');
require_once '../DB.php';

try {
 
    $query = "SELECT * FROM flights ORDER BY start_time ASC";
    $result = $conn->query($query);

    if ($result) {
        $flights = [];
        while ($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
        echo json_encode([
            'status' => 'success',
            'data' => $flights
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query failed: ' . $conn->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
