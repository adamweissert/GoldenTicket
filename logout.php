<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		<meta charset="UTF-8">
		<link href="css/stylesheet.css" rel="stylesheet">
	</title>
	<body>
	<div class="top">
    	<h1 class="title">The Golden Ticket</h1>
	</div>
	<?php
	session_start();
	session_destroy();

	$_SESSION['uid'] = 0;

	header("Location: MovieLogin.html");
	?>

	</body>
</head>
</html>