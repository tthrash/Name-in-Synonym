<?php
require_once('db_configuration.php');
// Gets the word id of the word being used for the given puzzle_id at the given index of position_in_name.
function getWordId($puzzleId, $position_in_name)
{
  $sql = "SELECT * FROM puzzle_words WHERE puzzle_id = '$puzzleId' AND position_in_name = '$position_in_name';";
  $result =  run_sql($sql);
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
      // shouldn't happen -- should be unique composite key between puzzle_id and position_in_name
    }
  }
  else
  {
    return null; // No word_id with this combination of puzzle_id and position_in_name (index of word in the puzzle_words).
  }
}

// returns an array of the indexes of the desired character value in the word with the desired word_id
function getCharIndex($word_id, $char_val)
{
  $sql = "SELECT * FROM characters WHERE word_id = '$word_id' AND character_value =  '$char_val';";
  $result =  run_sql($sql);
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

// Gets the word id of the given word from the word table
function getWordIdFromWord($word)
{
  // Get word if from word table
  $sql = 'SELECT word_id FROM words WHERE word_value =\'' . $word . '\';';
  $result =  run_sql($sql);
  // $num_rows = $result-> // finish check for num rows == 0 and > 1
  $row = $result->fetch_assoc();
  $word_id = $row["word_id"]; 
  return $word_id;
}

// Gets list of characters in contains in the word with the given word id
function getCharactersForWordId($word_id)
{
  // get character list for the given word
  $sql = 'SELECT * FROM characters WHERE word_id = \''.$word_id.'\' ORDER BY character_index;';
  //echo $sql;
  $result =  run_sql($sql);
  $num_rows = $result->num_rows;
  if ($num_rows > 0)
  {
    $rows = array();
    while($row = $result->fetch_assoc()) {
      array_push($rows,$row['character_value']);
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
  //get word if of the given word
  $word_id = getWordIdFromWords($word);
  // get character list for the given word
  return getCharactersForWordId($word_id);
}

// returns the word_id of param or the max word_id if no param provided
function getMaxWordId($index = -1)
{
  if ($index == -1) {
    $sql = 'SELECT MAX(word_id) AS Count FROM words;';
    $result = run_sql($sql);
    $row = $result->fetch_assoc();
    $count = $row["Count"];
    return ($count + 1);
  } else {
    $sql = 'SELECT word_id FROM words WHERE word_value =\'' . $index . '\';';
    $result = run_sql($sql);
    $row = $result->fetch_assoc();
    $word_id = $row["word_id"];
    return ($word_id);
  }
}

// returns the puzzle_id of param or the max puzzle_id if no param provided
function getMaxPuzzleId($index = -1)
{
  if ($index == -1) {
    $sql = 'SELECT MAX(puzzle_id) AS Count FROM puzzles;';
    $result =  run_sql($sql);
    $row = $result->fetch_assoc();
    $count = $row["Count"];
    return ($count + 1);
  } else {
    $sql = 'SELECT puzzle_id FROM puzzles WHERE puzzle_name =\'' . $index . '\';';
    $result =  run_sql($sql);
    $row = $result->fetch_assoc();
    $puzzle_id = $row["puzzle_id"];
    return ($puzzle_id);
  }
}

// returns the word value of the desired word_id
function getWordValue($word_id)
{
  $sql = 'SELECT * FROM words WHERE word_id = \''.$word_id.'\';';
  $result =  run_sql($sql);
  $num_rows = $result->num_rows;

  if ($num_rows == 1)
  {
    // should almost always land here
    $row = $result->fetch_assoc();
    return $row["word_value"];
  }else if($num_rows == 0)
  {
    return null; // word_id doesn't exist
  }
  else{
    // There is more than one word with this word_id (should be impossible because of the primary key on word_id)
  }
}

// returns the word_value of the word with the word_id = rep_id of the word_id given in the parameter.
function getClueWord($word_id)
{
  $sql = 'SELECT * FROM words WHERE word_id = (SELECT rep_id FROM words WHERE word_id = \''.$word_id.'\');';
  $result =  run_sql($sql);
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
      // select a different clue word if given word id is same as repid
      while($row=$result->fetch_assoc())
      {
        if($word_id != $row["word_id"])
        {
          return $row["word_value"];
        }
      }
    }
  }
  else
  {
    return null; // flow of control shouldn't go here for the most part, because it would mean the word_id
    // doesn't have a rep_id (referential integrity violation) or the word_id doesn't exist
  }
}


function getClue($word_id) {
  $sqlStatement = 'SELECT * FROM words WHERE rep_id=\''.$word_id.'\' AND word_id!=\''.$word_id.'\';';
  $result =  run_sql($sqlStatement);
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    $row  = $result->fetch_assoc();
    return $row["word_value"];
  }
  return null;
}

