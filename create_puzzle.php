<?php
	require('db_configuration.php');
	
	function checkName($name)
	{
		$sql = 'SELECT * FROM puzzles WHERE puzzle_name = \''.$name.'\';';
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		$result =  $db->query($sql);
		$num_rows = $result->num_rows;
		
		if ($num_rows > 0)
		{
			if($num_rows == 1)
			{
				$row = $result->fetch_assoc();
				return $row["puzzle_id"];
			}
			else
			{
				// choose random row.
			}
		}
		else
		{
			return null;
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