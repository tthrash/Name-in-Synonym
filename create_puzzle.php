<?php
require('db_configuration.php');
require('IndicTextAnalyzer\word_processor.php');
function checkName($name)
{
	$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	$result =  $db->query($sql);
	$num_rows = $result->num_rows;
	if ($num_rows == 0)
	{
		return false;
	}else if ($num_rows == 1){
		return true;
	}
	else{
		return null;
	}
}
function puzzle_already_exists($puzzle_name)
{
	$message = '<p style="font-size:40px;">The puzzle name "'.$puzzle_name.'" already exists. You can access the puzzle in the "List".</p>';
	return $message;
}
function getPuzzleId($puzzle_name)
{
	$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$puzzle_name.'\';';
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	$result =  $db->query($sql);
	$num_rows = $result->num_rows;
	if($num_rows == 1)
	{
		$row = $result->fetch_assoc();
		return $row["puzzle_id"];
	}
	else
		return null;
}
function create_puzzle($name, $email = 'hp6449qy@metrostate.edu')
{
		// ***** will need user name eventually. For now we will a default user name. *****
	$sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES
	(DEFAULT, \''.$name.'\', \'' . $email . '\');';
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	$result =  $db->query($sql);
}
function create_puzzle_words($name)
{
	$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	$result =  $db->query($sql);
	$row = $result->fetch_assoc();
	$puzzle_id = $row["puzzle_id"];
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows == 0)
		{
			return false;
		}else
		{
			
			$row = $result->fetch_assoc();
			$puzzle_id = $row["puzzle_id"];
			$name = $row["puzzle_name"];
			
			$flag = puzzle_words_empty($puzzle_id);
			if(!$flag)
			{
				$sql = 'DELETE FROM puzzle_words WHERE puzzle_id = \''.$puzzle_id.'\';';
				$db->query($sql);
			}
			
			$parsedWord = new wordProcessor($wordbuff, $language);
			$namelen = count($parsedWord);
			for($i = 0; $i < $namelen; $i++)
			{
				$character = $parsedWord[$i];
				random_puzzle_word($puzzle_id, $character, $i);
			}
			return true;
		}
	}
	
	function puzzle_words_empty($puzzle_id)
	{
		$sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = \''.$puzzle_id.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if($num_rows > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	// adds a random puzzle_word for the puzzle with name puzzle_name for the character at 
	// index position_in_name in the puzzle_name.
	function random_puzzle_word($puzzle_id, $character, $position_in_name)
	{
			$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
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
			
			$sql =  'INSERT INTO puzzle_words (puzzle_id, word_id, position_in_name) VALUES
						('.$puzzle_id.','.$word_id.','.$position_in_name.');';
			$result =  $db->query($sql);
	}
	// Inserts word pairs into words table
	function insertIntoWords($word1, $word2)
	{
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
	$db->set_charset("utf8");
	$result =  $db->query($sql);
	//echo '<p>'. $result . '</p>';
}
	
function insertIntoPuzzleWords($puzzle_id, $word_id, $position_in_name) {
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$sql = 'INSERT INTO puzzle_words (puzzle_id, word_id, position_in_name) VALUES (\'' . $puzzle_id. '\', \'' . $word_id . '\', \'' . $position_in_name .'\');';
	//echo '<p>'. $sql . '</p>';
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	$result =  $db->query($sql);
	//echo '<p>'. $result . '</p>';
}
	
// param is word_id not word
function insertIntoCharacters($word_id) {
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	
	$sqlCharSearch = 'SELECT word_id FROM characters WHERE word_id=\'' . $word_id . '\';';
	$result = $db->query($sqlCharSearch); // search for chars if they are in list
	$num_rows = $result->num_rows;
	if ($num_rows == 0) {
		$sqlWord = 'SELECT word_value FROM words WHERE word_id=\'' . $word_id . '\';';
		$result = $db->query($sqlWord);
		$row = $result->fetch_assoc();
		$word_value = $row["word_value"];
		
		$letters = new wordProcessor($list[$i],"");
		$logicalChars = $letters->getLogicalChars();
		for ($i = 0; $i < count($logicalChars); $i++) {
			$sql = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\'' . $word_id  . '\', \'' . $i . '\', \'' . $logicalChars[$i] . '\');';
			//echo '<p>'. $sql . '</p>';
			$result =  $db->query($sql);
			//echo '<p>'. $result . '</p>';
		}
	}
	
}
	
// returns the puzzle_id of param or the max puzzle_id if no param provided
function getMaxPuzzleId($index = -1) {
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
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
	$db->set_charset("utf8");
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
	
function getWordId($puzzleId, $position_in_name)
{
	$sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = \''.$puzzleId.'\' AND position_in_name = '.$position_in_name.';';
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
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
	$db->set_charset("utf8");
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
	$db->set_charset("utf8");
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
	$db->set_charset("utf8");
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
// creates the create puzzle table (puzzle_name_size, puzzle_name)
function create_puzzle_table($word, $action = "add_puzzle.php") {
$word_id = getWordIdFromWords($word);
$characters = getCharactersForWordId($word_id);
$table = "";
$table .= "<div class='add_wrapper'><h1>Enter the words and clues for <div class='red'>" . $word . "</div></h1>";
$table .= "<form action='" . $action . "' method='post'><table class='create_puzzle_table'><thead><tr><th>No</th><th>Character</th><th>Synonym (word)</th><th>Clue</th></thead>";
for ($i = 0; $i < count($characters); $i++) {
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
// creates the form for user to submit word
function create_word_input() {
return '<p class="title">Enter a name</p><form action="add_puzzle.php" method="post">
<div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter a name to create a puzzle"></input>
</div>
<br><input class="main-buttons align" type="submit" name="submit" value="Next.."></form>';
}
// returns true if char is found in word else error
function contains_char($word, $index, $contains)
{
	$chars = getCharactersForWord($word);
	$char = substr($word, $index, 1);
	if ($chars[$index] == $contains)
	{
		return True; //char was found
	} 
	else 
	{
		return "char error"; // char not found in $contains
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
// Gets list of characters in contains in the word with the given word id
function getCharactersForWordId($word_id)
{
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	// get character list for the given word
	$sql = 'SELECT * FROM characters WHERE word_id = \''.$word_id.'\';';		
	$result =  $db->query($sql);
	$num_rows = $result->num_rows;
	
	if ($num_rows > 0)
	{
		// should almost always land here			
		$rows = array();
		while($row = $result->fetch_assoc())
		{
			array_push($rows, $row["character_value"]);
		}
		return $rows;
	}
	else
	{
		return null; // flow of control shouldn't go here for the most part
	}
}
// Gets list of characters in contains in the given word 
function getCharactersForWord($word)
{
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	//get word if of the given word
	$word_id = getWordIdFromWords($word);
	// get character list for the given word
	return getCharactersForWordId($word_id);
}
// Gets the word if for given word from the word table
function getWordIdFromWords($word)
{
	$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
	$db->set_charset("utf8");
	// Get word if from word table
	$sql = 'SELECT word_id FROM words WHERE word_value =\'' . $word . '\';';
	$result =  $db->query($sql);
	$row = $result->fetch_assoc();
	$word_id = $row["word_id"]; 
	return $word_id;
}
function getWordLength($word)
{
	$letters = new wordProcessor($word,"");
    $logicalChars = $letters->getLogicalChars();
    return count($logicalChars);
}
function getWordChars($word)
{
	$letters = new wordProcessor($word,"");
    $logicalChars = $letters->getLogicalChars();
    return $logicalChars;
}
function insertWordsAndCharacter($listOfWords)
{
	for($i = 0; $i < count($listOfWords);$i++){
		$listOfWords[$i] = trim($listOfWords[$i]);
		//Check to see if entered word exists in the DB.
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
   		$db->set_charset("utf8");
		$sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $listOfWords[$i] . '\';';
		$result =  $db->query($sqlcheck);
		if(!$result)
		{
		    echo"Checking word failed!" . $db->error;
	  	} 
		$num_rows = $result->num_rows;
		//var_dump($list[$i]);
		if($num_rows == 0)
		{ 
			if($i == 0){
				$repId = getMaxWordId();
			}
			else
			{
				$repId = getMaxWordId($listOfWords[0]);
			}
			//insert each new word into word table.
			$sqlAddWord = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $listOfWords[$i] . '\', \'' . $repId . '\');';
			$result =  $db->query($sqlAddWord);
			if(!$result){
			    echo"Inserting word failed!" . $db->error;
	  		} 
	  		// Get word id
			$sql = 'SELECT word_id FROM words WHERE word_value =\'' . $listOfWords[$i] . '\';';
			$result =  $db->query($sql);
			if(!$result)
			{
			    echo"Getting word id failed!" . $db->error;			   
	  		} 
			$row = $result->fetch_assoc();
			$word_id = $row["word_id"]; 
			//echo $word_id;      
			$letters = new wordProcessor($listOfWords[$i],"");
			$logicalChars = $letters->getLogicalChars();
			var_dump($logicalChars);
			for($j = 0; $j < count($logicalChars); $j++) {
				//insert each letter into char table.
				$sqlAddLetters = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\''. $word_id . '\', \'' . $j .'\', \''. $logicalChars[$j].'\');';
				$result =  $db->query($sqlAddLetters);
				if(!$result)
				{
				    echo"Insertng character failed!" . $db->error;				    
	  			} 
			};
		}
		else
		{ 
	        //The word already exists in the database.
	        //echo "the word already exists.";
	         //Do Nothing if the word already exists in the DB.
		}
	}
}
?>
