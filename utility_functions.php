<?php
	/**
	 * Strips all slashes removes all spaces and converts htmlspecialchars into harmless input
	 * @param  string $data to be steralized
	 * @return string steralized data
	 */
	function validate_input($data)
	{
		$data = stripslashes($data);
		$data = preg_replace('/\s+/', '', $data);
		$data = htmlspecialchars($data);
		$data = trim($data);
    $data =  str_replace(";", "", $data);
		
		return $data;
	}

	/**
	 * Calles validate_input over an array
	 * @param  string $array to be steralized
	 * @return string steralized data
	 */
	function validate_array($array)
	{
		$arrayLng = count($array);
		for($i = 0; $i < $arrayLng; ++$i)
		{
			$array[$i] = validate_input($array[$i]);
		}
		$array = array_filter($array);
		return $array;
	}

	/**
	 * Returns a string that contains html to create a script alert
	 * @param  string [$message = -1] error message to display
	 * @return string html for alert messaage
	 */
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

  /**
   * 
   * @param string $data you want to test for sqlinject
   */
  function containsSql($data) { // NOTE: we should just use real_escape_string(); and  prepared statements.
    $flag = false;
    $encoding = 'UTF-8';
    $dataLower = mb_strtolower($data, $encoding);
    $length = mb_strlen($dataLower,$encoding);
    if (mb_strpos($dataLower, "drop", $encoding) || mb_strpos($dataLower, "show", $encoding)) { // word contains drop or show
      if (mb_strpos($dataLower, "table", $encoding) || mb_strpos($dataLower, "database", $encoding)) {
        return true;
      }
    }
    if (mb_strpos($dataLower, "select", $encoding) || mb_strpos($dataLower, "delete", $encoding) || mb_strpos($dataLower, "insert", $encoding) || mb_strpos($dataLower, "update", $encoding)) {
      if (mb_strpos($dataLower, "join", $encoding) || tableNames($dataLower) || mb_strpos($dataLower, "into", $encoding)) {
        return true;
      }
    }
    return $flag;
  }

/**
 * 
 * @param  [[Type]] $input [[Description]]
 * @return boolean  [[Description]]
 */
function containsTableNames($input) {
  $names = array("puzzles", "words", "puzzle_words", "characters", "users");
  foreach($names as $name) {
    if (mb_strpos($dataLower, "join", $encoding)) {
      return true;
    }
  }
  return false;
}

?>