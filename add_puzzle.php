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
			if (isset($_POST["puzzleWord"])) {
				$input = validate_input($_POST["puzzleWord"]);
				echo create_puzzle(strlen($input), $input);
			}
		}
		else {
			echo '<p class="title">Enter a name</p><form action="add_puzzle.php" method="post">
			<div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter a name to create a puzzle"></input>
			</div>
			<br><input class="main-buttons align" type="submit" name="submit" value="Show me.."></form>';
		}
		
		function validate_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			$data = preg_replace('/\s+/', '', $data);
			return $data;
		}
		
		function create_puzzle($size, $word) {
			$table = "";
			$table .= "<div class='add_wrapper'><h1>Enter the words and clues for <div class='red'>" . $word . "</div></h1>";
			$table .= "<table class='create_puzzle_table'><thead><tr><th>No</th><th>Character</th><th>Synonym (word)</th><th>Clue</th></thead>";
			for ($i = 0; $i < $size; $i++) {
				if ($i == 0) {
					$table .= "<tbody>";
				}
				$char = substr($word, $i, 1);
				$table .= "<tr><td>" . ($i + 1) . "</td><td>" . strtoupper($char) . "</td><td><input contenteditable='true' spellcheck='true' type='text'/></td><td><input contenteditable='true' spellcheck='true' type='text'/></td></tr>";
			}
			$table .= "</tbody></table><button class='puzzleButton' name='createPuzzle'>Create Puzzle</button></div>";
			return $table;
		}
	?>
  
  </div>
</body>
</html>