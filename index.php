<!DOCTYPE html>
<html>
<head>
<<<<<<< HEAD
<<<<<<< HEAD
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/main_style.css" type="text/css">
=======
=======
>>>>>>> 3e766a193335119bc5bf15efa2dcf164d97ea68e
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/main_style.css" type="text/css">
<<<<<<< HEAD
>>>>>>> 534ff3664bf03f98b245baf0ab1a09cfb63c865f
=======

>>>>>>> 3e766a193335119bc5bf15efa2dcf164d97ea68e
</head>
<title>Final Project</title>
<body>
  <h2>Final Project</h2>
  <h3>Team: DOLPHIN</h3>
  <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
  <br><br><br>

  <div class="header">
    <a href="http://puzzles.thisisjava.com/"><img class="logo" src="./pic/logo.png"></img></a>
    <div class="imageDiv">
      <input class="headerButton" type="image" src="./pic/list.png">
      <input class="headerButton" type="image" src="./pic/addPuzzle.png">
      <a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
      <a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
    </div>
    <div class="divTitle"><font class="font">Name in Synonyms</font></div>
    <br>
  </div>


  <form action="puzzle.php" method="post">
  <div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" value="Enter your Name to see the Puzzle (support for only 'metro' and 'nice')" onclick="clearFields();"></input></div>
  <br>
  <input class="showMe" type="submit" name="submit" value="Show me..">
  </form>


<script type="text/javascript">
  function clearFields(){
    document.getElementById("name-textbox").value = "";
  }
</script>

</body>
</html>
