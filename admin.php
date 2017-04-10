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
	//require('upload.php');
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
		<div id="export">
			<a href="admin_edit_synonyms.php">[1]Edit Synonyms for the word</a>
			<a href="export_db.php">[2]Export the word list (Source: Database; Target: Excel file)</a>
      <a href="#" class="upload">[3]Import the word list (Source: Excel File; Target: Database)</a>
      <div id="import">
        <p id="error" style="display: none;">Error: You must select a file to import</p>
        <?php
          require('upload.php');
          if($error){
        ?>
        <p id="error" style="display:block;background-color: #ce4646;padding:5px;color:#fff;">
        <?php echo $result; ?>
        </p>
        <?php } ?>
         <form class="upload" method="post" name="importFrom" enctype="multipart/form-data" onsubmit="return validateForm()">
          <label class="upload">Select file to upload:  <input class="upload" type="file" name="fileToUpload" id="fileToUpload"></label>
          <input class="upload" type="submit" value="Submit File" name="submit">
        </form>
      </div>
      <a href="export_db.php">[4] Export the logical_char list (Source: Database; Target: Excel file)</a>
			<a href="export_db.php">[5]Export the puzzle list (Source: Database; Target: Excel file)</a>
      <a href="admin_manage_users.php">[6] Manage Users (add, delete, update) (Extra Credit)</a>
    </div>
      
	<script>
		function validateForm() {
		    var eng = document.forms["importFrom"]["fileToUpload"].value;
		    if (eng == "") {
		    
		    	document.getElementById("error").style = "display:block;background-color: #ce4646;padding:5px;color:#fff;";
		        return false;
		    }
		}
	</script>
</body>
</html>
