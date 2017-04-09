<?php
  DEFINE('DATABASE_HOST', 'localhost');
  DEFINE('DATABASE_DATABASE', 'ics325');
  DEFINE('DATABASE_USER', 'prashant');
  DEFINE('DATABASE_PASSWORD', 'password$2');
	
	function run_sql($sql_script)
	{
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		// check connection
		if ($db->connect_error)
		{
			trigger_error('Database connection failed: '  . $db->connect_error, E_USER_ERROR);
			$db->close();
			return false;
		}
		else
		{
			$result = $db->query($sql_script);
			if(!$result)
			{
				trigger_error('Invalid SQL: ' . $sql_script . '; Error: ' . $db->error, E_USER_ERROR);
				$result->close();
				$db->close();
				return false;
			}
			else
			{
				return $result;
			}
		}
	}
?>
