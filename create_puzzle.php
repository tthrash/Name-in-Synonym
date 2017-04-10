<?php
	require_once('db_configuration.php');
	require_once('language_processor_functions.php');
	
	// Returns true if there is one puzzle name with this puzzle_name and false if the puzzle_name doesn't exist in the puzzles table.
	// Returns null if there is more than one puzzle_id with this puzzle name.
	function checkName($puzzle_name)
	{
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$puzzle_name.'\';';
		$result = run_sql($sql);
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
	
	function getPuzzleId($puzzle_name)
	{
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$puzzle_name.'\';';
		$result =  run_sql($sql);
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
		$sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES
		(DEFAULT, \''.$name.'\', \'' . $email . '\');';
		run_sql($sql);
	}
	function create_puzzle_words($name)
	{
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
		$result =  run_sql($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows == 0)
		{
			return false;
		}else
		{
			$row = $result->fetch_assoc();
			$puzzle_id = $row["puzzle_id"];
			$puzzle_name = $row["puzzle_name"];
			
			$flag = puzzle_words_empty($puzzle_id);
			if(!$flag)
			{
				$sql = 'DELETE FROM puzzle_words WHERE puzzle_id = \''.$puzzle_id.'\';';
				run_sql($sql);
			}
			
			$parsedWord = getWordChars($puzzle_name);
			$namelen = count($parsedWord);
			$puzzlewords = [];
			
			for($i = 0; $i < $namelen; $i++)
			{
				random_puzzle_word($puzzle_id, $parsedWord[$i], $i, $puzzlewords);
			}
		}
	}
	
	// adds a random puzzle_word for the puzzle with name puzzle_name for the character at 
	// index $i in the puzzle_name.
	function random_puzzle_word($puzzle_id, $character, $i, $puzzlewords)
	{
				$sql =  'SELECT word_id
					FROM characters 
					WHERE character_value = \''. $character .'\'';
				$result =  run_sql($sql);
				if(!$result)
				{
					echo "Get clue word failed";
				}
				$rows =[];
				while ($row= $result->fetch_assoc())
				{
					array_push($rows, $row);
				}

				echo $numofRows = $result->num_rows;
				
				while (true) 
				{				
					$random = rand(0, $numofRows-1);
					$word_id = $rows[$random]["word_id"];
					
					if(!in_array($word_id, $puzzlewords))
					{
						$sql =  'INSERT INTO puzzle_words (puzzle_id, word_id, position_in_name) VALUES
									('.$puzzle_id.','.$word_id.','.$i.');';
						$result =  run_sql($sql);
						if(!$result)
						{
							echo "Get clue word failed";
						}
						array_push($puzzlewords, $word_id);
						break;
					}
				}
	}
	
	// returns true if the puzzle doesn't have any puzzle words assigned to it; false if it does.
	function puzzle_words_empty($puzzle_id)
	{
		$sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = \''.$puzzle_id.'\';';
		$result =  run_sql($sql);
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
?>
