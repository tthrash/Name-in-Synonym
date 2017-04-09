<?php
	//testing input to steralize
	function validate_input($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = preg_replace('/\s+/', '', $data);
		return $data;
	}
	
	function display_error($message = -1)
	{
		$string = "";
		if ($message == -1) {
			$string = "<script>alert('invalid input try again');</script>";
		} else {
			$string = "<script>alert('" . $message . "');</script>";
		}
		return $string;
	}

?>