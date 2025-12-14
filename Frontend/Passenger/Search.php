<form id="searchForm">
    From: <input type="text" name="from" required>
    To: <input type="text" name="to" required>
    <button type="submit">Search</button>
</form>

<div id="results"></div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    fetch('backend/search_flight_service.php', {method:'POST', body: formData})
    .then(res => res.json())
    .then(data => {
        let html = data.map(f => `<p>${f.name} - ${f.itinerary} - ${f.fees}$</p>`).join('');
        document.getElementById('results').innerHTML = html;
    });
});
</script>
