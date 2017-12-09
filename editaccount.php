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

        $userQuery = "SELECT * FROM projectUsers WHERE uid=$uid;";
        $userResult = $conn->query($userQuery);
        
        if(!$userResult){
            exit("<script>alert('Could not fetch User Data!');");
        }
        else{
            $userData = $userResult->fetch_array(MYSQLI_ASSOC);
            $username = $userData['username'];
            $address = $userData['address'];
            $email = $userData['email'];
            $phone = $userData['phone'];
            $firstname = $userData['firstname'];
            $lastname = $userData['lastname'];
            
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
        <h1><?php echo $firstname.' '.$lastname; ?>'s Account Information</h1>
        <form action="editsubmit.php" method="post">
        <table>
     <tr><td>Username: </td><td><input  size="35" name="username" style="background-color: #ccc;"class="regularText" value="<?php echo $username; ?>" readonly></td></tr>
    <tr><td>Email: </td><td><input name="email"  size="35" class="regularText" value="<?php echo $email; ?>"></td></tr>
    <tr><td>Phone: </td><td><input name="phone"  size="35" placeholder="(XXX-XXX-XXXX)" class="regularText" value="<?php echo $phone; ?>">  </td></tr>
        <tr><td>Address: </td><td><input name="address" size="35" class="regularText" value="<?php echo $address; ?>"></td></tr>
    <tr><td>Current Password: </td><td><input type="password"  size="35" name="password" class="regularText"></td></tr>
    <tr><td>New Password: </td><td><input  size="35" type="password" name="password2" class="regularText"></td></tr>
 </table>
<br>
<p><input type="submit" value="Update Account Info" class="registerButton"></p>
</form>
    </div>
</body>
</html>