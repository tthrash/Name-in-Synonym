<?php	
require('create_puzzle.php');
require_once('common_sql_functions.php');
require_once('language_processor_functions.php');

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

      $logicalChars = getWordChars($word_value);
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
  return '<p class="title">Enter a name</p><form action="add_puzzle.php" method="post">
					<div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter a name to create a puzzle"></input>
					</div>
					<br><input class="main-buttons align" type="submit" name="submit" value="Next.."></form>';
}

// creates the create puzzle table (puzzle_name_size, puzzle_name)
function create_puzzle_table($word, $action = "add_puzzle.php")
{
  $characters = getWordChars($word);
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

function puzzleAddedTable($name)
{
  /* if(puzzle exists) 
     * getpuzzleid
     * 
     * getpuzzlewordsIdwherePosition=%i(puzzle_id)
     * count(puzzlewords)
     * foreach($puzzleword)
     * getChars($puzzleword)
     * foreach($char)
     *    correct string
     */
  $words = "";
  $wordArray = array();
  $puzzle_exists = checkName($name);
  $puzzleChars = getWordChars($name);
  if($puzzle_exists != null)
  {
    $puzzle_id = getPuzzleId($name);
    $puzzleWords = get_puzzle_words($puzzle_id);
    for($i = 0; $i < count($puzzleWords); $i++)
    {
      $word_value = getWordValue($puzzleWords[$i]);
      $logicalChars = getCharactersForWordId($puzzleWords[$i]);
      $clue_word = getClue($puzzleWords[$i]);
      $words .= '<tr><td>'.$clue_word.'</td><td>';
      $flag = false;
      for($j = 0; $j < count($logicalChars); ++$j)
      {
        if(in_array($logicalChars[$j], $puzzleChars, true) && $flag === false)
        {
          $words .= '<input class="word_char active" type="text" rows="1" cols="1" maxlength="1" value="'.$logicalChars[$j].'"readonly/>';
          $flag = true;
        }
        else
        {
          $words .= '<input class="word_char" type="text" rows="1" cols="1" maxlength="1" value="'.$logicalChars[$j].'"readonly/>';
        }
      }
      $words .= '</td>';
    }
    $words .= '</tr>';
    return $words;
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