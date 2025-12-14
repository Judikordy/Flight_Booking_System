<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Connect
include '../DB.php';

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"] ?? null;
$itinerary = $data["itinerary"] ?? null;
$fees = $data["fees"] ?? null;
$max_passengers = $data["max_passengers"] ?? null;
$start_time = $data["start_time"] ?? null;
$end_time = $data["end_time"] ?? null;
$company_id = $data["company_id"] ?? null;
$status = $data["status"] ?? "active";

if (!$name || !$itinerary || !$fees || !$max_passengers || !$start_time || !$end_time || !$company_id) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO flights (name, itinerary, fees, max_passengers, start_time, end_time, company_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdiisss", $name, $itinerary, $fees, $max_passengers, $start_time, $end_time, $company_id, $status);

if ($stmt->execute()) {
    $flight_id = $stmt->insert_id;

    $query = $conn->prepare("SELECT * FROM flights WHERE id = ?");
    $query->bind_param("i", $flight_id);
    $query->execute();
    $result = $query->get_result();
    $flight = $result->fetch_assoc();

    echo json_encode([
        "message" => "Flight created successfully",
        "flight" => $flight
    ]);

    $query->close();
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
