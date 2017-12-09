<!DOCTYPE html>
<html lang="en">
<head>
	<title>The Golden Ticket Movies</title>
	<meta charset="utf-8">
	<link href="css/stylesheet.css" rel="stylesheet">
</head>
<body>
<div class="top">
    <h1 class="title">The Golden Ticket</h1>
</div>
<div class="containerregister">
<?php

require_once 'db.php';

$conn = new mysqli($hn, $user, $passwd, $db);

if ($conn -> connect_error) {
	exit("Error in connecting  to MySQL"); //exit if the connection fails and display this message
}

//validate account info------------------------------------------
$username = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']));
$password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));
$passwordRetype = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password2']));
//validate personal info------------------------------------------
$firstname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['fname']));
$lastname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lname']));
$email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
$addr1 = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['addr1']));
$addr2 = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['addr2']));
$city = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['city']));
$state = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['state']));
$zip = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['zip']));
$phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone']));

$address = "$addr1, $addr2";

if (!empty($addr2)) {
		$address .= ', ';
}
$address .= "$city, $state, $zip";

/*echo $username;
echo "<br>";
echo $password;
echo "<br>";
echo $passwordRetype;
echo "<br>";
echo $firstname;
echo "<br>";
echo $lastname;
echo "<br>";
echo $email;
echo "<br>";
echo $addr1;
echo "<br>";
echo $addr2;
echo "<br>";
echo $city;
echo "<br>";
echo $state;
echo "<br>";
echo $zip;
echo "<br>";
echo $phone;*/

if (empty($username)||empty($password)||empty($passwordRetype)||empty($firstname)||empty($lastname)||empty($email)||empty($addr1)||empty($city)||empty($state)||empty($zip)||empty($phone)) {
	exit("<script>alert('Some fields are empty!'); document.location='Register.html';</script>");
}
//is anything empty
if ($password != $passwordRetype) {
	exit("<script>alert('Your passwords do not match!'); document.location='Register.html';</script>");
}
//do the passwords match

$hashed = hash('ripemd128', $salt1.$password.$salt2);
//encrypt user password using salts in db.php

$usersSelect = "SELECT * FROM projectUsers WHERE username = '$username';"; 
//select all from users where username matches
$result = $conn->query($usersSelect); 

if ($result && $result->num_rows != 0) {
	exit("<script>alert('Username is already taken!'); document.location='Register.html';</script>");
}
//check if the username is taken

$usersInsert = "INSERT INTO projectUsers(firstname, lastname, username, password, email, phone, address)
VALUES('$firstname', '$lastname', '$username', '$hashed', '$email', '$phone', '$address');";

$insertResult = $conn->query($usersInsert);

if($insertResult){
	echo "<h1>Account Created!</h1>";
	echo "<h3>Sending you back to the login page...</h3>";
	echo "<script>setTimeout('location = \"MovieLogin.html\"', 3000);</script>";
}
else{
	exit("<h1>Error in creating account!</h1>");
}
?>
</div>
</body>
</html>	
