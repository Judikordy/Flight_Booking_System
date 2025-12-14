<?php
function getPassengerInfo($conn, $passenger_id) {
    $passenger_sql = "SELECT * FROM users WHERE id=? AND type='passenger'";
    $stmt = $conn->prepare($passenger_sql);
    $stmt->bind_param("i", $passenger_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getCompletedFlights($conn, $passenger_id) {
    $completed_sql = "SELECT f.*, fp.status
                      FROM flights f
                      JOIN flights_passengers fp ON f.id = fp.flight_id
                      WHERE fp.passenger_id=? AND fp.status='completed'";
    $stmt = $conn->prepare($completed_sql);
    $stmt->bind_param("i", $passenger_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getCurrentFlights($conn, $passenger_id) {
    $current_sql = "SELECT f.*, fp.status
                    FROM flights f
                    JOIN flights_passengers fp ON f.id = fp.flight_id
                    WHERE fp.passenger_id=? AND fp.status IN ('pending','registered')";
    $stmt = $conn->prepare($current_sql);
    $stmt->bind_param("i", $passenger_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function searchFlights($conn, $from, $to) {
    $search_sql = "SELECT * FROM flights
        WHERE status='active'
          AND itinerary LIKE CONCAT('%', ?, '%')
          AND itinerary LIKE CONCAT('%', ?, '%')
          AND LOCATE(?, itinerary) < LOCATE(?, itinerary)";

    $stmt = $conn->prepare($search_sql);
    $stmt->bind_param("ssss", $from, $to, $from, $to);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


?>