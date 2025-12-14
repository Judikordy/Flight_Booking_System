<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Home</title>
    <link rel="stylesheet" href="../../CSS/Style.css">
</head>
<body>

<h1>Welcome, <span id="passenger-name">Loading...</span></h1>

<div>
    <p><strong>Email:</strong> <span id="passenger-email"></span></p>
    <p><strong>Phone:</strong> <span id="passenger-tel"></span></p>
    <img id="passenger-photo" src="" alt="Profile Photo" width="100" style="display:none;">
</div>

<h2>Completed Flights</h2>
<div id="completed-flights">Loading...</div>

<h2>Current Flights</h2>
<div id="current-flights">Loading...</div>

<br>
<a href="Search.php">Search for Flights</a> | 
<a href="Profile.php">My Profile</a>

<script>
fetch('../backend/Passenger/Passenger_Home_Service.php')
    .then(res => {
        if (!res.ok) throw new Error('Network error: ' + res.status);
        return res.json();
    })
    .then(data => {
        // if (!data.success) {
        //     alert(data.message);
        //     window.location.href = '../Login.php';
        //     return;
        // }

        const p = data.passenger;
        document.getElementById('passenger-name').textContent = p.name || 'Passenger';
        document.getElementById('passenger-email').textContent = p.email || '-';
        document.getElementById('passenger-tel').textContent = p.tel || '-';

        if (p.photo) {
            document.getElementById('passenger-photo').src = '../../uploads/' + p.photo;
            document.getElementById('passenger-photo').style.display = 'block';
        }

        const completedHTML = data.completed.length > 0
            ? data.completed.map(f => `<p><strong>${f.name}</strong> - ${f.itinerary}</p>`).join('')
            : '<p>No completed flights yet.</p>';
        document.getElementById('completed-flights').innerHTML = completedHTML;

        const currentHTML = data.current.length > 0
            ? data.current.map(f => `<p><strong>${f.name}</strong> - ${f.itinerary} (Status: ${f.status})</p>`).join('')
            : '<p>No active bookings.</p>';
        document.getElementById('current-flights').innerHTML = currentHTML;
    })
    .catch(err => {
        console.error(err);
        document.body.innerHTML = '<h2>Failed to load data. Please try again later.</h2>';
    });
</script>

</body>
</html>