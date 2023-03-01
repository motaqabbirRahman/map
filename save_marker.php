<?php
// Connect to database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'fire_map';
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$marker_info = $_POST['marker_info'];
$marker_type = $_POST['marker_type'];
$status = $_POST['status'];

// Insert data into markers table
$sql = "INSERT INTO markers (latitude, longitude, marker_info, marker_type, status) VALUES ($latitude, $longitude, '$marker_info', '$marker_type', '$status')";
if (mysqli_query($conn, $sql)) {
  $msg = "Marker saved successfully!!!";
  header("Location: index.php?msg=".urlencode($msg));
  exit();
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
