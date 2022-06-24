<?php
require '../config/config.php';

if ( !isset($_GET['offer_id']) || empty($_GET['offer_id'])) {

	// Missing required fields.
	$error = "offer_id is invalid.";

} else {
	// All required fields provided
	// DB Connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}
	$sql = "DELETE FROM offers WHERE user_id = " . $_SESSION["user_id"] . " AND id = '" . $_GET['offer_id'] . "';" ; 
	$results= $mysqli->query($sql);
	if(!$results){
		echo $mysqli->error;
		exit();
	}
	
	$mysqli->close();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Thank you</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="../assets/favicon.png">
	
    <!-- External CSS -->
	<link rel="stylesheet" type="text/css" href="../style.css">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Muli&display=swap" rel="stylesheet">

</head>
<body>
	<div class="main-nav">
		<a href="../index.php"><img alt="logo" id="logo" src="../assets/logo.png"></a>
		<div id="navbar">
			<a href="../index.php">Search</a>
			<a href="../offers/my_books.php" class="active">My Books</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

					<a href="../login/sign_in.php">Sign In</a>

			<?php else: ?>
				
					<a href="../login/logout.php">Sign Out</a>
					
			<?php endif; ?>
		</div>
	</div>
	<div class="offer-confirmation-main">
		<?php if(isset($error)) : ?> 
			<p><?php echo $error ?></p>
		<?php else : ?>
			<h1>Your listing has been successfully deleted.</h1>
			<a href="../offers/my_books.php" class="red-button">Return</a>
		<?php endif; ?>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>

<!-- Nav animation -->
<script src="nav_animation.js"></script>

</html>