<?php
// Connect to database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'fire_map';
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
// retrieve marker data from database
$markers = array();
$result = mysqli_query($conn, "SELECT * FROM markers");
while ($row = mysqli_fetch_assoc($result)) {
    $markers[] = array(
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude'],
        'marker_type' => $row['marker_type'],
        'marker_info' => $row['marker_info']
    );
}

// encode marker data as JSON and print it
echo json_encode($markers);

// Close database connection
mysqli_close($conn);


?>