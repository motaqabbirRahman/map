<?php
// Connect to databas
require_once 'connection.inc.php';

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


// Get form data
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$marker_info = $_POST['marker_info'];
$marker_type = $_POST['marker_type'];
$fire_date = $_POST['fire_date'];
$fire_time = $_POST['fire_time'];
$casualty = $_POST['casualty'];
$status = $_POST['status'];
$tel = $_POST['tel'];
$qty = $_POST['qty'];


if ($marker_type === 'fire') {
  // Concatenate the values of fire_date, fire_time, and casualty fields into a single string
  $marker_info = "Fire date: " . $_POST['fire_date'] . ", \nFire time: " . $_POST['fire_time'] . ", \nCasualty: " . $_POST['casualty'];
} else if ($marker_type === 'fire_station'){
  // For other marker types, set the marker_info field to an empty string
  $marker_info = "Tel / Mobile no: " . $_POST['tel'];
} else if ($marker_type === 'fire_extinguisher'){
  // For other marker types, set the marker_info field to an empty string
  $marker_info = "Tel / Mobile no: " . $_POST['tel'] . ", \nQuantity: " . $_POST['qty'] . " pc";

}

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
