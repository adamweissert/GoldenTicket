<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>The Golden Ticket</title>
	<link rel="stylesheet" href="css/stylesheet.css">
	<?php
		session_start();
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
	<li><a href="editaccount.php?user=<?php echo $uid; ?>">ACCOUNT</a></li>
	<li><a href="movies.php">HOME</a></li>
	</ul>
</div>

<div class="containerpurchase">
	<?php
		$childPrice = 7.25; //prices of tickets
		$adultPrice = 9.75;
		$seniorPrice = 8.00;
		$childQty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['childQuantity']));
		$adultQty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['adultQuantity']));
		$seniorQty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['seniorQuantity']));
		$movieID = $_POST['movie'];


		if ($seniorQty == 0 && $childQty== 0 && $adultQty == 0) {
			exit("<script>alert('Please Fill out all Desired Fields!');document.location='movies.php';</script>");
		}
		else{

		$moviesQuery = "SELECT * FROM projectMovies WHERE mid='$movieID';";
		$movieResult = $conn->query($moviesQuery);

		if (!$movieResult) {
			exit("<script>alert('Could Not Retrieve Movie Data');document.location='movies.php';</script>");
		}
		else{
			$row = $movieResult->fetch_array(MYSQLI_ASSOC); //fetch all movie data in associative array
			$date = $row['date']; //only need date and time to submit to the ticket table
			$time = $row['time'];

			$movieResult->close();
		}

		if (empty($childQty)) { //if this one is empty, then the total of it is 0, which will insert as a null value
			$childQty = 'NULL';
			$cTotal = 0;
		}
		else{
			$cTotal = $childPrice * $childQty; //otherwise you assume it has a value and multiply it by the price
		}

		if (empty($adultQty)) {
			$adultQty = 'NULL';
			$aTotal = 0;
		}
		else{
			$aTotal = $adultPrice * $adultQty;
		}

		if (empty($seniorQty)) {
			$seniorQty = 'NULL';
			$sTotal = 0;
		}
		else{
			$sTotal = $seniorPrice * $seniorQty;
		}

		

		$orderTotal = $cTotal + $aTotal + $sTotal; //overall order total adds together all 3 fields

		$order = "INSERT INTO projectTickets(uid, mid, childtickets, adulttickets, seniortickets, total, date, time) VALUES($uid, $movieID, $childQty, $adultQty, $seniorQty, $orderTotal, '$date', '$time');";

		//echo $order;

		$orderInsert = $conn->query($order);

		if ($orderInsert) {
            $purchaseSelect = "SELECT * FROM projectTickets WHERE uid=$uid AND mid=$movieID;";
            $purchaseResult = $conn->query($purchaseSelect);
            
            if(!purchaseResult){
                exit("<p>Could not get purchase info</p>");
            }
            else{
            $purchaseRow = $purchaseResult->fetch_array(MYSQLI_ASSOC);
            $movieSelect = "SELECT title FROM projectMovies WHERE mid='$movieID';";
            $movieResult = $conn->query($movieSelect);
                
                if(!$movieResult){
                    exit("<p>Could not get movie data");
                }
                else{
                    $movieRow = $movieResult->fetch_array(MYSQLI_ASSOC);
                    $movieTitle = $movieRow['title'];
                    $total = $purchaseRow['total'];
                    $time = $purchaseRow['time'];
                    $pulledDate = strtotime($purchaseRow['date']); //get date from DB
				    $formatDate = date('m/d/Y', $pulledDate); //format it in dd/mm/yyyy format to display
                    echo "<h1>The Golden Ticket thanks you for your purchase!</h1>";
                    echo "<h2>Review Your Order</h2>";
                    echo "<p><b>Movie:</b> $movieTitle</p>";
                    echo "<p><b>Date:</b> $formatDate</p>";
                    echo "<p><b>Time:</b> $time</p>";
                    echo "<p><b>Total: $</b> $total</p>";
                    echo "<br><input type='button' class='registerButton' value='Return to Homepage' onclick='window.location.href=\"movies.php\"'";
                }
			
            }
		}
		else{
			exit("<script>alert('Failure to Place Order!');document.location='movies.php';</script>");
		}

	}
	?>
</div>
</body>
</html>