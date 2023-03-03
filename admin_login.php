<?php

$login = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    require('connection.inc.php');
    $username = $_POST["username"];
    $password = $_POST["password"];
    
      $sql = "SELECT * from admin where username='$username' AND password='$password'";
      $result = mysqli_query($conn, $sql);
      $num = mysqli_num_rows($result);
      if ($num == 1){
        $login = true;
        session_start();
        $_SESSION['adminLoggedin'] = true;
        $_SESSION['username'] = $username;
        // $_SESSION['user_type'] = "admin";
        header("location: admin.php");
      }
     else{
      $showError = "Invalid Credentials";
     }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    <title>Admin Login</title>
</head>
<header>
</header>
<body>
    <body>
	<form action="admin_login.php" method="post" class="admin_login">
		<h2>Admin Login</h2>
		<input type="text" id="username" name="username" required placeholder="username">
		<input type="password" id="password" name="password" required placeholder="password">
		<input type="submit" value="Login" id="adminLogin">
        <?php 
                if ($login){
                    echo'<strong>You are logged in Successfully!</strong>';
                }
                if ($showError){
                    echo $showError;
                }
        ?>
	</form>
</body>
</html>