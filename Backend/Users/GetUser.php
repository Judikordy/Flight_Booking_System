<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

include '../DB.php';

$id = $_GET["id"] ?? null;

if (!$id) {
    echo json_encode(["error" => "User ID is required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, type, name, email, tel, logo FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "User not found"]);
} else {
    echo json_encode($result->fetch_assoc());
}

$stmt->close();
$conn->close();
?>
