<!DOCTYPE html>
<html lang="en">
	<head>
		<title>The Golden Ticket</title>
		<meta charset="UTF-8">
		<link href="css/stylesheet.css" rel="stylesheet">
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
		}?>
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
		
		$movies = array(); //set an empty array to be populated with movies
		$moviesQuery = "SELECT * FROM projectMovies ORDER BY date;";
		$moviesResult = $conn->query($moviesQuery);

		if (!$moviesResult) {
			session_destroy();
			exit("<p>Could not retrieve movies. Log in later.</p>");
		}
        else{
        	
			$amt = $moviesResult->num_rows;
			
			for($i=0;$i<=$amt;$i++){
				
				$movie = $moviesResult->fetch_object();
				array_push($movies, $movie);
				
			}
			$moviesResult->close();
			

			for($i=0;$i<$amt;$i++){
				$pulledDate = strtotime($movies[$i]->date); //get date from DB
				$formatDate = date('m/d/Y', $pulledDate); //format it in dd/mm/yyyy format to display
				$mid = $movies[$i]->mid; //get the id of the movie for the purchasing page

				echo "<div class='movie'><div class='poster'><a href='purchasetickets.php?id=$mid'><img src='posters/".$movies[$i]->posterSmall."'></a></div><div class='movieInfo'><h2>".$movies[$i]->title."</h2><br><b>Director:</b> ".$movies[$i]->director."<br><br><b>Synopsis:</b> ".$movies[$i]->synopsis."<br><br><b>Date:</b> ".$formatDate."<br><br><b>Time:</b> ".$movies[$i]->time."PM<br><br><input type='button' value='Buy Tickets'class='registerButton' onclick='window.location.href=\"purchasetickets.php?id=$mid\"'><br></div></div><br><br>";
				
			}
        }
	$conn->close();
	?>

</div>
</body>
</html>