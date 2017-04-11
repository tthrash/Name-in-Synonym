<?php	
	require('create_puzzle.php');
	require_once('common_sql_functions.php');
	
	function insertIntoPuzzle($nameOfPuzzle, $email = "hp6449qy@metrostate.edu") {
		$sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES (DEFAULT, \'' . $nameOfPuzzle. '\', \'' . $email . '\');';
		run_sql($sql);
	}

	function insertIntoPuzzleWords($puzzle_id, $word_id, $position_in_name) {
		$sql = 'INSERT INTO puzzle_words (puzzle_id, word_id, position_in_name) VALUES (\'' . $puzzle_id. '\', \'' . $word_id . '\', \'' . $position_in_name .'\');';
		run_sql($sql);
	}
	
	function insertIntoCharacters($word_id)
	{
		$sqlCharSearch = 'SELECT word_id FROM characters WHERE word_id=\'' . $word_id . '\';';
		$result = run_sql($sqlCharSearch);// search for chars if they are in list
		$num_rows = $result->num_rows;
		if ($num_rows == 0)
		{
			$sqlWord = 'SELECT word_value FROM words WHERE word_id=\'' . $word_id . '\';';
			$result = run_sql($sqlWord);
			$num_rows = $result->num_rows;
			if($num_rows > 0)
			{
				$row = $result->fetch_assoc();
				$word_value = $row["word_value"];
				
				$logicalChars = getWordChars($list[$i]);
				for ($i = 0; $i < count($logicalChars); $i++) {
					$sql = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\'' . $word_id  . '\', \'' . $i . '\', \'' . $logicalChars[$i] . '\');';
					run_sql($sql);
				}
			}
			else{
				// word doesn't exist
			}
		}
	}
	
	// Message if the puzzle already exists
	function puzzle_already_exists($puzzle_name)
	{
		$message = '<p style="font-size:40px;">The puzzle name "'.$puzzle_name.'" already exists. You can access the puzzle in the "List".</p>';
		return $message;
	}

	// creates the form for user to submit word
	function create_word_input()
	{
		return '<p class="title">Enter a name</p>
					<form action="add_puzzle.php" method="post">
						<div class="inputDiv">
							<input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter a name to create a puzzle"></input>
						</div><br>
						<input class="main-buttons align" type="submit" name="submit" value="Next..">
					</form>';
	}
	
	// creates the create puzzle table (puzzle_name_size, puzzle_name)
	function create_puzzle_table($word, $action = "add_puzzle.php")
	{
		$word_id = getWordIdFromWord($word);
		$characters = getCharactersForWordId($word_id);
		$table = "";
		$table .= "<div class='add_wrapper'><h1>Enter the words and clues for <div class='red'>" . $word . "</div></h1>";
		$table .= "<form action='" . $action . "' method='post'><table class='create_puzzle_table'><thead><tr><th>No</th><th>Character</th><th>Synonym (word)</th><th>Clue</th></thead>";
		for ($i = 0; $i < count($characters); $i++)
		{
			if ($i == 0) {
				$table .= "<tbody>";
			}
			$char = $characters[$i];
			if ($i == 0) {
				$table .= "<tr><td>" . ($i + 1) . "</td><td>" . $char . "</td><td><input contenteditable='true' spellcheck='true' type='text' name='word". $i . "' autofocus/></td><td><input contenteditable='true' spellcheck='true' type='text' name='clue" . $i . "'/></td></tr>";
			}
			else {
				$table .= "<tr><td>" . ($i + 1) . "</td><td>" . $char . "</td><td><input contenteditable='true' spellcheck='true' type='text' name='word". $i . "'/></td><td><input contenteditable='true' spellcheck='true' type='text' name='clue" . $i . "'/></td></tr>";
			}
		}
		$table .= "</tbody></table><input type='hidden' name='word' value='". $word . "'/><input type='hidden' name='size' value='". count($characters) . "'/><input class='puzzleButton' type='submit' name='submit' value='Create Puzzle'></form></div>";
		return $table;
	}

	function puzzleAddedTable($name = -1)
	{
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
	
	// Inserts word pairs into words table
	function insertIntoWords($word1, $word2)
	{
		$sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $word1 . '\';';
		$result =  run_sql($sqlcheck);
		$num_rows = $result->num_rows;
		
		$sqlcheck2 = 'SELECT * FROM words WHERE word_value = \''. $word2 . '\';';
		$result =  run_sql($sqlcheck2);
		$num_rows2 = $result->num_rows;
		
		if ($num_rows == 0) { // $word1 not found
			if ($num_rows2 == 0) {
				$sqlInsert = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word1 . '\', \'' . getMaxWordId() . '\');';
				$sqlInsert2 = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word2 . '\', \'' . getMaxWordId() . '\');';
				run_sql($sqlInsert);
				run_sql($sqlInsert2);
			} else {
				$sqlInsert = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word1 . '\', \'' . getMaxWordId($word2) . '\');';
				run_sql($sqlInsert);
			}
		} else {	// $word 1 found
			if ($num_rows2 == 0) {
				$sqlInsert2 = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $word2 . '\', \'' . getMaxWordId($word1) . '\');';
				$result =  run_sql($sqlInsert2);
			} else { // both found
				$sqlCheckLink = 'SELECT rep_id FROM words WHERE (word_value = \'' . $word1 . '\' OR word_value=\'' . $word2 . '\');';
				$result =  run_sql($sqlCheckLink);
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
	
		function createHeader($word) {
		return '<div style="text-align:center;font-size:60px;padding:0px;margin:0px;">Thank you.<br>The puzzle "<div class="red" style="display:inline;font-size:60px">'. $word . '"</div> is added to the database.</div>'; 
	}
	function createFooter() {
		return '<p style="font-size:45px;">You can access your puzzle in the "List"</p>';
	}
	
?>