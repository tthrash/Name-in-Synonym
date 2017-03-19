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
  <div class="text" id="confirmText">
    <font class="fontword">Name In Synonym <img src="./pic/arrow.png"> Add Word Pairs<br>
      Enter all the synonyms seperated by comma</font>
  </div>
  <br><br>
  <form method="post">
  <div class="inputDiv"><input type="textbox" name="addWord" id="name-textbox"></input></div>
  <br>
  <input  class="addButton" type="submit" name="submit" value="Add Word Pairs" onclick="confirmation()">
  </form>
  </div>
<script type="text/javascript">
  function confirmation(){
    document.getElementById("confirmText").innerHTML = 'blah';
  }
</script>
<?php 
require('db_configuration.php');

if(isset($_POST['submit'])){
  
  $words = trim($_POST['addWord']);
  //echo $_POST['addWord'];
  if($_POST['addWord'] == ''){
    echo "<p class= \"fontword\">You did not enter any words. Please try again.</p>";
  }
  else if(count(explode(',', $words)) < 2){
    echo "<p class= \"fontword\">You must enter two or more words seperated by a comma. Please try again.</p>";
  }else{

    //var_dump(explode(',', $words));

    $list = explode(',', $words);

     for($i = 0; $i < count($list);$i++){
      
      //insert each words into word table.
      var_dump($list[$i]);

      $letters=str_split($list[$i]);
      for($j = 0; $j < count($letters); $j++) {

      //insert each letter into char table.
      var_dump($letters[$j]);
      };

     };

  }
  
}

?>
</body>
</html>