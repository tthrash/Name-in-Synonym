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
	<font class="crumb">Name in Synonym <img src="./pic/arrow.png"/> Add Puzzle</font>
	<?PHP
		$input = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST["puzzleWord"])) {	// User submited a word
				$input = validate_input($_POST["puzzleWord"]);	//
				if (strlen($input) > 0) {
					echo create_puzzle(strlen($input), $input);
				}
				else {
					echo '<script>alert("Invalid Input!");</script>' . create_word_input();
				}
				
			}
			else if(isset($_POST["word"])) {	// User submited puzzle
				$name = $size = "";
				$list = array();
				if(empty($_POST["name"]) && empty($_POST["size"])) {
					//should not happen
				} else {
					$name = validate_input($_POST["name"]);
					$size = validate_input($_POST["size"]);
					for ($j = 0; $j < $size; $j++) {
						$tempWord = "word". $j;
						$tempClue = "clue" . $j;
						if(empty($_POST[$tempWord]) && empty($_POST[$tempClue])) {
							// left one of the Synonym or Clues empty
							// let user know of error
						}
						else {
							// valid input
							array_push($list, strtolower(validate_input($_POST[$tempWord])), strtolower(validate_input($_POST[$tempClue])));
							/*
							*********************************************************************************
								need to connect to db and input puzzle, word pair, char breakdown, puzzle_words
								should also test to see if letter is in Synonym
							*********************************************************************************
							*/
						}
						// Just to see if values were in list
						echo "<p>" . print_r($list) . "</p>";
					}
				}
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
		function create_puzzle($size, $word) {
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
					return True; 
				}
			}
		}
	?>
  </div>
</body>
</html>