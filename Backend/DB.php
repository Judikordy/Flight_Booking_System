<?php
$conn = mysqli_connect("localhost", "root", "", "flight_booking");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>