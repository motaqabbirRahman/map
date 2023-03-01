<?php
// Database credentials
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "fire_map";

// Create a database connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>
