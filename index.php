<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/main_style.css" type="text/css">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/main_style.css" type="text/css">

</head>
<title>Final Project</title>

<body>
	<?PHP
		require('session_validation.php');
	?>
	<h2>Final Project</h2>
	<h3>Team: DOLPHIN</h3>
	<h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
	<br><br><br>
	<div class="nav-wrapper">
		<div class="navBar">
			<?PHP echo getTopNav(); ?>
		</div>
	</div>
	<div class="divTitle">
		<font class="font">Name in Synonyms</font>
	</div>
	<br>
	</div>
	<form action="puzzle.php" method="post">
		<div class="inputDiv"><input type="textbox" name="puzzleWord" id="name-textbox" value="Enter your Name to see the Puzzle" onclick="clearFields();" />
		</div>
		<br>
		<input class="main-buttons align" type="submit" name="submit" value="Show me.." />
	</form>
	<script type="text/javascript">
		function clearFields() {
			document.getElementById("name-textbox").value = "";
		}

	</script>

</body>

</html>
