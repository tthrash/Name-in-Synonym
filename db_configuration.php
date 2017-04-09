<?php
	DEFINE('DATABASE_HOST', 'localhost');
	DEFINE('DATABASE_DATABASE', ' ');
	DEFINE('DATABASE_USER', 'root');
	DEFINE('DATABASE_PASSWORD', ' ');
	
	function run_sql($sql_script)
	{
		$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
		// check connection
		if ($db->connect_error)
		{
			trigger_error('Database connection failed: '  . $db->connect_error, E_USER_ERROR);
			$db->close;
			return false;
		}
		else
		{
			$result = $db->query($sql_script);
			if(!$result)
			{
				trigger_error('Invalid SQL: ' . $sql_script . '; Error: ' . $db->error, E_USER_ERROR);
				$result->close;
				$db->close;
				return false;
			}
			else
			{
				return $result;
			}
		}
	}
?>
