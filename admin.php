<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/main_style.css" type="text/css">
</head>
<?PHP
 		require('session_validation.php');
	?>
	<title>Final Project</title>

	<body>
		<h2>Final Project</h2>
		<h3>Team: DOLPHIN</h3>
		<h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
		<br><br><br>
		<div class="main-container">
			<div class="header">
				<a href="./index.php"><img class="logo" src="./pic/logo.png" /></a>
				<div class="imageDiv">
					<!-- -->
					<a href="./list_puzzles.php"><input class="headerButton" type="image" src="./pic/list.png"></a>
					<a href="./add_puzzle.php"><input class="headerButton" type="image" src="./pic/addPuzzle.png"></a>
					<a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
					<a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
					<a href="./admin.php"><button class="navOption headerButton">Admin</button></a>
					<!-- TODO: fixing css for admin -->
				</div>
				<div class="divTitle">
					<font class="font">Name in Synonyms</font>
				</div>
				<br>
			</div>
		</div>
		<div>
			<a href="/admin_edit_synonyms.php">Edit Synonyms</a>
			<a href="/export_db.php">Export the database</a>
			<!-- TODO: need to create export_db.php file -->
		</div>
		<div>
			<form method="post" action="">
				<input type="text" name="fileName" placeholder="filename" />
				<input type="submit" value="Import File" name="submint">
			</form>
		</div>
	</body>
</html>
