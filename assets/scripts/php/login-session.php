<?php
if (isset($_POST['login-submit'])) {

	require "connection.php";

	$email = $POST['email'];
	$pass = $POST['pass'];

	if(empty($email)||empty($pass)){
		header("Location: http://locknload.rf.gd/login.php");
		exit();
	}
	else{
		$sql = "select * from user_table where email =? or email = ?;";
		$stmt = mysqli_stmt_init($connection);
		if(!mysqli_stmt_prepare($stmt)){
			header("Location: http://locknload.rf.gd/login.php");
			exit();
		}
		else{
			mysqli_stmt_bind_param($stmt, "ss", $email, $email);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if($row = mysqli_fetch_assoc($result)){
				$passCheck = passwordVerify($pass, $row['pass']);
				if($passCheck==false){
					header("Location: http://locknload.rf.gd/login.php");
					exit();
				}
				else if ($passCheck==true) {
					session_start();
					$_SESSION['email'] = $row['email'];
					$_SESSION['pass'] = $row['pass'];

				}
				else{
					header("Location: http://locknload.rf.gd/admin-main.php");
					exit();
				}
			}
			else{
				echo "no such user";
				header("Location: http://locknload.rf.gd/login.php");
				exit();
			}
		}
	}
}

else{
	exit();
}