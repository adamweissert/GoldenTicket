<?php
/*ADAM WEISSERT
	  arw1016
	  CPSC 317
	  Final Project: Create a movie ticketing site where the  user  can  register  to  become  a  member  by  filling  a  registration  form  
	  including  the  user  name,  email  address,  and  password  (the  user  is  able  to  update  account  information  later).  
	  After  the  user  account  is  created,  the  user  can  log  into  the  website,  browse  through  current  movies  that  are  playing,  
	  pick  a  date  and  buy  tickets.  There are different fixed prices for kids, adults, and seniors.*/
		session_start(); //procedurally this should go first for the edit page to caputre id from session for a link
		require_once 'db.php';

		$conn = new mysqli($hn, $user, $passwd, $db);

		if ($conn -> connect_error) {
			exit("Error in connecting  to MySQL"); //exit if the connection fails and display this message
		}

		
		$username =	mysqli_real_escape_string($conn, $_POST['Username']);
		$password =	 mysqli_real_escape_string($conn, $_POST['Password']);
		//login info

		if (empty($username) || empty($password)) {
			exit("<script>alert('Please Fill in all Fields!');document.location='MovieLogin.html';</script>");
		}

		$hashLogin = hash('ripemd128', $salt1.$password.$salt2);
		$userQuery = "SELECT * FROM projectUsers where username ='$username' and password='$hashLogin';";
		$userResult = $conn->query($userQuery);

		if($userResult && $userResult->num_rows == 0){
			session_destroy();
			exit("<script>alert('Username/Password Incorrect! Try Again');document.location='MovieLogin.html';</script>");
		}
		else{
		$user = $userResult->fetch_object();
		$_SESSION['uid'] = $user->uid;
		$userResult->close();
		echo "<script>document.location='movies.php';</script>";
		}

	$conn->close();
?>
