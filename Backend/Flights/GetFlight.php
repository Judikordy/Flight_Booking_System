<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

include '../DB.php';

$flight_id = $_GET['id'] ?? null;

if (!$flight_id || !is_numeric($flight_id)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing flight ID"
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM flights WHERE id = ?");
$stmt->bind_param("i", $flight_id);
$stmt->execute();

$result = $stmt->get_result();
$flight = $result->fetch_assoc();

if ($flight) {
    echo json_encode([
        "status" => "success",
        "flight" => $flight
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Flight not found"
    ]);
}

$stmt->close();
$conn->close();
?>
