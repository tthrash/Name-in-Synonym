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
	<font class="crumb">Name in Synonym <img src="./pic/arrow.png"/> Add Puzzle</font>
	<?php
 		require_once('create_puzzle.php');
	?> 
	<?PHP
		$input = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST["puzzleWord"])) {	// User submited a word
				$input = validate_input($_POST["puzzleWord"]);	//
				if (strlen($input) > 0) {
					echo create_puzzle_table(strlen($input), $input);
				}
				else {
					echo '<script>alert("Invalid Input!");</script>' . create_word_input();
				}
				
			}
			else if(isset($_POST["word"])) {	// User submited puzzle
				$name = $size = "";
				$list = array();
				if(empty($_POST["word"]) && empty($_POST["size"])) {
					//should not happen
				} else {
					$name = strtolower(validate_input($_POST["word"]));
					$size = validate_input($_POST["size"]);
					$puzzleflag = FALSE;
					$errorflag = FALSE;
					for ($j = 0; $j < $size; $j++) {
						$tempWord = "word". $j;
						$tempClue = "clue" . $j;
						if(empty($_POST[$tempWord]) && empty($_POST[$tempClue])) {
							// left one of the Synonym or Clues empty
							// let user know of error
							if ($errorflag == FALSE) {
								echo create_puzzle_table($size, $name);
								echo display_error("Please give every synonym and clue a value!");
								$errorflag = TRUE;
							}
						}
						else {
							// valid input
							$word1 = strtolower(validate_input($_POST[$tempWord]));
							$word2 = strtolower(validate_input($_POST[$tempClue]));
							//echo "words: " . $word1. $word2;
							$char = substr($name, $j, 1);
							//echo "char: " . $char;
							$index = strpos($word1, $char);
							//echo "index: " . $index;
							if ($index === false){
								echo display_error("Char not found in word!");
							} else {
								if ($puzzleflag === FALSE) {
									// add to puzzle
									insertIntoPuzzle($name);
									$puzzleflag = TRUE;
								}
								// add to words
								insertIntoWords($word1, $word2);
								
								// add to char
								insertIntoCharacters(getMaxWordId($word1));
								insertIntoCharacters(getMaxWordId($word2));
								// add to puzzle words
									insertIntoPuzzleWords(getMaxPuzzleId($name), getMaxWordId($word1), $j);
								//array_push($list, $word1, $word2); // just for testing
							}
						}
					}
				}
				echo createHeader(validate_input($_POST["word"]));
				echo '<table class="main-tables" id="puzzle_table"><tr><th>Clue</th><th>Synonym</th></tr>';
				puzzleAddedTable();
				echo "</table>";
				echo createFooter();
			}
		}
		else {
			echo create_word_input();
		}
		
		//testing input to steralize
		function validate_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			$data = preg_replace('/\s+/', '', $data);
			return $data;
		}
		
		// creates the create puzzle table
		function create_puzzle_table($size, $word) {
			$table = "";
			$table .= "<div class='add_wrapper'><h1>Enter the words and clues for <div class='red'>" . $word . "</div></h1>";
			$table .= "<form action='add_puzzle.php' method='post'><table class='create_puzzle_table'><thead><tr><th>No</th><th>Character</th><th>Synonym (word)</th><th>Clue</th></thead>";
			for ($i = 0; $i < $size; $i++) {
				if ($i == 0) {
					$table .= "<tbody>";
				}
				$char = substr($word, $i, 1);
				if ($i == 0) {
					$table .= "<tr><td>" . ($i + 1) . "</td><td>" . strtoupper($char) . "</td><td><input contenteditable='true' spellcheck='true' type='text' name='word". $i . "' autofocus/></td><td><input contenteditable='true' spellcheck='true' type='text' name='clue" . $i . "'/></td></tr>";
				}
				else {
					$table .= "<tr><td>" . ($i + 1) . "</td><td>" . strtoupper($char) . "</td><td><input contenteditable='true' spellcheck='true' type='text' name='word". $i . "'/></td><td><input contenteditable='true' spellcheck='true' type='text' name='clue" . $i . "'/></td></tr>";
				}
			}
			$table .= "</tbody></table><input type='hidden' name='word' value='". $word . "'/><input type='hidden' name='size' value='". $size . "'/><input class='puzzleButton' type='submit' name='submit' value='Create Puzzle'></form></div>";
			return $table;
		}
		
		// creates the form for user to submit word
		function create_word_input() {
			return '<p class="title">Enter a name</p><form action="add_puzzle.php" method="post">
			<div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter a name to create a puzzle"></input>
			</div>
			<br><input class="main-buttons align" type="submit" name="submit" value="Show me.."></form>';
		}
		
		// returns true if char is found in word else error
		function contains_char($word, $index, $contains) {
			$char = substr($word, $index, 1);
			if ( strcmp($char, "") == 0) {
				return "index error"; // index outside size of word
			} else {
				$temp = strpos($contains, $char);
				if ( strcmp($temp,"FALSE") == 0) {
					return "char error"; // char not found in $contains
				} else {
					return True; // char was found
				}
			}
		}
		
		function display_error($message = -1) {
			$string = "";
			if ($message == -1) {
				$string = "<script>alert('invalid input try again');</script>";
			} else {
				$string = "<script>alert('" . $message . "');</script>";
			}
			return $string;
		}
		
		function puzzleAddedTable() {
			$words = "";
			$nameEntered = $_POST['word'];
			$nameEntered = strtolower($nameEntered);
			$nameEntered = trim($nameEntered);
			$puzzle_id = checkName($nameEntered);
			//echo $puzzle_id;
			if($puzzle_id != null)
			{
				$nameLen = strlen($nameEntered);
				//echo $nameLen;
				for($i = 0; $i < $nameLen; ++$i)
				{
					$word_id = getWordId($puzzle_id, $i);
					//echo $word_id;
					$word_value = getWordValue($word_id);
					//echo $word_value;
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
							echo '<input class="word_char" type="text" rows="1" cols="1" maxlength="1" value="'.$word_value[$j].'"readonly/>';
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
		
		function createHeader($word) {
			return '<div style="text-align:center;font-size:60px;padding:0px;margin:0px;">Thank you.<br>The puzzle "<div class="red" style="display:inline;font-size:60px">'. $word . '"</div> is added to the database.</div>'; 
		}
		
		function createFooter() {
			return '<p style="font-size:45px;">You can access your puzzle in the "List"</p>';
		}
	?>
  </div>
</body>
</html>