function getWordIdFromChar($char, $preferedPosition) {
  $sqlStatement = '';
  $result;
  $num_rows;
  if ($preferedPosition !== -1) {
    $sqlStatement = 'SELECT * FROM characters WHERE characters.character_value = \'' . $char . '\' AND characters.character_index=\'' . $preferedPosition . '\';';
    $result =  run_sql($sqlStatement);
    $num_rows = $result->num_rows;
    if ($num_rows <= 0) {
      $preferedPosition = -1;
    }
  } 
  if ($preferedPosition === -1) {
    $sqlStatement = 'SELECT * FROM characters WHERE characters.character_value = \'' . $char . '\';';
    $result =  run_sql($sqlStatement);
    $num_rows = $result->num_rows;
  }
  if ($num_rows > 0) {
    $index = 0;
    $randomNumber = mt_rand(0, $num_rows-1);
    while ($row  = $result->fetch_assoc()) {
      if ($index === $randomNumber) {
        return $row["word_id"];
      }
      $index++;
    }

  }
  return false;
}

function getRandomClueWord($word_id) {
  $sqlStatement = 'SELECT * FROM words WHERE rep_id=(SELECT rep_id FROM words WHERE word_id = \''.$word_id.'\') AND word_id!=\''.$word_id.'\'';
  $result =  run_sql($sqlStatement);
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    $index = 0;
    $randomNumber = mt_rand(0, $num_rows-1);
    while ($row  = $result->fetch_assoc()) {
      if ($index === $randomNumber) {
        return $row["word_value"];
      }
      $index++;
    }
  }
  return false;
}

function checkPuzzleId($puzzle_id) {
  $sql = 'SELECT * FROM puzzles WHERE puzzle_id=\''.$puzzle_id.'\';';
  $result =  run_sql($sql);
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    return true;
  }
  return false; 
}

function getWordValuesFromPuzzleWords($puzzle_id) {
  $sql = 'SELECT words.word_value FROM puzzle_words INNER JOIN words ON puzzle_words.word_id=words.word_id WHERE puzzle_words.puzzle_id=\''.$puzzle_id.'\' ORDER BY position_in_name;';
  $result =  run_sql($sql);
  $num_rows = $result->num_rows;
  $words = array();
  if ($num_rows > 0) {
    while ($row  = $result->fetch_assoc()) {
      array_push($words, $row["word_value"]);
    }
    return $words;
  }
  return false;
}

function getClueValuesFromPuzzleWords($puzzle_id) {
  $sql = 'SELECT words.word_value FROM puzzle_words INNER JOIN words ON puzzle_words.clue_id=words.word_id WHERE puzzle_words.puzzle_id=\''.$puzzle_id.'\' ORDER BY position_in_name;';
  $result =  run_sql($sql);
  $num_rows = $result->num_rows;
  $words = array();
  if ($num_rows > 0) {
    while ($row  = $result->fetch_assoc()) {
      array_push($words, $row["word_value"]);
    }
    return $words;
  }
  return false;
}


?>
