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
    <h2>Final Project</h2>
    <h3>Team: DOLPHIN</h3>
    <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
    <br><br><br>
    <?PHP echo getTopNav(); ?>
    <!--FIXME: location of fail message not displayed properly-->
    <div id="pop_up_fail" class="container pop_up" style="display:none">
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
          } else {
            echo "<p>error</p>";
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
              $index = getWordIdFromChar($char);
              if ($index != false) {
                array_push($word_array, getWordValue($index));
                array_push($clues_array, getRandomClueWord($index));

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
                    echo '<input class="word_char active" type="text" maxlength="7" value="'.$word_chars[$j].'" readonly/>';
                  }
                  else
                  {
                    echo '<input class="word_char" type="text" maxlength="7" value=""/>';
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

          class Puzzle {
            function Puzzle($puzzleName,$puzzle_id = -1, $preferedPosition = -1) {
              try {
                $this->setName($puzzleName);
                $this->setId($puzzle_id);
                $this->setPreferedPosition($preferedPosition);
                $this->setPuzzleWords($puzzleName,$puzzle_id);
                $this->setCharIndexes();
                $this->createJSSolution();
                $this->createInputBoxes();
                //$this->createPuzzle();
              } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
              }
            }

            function setName($puzzleName) {
              if (!is_string($puzzleName)) {
                throw new Exception("Name of a puzzle must be a string!");
              }
              $this->puzzleName = $puzzleName;
            }

            function setId($puzzle_id) {
              if (!is_int($puzzle_id)) {
                throw new Exception("Puzzle_Id must be an integer!");
              } else if ($puzzle_id !== -1 && !checkPuzzleId($puzzle_id)) {
                throw new Exception("Puzzle_Id did not find a match!");
              }
              $this->puzzle_id = $puzzle_id;
            }

            function setPreferedPosition($preferedPosition) {
              if (!is_int($preferedPosition)) {
                throw new Exception("Position must be an integer");
              } else if ($preferedPosition < -1) {
                throw new Exception("Position out of bounds");
              }
              $this->position = $preferedPosition;
            }

            /**
             * break name entered into chars
             * if puzzle exisits get words, clues
             * else create random words, clues
             * save them to the object
             */
            function setPuzzleWords() {
              $word_array = array();
              $clues_array = array();
              $puzzle_chars = getWordChars($this->puzzleName);
              foreach($puzzle_chars as $char) {
                $index = getWordIdFromChar($char,$this->position);
                if ($index != false && $this->position === -1) {
                  array_push($word_array, getWordValue($index));
                  array_push($clues_array, getRandomClueWord($index));

                } else if ($this->puzzle_id !== -1) {
                  $word_array = getWordValuesFromPuzzleWords($puzzle_id);
                  $clues_array = getClueValuesFromPuzzleWords($puzzle_id);
                  break;
                } else {
                  array_push($word_array, $char);
                  array_push($clues_array, $char);
                }
              }
              $this->puzzle_chars = $puzzle_chars;
              $this->word_array = $word_array;
              $this->clues_array = $clues_array;
            }

            // FIXME: does not with with both input styles
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
            
            /*
             * FUTURE: implement toggle display for different input style or 2nd function for alt
             * style of input.
             */
            function createInputBoxes() {
              $htmlTable = "";
              $i = 0;
              foreach($this->puzzle_chars as $puzzleChar) {
                $word_chars = getWordChars($this->word_array[$i]);
                $htmlTable .= "<tr><td>" . $this->clues_array[$i] . "</td><td>";
                $htmlTable .= '<div class="altPuzzleInput" style="display:none"><input class="active" type="text" maxlength="7" value="'. $puzzleChar . ' (' . $this->char_indexes[$i] . '/ ' . count($word_chars) . ')" readonly/><input class="" type="text" value=""/></div><div class="puzzleInput" style="display:block">';
                $j = 0;
                foreach($word_chars as $char) {
                  if($char === $puzzleChar)
                  {
                    $htmlTable .= '<input class="word_char active" type="text" maxlength="7" value="'.$word_chars[$j].'" readonly/>';
                  }
                  else
                  {
                    $htmlTable .= '<input class="word_char" type="text" maxlength="7" value=""/>';
                  }
                  $j++;
                }
                $htmlTable .= "</div>";
                $i++;
              }
              $this->htmlTable = $htmlTable;
            }

            function setCharIndexes() {
              $char_indexes = array();
              $i = 0;
              foreach($this->puzzle_chars as $char) {
                $word_chars = getWordChars($this->word_array[$i]);
                $j = 0;
                foreach($word_chars as $char2) {
                  if ($char === $char2) {
                    array_push($char_indexes, $j+1);
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

            }
          }



          ?>
        </tbody>
      </table>
      <?PHP //var_dump($puzzle)?>
      <!--FIXME: success photo is not in right location -->
      <img id="success_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none">
    </div>
    <!--FIXME: submit does not word because of two different input styles-->
    <input class="main-buttons" type="button" name="submit_solution" value="Submit Solution" onclick="main_buttons('submit');">

    <!--FIXME: show solution does not work because of two different input styles-->
    <?PHP echo getShowSolution($nameEntered); ?>
    <input class="main-buttons" type="button" name="changeInputMode" value="Change Input Mode" onclick="change_puzzle_input()">
    <!-- <input class="main-buttons" type="button" name="show_solution" value="Show Solution" onclick="main_buttons('show');"> -->

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
        var theWord = rebuildWord(word);
        var childrenLength = table.rows[i].cells[1].children.length;
        for (var j = 0; j < childrenLength; j++) {
          input_word += table.rows[i].cells[1].children[j].value;
        }

        if (theWord != input_word) {
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
          el.style.display = "block";
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

        for (var j = 0; j < childrenLength; j++) {
          table.rows[i].cells[1].children[j].value = word_array[j];
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
        if (el.style.display == "block") {
          el.style.display = "none";
        } else {
          el.style.display = "block";
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
