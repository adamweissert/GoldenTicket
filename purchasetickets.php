<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>The Golden Ticket</title>
	<link rel="stylesheet" href="css/stylesheet.css">
	<?php
		session_start(); //procedurally this should go first for the edit page to caputre id from session for a link
		require_once 'db.php';

		$uid = $_SESSION['uid']; //user id set from session data
		
		if (!$uid) { //check session data
			session_destroy();
			exit("<script>alert('Login First!');document.location='MovieLogin.html';</script>");
		}
		$conn = new mysqli($hn, $user, $passwd, $db);
		//echo $uid;
		if ($conn -> connect_error) {
			exit("Error in connecting  to MySQL"); //exit if the connection fails and display this message
		}
	?>
</head>
<body>
<div class="top">
    <h1 class="title">The Golden Ticket</h1>
	<ul class="nav">
	<li><a href="logout.php">LOG OUT</a></li>
	<li><a href="editaccount.php">ACCOUNT</a></li>
	<li><a href="movies.php">HOME</a></li>
	</ul>
</div>
<div class="containermovies">
	<?php

		$mid = $_REQUEST['id']; //grabs the movie id from the click
		$childPrice = 7.25; //prices of tickets
		$adultPrice = 9.75;
		$seniorPrice = 8.00;

		$movieQuery2 = "SELECT * FROM projectMovies WHERE mid ='$mid';"; //everything about this movie
		$movieResult2 = $conn->query($movieQuery2);

		if (!movieResult2) {
			exit("<p>Could not retrieve movie data</p>");
		}
		else{
			$row = $movieResult2->fetch_array(MYSQLI_ASSOC); //fetch all movie data in associative array
			$poster = $row['poster'];
			$title = $row['title'];
			$director = $row['director'];
			$synopsis = $row['synopsis'];
			$date = strtotime($row['date']); //fixing the date format
			$fixedDate = date('m/d/Y', $date);
			$time = $row['time'];

			$movieResult2->close();

			echo "<div class='moviePage'><div class='poster'><img src='posters/".$poster."'></div><div class='movieInfo'><h2>".$title."</h2><br><b>Director:</b> ".$director."<br><br><b>Synopsis:</b> ".$synopsis."<br><br><b>Date:</b> ".$fixedDate."<br><br><b>Time:</b> ".$time."PM<br><br><hr>";
		}
		$conn->close();

	?>	
	<h2>Buy Tickets</h2>
	<form action="submitpurchase.php" method="post">
		<input type="hidden" name='movie' value="<?php echo $mid;?>">
		<table width="50%">
			<tr><th style="text-align: left;">Ticket</th><th style="text-align: left;">Price</th><th style="text-align: left;">Quantity</th></tr>
			<tr><td >Child</td><td>$<?php echo $childPrice;?></td><td><input type="number" name="childQuantity" class="regularText" size="2"></td></tr>
			<tr><td>Adult</td><td>$<?php echo $adultPrice;?></td><td><input lang="en" type="number" name="adultQuantity" class="regularText" size="2"></td></tr>
			<tr><td>Senior Citizen</td><td>$<?php echo $seniorPrice;?>.00</td><td><input type="number" name="seniorQuantity" class="regularText" size="2"></td></tr>
		</table>
		<p><input type="submit" value="Purchase" class="registerButton"></p>
	</form>
	</div>
	</div>
</div>
</body>
</html>