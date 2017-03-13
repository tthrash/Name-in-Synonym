<?php
	/*
  DEFINE('DATABASE_HOST', 'localhost');
<<<<<<< HEAD
	DEFINE('DATABASE_DATABASE', '   ');
	DEFINE('DATABASE_USER', 'root');
	DEFINE('DATABASE_PASSWORD', '  '); 

=======
	DEFINE('DATABASE_DATABASE', 'name_and_synonym');
	DEFINE('DATABASE_USER', 'prashant');
	DEFINE('DATABASE_PASSWORD', 'password$2'); 
	*/
	
	DEFINE('DATABASE_HOST', 'localhost');
	DEFINE('DATABASE_DATABASE', 'ics325');
	DEFINE('DATABASE_USER', 'username');
	DEFINE('DATABASE_PASSWORD', 'password');
>>>>>>> 2e8b8ce096729deb2e7e62b134229c05e9f803e1
	
$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
if (mysqli_connect_errno()) {
   echo '<p>Error: Could not connect to database.<br/>
   Please try again later.</p>';
   echo $db->error;
   exit;
}	

/* change character set to utf8 */
if (!$db->set_charset("utf8")) {
    //printf("Error loading character set utf8: %s\n", $db->error);
    exit();
} else {
   // printf("Current character set: %s\n", $db->character_set_name());
}
?>
