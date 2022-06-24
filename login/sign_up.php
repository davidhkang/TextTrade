<?php
require '../config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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
			<a href="../offers/my_books.php">My Books</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

					<a href="../login/sign_in.php" class="active">Sign In</a>

			<?php else: ?>
				
					<a href="../login/logout.php" class="active">Sign Out</a>
					
			<?php endif; ?>
		</div>
	</div>
	<div class="signup-main">
		<form action="sign_up_confirmation.php" method="POST">
			<div class="form-row" >
				<input class="signin-input" type="text" placeholder="first name" name="firstname" id="firstname-id">
			</div>
			<div class="form-row" >
				<input class="signin-input" type="email" placeholder="email" name="email" id="email-id">
			</div>
			<div class="form-row" >
				<input class="signin-input" type="text" placeholder="username"name="username" id="username-id">
			</div>
			<div class="form-row" >
				<input class="signin-input" type="password" placeholder="password" name="password" id="password-id">
			</div>
			<p class="error red"></p>
			<div class="form-row">
				<button class="red-button" type="submit">Register</button>
				<button class="black-button" type="reset">Clear</button>
			</div> <!-- .form-row -->
			
		</form>
	</div>

</body>

<script>
	// Client-side input validation (make sure required fields are filled in)
	document.querySelector('form').onsubmit = function(event){
		event.preventDefault();

		firstnameValid = false;
		emailValid = false;
		usernameValid = false;
		passwordValid = false;

		if ( document.querySelector('#firstname-id').value.trim().length == 0 ) {
			document.querySelector('#firstname-id').classList.add('input-error');
		} else {
			firstnameValid = true;
			document.querySelector('#firstname-id').classList.remove('input-error')
		}
		if ( document.querySelector('#email-id').value.trim().length == 0 ) {
			document.querySelector('#email-id').classList.add('input-error');
		} else {
			emailValid = true;
			document.querySelector('#email-id').classList.remove('input-error')
		}
		if ( document.querySelector('#username-id').value.trim().length == 0 ) {
			document.querySelector('#username-id').classList.add('input-error');
		} else {
			usernameValid = true;
			document.querySelector('#username-id').classList.remove('input-error')
		}
		if ( document.querySelector('#password-id').value.trim().length == 0 ) {
			document.querySelector('#password-id').classList.add('input-error');
		} else {
			passwordValid = true;
			document.querySelector('#password-id').classList.remove('input-error')
		}

		if(firstnameValid == false || emailValid == false || usernameValid == false || passwordValid == false){
			document.querySelector(".error").innerHTML = "Please fill out all fields.";
		}else{
			document.querySelector('form').submit(); 
		}
	}

</script>

<!-- Nav animation -->
<script src="nav_animation.js"></script>

</html>