<?php
// Connect to database
require 'connection.inc.php';


// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Get the marker ID from the AJAX request
$markerId = $_POST['marker_id'];

// Update the marker status in the database
$sql = "UPDATE markers SET status = 'approved' WHERE id = '$markerId'";
if (mysqli_query($conn, $sql)) {
    echo "Marker approved successfully";
} else {
    echo "Error approving marker: " . mysqli_error($conn);
}


mysqli_close($conn);
?>
