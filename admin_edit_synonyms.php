<!DOCTYPE html>
<html>
<head>
  <?PHP
		require_once('db_configuration.php');
		require_once('create_puzzle.php');
		require_once('add_words_process.php');
		session_start();
		require('session_validation.php');
	?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/main_style.css" type="text/css">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="styles/custom_nav.css" type="text/css">
  <style>
    .text {
      position: relative;
      top: 20px;
      height: 30px;
      margin-left: 30px;
      width: 680px;
    }
    
    .fontword {
      font-size: 30px;
    }
    
    .divInputs {
      position: relative;
      top: 80px;
      height: 140px;
      margin-left: 200px;
      width: 980px;
    }
    
    .inputleft {
      border-radius: 25px;
      height: 130px;
      width: 450px;
      border-width: 3px;
      border-style: solid;
      margin-top: 90px;
      margin-left: 0px;
    }
    
    .inputright {
      border-radius: 25px;
      height: 130px;
      width: 450px;
      border-width: 3px;
      border-style: solid;
      margin-top: -135px;
      margin-left: 530px;
    }
    
    .textbox {
      background-color: transparent;
      border: 0px solid;
      outline: none;
      margin-left: 15px;
      margin-top: 10px;
      height: 100px;
      width: 340px;
      font-size: 35px;
    }
    
    .imagediv {
      margin-top: -100px;
      margin-left: 460px;
    }
    
    .addButton {
      background-color: #70baeb;
      border: 2px solid black;
      color: black;
      padding: 15px 32px;
      width: 320px;
      height: 60px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 30px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 12px;
      margin-left: 430px;
      margin-top: 50px;
    }

  </style>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/main_style.css" type="text/css">
</head>
<title>Final Project</title>
<body>
  <?PHP echo getTopNav(); ?>
  <?php 
	 if(isset($_GET['word']))
	 {
		$wordProvided = $_GET['word'];
		if($wordProvided != NULL)
		{
			$sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $wordProvided. '\';';
			$result =  run_sql($sqlcheck);
			$row = $result->fetch_assoc();
			$repId = $row["rep_id"];
			$show=$wordProvided;
			
			
			$synonyms = array();
			// adding the words that have the same rep id as the main search word into $synonyms. Adding each of the words values to $show
			$sqlGetSynonyms = 'SELECT * FROM words WHERE rep_id = \''. $repId. '\';';
			$result =  run_sql($sqlGetSynonyms);
			while($row = $result->fetch_assoc()){
				array_push($synonyms, $row);
				$data = $row["word_value"];
				
        if($data != $wordProvided)
				{
					$show = $show.", ".$data;
				}
			}
		}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['updateWord'] == ''){
	  echo "<div class='result' id='confirmText'>";
	  echo "<font class='fontword'>Thank you. The synonym list has been updated.<br><br>";
	  echo "Would you like to update another set of synonyms?</font>";
	  echo "</div>";

	  echo "<form method='post' id='inputForm'>";
	  echo "<div class='inputDiv'><input type='textbox' name='updateWord' id='name-textbox'></input></div>";
	  echo "<br>
			<input class='addButton' id='addButton' type='submit' name='submit' value='Update Word Pairs'>
			</form>
			</div>";
	}else{
	  echo "<div class='result' id='confirmText'>";
	  echo "<font class='fontword'>Name In Synonym <img src='./pic/arrow.png'> Edit Synonyms<br><br>";
	  echo "Here are all the synonyms of the word \"<font color='blue'>  $wordProvided  </font>\" <br>";
	  echo "You can add, delete, or update any word in the list</font>";
	  echo "</div>";
	  echo "<form method='post' id='inputForm'>";
	  echo "<div class='inputDiv'><input type='textbox' name='updateWord' id='name-textbox' value='$show' ></input></div>";
	  echo "<br>
			<input class='addButton' id='addButton' type='submit' name='submit' value='Update Word Pairs'>
			</form>
			</div>";
	}

	if(isset($_POST['submit']))
	{
		foreach ($synonyms as $word)
		{
			$word_id = $word["word_id"];
			
			// for later use to add new random puzzle_words
			$sql_puzzle_words = 'SELECT puzzle_id, position_in_name FROM puzzle_words WHERE word_id = '.$word_id.';';
			$puzzle_words = run_sql($sql_puzzle_words);
			
			$sqlDeleteChar = 'DELETE FROM characters WHERE word_id = \''. $word_id. '\';';
			run_sql($sqlDeleteChar);

			$aqlDeletePuzzleWord = 'DELETE FROM puzzle_words WHERE word_id = \''. $word_id . '\';';
			run_sql($aqlDeletePuzzleWord);
			
			run_sql('SET foreign_key_checks = 0;');
			
			$sqlDeletewords = 'DELETE FROM words WHERE word_id = \''. $word_id  . '\';';
			run_sql($sqlDeletewords);
			
			run_sql('SET foreign_key_checks = 1;');
      
			$word_added = "";
			// add new random puzzle_words
			while($puzzle_word = $puzzle_words->fetch_assoc())
			{
					$puzzle_id = $puzzle_word["puzzle_id"];
					// get array of words associated with puzzle_id in puzzle_words
					$puzzlewords = get_puzzle_words($puzzle_id);
					$index = $puzzle_word["position_in_name"];
					
					// get character in puzzle name at index
					$sql_getPuzzle = 'SELECT * FROM puzzles WHERE puzzle_id = ' . $puzzle_id . ';';
					$result =  run_sql($sql_getPuzzle);
					$row = $result->fetch_assoc();
					$puzzle_name = $row["puzzle_name"];
					$parsed_name = getWordChars($puzzle_name);
					$character = $parsed_name[$index];
					
					$word_added = add_puzzle_word($puzzle_id, $character, $index, $puzzlewords);
					
					if($word_added == null)
					{
						// no word could be added. More should probably be done (some type of default action).
					}
			}
		}

		$newWords = trim($_POST['updateWord']);

		$list = explode(',', $newWords);

		insertWordsAndCharacter($list);
	}
?>
</body>
</html>
