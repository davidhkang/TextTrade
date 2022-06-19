<?php
	// To logout, remove the SESSION variables
	// To destroy a session, must start a sessionn
	session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log Out</title>
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
			<a href="../login/sign_in.php">Sign In</a>
		</div>
	</div>
	<div class="logout-main">
		<h2>You are now logged out.</h2>
		<a class="red-button" href="../index.php">Home</a>
		<a class="black-button" href="sign_in.php">Log Back In</a>
	</div>
<!-- Nav animation -->
<script src="nav_animation.js"></script>
</html>