<?php
	require_once('db_configuration.php');
	require_once('common_sql_functions.php');
	require_once('language_processor_functions.php');
	require_once('utility_functions.php');
	
	function insertWordsAndCharacter($listOfWords)
	{
		$listOfWords = validate_array($listOfWords);
		for($i = 0; $i < count($listOfWords);$i++){
			//Check to see if entered word exists in the DB.
			$sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $listOfWords[$i] . '\';';
      echo "<p>$sqlcheck</p>";
			$result =  run_sql($sqlcheck);
			if(!$result)
			{
				echo"Checking word failed!";
			} 
			$num_rows = $result->num_rows;
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
        echo "<p>$sqlAddWord</p>";
				$result =  run_sql($sqlAddWord);
				if(!$result){
					echo"Inserting word failed!";
				} 
				// Get word id
				$sql = 'SELECT word_id FROM words WHERE word_value =\'' . $listOfWords[$i] . '\';';
				$result =  run_sql($sql);
				if(!$result)
				{
					echo"Getting word id failed!";			   
				} 
				$row = $result->fetch_assoc();
				$word_id = $row["word_id"];
				$logicalChars = getWordChars($listOfWords[$i]);
        echo "<br><br><br>";
        var_dump($logicalChars);
				for($j = 0; $j < count($logicalChars); $j++)
				{
					//insert each letter into char table.
					$sqlAddLetters = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\''. $word_id . '\', \'' . $j .'\', \''. $logicalChars[$j].'\');';
					$result =  run_sql($sqlAddLetters);
					if(!$result)
					{
						echo"Inserting character failed! ";		    
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