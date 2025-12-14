<?php
header("Content-Type: application/json");
include '../DB.php';

$data = json_decode(file_get_contents("php://input"), true);

$type = $data["type"] ?? null;
$name = $data["name"] ?? null;
$email = $data["email"] ?? null;
$password = $data["password"] ?? null;
$tel = $data["tel"] ?? null;
$logo = $data["logo"] ?? "default.png";

if (!$type || !$name || !$email || !$password || !$tel) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO users (type, name, email, password, tel, logo)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("ssssss", $type, $name, $email, $password, $tel, $logo);

if ($stmt->execute()) {
    echo json_encode(["message" => "User created"]);
} else {
    echo json_encode(["error" => "User already exists"]);
}
$stmt->close();
$conn->close();
?>