<?php
require '../config/config.php';

// server side validation (to make sure required fields are filled in)
if ( !isset($_POST['email']) || empty($_POST['email'])
	|| !isset($_POST['username']) || empty($_POST['username'])
	|| !isset($_POST['password']) || empty($_POST['password'])
	|| !isset($_POST['firstname']) || empty($_POST['firstname']) ) {
	$error = "Please fill out all required fields.";
}else{
	// Store this user into the database
	// connect to db
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno){
		echo $mysqli->connection_error;
		exit();
	}

	// Check if username is already taken (already exists in the users table)
	$sql_registered = "SELECT * FROM users WHERE username = '" . $_POST["username"] . "' OR email = '" .$_POST["email"] . "';" ; 
	$results_registered = $mysqli->query($sql_registered);
	if(!$results_registered){
		echo $mysqli->error;
		exit();
	}

	if($results_registered->num_rows > 0){
		$error = "Username or email is already taken. Please choose another one.";
	}else{
		// Hash the password
		// $password = hash("sha256", $_POST['password']);
		$password = $_POST['password'];

		// Insert values with SQL
		$statement = $mysqli->prepare("INSERT INTO users(first_name, username, email, password) VALUES(?,?,?,?);");	
		$statement->bind_param("ssss", $_POST["firstname"], $_POST["username"], $_POST["email"], $password);

		$executed = $statement->execute();
		if(!$executed){
			echo $mysqli->error;
			exit();
		}

		// get user id
		$sql_user_id = "SELECT * FROM users WHERE username = '" . $_POST["username"] . "' OR email = '" .$_POST["email"] . "';" ; 
		$results_user_id = $mysqli->query($sql_user_id);
		if(!$results_user_id){
			echo $mysqli->error;
			exit();
		}

		$mysqli->close();
		$row = $results_user_id->fetch_assoc();
		$_SESSION["user_id"] = $row["id"];
		$_SESSION["first_name"] = $_POST["firstname"];
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["logged_in"] = true;

		// Redirect user to the homepage (index.php)
	}

	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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

					<a href="../login/sign_in.php">Sign In</a>

			<?php else: ?>
				
					<a href="../login/logout.php">Sign Out</a>
					
			<?php endif; ?>
		</div>
	</div>
	<div class="signup-confirm-main">
		<?php if ( isset($error) && !empty($error) ) : ?>
			<h3 class="red"><?php echo $error ?></h3>
			<a href="javascript:history.back()" class="red-button">Return</a>
		<?php else : ?>
			<h1>Welcome, <?php echo $_SESSION["first_name"]?>!</h1>
			<div class="form-row">
				<a href="../index.php" class="red-button">Get Started</a>
			</div> <!-- .form-row -->
		<?php endif; ?>
		
	</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>


<!-- Nav animation -->
<script src="nav_animation.js"></script>

</html>