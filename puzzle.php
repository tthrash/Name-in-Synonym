<!DOCTYPE html>
<html>
<head>
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
  	<a href="http://puzzles.thisisjava.com/"><img class="logo" src="./pic/logo.png"></img></a>
  	<div class="imageDiv">
  		<input class="headerButton" type="image" src="./pic/list.png">
		<input class="headerButton" type="image" src="./pic/addPuzzle.png">
  		<input class="headerButton" type="image" src="./pic/addWord.png">
  		<input class="headerButton" type="image" src="./pic/login.png">
  	</div>
  	<div class="divTitle"><font class="font">Name in Synonyms</font></div>
  	<br>	
  </div>
  <?php
	 
    if (isset($_POST['submit'])){ //this is just a test to see the word in this page.
      echo "$_POST[puzzleWord]";
    }

  ?>
</body>
</html>