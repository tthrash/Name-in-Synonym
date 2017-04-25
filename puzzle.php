<!DOCTYPE html>
<html>

  <head>
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
    <title>Final Project</title>
  </head>

  <body>
    <?php
    require('create_puzzle.php');
    require('common_sql_functions.php');
    require('utility_functions.php');
    session_start();
    require('session_validation.php');
    ?>
    <?PHP echo getTopNav(); ?>
    <!--FIXME: location of fail message not displayed properly-->
    <div id="pop_up_fail" class="pop_up" style="display:none">
      <div class="pop_up_background">

        <img class="pop_up_img_fail" alt="fail puzzle pop up" src="pic/info_circle.png">
        <div class="pop_up_text">Incorrect! <br>Try Again!</div>
        <button class="pop_up_button" onclick="change_display_none('pop_up_fail')">OK</button>
      </div>
    </div>
    <!--<div class="header2">Here's your "Name in Synonyms"</div>-->
    <div class="container">
      <h1>Here's your "Name in Synonyms"</h1>
      <table class="table table-condensed main-tables" id="puzzle_table">
        <thead>
          <tr>
            <th>Clue</th>
            <th>Synonym</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $words = "";
          $nameEntered = "";
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['randomPlay']) && isset($_POST['puzzleWord'])) { //random puzzle
              $puzzleName = validate_input($_POST['puzzleWord']);
              $puzzle = new Puzzle($puzzleName,-1,-1);
              $words = $puzzle->js_solution;
              echo $puzzle->htmlTable;
            } else if (isset($_POST['iDesign']) && isset($_POST['puzzleWord'])) { // I will design
              // TODO: admin design needs to be implemented
              // admin create
              // get puzzle words
              // get puzzle clues
              // define js
              // create both puzzles
			  
			  
            }
          } else if (isset($_GET['puzzleName']) && isset($_GET['id'])) { // play button from puzzle list
            echo "<p>play button</p>";
    // TODO: Needs to give option to play again if they guess correctly
    $words = "";
    $nameEntered = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['randomPlay']) && isset($_POST['puzzleWord'])) { //random puzzle
        $puzzleName = validate_input($_POST['puzzleWord']);
        if (strlen($puzzleName) > 0) {
          $puzzle = new Puzzle($puzzleName,-1,-1, 2, 20);
          $words = $puzzle->js_solution;
          echo $puzzle->htmlTable;
          echo $puzzle->buttons;
        } else {
          playAgain();
        }
      } else if (isset($_POST['iDesign']) && isset($_POST['puzzleWord'])) { // I will design
        $puzzleName = validate_input($_POST['puzzleWord']);
        if (strlen($puzzleName) > 0) {
          if (isset($_POST['maxLength']) && isset($_POST['minLength']) && isset($_POST['position'])) {
            echo "<form method='POST' action='#'>";
            
            $preferedPosition = (int)validate_input($_POST['position']);
            $minLength = (int)validate_input($_POST['minLength']);
            $maxLength = (int)validate_input($_POST['maxLength']);
            $puzzle = new Puzzle($puzzleName,-1, $preferedPosition, $minLength, $maxLength);
            $words = $puzzle->js_solution;
            // FIXME: is you have time change to show all char
            echo $puzzle->createAdminInputBoxes();
            echo $puzzle->admin_buttons;
            echo "</form>";
          } else {
            // TODO: admin design needs to be implemented
            // admin create
            // get puzzle words
            // get puzzle clues
            // define js
            // create both puzzles
            echo "<form method='POST' action='#'>";
            $puzzle = new Puzzle($puzzleName,-1, 2, 2, 20);
            $words = $puzzle->js_solution;
            // FIXME: is you have time change to show all chars
            echo $puzzle->createAdminInputBoxes();
            echo $puzzle->admin_buttons;
            echo "</form>";
          } 
        } else {
          playAgain();
        }
      } else if (isset($_POST['saveIDesign'])) {
        $puzzleInfo = pullInputFromSave();
        $clue_id_array = array_pop($puzzleInfo);
        $word_id_array = array_pop($puzzleInfo);
        $puzzleName = array_pop($puzzleInfo);
        savePuzzle($puzzleName, $word_id_array, $clue_id_array);
        echo "<script>alert('Puzzle: $puzzleName was saved.');</script>";
        echo "<form method='POST' action='#'>";
        $puzzleName = validate_input($_POST['puzzleWord']);
        $preferedPosition = (int)validate_input($_POST['position']);
        $minLength = (int)validate_input($_POST['minLength']);
        $maxLength = (int)validate_input($_POST['maxLength']);
        $puzzle = new Puzzle($puzzleName,-1, $preferedPosition, $minLength, $maxLength);
        $words = $puzzle->js_solution;
        // FIXME: is you have time change to show all char
        echo $puzzle->createAdminInputBoxes();
        echo $puzzle->admin_buttons;
        echo "</form>";
      }
    } else if (isset($_GET['puzzleName']) && isset($_GET['id'])) { // play button from puzzle list
      $puzzleName = validate_input($_GET['puzzleName']);
      if (strlen($puzzleName) > 0) {
        $puzzle_id = (int)validate_input($_GET['id']);
        $puzzle = new Puzzle($puzzleName,$puzzle_id,-1, 2, 20);
        $words = $puzzle->js_solution;
        echo $puzzle->htmlTable;
        echo $puzzle->buttons;
      } else {
        playAgain();
      }
    } else if (isset($_GET['puzzleName'])) {
      $puzzleName = validate_input($_GET['puzzleName']);
      $puzzle = new Puzzle($puzzleName,-1,-1, 2, 20);
      $words = $puzzle->js_solution;
      echo $puzzle->htmlTable;
      echo $puzzle->buttons;
    } else {
      echo "<h1>An error happend please go back and recheck your puzzle name.</h1>";
      // TODO: re-input name?
    }

    /*
         * step1: steralize input
         * step2: see if puzzle exisits
         * step3: get words
         * step4: get clues
         * step5: generate puzzle
         * step6: gernerate js
         */
    function generateExactPuzzle($nameOfPuzzle, $puzzle_id) {
      $words = "";
      $nameEntered = validate_input($nameOfPuzzle);
      $puzzle_id = validate_input($puzzle_id);
      $nameEntered = mb_strtolower($nameOfPuzzle, 'UTF-8');
      // check if the name already exists
      $nameExist = checkPuzzleId($puzzle_id);
      if(!$nameExist)
      {
        generatePuzzle($nameEntered);
      } else { // puzzle exists
        $puzzle_chars = getWordChars($nameEntered);
        $word_array = getWordValuesFromPuzzleWords($puzzle_id);
        $clues_array = getClueValuesFromPuzzleWords($puzzle_id);
        $i=0;
        foreach($puzzle_chars as $char) {
          $word_chars = getWordChars($word_array[$i]);

          // this is for building a comma seperate string of the words for the puzzle. For later use in javascript.
          if($i == 0)
          {
            $words .= buildJScriptWords($word_chars);
          }
          else
          {
            $words .= ','.buildJScriptWords($word_chars);
          }
          //var_dump($clues_array);
          echo '<tr><td>'.$clues_array[$i].'</td><td>';
          //$char_indexes = mb_strpos($)//getCharIndex($word_id, $puzzle_name_chars[$i]);
          $wordlen = count($word_chars);

          for($j = 0; $j < $wordlen; ++$j)
          {
            if($char === $word_chars[$j])
            {
              echo '<input class="word_char active" type="text" maxLength="7" value="'.$word_chars[$j].'" readonly/>';
            }
            $word_chars = getWordChars($word_array[$i]);
			
              // this is for building a comma seperate string of the words for the puzzle. For later use in javascript.
              if($i == 0)
              {
                $words .= buildJScriptWords($word_chars);
              }
              else
              {
                $words .= ','.buildJScriptWords($word_chars);
              }
              //var_dump($clues_array);
              echo '<tr><td>'.$clues_array[$i].'</td><td>';
              //$char_indexes = mb_strpos($)//getCharIndex($word_id, $puzzle_name_chars[$i]);
              $wordlen = count($word_chars);

              for($j = 0; $j < $wordlen; ++$j)
              {
                if($char === $word_chars[$j])
                {
                  echo '<input class="word_char active" type="text" maxlength="7" value="'.$word_chars[$j].'" readonly/>';
                }
                else
                {
                  echo '<input class="word_char" type="text" maxlength="7" value=""/>';
                }
              }
              echo '</tr>';
              $i++;
        }
        return $words;
      }
    }

    /**
     * Generates a random puzzle given a name for the puzzle
     * @param  string $nameOfPuzzle name of the puzzle
     * @return string words that are used to display js solution
     */
    function generatePuzzle($nameOfPuzzle) {
      $words = "";
      $nameEntered = validate_input($nameOfPuzzle);
      $nameEntered = mb_strtolower($nameOfPuzzle, 'UTF-8');
      //echo "<p>NameEntered: $nameEntered</p>";

      $puzzle_chars = getWordChars($nameEntered);
      $word_array = array();
      $clues_array = array();
      $i=0;
      foreach($puzzle_chars as $char) {
        //echo "<p>char: $char</p>";
        $word_id = random_word_id($char, $word_array);
        if ($word_id != null) {
          array_push($word_array, getWordValue($word_id));
          array_push($clues_array, getRandomClueWord($word_id));

        } else {
          array_push($word_array, $char);
          array_push($clues_array, $char);
        }
        $word_chars = getWordChars($word_array[$i]);

        // this is for building a comma seperate string of the words for the puzzle. For later use in javascript.
        if($i == 0)
        {
          $words .= buildJScriptWords($word_chars);
        }
        else
        {
          $words .= ','.buildJScriptWords($word_chars);
        }
        //var_dump($clues_array);
        echo '<tr><td>'.$clues_array[$i].'</td><td>';
        //$char_indexes = mb_strpos($)//getCharIndex($word_id, $puzzle_name_chars[$i]);
        $wordlen = count($word_chars);

        for($j = 0; $j < $wordlen; ++$j)
        {
          if($char === $word_chars[$j])
          {
            echo '<input class="word_char active" type="text" maxLength="7" value="'.$word_chars[$j].'" readonly/>';
          }
          else
          {
            echo '<input class="word_char" type="text" maxLength="7" value=""/>';
          }
        }
        echo '</tr>';
        $i++;
      }
      return $words;
    }

    function createPuzzle($nameEntered)
    {
      $nameExist = false;
      $words = "";
      // clean up the name entered
      $nameEntered = validate_input($nameEntered);
      $nameEntered = mb_strtolower($nameEntered, 'UTF-8');


      // check if the name already exists
      $nameExist = checkName($nameEntered);
      if(!$nameExist)
      {
        create_puzzle($nameEntered);
        create_puzzle_words($nameEntered);
      }

      $puzzle_id = getPuzzleId($nameEntered);

      if($puzzle_id != null)
      {
        $puzzle_name_chars = getWordChars($nameEntered);
        // get length of puzzle name
        $nameLen = count($puzzle_name_chars);

        // for each character in the puzzle name
        for($i = 0; $i < $nameLen; ++$i)
        {
          // get the word_id from the puzzle_words table at position $i in the puzzle name.
          $word_id = getWordId($puzzle_id, $i);

          // then get the word_value of that word_id
          $word_value = getWordValue($word_id);

          // get the character array of the word
          $word_chars = getWordChars($word_value);

          // this is for building a comma seperate string of the words for the puzzle. For later use in javascript.
          if($i == 0)
          {
            $words .= buildJScriptWords($word_chars);
          }
          else
          {
            $words .= ','.buildJScriptWords($word_chars);
          }

          // output the clue word of the word (the word_value with the word_id = rep_id of this word)
          $clue_word = getClueWord($word_id);

          // Add clue word to first column of the row
          echo '<tr>
							 <td>'.$clue_word.'</td>
							 <td>';

          $char_indexes = getCharIndex($word_id, $puzzle_name_chars[$i]);
          $wordlen = count($word_chars);

          for($j = 0; $j < $wordlen; ++$j)
          {
            if(in_array($j, $char_indexes))
            {
              echo '<input class="word_char active" type="text" maxLength="7" value="'.$word_chars[$j].'" readonly/>';
            }
            else
            {
              echo '<input class="word_char" type="text" maxLength="7" value=""/>';
            }
          }
          echo '</tr>';
        }
      }
      else{
        // set name-textbox on index.php to error message that name doesn't exist
        // re
      }
      return $words;
    }

    // Takes in an array of characters and builds a string by seperating them with '-'. Returns the string.
    function buildJScriptWords($word_chars)
    {
      $string = "";
      $wordLng = count($word_chars);
      for($i = 0; $i < $wordLng ; ++$i)
      {
        if($i == 0)
        {
          $string .= $word_chars[$i];
        }
        else{
          $string .= '-'.$word_chars[$i];
        }
      }
      return $string;
    }

    function pullInputFromSave() {

      $input = array();
      $puzzleName = "";
      $word_id_array = array();
      $clue_id_array = array();
      $word = 'word';
      $clue = 'clue';
      $i = 0;
      if (isset($_POST['puzzleWord'])) {
        $puzzleName = mb_strtolower(validate_input($_POST['puzzleWord']), 'UTF-8');
      }
      for ($i = 0; isset($_POST[$word."".$i]) && isset($_POST[$clue."".$i]);$i++) {
        array_push($word_id_array,validate_input($_POST[$word."".$i]));
        array_push($clue_id_array,validate_input($_POST[$clue."".$i]));
      }
      array_push($input,$puzzleName, $word_id_array, $clue_id_array);
      return $input;
    }

    function savePuzzle($puzzleName, $word_id_array, $clue_id_array) {
      // create puzzle
      create_puzzle($puzzleName);
      // create puzzle_words
      $puzzle_id = (getMaxPuzzleId(-1)-1);
      input_puzzle_words($word_id_array, $clue_id_array, $puzzle_id);
    }

    function getWordIdArray($word_array) {
      $word_id_array = array();
      foreach($word_array as $word) {
        array_push($word_id_array, getWordIdFromWord($word));
      }
      return $word_id_array;
    }

    function getClueIdArray($clue_array) {
      $clue_id_array = array();
      foreach($clue_array as $word) {
        array_push($clue_id_array, getWordIdFromWord($word));
      }
      return $clue_id_array;
    }

    function playAgain() {
      echo '<h2>Puzzle name was empty please enter a name</h2><form action="puzzle.php" method="post">
        <div class="container">
          <div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter your Name to see the Puzzle" onclick="clearFields();" />
          </div>
          <br>
          <div style="text-align:center">
            <input class="main-buttons" type="submit" name="randomPlay" value="Show me.." />
          </div>
        </div>
      </form>';
    }

    /**
     * Puzzle class that represents a puzzle in the database
     */
    class Puzzle
    {
      /**
       * Constructor that initializes all param's
       * @param string  $puzzleName       of puzzle
       * @param integer $puzzle_id        of puzzle
       * @param integer $preferedPosition of char in synonym must be >= -1
       * @param integer $minLength        of synonyms in puzzle must be >= -1
       * @param integer $maxLength        of synonyms in puzle must be >= -1
       */
      function Puzzle($puzzleName,$puzzle_id, $preferedPosition, $minLength, $maxLength) {
        try {
          $this->setName($puzzleName);
          $this->setId($puzzle_id);
          $this->setMinmaxLength($minLength, $maxLength);
          $this->setPreferedPosition($preferedPosition);
          $this->setPuzzleWords($puzzleName,$puzzle_id);
          $this->setCharIndexes();
          $this->createJSSolution();
          $this->createInputBoxes();
          $this->createTableFooter();
          if ($puzzle_id === -1) {
            $this->createPuzzle();
          }
        } catch (Exception $e) {
          echo 'Message: ' . $e->getMessage();
        }
      }

      /**
       * Sets the name of the puzzle
       * @param string $puzzleName of puzzle
       */
      function setName($puzzleName) {
        if (!is_string($puzzleName)) {
          throw new Exception("Name of a puzzle must be a string!");
        } else if (empty($puzzleName)) {
          throw new Exception("Name of puzzle can't be empty!");
        }
        $this->puzzleName = mb_strtolower($puzzleName, 'UTF-8');
      }

      /**
       * Initializes puzzle_id to param if the puzzle does exits
       * @param integer $puzzle_id of puzzle
       */
      function setId($puzzle_id) {
        if (!is_int($puzzle_id)) {
          throw new Exception("Puzzle_Id must be an integer!");
        } else if ($puzzle_id !== -1 && !checkPuzzleId($puzzle_id)) {
          throw new Exception("Puzzle_Id did not find a match!");
        }
        $this->puzzle_id = $puzzle_id;
      }

      function setMinmaxLength($minLength, $maxLength) {
        if (!is_int($minLength) || !is_int($maxLength)) {
          throw new Exception("Puzzle length must be an integer!");
        } else if ($minLength < 0 || $maxLength < 0) {
          throw new Exception("Puzzle length must greater than 0!");
        } else {
          $this->minLength = $minLength;
          $this->maxLength = $maxLength;
        }
      }

      /**
       * Sets the prefered position of the char in synonyms
       * @param integer $preferedPosition of char in synonyms
       */
      function setPreferedPosition($preferedPosition) {
        if (!is_int($preferedPosition)) {
          throw new Exception("Position must be an integer");
        } else if ($preferedPosition < -1) {
          throw new Exception("Position out of bounds");
        }
        $this->position = $preferedPosition;
      }

      /**
       * breaks name entered into chars
       * if puzzle exisits get words and clues
       * else create random words and clues
       * then saves them to the object
       */
      function setPuzzleWords() {
        $word_array = array();
        $clues_array = array();
        $puzzle_chars = getWordChars($this->puzzleName);
        foreach($puzzle_chars as $char) {
          $index = getWordIdFromChar($char,$this->position,$this->minLength,$this->maxLength);
          if ($this->puzzle_id !== -1) {
            $word_array = getWordValuesFromPuzzleWords($this->puzzle_id);
            $clues_array = getClueValuesFromPuzzleWords($this->puzzle_id);
            break;
          } 
          if ($index != false) {
            array_push($word_array, getWordValue($index));
            array_push($clues_array, getRandomClueWord($index));

          } else {
            array_push($word_array, $char);
            array_push($clues_array, $char);
          }
        }
        $this->puzzle_chars = $puzzle_chars;
        $this->word_array = $word_array;
        $this->clues_array = $clues_array;
      }

      /**
       * Creates comma delimited list to be used in show solution
       */
      function createJSSolution() {
        $words = "";
        $i = 0;
        foreach($this->puzzle_chars as $char) {
          $word_chars = getWordChars($this->word_array[$i]);
          // this is for building a comma seperate string of the words for the puzzle. For later use in javascript.
          if($i == 0)
          {
            $words .= buildJScriptWords($word_chars);
          }
          else
          {
            $words .= ','.buildJScriptWords($word_chars);
          }
          $i++;
        }
        $this->js_solution = $words;
      }




      /**
       * Creates html for body of table for user to input synonyms
       */
      function createInputBoxes() {
        $htmlTable = '<div class="container"><h1>Here\'s your "Name in Synonyms"</h1><table class="table table-condensed main-tables" id="puzzle_table"><thead><tr><th>Clue</th><th>Synonym</th></tr></thead><tbody>';
        $i = 0;
        foreach($this->puzzle_chars as $puzzleChar) {
          $word_chars = getWordChars($this->word_array[$i]);
          $htmlTable .= "<tr><td>" . $this->clues_array[$i] . "</td><td>";
          $htmlTable .= '<input class="altPuzzleInput active" type="text" maxLength="7" value="'. $puzzleChar . ' (' . $this->char_indexes[$i] . '/ ' . count($word_chars) . ')" style="display:none" readonly/><input class="altPuzzleInput" type="text" value="" style="display:none"/>';
          $j = 0;
          $flag=false;
          foreach($word_chars as $char) {
            if($char === $puzzleChar && !$flag)
            {
              $htmlTable .= '<input class="puzzleInput word_char active" type="text" maxLength="7" value="'.$word_chars[$j].'" style="display:inline" readonly/>';
              $flag=true;
            }
            else
            {
              $htmlTable .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="" style="display:inline"/>';
            }
            $j++;
          }
          $htmlTable .= "</div>";
          $i++;
        }
        $htmlTable .= '</tbody></table><img id="success_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none"></div>';
        $this->htmlTable = $htmlTable;
      }

      function createAdminInputBoxes() {
        $htmlTable = '<div class="container"><h1>Here\'s your "Name in Synonyms"</h1><table class="table table-condensed main-tables" id="puzzle_table" style="width:100%"><thead><tr><th>Clue</th><th>Synonym</th></tr></thead><tbody>';
        $i = 0;
        foreach($this->puzzle_chars as $puzzleChar) {
          $word_chars = getWordChars($this->word_array[$i]);
          $htmlTable .= "<tr><td>" . $this->clues_array[$i] . "<input type='hidden' name='clue".$i."' value='". getWordIdFromWord($this->clues_array[$i]) ."'/></td><td>";
          $j = 0;
          $flag=false;
          foreach($word_chars as $char) {
            if($char === $puzzleChar && !$flag)
            {
              $htmlTable .= '<input type=\'hidden\' name=\'word'.$i.'\' value="'. getWordIdFromWord($this->word_array[$i]) .'"/><input class="puzzleInput word_char active" type="text" maxLength="7" value="'.$word_chars[$j].'" style="display:inline" readonly/>';
              $flag=true;
            }
            else
            {
              $htmlTable .= '<input class="puzzleInput word_char" type="text" maxLength="7" value="'.$word_chars[$j].'" style="display:inline"/>';
            }
            $j++;
          }
          $htmlTable .= "</div>";
          $i++;
        }
        $htmlTable .= '</tbody></table></div>';
        return $htmlTable;
      }

      /**
       * Sets the char indexes where the char in puzzle_chars can
       * be found in the synonyms
       */
      function setCharIndexes() {
        $char_indexes = array();
        $i = 0;
        foreach($this->puzzle_chars as $char) {
          $word_chars = getWordChars($this->word_array[$i]);
          $j = 0;
          foreach($word_chars as $char2) {
            if ($char === $char2) {
              array_push($char_indexes, $j+1);
              break;
            }
            $j++;
          }
          $i++;
          $j =0;
        }
        $this->char_indexes = $char_indexes;
      }

      // TODO: creates a puzzle in db based on puzzle object info
      function createPuzzle() {
        // create puzzle
        // get word_id_array
        // get clue_id_array
        $puzzleName = $this->puzzleName;
        $word_id_array = getWordIdArray($this->word_array);
        $clue_id_array = getClueIdArray($this->word_array);
        savePuzzle($puzzleName, $word_id_array, $clue_id_array);
      }

      function createTableFooter() {
        $this->buttons = '<div class="container" ><input class="main-buttons" type="button" name="submit_solution" value="Submit Solution" onclick="main_buttons(\'submit\');">
      ' . getShowSolution($this->puzzleName) . '<input class="main-buttons" type="button" name="changeInputMode" value="Change Input Mode" onclick="change_puzzle_input()"></div>';
        $this->admin_buttons = '<div class="container"><h2 style="margin-top:0;">Show synonyms between <input class="word_char active" type="number" maxLength="2" name="minLength" value="'.$this->minLength.'" style="display:inline"/> and <input class="word_char active" type="number" maxLength="2" name="maxLength" value="'.$this->maxLength.'" style="display:inline"/><input class="word_char active" type="hidden" m name="puzzleWord" value="'.$this->puzzleName.'"/> characters<br>Prioritize the synonyms with character in position <input class="word_char active" type="number" maxLength="2" name="position" value="'.$this->position.'" style="display:inline"/></h2><div style="text-align:center"><input class="main-buttons" type="submit" name="iDesign" value="Refresh"/><input class="main-buttons" type="submit" name="saveIDesign" value="Save"/></div></div>';
      }
    }
    ?>


    <script>
      // main function for the buttons when they're clicked.
      function main_buttons(button_name) {
        // the words should be seperated by commas and the characters of the words by '-'.
        var words = "<?php echo $words ?>";
        var wordsArray = words.split(",");
        // get the table and it's length.
        var table = document.getElementById("puzzle_table");
        var tableLength = table.rows.length;
        // helper variables.
        var words_correct = true;
        var childrenLength = 0;

        // start at 1 because top row for the table is the header of the table.
        for (var i = 1; i < tableLength; i++) {
          // for submit_solution
          if (button_name == "submit") {
            // call submit_validation handler method for the submit solution button
            words_correct = submit_validation(table, wordsArray[i - 1], i);

            // break out of loop. If the next word is the last word and the user guessed it right, 
            // then the words_correct would end up as true, even if one word was false.
            if (words_correct === false) {
              break;
            }
          } else if (button_name == "show") // for show solution
          {
            // call show_solution handler method for the show solution button
            show_solution(table, wordsArray[i - 1], i);
          }
        }

        if (button_name == "submit") {
          // checks if the words are correct by passing in words_correct boolean flag.
          checkCorrect(words_correct);
        }
      }

      // 
      function submit_validation(table, word, i) {
        var input_word = "";
        var alt_input_word = "";
        var theWord = rebuildWord(word);
        var childrenLength = table.rows[i].cells[1].children.length;

        alt_input_word += table.rows[i].cells[1].children[1].value;
        for (var j = 0; j < childrenLength - 2; j++) {
          var k = j + 2;
          input_word += table.rows[i].cells[1].children[k].value;
        }

        if (theWord != input_word && theWord != alt_input_word) {
          return false;
        } else {
          return true;
        }
      }

      // rebuild the word whose charactes are seperated by "-".
      function rebuildWord(word) {
        var built_word = "";
        var word_characters = word.split("-");
        var array_length = word_characters.length;

        for (var i = 0; i < array_length; ++i) {
          built_word += word_characters[i];
        }
        return built_word;
      }

      function checkCorrect(words_correct) {
        if (words_correct) // success case
        {
          //alert("Sucess!");
          var el = document.getElementById("success_photo");
          el.style.display = "inline";
        } else { // failure case
          var el = document.getElementById("pop_up_fail");
          el.style.display = "block";
          clear_puzzle();
        }
      }
      // displays the characters of the current word in the puzzle table from the for loop in main_buttons function. 
      function show_solution(table, word, i) {
        var childrenLength = 0;
        var word_array = null;
        var nWord = word;

        word_array = nWord.split("-");
        childrenLength = table.rows[i].cells[1].children.length;
        if (table.rows[i].cells[1].children[1].value.length > 0) {
          clear_puzzle();
        }
        for (var j = 0; j < word_array.length; j++) {
          table.rows[i].cells[1].children[1].value += word_array[j];
        }
        for (var j = 0; j < childrenLength - 2; j++) {
          var k = j + 2;
          table.rows[i].cells[1].children[k].value = word_array[j];
        }
      }
      // clears the character values for all of the words in the puzzle table.
      function clear_puzzle() {
        var table = document.getElementById("puzzle_table");
        var tableLength = table.rows.length;
        var childrenLength = 0;

        for (var i = 1; i < tableLength; i++) {
          childrenLength = table.rows[i].cells[1].children.length;
          for (var j = 0; j < childrenLength; j++) {
            if (!(table.rows[i].cells[1].children[j].className.includes("active"))) {
              table.rows[i].cells[1].children[j].value = "";
            }
          }
        }
      }

      function change_display_none(o) {
        var el = document.getElementById(o);
        el.style.display = "none";
      }

      function toggle_display(el) {
        if (el.style.display == "inline") {
          el.style.display = "none";
        } else {
          el.style.display = "inline";
        }
      }

      function change_puzzle_input() {
        var alt = document.getElementsByClassName("altPuzzleInput");
        var i;
        for (i = 0; i < alt.length; i++) {
          toggle_display(alt[i]);
        }
        var norm = document.getElementsByClassName("puzzleInput");
        var i;
        for (i = 0; i < norm.length; i++) {
          toggle_display(norm[i]);
        }
      }

    </script>
  </body>

</html>
