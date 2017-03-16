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
	?> 
  <h2>Final Project</h2>
  <h3>Team: DOLPHIN</h3>
  <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
  <br><br><br>
  <div class="main-container">
  <div class="header">
    <a href="http://puzzles.thisisjava.com/"><img class="logo" src="./pic/logo.png"></img></a>
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
  <div class="header2">Here's your "Name in Synonyms"</div>
  <div>
   <table class="main-tables" id="puzzle_table">
	   <tr>
		  <th>Clue</th>
		  <th>Synonym</th>
	   </tr>
  <?php
	$words = "";
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
					if($i == 0)
					{
						$words .= $word_value;
					}
					else
					{
						$words .= ','.$word_value;
					}
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
							echo '<input class="word_char active" type="text" rows="1" cols="1" maxlength="1" value="'.$word_value[$j].'"readonly/>';
						}
						else
						{
							echo '<input class="word_char" type="text" rows="1" cols="1" maxlength="1" name="'.$word_value.'_'.$j.'" value=""/>';
						}
					}
					echo '</tr>';
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
	<img id="succes_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none">
	</div>
	<input class="main-buttons" type="button" name="submit_solution" value="Submit Solution" onclick="submit_validation()">
    <input class="main-buttons" type="button" name="show_solution" value="Show Solution" onclick="show_solution()">
	<form action="index.php" class="align">
		<input class="main-buttons" type="submit" name="return_home" value="Return Home">
	</form>
	</div>
</body>
<script>
	function submit_validation()
	{
		var words = "<?php echo $words ?>";
		var wordsArray = words.split(",");
		var table = document.getElementById("puzzle_table");
		var tableLength = table.rows.length;
		var words_correct = true;
		var childrenLength = 0;
		
		for (var i = 1; i < tableLength; i++)
		{
			var word = "";
			childrenLength = table.rows[i].cells[1].children.length;
			for (var j = 0; j < childrenLength; j++)
			{
				word += table.rows[i].cells[1].children[j].value;
			}
			
			if(wordsArray[(i-1)] != word)
			{
				words_correct = false;
			}
        }
		if(words_correct) // success case
		{
			//alert("Sucess!");
			var el = document.getElementById("succes_photo");
			el.style.display = "block";
		}
		else{ // failure case
			var el = document.getElementById("pop_up_fail");
			el.style.display = "block";
			clear_puzzle();
		}
	}
	
	function show_solution()
	{
		var words = "<?php echo $words ?>";
		var wordsArray = words.split(",");
		var table = document.getElementById("puzzle_table");
		var tableLength = table.rows.length;
		var childrenLength = 0;
		
		for (var i = 1; i < tableLength; i++)
		{
			childrenLength = table.rows[i].cells[1].children.length;
			for (var j = 0; j < childrenLength; j++)
			{
				table.rows[i].cells[1].children[j].value = wordsArray[(i-1)].substring(j, (j+1));
			}
        }
	}
	
	function clear_puzzle()
	{
		var table = document.getElementById("puzzle_table");
		var tableLength = table.rows.length;
		var childrenLength = 0;
		
		for (var i = 1; i < tableLength; i++)
		{
			childrenLength = table.rows[i].cells[1].children.length;
			for (var j = 0; j < childrenLength; j++)
			{
				
				if(!(table.rows[i].cells[1].children[j].className.includes("active")))
				{
					table.rows[i].cells[1].children[j].value = "";
				}
			}
        }
	}
	
		function toggle_display(o) {
		var el = document.getElementById(o)
		el.style.display = "none";
	}
</script>
<!-- <script type="text/javascript" src="javascript/puzzle.js"></script> -->
</html>