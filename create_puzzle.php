<?php
	require('db_configuration.php');
	
	function checkName($name)
	{
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows == 0)
		{
			create_puzzle($name);
			create_puzzle_words($name);
		}
		
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if($num_rows == 1)
		{
			$row = $result->fetch_assoc();
			return $row["puzzle_id"];
		}
		else{
			// error -- there should only be one row now.
		}
	}
	
	function create_puzzle($name)
	{
		$email = 'hp6449qy@metrostate.edu';
		// ***** will need user name eventually. For now we will a default user name. *****
		$sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES
					(DEFAULT, \''.$name.'\', \'' . $email . '\');';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
	}
	
	function create_puzzle_words($name)
	{
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$row = $result->fetch_assoc();
		$puzzle_id = $row["puzzle_id"];
		
		$namelen = strlen($name);
		for($i = 0; $i < $namelen; $i++)
		{
			$character = $name[$i];
			$sql =  'SELECT word_id
						FROM characters 
						WHERE word_id IN (SELECT word_id FROM words WHERE word_id <> rep_id)
						AND word_id >= 
						(SELECT FLOOR( MAX(word_id) * RAND()) FROM characters) 
						AND character_value = \''.$character.'\'
						ORDER BY word_id LIMIT 1;';
			$result =  $db->query($sql);
			$row = $result->fetch_assoc();
			$word_id = $row["word_id"];
			
			$sql =  'INSERT INTO puzzle_words (puzzle_id, word_id, position_inName) VALUES
						('.$puzzle_id.','.$word_id.','.$i.');';
			$result =  $db->query($sql);
		}
	}
	
	// Inserts word pairs into words table
	function insertIntoWords($word1, $word2) {
		$sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $word1 . '\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sqlcheck);
		$num_rows = $result->num_rows;
		$sqlcheck2 = 'SELECT * FROM words WHERE word_value = \''. $word2 . '\';';
		$result =  $db->query($sqlcheck2);
		$num_rows2 = $result->num_rows;
		if ($num_rows == 0) { // $word1 not found
			if ($num_rows2 == 0) {
				$sqlInsert = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word1 . '\', \'' . getMaxWordId() . '\');';
				$sqlInsert2 = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word2 . '\', \'' . getMaxWordId() . '\');';
				//echo '<p>'. $sqlInsert . '</p>';
				//echo '<p>'. $sqlInsert2 . '</p>';
				$result =  $db->query($sqlInsert);
				$result =  $db->query($sqlInsert2);
			} else {
				$sqlInsert = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word1 . '\', \'' . getMaxWordId($word2) . '\');';
				//echo '<p>'. $sqlInsert . '</p>';
				$result =  $db->query($sqlInsert);
			}
		} else {	// $word 1 found
			if ($num_rows2 == 0) {
				$sqlInsert2 = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word2 . '\', \'' . getMaxWordId($word1) . '\');';
				//echo '<p>'. $sqlInsert2 . '</p>';
				$result =  $db->query($sqlInsert2);
			} else { // both found
				$sqlCheckLink = 'SELECT rep_id FROM words WHERE (word_value = \'' . $word1 . '\' OR word_value=\'' . $word2 . '\');';
				$result =  $db->query($sqlCheckLink);
				$row = $result->fetch_assoc();
				$row2 = $result->fetch_assoc();
				$id = $row["rep_id"];
				$id2 = $row2["rep_id"];
				if ($id != $id2) { // words not linked
					// What do we want to do here?
				} else { // words linked
					//do nothing
				}
			}
		}
	}
	
	function insertIntoPuzzle($nameOfPuzzle, $email = "hp6449qy@metrostate.edu") {
		$sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES (DEFAULT, \'' . $nameOfPuzzle. '\', \'' . $email . '\');';
		//echo '<p>'. $sql . '</p>';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		//echo '<p>'. $result . '</p>';
	}
	
	function insertIntoPuzzleWords($puzzle_id, $word_id, $position_inName) {
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$sql = 'INSERT INTO puzzle_words (puzzle_id, word_id, position_inName) VALUES (\'' . $puzzle_id. '\', \'' . $word_id . '\', \'' . $position_inName .'\');';
		//echo '<p>'. $sql . '</p>';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		//echo '<p>'. $result . '</p>';
	}
	
	// param is word_id not word
	function insertIntoCharacters($word_id) {
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		
		$sqlCharSearch = 'SELECT word_id FROM characters WHERE word_id=\'' . $word_id . '\';';
		$result = $db->query($sqlCharSearch); // search for chars if they are in list
		$num_rows = $result->num_rows;
		if ($num_rows == 0) {
			$sqlWord = 'SELECT word_value FROM words WHERE word_id=\'' . $word_id . '\';';
			$result = $db->query($sqlWord);
			$row = $result->fetch_assoc();
			$word_value = $row["word_value"];
			
			for ($i = 0; $i < strlen($word_value); $i++) {
				$char = substr($word_value, $i, 1);
				$sql = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\'' . $word_id  . '\', \'' . $i . '\', \'' . $char . '\');';
				//echo '<p>'. $sql . '</p>';
				$result =  $db->query($sql);
				//echo '<p>'. $result . '</p>';
			}
		}
		
	}
	
	// returns the puzzle_id of param or the max puzzle_id if no param provided
	function getMaxPuzzleId($index = -1) {
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		if ($index == -1) {
			$sql = 'SELECT MAX(puzzle_id) AS Count FROM puzzles;';
			$result =  $db->query($sql);
			$row = $result->fetch_assoc();
			$count = $row["Count"];
			return ($count + 1);
		} else {
			$sql = 'SELECT puzzle_id FROM puzzles WHERE puzzle_name =\'' . $index . '\';';
			$result =  $db->query($sql);
			$row = $result->fetch_assoc();
			$puzzle_id = $row["puzzle_id"];
			return ($puzzle_id);
		}
	}
	
	
	
	// returns the word_id of param or the max word_id if no param provided
	function getMaxWordId($index = -1) {
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		if ($index == -1) {
			$sql = 'SELECT MAX(word_id) AS Count FROM words;';
			$result =  $db->query($sql);
			$row = $result->fetch_assoc();
			$count = $row["Count"];
			return ($count + 1);
		} else {
			$sql = 'SELECT word_id FROM words WHERE word_value =\'' . $index . '\';';
			$result =  $db->query($sql);
			$row = $result->fetch_assoc();
			$word_id = $row["word_id"];
			return ($word_id);
		}
	}
	
	function getWordId($puzzleId, $position_inName)
	{
		$sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = \''.$puzzleId.'\' AND position_inName = '.$position_inName.';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows > 0)
		{
			if($num_rows == 1)
			{
				// should almost always land here
				$row = $result->fetch_assoc();
				return $row["word_id"];
			}
			else
			{
				// shouldn't happen -- should be unique
			}
		}
		else
		{
			return null; // flow of control shouldn't go here for the most part
		}
	}
	function getWordValue($word_id)
	{
		$sql = 'SELECT * FROM words WHERE word_id = \''.$word_id.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows > 0)
		{
			// should almost always land here
			$row = $result->fetch_assoc();
			return $row["word_value"];
		}
		else
		{
			return null; // flow of control shouldn't go here for the most part
		}
	}
	function getClueWord($word_id)
	{
		$sql = 'SELECT word_value FROM words WHERE word_id = (SELECT rep_id FROM words WHERE word_id = \''.$word_id.'\');';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows > 0)
		{
			if($num_rows == 1)
			{
				// should almost always land here
				$row = $result->fetch_assoc();
				return $row["word_value"];
			}
			else
			{
				// shouldn't happen -- should be unique
			}
		}
		else
		{
			return null; // flow of control shouldn't go here for the most part
		}
	}
	function getCharIndex($word_id, $char_val)
	{
		$sql = 'SELECT * FROM characters WHERE word_id = \''.$word_id.'\' AND character_value = \''.$char_val.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows > 0)
		{
				// should almost always land here
				$indexes = array();
				while($row = $result->fetch_assoc())
				{
						array_push($indexes, $row["character_index"]);
				}
				return $indexes;
		}
		else
		{
			return null; // flow of control shouldn't go here for the most part
		}
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
		function create_puzzle_table($size, $word, $action = "add_puzzle.php") {
			$table = "";
			$table .= "<div class='add_wrapper'><h1>Enter the words and clues for <div class='red'>" . $word . "</div></h1>";
			$table .= "<form action='" . $action . "' method='post'><table class='create_puzzle_table'><thead><tr><th>No</th><th>Character</th><th>Synonym (word)</th><th>Clue</th></thead>";
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
			<br><input class="main-buttons align" type="submit" name="submit" value="Next.."></form>';
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
		
		function puzzleAddedTable($name = -1) {
			$words = "";
			if ($name == -1) {
				$nameEntered = $_POST['word'];
			}
			else {
				$nameEntered = $name;
			}
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
					$flag = false;
					for($j = 0; $j < $wordlen; ++$j)
					{
						
						if(in_array($j, $char_indexes) && $flag === false)
						{
							echo '<input class="word_char active" type="text" rows="1" cols="1" maxlength="1" value="'.$word_value[$j].'"readonly/>';
							$flag = true;
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