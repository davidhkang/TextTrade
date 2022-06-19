<?php
require '../config/config.php';

// If user is already logged and is trying to log in again, just redirect them out of this page
if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]){
	// Check if user has entered a username or password
	if ( isset($_POST['username']) && isset($_POST['password']) ) {
		// User did not enter username/password (it's blank)
		if ( empty($_POST['username']) || empty($_POST['password']) ) {
			$error = "Please enter username and password.";
		}
		// user did enter username/pw but need to check if the username/pw exist in our database
		else {

			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}

			// Hash whatever user typed in for the password and compare this with password in DB
			// $passwordInput = hash("sha256", $_POST["password"]);
			$passwordInput = $_POST["password"];


			$sql = "SELECT * FROM users
						WHERE username = '" . $_POST['username'] . "' AND password = '" . $passwordInput . "';";			
			$results = $mysqli->query($sql);
			if(!$results) {
				echo $mysqli->error;
				exit();
			}

			// if we get at least one result back we know there's a match
			if($results->num_rows > 0) {
				// Set session variables to remember this user
				$row = $results->fetch_assoc();
				$_SESSION["user_id"] = $row["id"];
				$_SESSION["first_name"] = $row["first_name"];
				$_SESSION["logged_in"] = true;

				// Redirect user to the homepage (index.php)
				header("Location: ../index.php");
			}
			else {
				$error = "Invalid username or password.";
			}
		} 
	}
}else {
	// if user is already logged in and is trying to log in again, just redirect them out of the page
	header("Location: ../index.php");
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="../images/favicon.png">
	
    <!-- External CSS -->
	<link rel="stylesheet" type="text/css" href="../style.css">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Muli&display=swap" rel="stylesheet">

</head>
<body>
	<div class="main-nav">
		<a href="../index.php"><img alt="logo" id="logo" src="../images/logo.png"></a>
		<div id="navbar">
			<a href="../index.php">Search</a>
			<a href="../offers/my_books.php">My Books</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

					<a href="../login/sign_in.php" class="active">Sign In</a>

			<?php else: ?>
				
					<a href="../login/logout.php" class="active">Sign Out</a>
					
			<?php endif; ?>
		</div>
	</div>
	<div class="signin-main">
		<?php if (isset($error) && !empty($error) ) : ?>
			<h3 class="red"><?php echo $error ?></h3>
		<?php endif; ?>
		<form action="sign_in.php" method="POST">
			<div class="form-row" >
				<input class="signin-input" type="text" placeholder="username" name="username">
			</div>
			<div class="form-row" >
				<input class="signin-input" type="password" placeholder="password" name="password">
			</div>
			<div class="form-row">
				<button class="red-button" type="submit">Sign In</button>
				<a href="sign_up.php" class="black-button">Register</a>
			</div> <!-- .form-row -->
		</form>
	</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>


<!-- Nav animation -->
<script src="nav_animation.js"></script>

</html>