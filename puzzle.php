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
		require('create_puzzle.php');
		
		if(isset($_POST['submit_solution']))
		{
			redirect('process_puzzle.php');
		}else if (isset($_POST['show_solution']))
		{
			redirect('index.php'); // get rid of this and check for this when we check for submit, then if it is set
			                                   // call a different php function that will display the full table in this php page.
		}
		function redirect($url) {
			ob_start();
			header('Location: '.$url);
			ob_end_flush();
			die();
		}
	?>
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
  	<div class="divTitle font">Name in Synonyms</div>
  	<br>	
  </div>
  <center>
	<div id="pop_up_fail" class="container pop_up">
		<div class="pop_up_background">
			
			<img class="pop_up_img_fail" src="pic/info_circle.png">
			<div class="pop_up_text">Incorrect! <br>Try Again!</div>
			<button class="pop_up_button" onclick="toggle_display('pop_up_fail')">OK</button>
		</div>
	</div>
  <div style="font-size: 40px; margin: 10px;">Here's your "Name in Synonyms"</div>
  <form action="<?php echo htmlspecialchars("submit_validation.php");?>" method="post">
   <table class="main-tables">
	   <tr>
		  <th>Clue</th>
		  <th>Synonym</th>
	   </tr>
  <?php
	if(isset($_POST['submit']))
	{
		if(isset($_POST['puzzleWord']))
		{
			$nameEntered = $_POST['puzzleWord'];
			$nameEntered = strtolower($nameEntered);
			$nameEntered = trim($nameEntered);
			$puzzle_id = checkName($nameEntered);
			if($puzzle_id != null)
			{
				$nameLen = strlen($nameEntered);
				for($i = 0; $i < $nameLen; ++$i)
				{
					$word_id = getWordId($puzzle_id, $i);
					$word_value = getWordValue($word_id);
					$clue_word = getClueWord($word_id);
					$char_indexes = getCharIndex($word_id, $nameEntered[$i]);
					echo '<tr>
							 <td>'.$clue_word.'</td>
							 <td>';
				    $wordlen = strlen($word_value);
					for($j = 0; $j < $wordlen; ++$j)
					{
						if(in_array($j, $char_indexes))
						{
							echo '<input class="word_char active" type="text" rows="1" cols="1" maxlength="1" name="'.$word_id.'_'.$j.'" value="'.$word_value[$j].'"readonly/>';
						}
						else
						{
							echo '<input class="word_char" type="text" rows="1" cols="1" maxlength="1" name="'.$word_id.'_'.$j.'" value=""/>';
						}
					}
				}
			}
			else{
				// set name-textbox on index.php to error message that name doesn't exist
				// re
			}
		}
	}
  ?>
	</table>
	<input class="showMe align" type="submit" name="submit_solution" value="Submit Solution">
    <input class="showMe align" type="submit" name="show_solution" value="Show Solution">
   </form>
   </center>
</body>
<script>
	function toggle_display(o) {
		var el = document.getElementById(o)
		el.style.display = "none";
	}
</script>
</html>