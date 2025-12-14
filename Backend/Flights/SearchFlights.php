<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
include '../DB.php'; 

$from_city = isset($_POST['from']) ? trim($_POST['from']) : (isset($_GET['from']) ? trim($_GET['from']) : '');
$to_city   = isset($_POST['to']) ? trim($_POST['to']) : (isset($_GET['to']) ? trim($_GET['to']) : '');

if (!$from_city || !$to_city) {
    echo json_encode(['status' => 'error', 'message' => 'From and To cities are required']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, name, itinerary, fees, start_time, end_time FROM flights WHERE status='active'");
    $stmt->execute();
    $result = $stmt->get_result();

    $flights = [];
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }

    $results = [];

    foreach ($flights as $flight) {
        $cities = array_map('trim', explode(',', $flight['itinerary']));
        if (!$cities) continue;

        if (strcasecmp($cities[0], $from_city) === 0 && strcasecmp(end($cities), $to_city) === 0) {

            $pass_stmt = $conn->prepare("
                SELECT 
                    SUM(CASE WHEN status='registered' THEN 1 ELSE 0 END) AS registered,
                    SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) AS pending
                FROM flights_passengers
                WHERE flight_id = ?
            ");
            $pass_stmt->bind_param("i", $flight['id']);
            $pass_stmt->execute();
            $pass_result = $pass_stmt->get_result();
            $pass_counts = $pass_result->fetch_assoc();

            $flight['passengers_registered'] = (int)$pass_counts['registered'];
            $flight['passengers_pending'] = (int)$pass_counts['pending'];

            $results[] = $flight;
        }
    }

    echo json_encode([
        'status' => 'success',
        'flights' => $results
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
$conn->close();
?>