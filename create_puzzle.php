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
		// ***** will need user name eventually. For now we will a default user name. *****
		$sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES
					(DEFAULT, \''.$name.'\', \'hp6449qy@metrostate.edu\');';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$row = $result->fetch_assoc();
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
			$sql = 'SELECT word_id FROM characters WHERE character_value = \''.$character.'\' GROUP BY word_id;';
			$result =  $db->query($sql);
			
			
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
?>