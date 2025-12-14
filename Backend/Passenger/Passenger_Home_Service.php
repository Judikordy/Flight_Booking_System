<?php

session_start();
header('Content-Type: application/json');

require_once '../DB.php';
require_once '../Utils/Flight_Functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'passenger') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$passenger_id = $_SESSION['user_id'];

try {
    $response = [
        'success' => true,
        'passenger' => getPassengerInfo($conn, $passenger_id),
        'completed' => getCompletedFlights($conn, $passenger_id),
        'current'   => getCurrentFlights($conn, $passenger_id)
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

exit;
?>