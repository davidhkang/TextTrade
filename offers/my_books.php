<?php
require '../config/config.php';
if(isset($_SESSION["logged_in"])){
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno){
		echo $mysqli->connection_error;
		exit();
	}
	$sql = "SELECT * FROM offers WHERE user_id = " . $_SESSION['user_id'] . ";";
	$results = $mysqli->query($sql);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}
	$num_rows = $results->num_rows;
	if($num_rows == 0) {
		$nobooks = true;
	}
	$offer_id_list = [];
	$book_id_list = [];
	$price_list = [];
	while($row = $results->fetch_assoc()){
		array_push($offer_id_list, $row["id"]);
		array_push($book_id_list, $row["book_id"]);
		array_push($price_list, $row["price"]);
	}
	$mysqli->close();
}else{
	$num_rows = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Books</title>
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
			<a href="../offers/my_books.php" class="active">My Books</a>
			<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>
					<a href="../login/sign_in.php">Sign In</a>
			<?php else: ?>
					<a href="../login/logout.php">Sign Out</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="my-books-main">
		<h1 class="heading">My Books</h1>
		<?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>

			<p>Sign in or sign up to make your first listing.</p>
			<a href="../login/sign_in.php" class="red-button">Sign In</a>
		<?php elseif(isset($nobooks)) : ?> 
			<p class="red">You currently have no books listed.</p>
		<?php endif; ?>
		<div class="my-books-results">
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>
<script>
	let ajaxCall = function(id, count){
		// runs ajax
		$.ajax({
			method: "GET",
			url: "https://www.googleapis.com/books/v1/volumes/" + id
		})
		.done(function(response){
			console.log("success")
			displayResults(response, count);
		})
		.fail(function(){
			console.log('Error!');
		});
	}

	// calls ajax for the number of books that the user has listed
	$('.my-books-results').empty();
	let num_rows = <?php echo $num_rows; ?>;
	let price_list = [];
	let book_id_list = [];
	let offer_id_list = [];

	<?php for($i=0; $i<$num_rows; $i++) : ?>
		price_list.push('<?php echo $price_list[$i]; ?>');
		book_id_list.push('<?php echo $book_id_list[$i]; ?>');
		offer_id_list.push('<?php echo $offer_id_list[$i]; ?>')
	<?php endfor; ?>

	console.log(price_list);
	console.log(book_id_list);

	for (i=0; i<num_rows; i++){
		ajaxCall(book_id_list[i], i);
	}
    	
	let displayResults = function(response, count){
		console.log(response);
		$('.my-books-results').append('<hr>')
		$('.my-books-results').append('<div class="books"></div>');
		// book cover
		$('.my-books-results .books:last-child()').append('<img class="book-cover">');
		if(response.volumeInfo.imageLinks != undefined){
			$('.my-books-results .books:last-child() img').attr("src",response.volumeInfo.imageLinks.thumbnail);
		}else{
			$('.my-books-results .books:last-child() img').attr("src", "images/bookcover.png");
		}

		// title
		$('.my-books-results .books:last-child()').append('<h2 class="title"></h2>')

		// subtitle
		if (response.volumeInfo.subtitle != undefined){
			$('.my-books-results .books:last-child() h2').html(response.volumeInfo.title + ": " + response.volumeInfo.subtitle);
		}else{
			$('.my-books-results .books:last-child() h2').html(response.volumeInfo.title);
		}
		
		// author
		$('.my-books-results .books:last-child()').append('<p class="author"></p>')
		let authors = "";
		if(response.volumeInfo.authors != undefined){
			for (let j=0; j<response.volumeInfo.authors.length; j++){
			if (j != 0){
				authors = authors + ', ' + response.volumeInfo.authors[j];
			}else {
				authors = response.volumeInfo.authors[j];
			}
		}
		$('.my-books-results .books:last-child() p').html("By " + authors);
		}
		
		// price
		$('.my-books-results .books:last-child()').append('<p class="price"></p>')
		$('.my-books-results .books:last-child() .price').html("Price offered: $" + price_list[count]);

		// buttons
		$('.my-books-results .books:last-child()').append('<a class="black-button" href="../edit_listing/remove_listing.php?offer_id=' + offer_id_list[count] + '">Remove</a><a href="../edit_listing/edit_listing.php?offer_id=' + offer_id_list[count] + '" class="red-button">Edit Listing</a>')

		$('.my-books-results').append('<div class="clear-float"></div>')
	}
</script>
<!-- Nav animation -->
<script src="nav_animation.js"></script>
</html>