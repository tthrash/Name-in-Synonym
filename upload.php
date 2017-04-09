<?php 
	header( 'Content-Type: text/html; charset=utf-8' ); 

	include './PHPExcel/PHPExcel/IOFactory.php';
	//require ('db_configuration.php');
	require('create_puzzle.php');
	$error = false;
	$result = "";

	if(isset($_POST['submit'])){

		//function to delete all data except for users table from the db. 
		deleteAllData();
				
		$inputFileName = $_FILES["fileToUpload"]["tmp_name"];
		
		
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) 
			. '": ' . $e->getMessage());
		}


		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

  		
		for ($row = 2; $row <= $highestRow; $row++) {
			//  Read the row of data into an array
			$rowData = $sheet->rangeToArray('B' . $row )[0];

			$words = explode(',', str_replace(' ','',$rowData[0]));

			//Don't know if - is a part of the word or something the prof added to check our processing. Can use later.
			//$words = explode(',', str_replace('-','',$rowData[0]));

			//filter through the array words since some of the word pair have commas at the end
			$finalList= array_filter($words);
			insertnewWordsAndCharacter($finalList);
			
		}
	
	}

	function deleteAllData(){
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
   		mysqli_query($db,'SET foreign_key_checks = 0');
		$sqlDeleteCharacters = 'DELETE FROM characters';
		$result =  $db->query($sqlDeleteCharacters);
		if(!$result)
		{
		    echo"Deleting characters failed!" . $db->error;
	  	}
	  	$sqlDeletePuzzlewords = 'DELETE FROM puzzle_words';
		$result =  $db->query($sqlDeletePuzzlewords);
		if(!$result)
		{
		    echo"Deleting puzzlewords failed!" . $db->error;
	  	}
	  	$sqlDeletePuzzles = 'DELETE FROM puzzles';
		$result =  $db->query($sqlDeletePuzzles);
		if(!$result)
		{
		    echo"Deleting puzzles failed!" . $db->error;
	  	}
	  	$sqlDeleteWords = 'DELETE FROM words';
		$result =  $db->query($sqlDeleteWords);
		if(!$result)
		{
		    echo"Deleting words failed!" . $db->error;
	  	}
	  	mysqli_query($db,'SET foreign_key_checks = 1');
	  	mysqli_query($db, 'ALTER TABLE words AUTO_INCREMENT = 1');
	};

	//Will use this function for now until refactoring is done. Will update afterwards.
	function insertnewWordsAndCharacter($listOfWords)
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
				//echo $repId . "-----";
				//insert each new word into word table.
				$sqlAddWord = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $listOfWords[$i] . '\', \'' . $repId . '\');';
				//echo $sqlAddWord;
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
				//var_dump($logicalChars);
				for($j = 0; $j < count($logicalChars); $j++) {
					//insert each letter into char table.
					$sqlAddLetters = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\''. $word_id . '\', \'' . $j .'\', \''. $logicalChars[$j].'\');';
					$result =  $db->query($sqlAddLetters);
					if(!$result)
					{
						echo "new word:";
						echo $word_id. " ";
						var_dump($logicalChars);
						echo $j;
						echo $logicalChars[$j];
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
