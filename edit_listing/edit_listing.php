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
	$sql = "SELECT * FROM offers WHERE user_id = " . $_SESSION["user_id"] . " AND id = '" . $_GET['offer_id'] . "';" ; 
	$results= $mysqli->query($sql);
	if(!$results){
		echo $mysqli->error;
		exit();
	}

	$row = $results->fetch_assoc();

	$mysqli->close();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Your Offer</title>
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
	<div class="offer-main">
		<div class="offer-form">
			<h1 class="heading">Edit Your Offer</h1>
			<hr>
			<h1 class="title"></h1>
			<h1 class="author"></h1>
			<form action="edit_confirmation.php" method="POST">
				<div class="form-row">
					<label class="main-label">Condition<span class="red">*</span> :</label>
						<input id="as-new" type="radio" name="condition" value="as-new">
						<label for="as-new" class="condition-inputs">As New</label>
						
						<input id="great" type="radio" name="condition" value="great">
						<label for="great" class="condition-inputs">Great</label>

						<input id="good" type="radio" name="condition" value="good">
						<label for="good" class="condition-inputs">Good</label>
					</label>
				</div>
				<div class="form-row price-input">
					<label id="price" class="main-label" for="price">Price<span class="red">*</span> : </label>
					<input id="price-id" type="number" step="0.01" placeholder="10.50" name="price" value="<?php echo $row["price"]?>">
				</div>
				<div class="form-row phone-num-input" >
					<label id="phone-num" class="main-label" for="phone-num">Phone number : </label>
					<input type="number" name="number" placeholder="2134351234" value="<?php echo $row["phone_number"]?>">
				</div>

				<!-- hidden input for passing offer id -->
				<input type="hidden" name="offer-id" value="<?php echo $_GET["offer_id"]?>">
				<p class="required">*Required</p>
				<div class="form-row">
					<button class="red-button" type="submit">Edit Offer</button>
					<button class="black-button" type="reset">Clear</button>
				</div> <!-- .form-row -->
			</form>
		</div>
	</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>

<script>
	let ajaxCall = function(){
		let id = "<?php echo $row['book_id']; ?>";
		// runs ajax
		$.ajax({
			method: "GET",
			url: "https://www.googleapis.com/books/v1/volumes/" + id
		})
		.done(function(response){
			console.log("success")
			displayResults(response);
		})
		.fail(function(){
			console.log('Error!');
		});
	}
	let displayResults = function(response){
		console.log(1)
		if (response.volumeInfo.subtitle != undefined){
			$('.offer-form .title').html(response.volumeInfo.title + ": " + response.volumeInfo.subtitle);
		}else{
			$('.offer-form .title').html(response.volumeInfo.title);
		}

		let authors = "";
		if (response.volumeInfo.authors != undefined){
			for (let j=0; j<response.volumeInfo.authors.length; j++){
				if (j != 0){
					authors = authors + ', ' + response.volumeInfo.authors[j];
				}else {
					authors = response.volumeInfo.authors[j];
				}
			}
			$('.offer-form .author').html("By " + authors);
			console.log(2)
		}
	}
	ajaxCall();

	// Client-side input validation (make sure required fields are filled in)
	document.querySelector('form').onsubmit = function(event){
		event.preventDefault();

		priceValid = false;
		console.log("error")

		if ( document.querySelector('#price-id').value.length == 0 ) {
			document.querySelector('#price-id').classList.add('input-error');
		} else {
			priceValid = true;
			document.querySelector('#price-id').classList.remove('input-error')
		}

		if(priceValid == true){
			document.querySelector('form').submit(); 
		}
	}

	// pre-check correct condition
	if(<?php echo $row['condition_id']; ?> == 3){
		$('#as-new').attr("checked", "checked");
	}else if(<?php echo $row['condition_id']; ?> == 2){
		$('#great').attr("checked", "checked");
	}else{
		$('#good').attr("checked", "checked");
	}
		 


</script>
<!-- Nav animation -->
<script src="nav_animation.js"></script>

</html>