<?php
require '../config/config.php';

if ( !isset($_POST['condition']) || empty($_POST['condition']) 
	|| !isset($_POST['price']) || empty($_POST['price'])
	|| !isset($_POST['book-id']) || empty($_POST['book-id']) ) {

	// Missing required fields.
	$error = "Please fill out all required fields.";

} else {
	// All required fields provi
	// DB Connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}

	// take care of optional inputs
	if ( isset($_POST['number']) && !empty($_POST['number']) ) {
		// User selected album value.
		$number = $_POST['number'];
	} else {
		// User did not select album value.
		$number = "null";
	}

	// convert conditions into 1,2, or 3
	if($_POST["condition"] == "good"){
		$condition_id = 1;
	}else if($_POST["condition"] == "great"){
		$condition_id = 2;
	}else if($_POST["condition"] == "as-new"){
		$condition_id = 3;
	}
	
	$statement = $mysqli->prepare("INSERT INTO offers (book_id, condition_id, price, user_id, phone_number)
					VALUES (?,?,?,?,?);");
	$statement->bind_param("sidii", $_POST["book-id"], $condition_id, $_POST["price"], $_SESSION["user_id"], $number);

	$executed = $statement->execute();
	if(!$executed){
		echo $mysqli->error;
		exit();
	}
	$mysqli->close();
	header('Location: offer_confirmation2.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Thank You</title>
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
</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>
<script>
</script>
<!-- Nav animation -->
<script src="nav_animation.js"></script>
</html>