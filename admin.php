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
	require('upload.php');
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
			<a href="admin_edit_synonyms.php">[1]Edit Synonyms for the word</a>
			<a href="export_db.php">[2]Export the word list (Source: Database; Target: Excel file)</a>
			<form method="post">
				<a href="">[3] Import the word list (Source: Excel File; Target: Database) </a> 
					<input type="file" name="fileToUpload" id="fileToUpload">
				
					<input type="submit" value="Submit File" name="submit">
			</form>
			<a href="export_db.php">[4] Export the logical_char list (Source: Database; Target: Excel file)</a>
			<a href="export_db.php">[5]Export the puzzle list (Source: Database; Target: Excel file)</a>
      		<a href="admin_manage_users.php">[6] Manage Users (add, delete, update) (Extra Credit)</a>
		</div>
		<div>
		</div>
	</div>
</body>
</html>
