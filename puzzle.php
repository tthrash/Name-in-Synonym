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
    <a href="./index.php"><img class="logo" alt="logo button to index page" src="./pic/logo.png" ></a>
    <div class="imageDiv">
	  <a href="./list_puzzles.php"><img class="headerButton" alt="list button" src="./pic/list.png"></a>
	  <a href="./add_puzzle.php"><img class="headerButton" alt="add puzzle button" src="./pic/addPuzzle.png"></a>
      <a href="./addWordPair.php"><img class="headerButton" alt="add word button" src="./pic/addWord.png"></a>
      <a href="./login.php"><img class="headerButton" alt="login button" src="./pic/login.png"></a>
    </div>
    <div class="divTitle"><div class="font">Name in Synonyms</div></div>
    <br>
  </div>
  <div id="pop_up_fail" class="container pop_up" style="display:none">
=======
	<?php
		require('create_puzzle.php');
		require('session_validation.php');
	?>
	<h2>Final Project</h2>
	<h3>Team: DOLPHIN</h3>
	<h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
	<br><br><br>
	<div class="nav-wrapper">
		<div class="navBar">
			<?PHP echo getTopNav(); ?>
		</div>
	</div>
	<div id="pop_up_fail" class="container pop_up" style="display:none">
>>>>>>> 8f5bd71fc2e423d100a70d7a629051b60848a574
		<div class="pop_up_background">

			<img class="pop_up_img_fail" alt="fail puzzle pop up" src="pic/info_circle.png">
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
			$words = createPuzzle($nameEntered);
		}
	}else if(isset($_GET['puzzleName']))
	{
		$nameEntered = $_GET['puzzleName'];
		$words = createPuzzle($nameEntered);
	}
	
	
	function createPuzzle($nameEntered)
	{
			$nameExist = false;
			$words = "";
			// clean up the name entered
			$nameEntered = validate_input($nameEntered);
			$nameEntered = strtolower($nameEntered);
			
			
			// check if the name already exists
			$nameExist = checkName($nameEntered);
			if(!$nameExist)
			{
				create_puzzle($nameEntered);
				create_puzzle_words($nameEntered);
			}
			
			$puzzle_id = getPuzzleId($nameEntered);

			if($puzzle_id != null)
			{
				$puzzle_name_chars = getWordChars($nameEntered);
				// get length of puzzle name
				$nameLen = count($puzzle_name_chars);
				
				// for each character in the puzzle name
				for($i = 0; $i < $nameLen; ++$i)
				{
					// get the word_id from the puzzle_words table at position $i in the puzzle name.
					$word_id = getWordId($puzzle_id, $i);

					// then get the word_value of that word_id
					$word_value = getWordValue($word_id);
					
					// get the character array of the word
					$word_chars = getWordChars($word_value);
					
					// this is for building a comma seperate string of the words for the puzzle. For later use in javascript.
					if($i == 0)
					{
						$words .= buildJScriptWords($word_chars);
					}
					else
					{
						$words .= ','.buildJScriptWords($word_chars);
					}
					
					// output the clue word of the word (the word_value with the word_id = rep_id of the word)
					$clue_word = getClueWord($word_id);
					
					$char_indexes = getCharIndex($word_id, $puzzle_name_chars[$i]);
					// Add clue word to first column of the row
					echo '<tr>
							 <td>'.$clue_word.'</td>
							 <td>';
					
				    $wordlen = count($word_chars);
					
					for($j = 0; $j < $wordlen; ++$j)
					{
						if(in_array($j, $char_indexes))
						{
							echo '<input class="word_char active" type="text" maxlength="7" value="'.$word_chars[$j].'" readonly/>';
						}
						else
						{
							echo '<input class="word_char" type="text" maxlength="7" value=""/>';
						}
					}
					echo '</tr>';
				}
			}
			else{
				// set name-textbox on index.php to error message that name doesn't exist
				// re
			}
			return $words;
	}
	
	// Takes in an array of characters and builds a string by seperating them with '-'. Returns the string.
	function buildJScriptWords($word_chars)
	{
		$string = "";
		$wordLng = count($word_chars);
		for($i = 0; $i < $wordLng ; ++$i)
		{
			if($i == 0)
			{
				$string .= $word_chars[$i];
			}
			else{
				$string .= '-'.$word_chars[$i];
			}
		}
		return $string;
	}
  ?>
	</table>
	<img id="succes_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none">
	</div>
	<input class="main-buttons" type="button" name="submit_solution" value="Submit Solution" onclick="main_buttons('submit');">
    <input class="main-buttons" type="button" name="show_solution" value="Show Solution" onclick="main_buttons('show');">
	</div>
	
 <script>
	function main_buttons (button_name)
	{
		var words = "<?php echo $words ?>";
		var wordsArray = words.split(",");
		var table = document.getElementById("puzzle_table");
		var tableLength = table.rows.length;
		var words_correct = true;
		var childrenLength = 0;
		
		for (var i = 1; i < tableLength; i++)
		{
			if(button_name == "submit")
			{
				words_correct = submit_validation(table, wordsArray[i-1], i);
				
				if(words_correct === false)
				{
					break;
				}
			}
			else if (button_name == "show")
			{
				show_solution(table, wordsArray[i-1], i);
			}
        }
		
		if(button_name == "submit")
		{
			checkCorrect(words_correct);
		}
	}
	
	function submit_validation(table, word, i)
	{
			var input_word = "";
			var theWord = rebuildWord(word);
			var childrenLength = table.rows[i].cells[1].children.length;
			for (var j = 0; j < childrenLength; j++)
			{
				input_word += table.rows[i].cells[1].children[j].value;
			}
			
			if(theWord != input_word)
			{
				return false;
			}
			else
			{
				return true;
			}
	}
	
	// rebuild the word whose charactes are seperated by "-".
	function rebuildWord(word)
	{
		
		var built_word = "";
		var word_characters = word.split("-");
		var array_length = word_characters.length;
		
		for(var i = 0; i < array_length; ++i)
		{
			built_word += word_characters[i];
		}
		return built_word;
	}
	
	function checkCorrect(words_correct)
	{
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
	
	function show_solution(table, word, i)
	{
		var childrenLength = 0;
		var word_array = null;
		var nWord = word;
		
		word_array = nWord.split("-");
		childrenLength = table.rows[i].cells[1].children.length;
		
		for (var j = 0; j < childrenLength; j++)
		{
			table.rows[i].cells[1].children[j].value = word_array[j];
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
		var el = document.getElementById(o);
		el.style.display = "none";
	}
</script> 
</body>
</html>
