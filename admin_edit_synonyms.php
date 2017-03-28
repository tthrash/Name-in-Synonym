<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/main_style.css" type="text/css">
  <style>
  .text{
    position: relative;
    top: 20px;
    height: 30px;
    margin-left: 30px;
    width: 680px;
}
.fontword{
    font-size: 30px;
}
.divInputs{
    position: relative;
    top: 80px;
    height: 140px;
    margin-left: 200px;
    width: 980px;
}
.inputleft{
    border-radius: 25px;
    height: 130px;
    width: 450px;
    border-width: 3px;
    border-style: solid;
    margin-top: 90px;
    margin-left: 0px;
}
.inputright{
    border-radius: 25px;
    height: 130px;
    width: 450px;
    border-width: 3px;
    border-style: solid;
    margin-top: -135px;
    margin-left: 530px;
}
.textbox{
    background-color: transparent;
    border: 0px solid;
    outline: none;
    margin-left: 15px;
    margin-top: 10px;
    height:100px;
    width:340px;
    font-size: 35px;    
}
.imagediv{
    margin-top: -100px;
    margin-left: 460px; 
}
.addButton{
    background-color: #70baeb;
    border: 2px solid black;
    color: black;
    padding: 15px 32px;
    width: 320px;
    height: 60px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size:30px;
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
  <h2>Final Project</h2>
  <h3>Team: DOLPHIN</h3>
  <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
  <br><br><br>
  <div class="main-container">
      <div class="header">
        <a href="./index.php"><img class="logo" src="./pic/logo.png"></img></a>
        <div class="imageDiv">
         <a href="./list_puzzles.php"><input class="headerButton" type="image" src="./pic/list.png"></a>
         <a href="./add_puzzle.php"><input class="headerButton" type="image" src="./pic/addPuzzle.png"></a>
         <a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
         <a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
     </div>
     <div class="divTitle"><font class="font">Name in Synonyms</font></div>
     <br>
 </div>
 <br>
 <?php 
//require('db_configuration.php');
 require('create_puzzle.php');

 $db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

 if(isset($_GET['word']))
 {
    $wordProvided = $_GET['word'];
    //echo $wordProvided;
    if($wordProvided != NULL)
    {
        $sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $wordProvided. '\';';
        $result =  $db->query($sqlcheck);
        $row = $result->fetch_assoc();
        $repId = $row["rep_id"];
        $show=$wordProvided;

        $sqlGetSynonyms = 'SELECT * FROM words WHERE rep_id = \''. $repId. '\';';
        $result =  $db->query($sqlGetSynonyms);
        $synonyms = array();
        while($row = $result->fetch_assoc()){
            array_push($synonyms, $row);
            $data = $row["word_value"];
            if($data != $wordProvided)
            {
                $show = $show.", ".$data;
            }
        }
        //echo $show;
    }
}

?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['updateWord'] == ''){
  echo "<div class='result' id='confirmText'>";
  echo "<font class='fontword'>Thank you. The synonym list has been updated.<br><br>";
  echo "Would you like to update another set of synonyms?</font>";
  echo "</div>";
}else{
  echo "<div class='result' id='confirmText'>";
  echo "<font class='fontword'>Name In Synonym <img src='./pic/arrow.png'> Edit Synonyms<br><br>";
  echo "Here are all the synonyms of the word \"<font color='blue'>  $wordProvided  </font>\" <br>";
  echo "You can add, delete, or update any word in the list</font>";
  echo "</div>";
}
?>

<form method="post" id="inputForm">
    <div class="inputDiv"><input type="textbox" name="updateWord" id="name-textbox" value="<?php  echo htmlentities($show) ?>"></input></div>
    <br>
    <input class="addButton" id="addButton" type="submit" name="submit" value="Update Word Pairs">
</form>
</div>
<?php 

if(isset($_POST['submit'])){
    //ECHO $_POST['updateWord'];
    //echo $show;

    $db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
    var_dump($synonyms);
    foreach ($synonyms as $word){

        $word_id = $word["word_id"];
        
        var_dump($word_id);
        $sqlDeleteChar = 'DELETE FROM characters WHERE word_id = \''. $word_id. '\';';
        $result =  $db->query($sqlDeleteChar);
        // if($result)
        // {
        //     echo "deleted from characters";
        // }

        $aqlDeletePuzzleWord = 'DELETE FROM puzzle_words WHERE word_id = \''. $word_id . '\';';
        $result =  $db->query($aqlDeletePuzzleWord);
        // if($result)
        // {
        //     echo "deleted from puzzle-words";
        // }
        $sqlDeletewords = 'DELETE FROM words WHERE word_id = \''. $word_id  . '\';';
        $result = $db->query($sqlDeletewords);
        // if($result)
        // {
        //     echo "deleted from words";
        // }
    }

    $newWords = trim($_POST['updateWord']);
    echo $newWords;


    $list = explode(',', $newWords);

    for($i = 0; $i < count($list);$i++){

         $list[$i] = trim($list[$i]);

        //Check to see if entered word exists in the DB.
         $sqlcheck = 'SELECT * FROM words WHERE word_value = \''. $list[$i] . '\';';
         $result =  $db->query($sqlcheck);

         $num_rows = $result->num_rows;
         var_dump($list[$i]);

        if($num_rows == 0){ 
            if($i == 0){
               $repId = getMaxWordId();
           }
           else
           {
               $repId = getMaxWordId($list[0]);
           }

                    //insert each new word into word table.
           $sqlAddWord = 'INSERT INTO words (word_id, word_value, rep_id) VALUES (DEFAULT, \'' . $list[$i] . '\', \'' . $repId . '\');';
           $result =  $db->query($sqlAddWord);

           $sql = 'SELECT word_id FROM words WHERE word_value =\'' . $list[$i] . '\';';
           $result =  $db->query($sql);
           $row = $result->fetch_assoc();
           $word_id = $row["word_id"]; 
                    //echo $word_id;      

           $letters=str_split($list[$i]);
           for($j = 0; $j < count($letters); $j++) {
                         //insert each letter into char table.
               $sqlAddLetters = 'INSERT INTO characters (word_id, character_index, character_value) VALUES (\''. $word_id . '\', \'' . $j .'\', \''. $letters[$j].'\');';
               $result =  $db->query($sqlAddLetters);
           };
        }
        //header("Location:admin_edit_synonyms.php");
    }
}

?>

</body>
</html>
