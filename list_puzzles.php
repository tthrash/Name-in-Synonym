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
  <div id="pop_up_fail" class="container pop_up" style="display:none">
		<div class="pop_up_background">
			
			<img class="pop_up_img_fail" src="pic/info_circle.png">
			<div class="pop_up_text">Incorrect! <br>Try Again!</div>
			<button class="pop_up_button" onclick="toggle_display('pop_up_fail')">OK</button>
		</div>
  </div>
  <div>
   <table class="main-tables">
	   <tr>
		  <th>Puzzle Name</th>
		  <th>Actions</th>
	   </tr>
  <?php
		
	  	$sql = 'SELECT * FROM puzzles;';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		
		while($row = $result->fetch_assoc())
		{
			echo '<tr>
					 <td><a href=puzzle.php?puzzleName="'.$row["puzzle_name"].'>'.$row["puzzle_name"].'</a></td>
					 <td>
						 <a href="puzzle.php?puzzleName='.$row["puzzle_name"].'">
							<img class="table_image" src="pic/play.png" alt="Play '.$row["puzzle_name"].' puzzle"></img>
						 </a>
						 <a href="change_puzzle.php?puzzleName='.$row["puzzle_name"].'&button=edit">
							<img class="table_image" src="pic/edit.jpg" alt="Edit '.$row["puzzle_name"].' puzzle"></img>
						 </a>
						 <a href="change_puzzle.php?puzzleName='.$row["puzzle_name"].'&button=delete">
							<img class="table_image" src="pic/delete.jpg" alt="Delete '.$row["puzzle_name"].' puzzle"></img>
						 </a>
					 </td>
					 </tr>';
		}
		
  ?>
	</table>
</body>
</html>