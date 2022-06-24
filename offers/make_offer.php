<?php
require '../config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Make an Offer</title>
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

	<div class="loader-wrapper">
		<div class="loader"></div>
	</div>

	<div class="main-nav">
		<a href="../index.php"><img alt="logo" id="logo" src="../assets/logo.png"></a>
		<div id="navbar">
			<a href="../index.php" class="active">Search</a>
			<a href="../offers/my_books.php">My Books</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

					<a href="../login/sign_in.php">Sign In</a>

			<?php else: ?>
				
					<a href="../login/logout.php">Sign Out</a>
					
			<?php endif; ?>
		</div>
	</div>
	<div class="offer-main">
		<div class="offer-form">
			<h1 class="heading">Make an Offer</h1>
			<hr>
			<h1 class="title"></h1>
			<h1 class="author"></h1>
			<form action="offer_confirmation.php" method="POST">
				<div class="form-row">
					<label class="main-label">Condition<span class="red">*</span> :</label>
						<input id="good" type="radio" name="condition" value="good">
						<label for="good" class="condition-inputs">Good</label>
						<input id="great" type="radio" name="condition" value="great">
						<label for="great" class="condition-inputs">Great</label>
						<input id="as-new" type="radio" name="condition" value="as-new" checked>
						<label for="as-new" class="condition-inputs">As New</label>
					</label>
				</div>
				<div class="form-row price-input">
					<label id="price" class="main-label" for="price">Price<span class="red">*</span> : </label>
					<input id="price-id" type="number" step="0.01" placeholder="10.50" name="price">
				</div>
				<div class="form-row phone-num-input" >
					<label id="phone-num" class="main-label" for="phone-num">Phone number : </label>
					<input type="number" name="number" placeholder="2134351234">
				</div>

				<!-- hidden input for passing book id -->
				<input type="hidden" name="book-id" value="<?php echo $_GET["id"]; ?>">
				<p class="required">*Required</p>
				<div class="form-row">
					<button class="red-button" type="submit">Submit Offer</button>
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
		let id = "<?php echo $_GET["id"]; ?>";
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
	const displayResults = (response) => {
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
		}

		$(".loader-wrapper").fadeOut("normal");
	}
	ajaxCall();

	// Client-side input validation (make sure required fields are filled in)
	document.querySelector('form').onsubmit = function(event){
		event.preventDefault();
		priceValid = false;
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

</script>
<!-- Nav animation -->
<script src="nav_animation.js"></script>

</html>