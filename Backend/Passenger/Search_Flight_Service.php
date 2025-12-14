<?php
include 'db.php';
include 'flight_function.php';

$from = $_POST['from'] ?? '';
$to   = $_POST['to'] ?? '';

$result = searchFlights($conn, $from, $to);

echo json_encode($result);
?>