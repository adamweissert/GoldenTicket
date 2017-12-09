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
        $passQuery = "SELECT password FROM projectUsers WHERE uid=$uid;"; //get password
        $passResult = $conn->query($passQuery);

        if(!passResult){
        exit("<script>alert('Could not get user data);document.location='editaccount.php';</script>");
        }
        else{
            $row = $passResult->fetch_array(MYSQLI_ASSOC);
            $dbPass = $row['password'];
            echo $dbPass;
        }
        $address = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['address']));
        $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
        $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['phone'])); 
        $currPass = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));
        $newPass =htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password2']));
        
        if(empty($email)||empty($phone)||empty($address)||empty($currPass)){
    exit("<script>alert('Please fill in all required fields!');document.location='editaccount.php';</script>");
        }
        else{
            $pass = hash('ripemd128', $salt1.$currPass.$salt2); //salt old pass to compare
            
            if($pass == $dbPass){ //if the typed pass equals the db pass
                if(!empty($newPass)){ //if the new pass field has a value
                    $newPassSalt = hash('ripemd128', $salt1.$newPass.$salt2); //salt new password if it is not empty
                    $passUpdate = "UPDATE projectUsers SET password='$newPassSalt' WHERE uid='$uid';"; //update the password field first
                    $passUpdateResult = $conn->query($passUpdate);
                    
                    $userUpdate = "UPDATE projectUsers SET email='$email', phone='$phone', address='$address' WHERE uid='$uid';"; //then update user info
                    $userUpdateResult = $conn->query($userUpdate);
                    
                    if(!userUpdateResult||!passUpdateResult){
                        exit("<script>alert('Error updating');document.location='editaccount.php';</script>");
                    }//if the queries fail
                    else{
                        echo "<script>alert('Update Submitted!');document.location='editaccount.php';</script>";
                    }//submitted correctly
                } //new pass is not empty
                else{ //if the new pass field is empty, do not update password
                    $userUpdate = "UPDATE projectUsers SET email='$email', phone='$phone', address='$address' WHERE uid='$uid';";
                    $userUpdateResult = $conn->query($userUpdate);
                    
                    if(!userUpdateResult){
                        exit("<script>alert('Error updating');document.location='editaccount.php';</script>");
                    }
                    else{
                        echo "<script>alert('Update Submitted!');document.location='editaccount.php';</script>";
                    }
                } 
            }//if the passwords equal each other
            else{
                exit("<script>alert('Incorrect Password!');document.location='editaccount.php';</script>");
            } //if the passwords do not match
        } //if the vals are not empty


?>