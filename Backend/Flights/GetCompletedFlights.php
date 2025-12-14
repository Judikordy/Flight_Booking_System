<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include '../DB.php';
include '../utils/flight_functions.php';

$passenger_id = $_GET['id'] ?? null;

if (!$passenger_id || !is_numeric($passenger_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid passenger ID"]);
    exit;
}

$completed_flights = getCompletedFlights($conn, $passenger_id);

echo json_encode([
    "status" => "success",
    "completed_flights" => $completed_flights
]);

$conn->close();
?>
