<?php 
	header( 'Content-Type: text/html; charset=utf-8' ); 
	require_once('db_configuration.php');
	require('language_processor_functions.php');
	require('utility_functions.php');
	require('common_sql_functions.php');
	include './PHPExcel/PHPExcel/IOFactory.php';
	
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
		echo '<h2 style="color:	green;" class="upload">Import Successful!</h2>';
	}

	function deleteAllData(){
		run_sql('SET foreign_key_checks = 0;');
		
		$sqlDeleteCharacters = 'DELETE FROM characters;';
		run_sql($sqlDeleteCharacters);
		
	  	$sqlDeletePuzzlewords = 'DELETE FROM puzzle_words;';
		run_sql($sqlDeletePuzzlewords);
		
	  	$sqlDeletePuzzles = 'DELETE FROM puzzles;';
		run_sql($sqlDeletePuzzles);
		
		$sqlDeleteWords = 'DELETE FROM words ';
		run_sql($sqlDeleteWords);
		
	  	$sqlDeleteWords = 'DELETE FROM words';
		run_sql($sqlDeleteWords); 
		
		run_sql('SET foreign_key_checks = 0;');
		run_sql('ALTER TABLE words AUTO_INCREMENT = 1;');
	}

	//Will use this function for now until refactoring is done. Will update afterwards.
	function insertnewWordsAndCharacter($listOfWords)
	{
		for($i = 0; $i < count($listOfWords);$i++){
			
			$listOfWords[$i] = validate_input($listOfWords[$i]);
			
			// to remove invalid character eg: \u00a0
			$listOfWords[$i] =  str_replace(chr(194).chr(160),'',$listOfWords[$i]);
			
			//Check to see if entered word exists in the DB.
			$sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $listOfWords[$i] . '\';';
			$result =   run_sql($sqlcheck);
			
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
				$result =  run_sql($sqlAddWord);

		  		// Get word id
				$sql = 'SELECT word_id FROM words WHERE word_value =\'' . $listOfWords[$i] . '\';';
				$result =  run_sql($sql);
				$row = $result->fetch_assoc();
				$word_id = $row["word_id"];
				
				$logicalChars = getWordChars($listOfWords[$i]);
				
				for($j = 0; $j < count($logicalChars); $j++)
				{
					//insert each letter into char table.
					$sqlAddLetters = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\''. $word_id . '\', \'' . $j .'\', \''. $logicalChars[$j].'\');';
					run_sql($sqlAddLetters);
				}
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
