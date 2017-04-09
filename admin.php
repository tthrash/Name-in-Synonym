<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/main_style.css" type="text/css">
	<title>Final Project</title>
</head>
<?PHP
	require('session_validation.php');
?>
<body>
	<h2>Final Project</h2>
	<h3>Team: DOLPHIN</h3>
	<h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
	<br><br><br>
	<div class="nav-wrapper">
		<div class="navBar">
			<?PHP echo getTopNav(); ?>
		</div>
	</div>
	<div class="admin-wrapper">
		<div id="export">
			<a href="admin_edit_synonyms.php">Edit Synonyms</a>
      <a href="admin_manage_users.php">Manage Users</a>
			<a href="export_db2.php">Export the database</a>
		</div>
		<div id="import">
			<form action="upload.php" method="post" enctype="multipart/form-data">
				<label>Select file to upload:  <input type="file" name="fileToUpload" id="fileToUpload"></label>
				<br>
				<input type="submit" value="Submit File" name="submit">
			</form>
		</div>
	</div>
</body>
</html>
