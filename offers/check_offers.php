<?php
require '../config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno){
	echo $mysqli->connection_error;
	exit();
}

$sql = "SELECT conditions.condition AS cond, price, first_name, phone_number, email 
	FROM offers 
	JOIN users 
		ON offers.user_id = users.id
	JOIN conditions
		ON offers.condition_id = conditions.id
	WHERE book_id = '" . $_GET['id'] . "';";

$results = $mysqli->query($sql);
if(!$results) {
	echo $mysqli->error;
	exit();
}

if($results->num_rows == 0) {
	$nobooks = true;
}

$price_list = [];
$condition_list = [];
$email_list = [];
$phone_number_list = [];
$first_name_list = [];

while($row = $results->fetch_assoc()){
	array_push($price_list, $row["price"]);
	array_push($condition_list, $row["cond"]);
	array_push($email_list, $row["email"]);
	array_push($phone_number_list , $row["phone_number"]);
	array_push($first_name_list , $row["first_name"]);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Check Offers</title>
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
	<div class="offer-main">
		<div class="offer-form">
			<h1 class="heading">Check Offers</h1>
			<hr>
			<h1 class="title"></h1>
			<h1 class="author"></h1>
			<br>
			<?php if(isset($nobooks)) : ?>
				<p class="red">Unfortunately, there are currently no offers for this book.</p>
			<?php else: ?>
				<table class="offers-table">
				  <tr>
				    <th>Name</th>
				    <th>Email</th>
				    <th>Phone</th>
				    <th>Price</th>
				    <th>Condition</th>
				  </tr>
				</table>
			<?php endif; ?>
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>

<script>
	// ajax call for title and author
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
		$(".secondary-loader-wrapper").fadeOut("normal");
	}
	ajaxCall();
	
	// copy php arrays into js arrays
	let num_rows = <?php echo $results->num_rows ?>;

	let price_list = [];
	let condition_list = [];
	let email_list = [];
	let phone_number_list = [];
	let first_name_list = [];

	<?php for($i=0; $i<$results->num_rows; $i++) : ?>
		price_list.push('<?php echo $price_list[$i]; ?>');
		condition_list.push('<?php echo $condition_list[$i]; ?>');
		email_list.push('<?php echo $email_list[$i]; ?>');
		phone_number_list.push('<?php echo $phone_number_list[$i]; ?>');
		first_name_list.push('<?php echo $first_name_list[$i]; ?>');
	<?php endfor; ?>

	// append offers
	for (let i=0; i<num_rows; i++){
		$('.offers-table').append('<tr></tr>')
		$('.offers-table tr:last-child()').append('<td></td>')
		$('.offers-table tr:last-child() td:last-child()').html(first_name_list[i]);
		$('.offers-table tr:last-child()').append('<td></td>')
		$('.offers-table tr:last-child() td:last-child()').html(email_list[i]);
		$('.offers-table tr:last-child()').append('<td></td>')
		$('.offers-table tr:last-child() td:last-child()').html(phone_number_list[i]);
		$('.offers-table tr:last-child()').append('<td></td>')
		$('.offers-table tr:last-child() td:last-child()').html("$" + price_list[i]);
		$('.offers-table tr:last-child()').append('<td></td>')
		$('.offers-table tr:last-child() td:last-child()').html(condition_list[i]);
	}
	
</script>
<!-- Nav animation -->
<script src="nav_animation.js"></script>
</html>