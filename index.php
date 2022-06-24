<?php
	require 'config/config.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno){
		echo $mysqli->connection_error;
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TextTrade</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:image" content="assets/logo.png">
    <meta property="og:type" content="website">
    <meta property="og:title" content="TextTrade">
    <meta property="og:description" content="A platform for Trojans to buy and sell used textbooks." >
    <link rel="icon" href="assets/favicon.png">
		

    <!-- External CSS -->
	<link rel="stylesheet" type="text/css" href="style.css">

	

	<!-- Fonts -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
	<div class="loader-wrapper">
		<div class="spinner"></div>
	</div>
	

	<div class="main-nav">
		<a href="index.php"><img alt="logo" id="logo" src="assets/logo.png"></a>
		<div id="navbar">
			<a href="index.php" class="active">Search</a>
			<a href="offers/my_books.php">My Books</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

					<a href="login/sign_in.php">Sign In</a>

			<?php else: ?>
				
					<a href="login/logout.php">Sign Out</a>
					
			<?php endif; ?>
		</div>
	</div>
	<div class="home-main">
		
		<div class="search-bar">
			<h1>Find a textbook you'd like to buy or sell.</h1>
			<form id="search-form" >
				<input id="searched-book" type="text" placeholder="organic chemistry" onclick="">
			</form>
		</div><!-- .search-bar -->
		<div id="book-list">
			
		</div>
	</div><!-- .main -->
	<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>


	<script>


		// pre-loader
		$(window).on("load", function(){
			$(".loader-wrapper").fadeOut("slow");
			$(".search-bar h1").addClass( "search-text-animation" );
			$(".search-bar input").addClass( "search-bar-animation" );
		});



		let ajaxCall = function(){

			let bookTitle = $('#searched-book').val();
			// runs ajax
			$.ajax({
				method: "GET",
				url: "https://www.googleapis.com/books/v1/volumes",
				data: {
					q: bookTitle,
					maxResults: 20,
					key: "AIzaSyBFg2o-r7i_c1Gl0zKmkUP6zj2dJxA9jN8"
				}

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
			$('#book-list').empty();
			for(let i=0; i<20; i++){


				// container div
				$('#book-list').append('<div class="books"></div>');

				// book cover
				$('#book-list .books:last-child()').append('<img class="book-cover">');
				if(response.items[i].volumeInfo.imageLinks != undefined){
					$('#book-list .books:last-child() img').attr("src",response.items[i].volumeInfo.imageLinks.thumbnail);
				}else{
					$('#book-list .books:last-child() img').attr("src", "assets/bookcover.png");
				}
				

				// title
				$('#book-list .books:last-child()').append('<a target="_blank"><h2 class="title"></h2></a>')
				$('#book-list .books:last-child() a').attr("href", "offers/details.php?id=" + response.items[i].id);

				// subtitle
				if (response.items[i].volumeInfo.subtitle != undefined){
					$('#book-list .books:last-child() h2').html(response.items[i].volumeInfo.title + ": " + response.items[i].volumeInfo.subtitle);
				}else{
					$('#book-list .books:last-child() h2').html(response.items[i].volumeInfo.title);
				}
				
				// author
				$('#book-list .books:last-child()').append('<p class="author"></p>')
				let authors = "";
				if(response.items[i].volumeInfo.authors != undefined){
					for (let j=0; j<response.items[i].volumeInfo.authors.length; j++){
					if (j != 0){
						authors = authors + ', ' + response.items[i].volumeInfo.authors[j];
					}else {
						authors = response.items[i].volumeInfo.authors[j];
					}
				}
				$('#book-list .books:last-child() p').html(authors);
				}
				
				
				// description
				$('#book-list .books:last-child()').append('<p class="description"></p>')
				if(response.items[i].volumeInfo.description != undefined){
					if (response.items[i].volumeInfo.description.length > 200){
						$('#book-list .books:last-child() .description').html(response.items[i].volumeInfo.description.substring(0,150) + '...');
					}else{
						$('#book-list .books:last-child() .description').html(response.items[i].volumeInfo.description);
					}
				}
				$('#book-list').append('<div class="clear-float"></div>')
				$('#book-list').append('<hr></hr>')
			}


			
		}	
		$('#search-form').submit(function(event){
			event.preventDefault();
			$(".search-bar").css("padding-top", "15vh")
			ajaxCall();
			
		})

		// pre-loader
		$(window).on("load", function(){
			$(".loader-wrapper").fadeOut("slow");
		});

	</script>
	<!-- Nav animation -->
	<script src="js/nav_animation.js"></script>
	
</body>
</html>