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
  </head>
  <title>Final Project</title>
  <body>
    <?PHP
    session_start();
    require('session_validation.php');
    ?>
    <?PHP echo getTopNav(); ?>
    <div class="divTitle">
      <font class="font">Name in Synonyms</font>
    </div>
    <br>
    <div>
      <form action="puzzle.php" method="post">
        <div class="container">
          <div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" placeholder="Enter your Name to see the Puzzle" onclick="clearFields();" />
          </div>
          <br>
          <div style="text-align:center">
            <?PHP
            echo '<input class="main-buttons" type="submit" name="randomPlay" value="Show me.." />';
            if (adminSessionExists()) {
              echo '<input class="main-buttons" type="submit" name="iDesign" value="I will design.." />';
            }
            ?>
          </div>
        </div>
      </form>
    </div>
    <script type="text/javascript">
      function clearFields() {
        document.getElementById("name-textbox").value = "";
      }

    </script>
  </body>
</html>
