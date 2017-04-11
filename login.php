<!DOCTYPE html>
<html>
<head>
	<?PHP
		//session_start();
		require('session_validation.php');
		require_once('db_configuration.php');
		/*
		if (isset($_SESSION['valid_user'])){
			echo "Valid User: ";
			echo $_SESSION['valid_user']."</br>";
		}
		else if (isset($_SESSION['valid_admin'])){
			echo "Valid Admin: ";
			echo $_SESSION['valid_admin'];
		}
		else{
		} 
		*/
	?>
	<style>
		.divContainer {
			position: relative;
			top: 20px;
			height: 30px;
			margin-left: 380px;
			width: 640px;
		}
		
		.text {
			font-size: 30px;
		}
		
		.textbox {
			outline: none;
			margin-left: 15px;
			margin-top: 10px;
			height: 100px;
			width: 480px;
			font-size: 40px;
			border-style: outset;
		}
		
		.loginbutton {
			position: relative;
			margin-left: 290px;
			margin-top: 30px;
		}
		
		.message {
			position: relative;
			margin-left: 260px;
			margin-top: 30px;
			height: 100px;
			width: 480px;
		}
		
		.messageText {
			font-size: 20px;
		}
		
		a {
			color: red;
			font-weight: bold;
			text-decoration: none;
		}
	</style>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/main_style.css" type="text/css">
</head>
<title>Final Project</title>
<body>
	<h2>Final Project</h2>
	<h3>Team: DOLPHIN</h3>
	<h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
	<br><br><br>
	<div class="nav-wrapper">
		<div class="navBar">
			<?PHP 
			//session_start();
			echo getTopNav(); 
			?>
		</div>
	</div>
	<br><br><br>
	<?php
		if (isset($_SESSION['valid_user'])){
			echo "Valid User: ";
			echo $_GET($_SESSION["valid_user"])."</br>";
		}
		else if (isset($_SESSION['valid_admin'])){
			echo "Valid Admin: ";
			echo $_GET($_SESSION['valid_admin']);
		}
		else{
		} 
	
    if(isset($_POST['submit']))
	{
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		
     
		//Check connection
		if (!empty($user) & !empty($pass) & ($user != 'admin') & ($pass != 'admin'))
		{
     		session_start();
	/* 			$result = mysqli_query($con,"SELECT * FROM users where user_email='$user' and password='$pass'");
				$row = mysql_fetch_array($result);
				
				if ($row >= 1){
					while($row = mysqli_fetch_array($result)){
					$expire = time()+60*60*24*30; //1 month
					//setcookie("uid", $row['id_varified'], $expire);
					$_SESSION['valid_user'] = $user;
					echo "Login successful as" . $row['user_email'] . "";
					//echo $_SESSION['Name'];
					}
				}
				else {
					echo "Not a valid user account";
			 } */
		}else if (($user == 'admin') && ($pass == 'admin')){
				session_start();
				$_SESSION['valid_admin'] = $user;
				$expire = time()+60*60*24*30; //1 month
				//setcookie("admin", $user, $expire);
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=admin.php\">";
		} else if (($user == null) && ($pass != null)){
		echo "Username field is blank";
		}else if (($user != null) && ($pass == null)){
			echo "Password field is blank";
		} else if (($user == null) && ($pass == null)){
			echo "Username & Password field is blank";
		}	else{
			 //false info
			 echo "<b>Username or Password is wrong.</b>";
		}
	}

  echo "<div class='divContainer'>
          <form method='POST' action='login.php'>
            <font class='text'>Email* </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
            <input class='textbox' type='text' name='user' id='user_email'><br><br>
            <font class='text'>Password* </font> 
            <input class='textbox' type='password' name='pass' id='password'><br><br>
              <div class='loginbutton'>
                <input type='Submit' height='90px' src='./pic/loginButton.png' name='submit' alt='Submit' Value='Submit'>
              </div>
          </form> 
          <div class='message'>
          <div class='messageText'>
          Don't have an account?  <a href=''>Create One!</a><br>
          Forgot Password?  <a href=''>Request a reset!</a></div> 
          </div>
        </div>";
  ?>

</body>
</html>