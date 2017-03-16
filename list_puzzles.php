<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/main_style.css" type="text/css"> 
	<title>Final Project</title>
</head>
<body>
 	<?php
 		require('db_configuration.php');
	?> 
  <h2>Final Project</h2>
  <h3>Team: DOLPHIN</h3>
  <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
  <br><br><br>
  <div class="main-container">
  <div class="header">
    <a href="http://puzzles.thisisjava.com/"><img class="logo" src="./pic/logo.png"></img></a>
    <div class="imageDiv">
      <input class="headerButton" type="image" src="./pic/list.png">
      <input class="headerButton" type="image" src="./pic/addPuzzle.png">
      <a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
      <a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
    </div>
    <div class="divTitle"><font class="font">Name in Synonyms</font></div>
    <br>
  </div>
  <div id="pop_up_fail" class="container pop_up" style="display:none">
		<div class="pop_up_background">
			
			<img class="pop_up_img_fail" src="pic/info_circle.png">
			<div class="pop_up_text">Incorrect! <br>Try Again!</div>
			<button class="pop_up_button" onclick="toggle_display('pop_up_fail')">OK</button>
		</div>
  </div>
  <div class="style="font-size: 40px; margin: 10px;">Here's your "Name in Synonyms"</div>
  <div>
   <table class="main-tables">
	   <tr>
		  <th>Puzzle Name</th>
		  <th>Actions</th>
	   </tr>
  <?php
  
  ?>
	</table>
	<img id="succes_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none">
	</div>
	<input class="main-buttons" type="button" name="submit_solution" value="Submit Solution" onclick="submit_validation()">
    <input class="main-buttons" type="button" name="show_solution" value="Show Solution" onclick="show_solution()">
	<form action="index.php">
		<input class="main-buttons" type="submit" name="return_home" value="Return Home">
	</form>
	</div>
</body>
</html>