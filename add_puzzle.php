<!DOCTYPE html>
<html>
<head>
	<?PHP
		require('session_validation.php');
		require('add_puzzle_process.php');
		require_once('utility_functions.php');
	?>
	
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
			<?PHP echo getTopNav(); ?>
		</div>
	</div>
	<font class="crumb">Name in Synonym <img src="./pic/arrow.png" /> Add Puzzle</font>
	<?php
		$input = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if (isset($_POST["puzzleWord"]))
			{	// User submited a puzzle name
				$input = validate_input($_POST["puzzleWord"]);
				if (strlen($input) > 0) {
					// first we need to check if the puzzle name already exists.
					$nameExist = false;
					$nameExist = checkName($input);
					if(!$nameExist)
					{
						// puzzle name doesn't exist
						echo create_puzzle_table($input);
					}
					else{
						// puzzle name already exists
						echo '<p><h1>The puzzle \'' . $input . '\' already exists. You can access the puzzle by clicking the list button</h1></p>'; 
					}
				}
				else {
					echo '<script>alert("Invalid Input!");</script>' . create_word_input();
				}
				
			}
			else if(isset($_POST["word"]))
			{	// User submited puzzle
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
			// first place the program goes when the user selects "add a puzzle"
			echo create_word_input();
		}
	?>
</body>

</html>
