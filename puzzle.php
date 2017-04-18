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
        <button class="pop_up_button" onclick="toggle_display('pop_up_fail')">OK</button>
      </div>
    </div>
    <div class="header2">Here's your "Name in Synonyms"</div>
    <div>
      <table class="main-tables" id="puzzle_table">
        <tr>
          <th>Clue</th>
          <th>Synonym</th>
        </tr>
        <?php
        $words = "";
        $nameEntered = "";
        if(isset($_POST['submit']))
        {
          if(isset($_POST['puzzleWord']))
          {
            $nameEntered = $_POST['puzzleWord'];
            $words = generatePuzzle($nameEntered);

            //$words = createPuzzle($nameEntered);
          }
        }else if(isset($_GET['puzzleName']) && isset($_GET['id']))
        {
          $nameEntered = $_GET['puzzleName'];
          $puzzle_id = $_GET['id'];
          $words = generateExactPuzzle($nameEntered, $puzzle_id);
          //$words = createPuzzle($nameEntered);
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
            $word_array = getWordIdsFromPuzzleWords($puzzle_id);
            $clues_array = getClueIdsFromPuzzleWords($puzzle_id);
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
        ?>
      </table>
      <!--FIXME: succes photo is not in right location -->
      <img id="succes_photo" class="success" src="pic/thumbs_up.png" alt="Success!" style="display:none">
    </div>
    <input class="main-buttons" type="button" name="submit_solution" value="Submit Solution" onclick="main_buttons('submit');">

    <?PHP echo getShowSolution($nameEntered); ?>
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
          var el = document.getElementById("succes_photo");
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

      function toggle_display(o) {
        var el = document.getElementById(o);
        el.style.display = "none";
      }

    </script>
  </body>

</html>
