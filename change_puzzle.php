<!DOCTYPE html>
<html>
  <head>
    <?PHP
      session_start();
      require('session_validation.php');
      require_once('add_puzzle_process.php');
      require_once('utility_functions.php');
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
    <title>Final Project</title>
  </head>
  <body>
    <h2>Final Project</h2>
    <h3>Team: DOLPHIN</h3>
    <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
    <br><br><br>
    <?PHP echo getTopNav(); ?>
    <div id="pop_up_fail" class="container pop_up" style="display:none">
      <div class="pop_up_background">
        <img class="pop_up_img_fail" src="pic/info_circle.png">
        <div class="pop_up_text">Incorrect! <br>Try Again!</div>
        <button class="pop_up_button" onclick="toggle_display('pop_up_fail')">OK</button>
      </div>
    </div>
    <?php
      $sqlUpdate ="";
      if (isset($_GET['puzzleName'])) {
        $nameEntered = validate_input($_GET['puzzleName']);
        echo create_puzzle_table($nameEntered, "change_puzzle.php?");	
      }
      else if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST["word"])) {
          $name = $size = "";
          $list = array();
          if(empty($_POST["word"]) && empty($_POST["size"])) {
            //should not happen
          }
          else {
            $name = mb_strtolower(validate_input($_POST["word"]), 'UTF-8');
            $size = validate_input($_POST["size"]);
            $errorflag = FALSE;
            for ($j = 0; $j < $size; $j++) {
              $tempWord = "word". $j;
              $tempClue = "clue" . $j;
              if(empty($_POST[$tempWord]) && empty($_POST[$tempClue])) {
                // left one of the Synonym or Clues empty
                // let user know of error
                if ($errorflag == FALSE) {
                  echo create_puzzle_table($name, "change_puzzle.php?");
                  echo display_error("Please give every synonym and clue a value!");
                  $errorflag = TRUE;
                }
              }
              else {
                // valid input
                $word1 = mb_strtolower(validate_input($_POST[$tempWord]), 'UTF-8');
                $word2 = mb_strtolower(validate_input($_POST[$tempClue]), 'UTF-8');
                //echo "words: " . $word1. $word2;
                $char = substr($name, $j, 1);
                $char = 
                  //echo "char: " . $char;
                  $index = strpos($word1, $char);
                //echo "index: " . $index;
                if ($index === false){
                  echo	create_puzzle_table($name, "change_puzzle.php?");
                  echo display_error("Char not found in word!");
                  return;
                } else {
                  // add to words
                  insertIntoWords($word1, $word2);
                  // add to char
                  insertIntoCharacters(getMaxWordId($word1));
                  insertIntoCharacters(getMaxWordId($word2));
                  $db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
                  $sqlUpdate = 'UPDATE puzzle_words SET word_id=\'' . getMaxWordId($word1) . '\' WHERE puzzle_id=\'' . getMaxPuzzleId($name) . '\' AND position_inName=\'' . $j . '\';';
                  $result =  $db->query($sqlUpdate);
                  //$num_rows = $result->num_rows;
                }
              }

            }
          }
          if (strcmp($sqlUpdate, "") == 0) {

          } else {
            echo createHeader(validate_input($_POST["word"]));
            echo '<table class="main-tables" id="puzzle_table"><tr><th>Clue</th><th>Synonym</th></tr>';
            puzzleAddedTable(validate_input($_POST["word"]));
            echo "</table>";
            echo createFooter();
          }
        }
      }
    ?>
  </body>
  <!-- <script type="text/javascript" src="javascript/puzzle.js"></script> -->
</html>
