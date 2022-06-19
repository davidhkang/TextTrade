<?php
require '../config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Book Details</title>
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

	<div class="secondary-loader-wrapper">
		<div class="secondary-spinner"></div>
	</div>
	

	<div class="main-nav">
		<a href="../index.php"><img alt="logo" id="logo" src="../images/logo.png"></a>
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
	<div class="details-main">
		<div class="details">
			<h2 class="title"></h2>
			<p class="author"></p>
			<p class="price"></p>
			<p class="publisher"></p>   
			<a class="black-button"href="check_offers.php?id=<?php echo $_GET["id"]; ?>">Check Offers</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>
				<a href="../login/signin_redirection.php" class="red-button">Make an Offer</a>
			<?php else: ?>
				<a class="red-button" href="make_offer.php?id=<?php echo $_GET["id"]; ?>">Make an Offer</a>
			<?php endif; ?>

		</div>
		<div class="cover">
			<img src="">
		</div>
		<div class="clear-float"></div>
		<hr>
		<div class="description">
			<p></p>
		</div>
		<div class="similar-books">
			<!-- ADD SIMILAR BOOKS FEATURE HERE -->
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
	let displayResults = function(response){
		console.log(response);

		// title
		if (response.volumeInfo.subtitle != undefined){
			$('.details-main .title').html(response.volumeInfo.title + ": " + response.volumeInfo.subtitle);
		}else{
			$('.details-main .title').html(response.volumeInfo.title);
		}

		// author(s)
		let authors = "";
		if (response.volumeInfo.authors != undefined){
			for (let j=0; j<response.volumeInfo.authors.length; j++){
				if (j != 0){
					authors = authors + ', ' + response.volumeInfo.authors[j];
				}else {
					authors = response.volumeInfo.authors[j];
				}
			}
			$('.details-main .author').html("By " + authors);
		}
		

		// book cover
		$('.cover img').attr("src", response.volumeInfo.imageLinks.thumbnail);
		

		// price
		if (response.saleInfo.retailPrice != undefined){
			if(response.saleInfo.retailPrice.currencyCode == 'USD'){
				$('.details-main .price').html("Retail Price: $" + response.saleInfo.retailPrice.amount);
			}else{
				$('.details-main .price').html("Retail Price: " + response.saleInfo.retailPrice.amount + " " + response.saleInfo.retailPrice.currencyCode);
			}
			
		}
		
		// publisher
		if (response.volumeInfo.publisher != undefined){
			$('.details-main .publisher').html("Publisher: " + response.volumeInfo.publisher);
		}

		// description
		if (response.volumeInfo.description != undefined){
			if (response.volumeInfo.description.length > 500){
				$('.details-main .description p').html(response.volumeInfo.description.substring(0,500) + '...');
			}else{
				$('.details-main .description p').html(response.volumeInfo.description);
			}
		}

		$(".secondary-loader-wrapper").fadeOut("slow");
	}
	ajaxCall();




</script>
<!-- Nav animation -->
<script src="nav_animation.js"></script>
</html>