<?php
	//testing input to steralize
	function validate_input($data)
	{
		$data = stripslashes($data);
		$data = preg_replace('/\s+/', '', $data);
		$data = htmlspecialchars($data);
		$data = trim($data);
		
		return $data;
	}
	function validate_array($array)
	{
		$arrayLng = count($array);
		for($i = 0; $i < $arrayLng; ++$i)
		{
			$array[$i] = validate_input($array[$i]);
			//echo 'Array ' . $i .': \''.$array[$i] .'\'<br>';
		}
		$array = array_filter($array);
		return $array;
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