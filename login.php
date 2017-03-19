<!DOCTYPE html>
<html>
<head>
  <style>
  .divContainer{
    position: relative;
    top: 20px;
    height: 30px;
    margin-left: 380px;
    width: 640px;
  }
  .text{
    font-size: 30px;
  }
  .textbox{
    outline: none;
    margin-left: 15px;
    margin-top: 10px;
    height:100px;
    width:480px;
    font-size: 40px;
    border-style: outset;
  }
  .loginbutton{
    position: relative;
    margin-left: 290px;
    margin-top: 30px;
  }
  .message{
    position: relative;
    margin-left: 260px;
    margin-top: 30px;
    height:100px;
    width:480px;
  }
  .messageText{
    font-size: 20px;
  }
  a{
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

  <div class="header">
  	<a href="./index.php"><img class="logo" src="./pic/logo.png"></img></a>
  	<div class="imageDiv">
	  <a href="./list_puzzles.php"><input class="headerButton" type="image" src="./pic/list.png"></a>
	  <a href="./add_puzzle.php"><input class="headerButton" type="image" src="./pic/addPuzzle.png"></a>
      <a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
      <a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
  	</div>
  	<div class="divTitle"><font class="font">Name in Synonyms</font></div>
  	<br>	
  </div>
  <br><br><br>
 <?php
  // if(isset($_POST['submit'])){
  //   $user = $_POST['user'];
  //   $pass = $_POST['pass'];


  //   $con = mysqli_connect("localhost","prashant","password$2");

  //   $records = mysqli_select_db($con, 'name_and_synonym');

  //   // Check connection
  //   if (!$con || !$records){
  //     echo "Failed to connect to MySQL/Database: " . mysqli_connect_error();
  //   } //else{echo"connected";};

  //     if(mysqli_num_rows(mysqli_query($con,"SELECT * FROM registered_users where user_email='$user' and password='$pass'"))){
  //       //Correct info
  //       $result = mysqli_query($con,"SELECT * FROM registered_users where user_email='$user' and password='$pass'");
  //       while($row = mysqli_fetch_array($result)){
  //         $expire = time()+60*60*24*30; //1 month
  //         setcookie("uid", $row['uid'], $expire);
  //         echo "Login successful as" . $row['user_email'] . "";
  //       }

  //     }else{
  //       //false info
  //       echo "<b>Username or Password is wrong.</b>";
  //     }

  // mysqli_close($con);
  // }
  echo "<div class='divContainer'>
          <form method='post'>
            <font class='text'>Email* </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
            <input class='textbox' type='text' name='user' id='user_email'><br><br>
            <font class='text'>Password* </font> 
            <input class='textbox' type='password' name='pass' id='password'><br><br>
              <div class='loginbutton'>
                <input type='image' height='90px' src='./pic/loginButton.png' name='submit'>
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