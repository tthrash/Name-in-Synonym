<?php
	/**
	 * Returns true if user has a valid
	 * session else false
	 */
	function sessionExists() {
		// TODO: needs implentation of login and session
		return true;
	}

	/**
	 * Returns true if user has a valid session
	 * and if the user is an admin
	 */
	function adminSessionExists() {
		if (sessionExists()) {
			// TODO: varify if admin
			return true;
		}
		return false;
	}

	/**
	 * Generates topnav to display admin topnav
	 * if admin is logged in else displays
	 * normal navbar
	 */
	function getTopNav() {
		$topNav = "";
		if (adminSessionExists()) {
			$topNav = '<a href="./index.php"><img class="logo" src="./pic/logo.png"></img></a>
			<div class="imageDiv">
			 <a href="./admin.php"><input class="headerButton" type="image" src="./pic/admin.png"></a>
			 <a href="./list_puzzles.php"><input class="headerButton" type="image" src="./pic/list.png"></a>
			 <a href="./add_puzzle.php"><input class="headerButton" type="image" src="./pic/addPuzzle.png"></a>
			 <a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
			 <a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
		 </div>
		 <div class="divTitle"><font class="font">Name in Synonyms</font></div>
		 <br>';
		} else {
			$topNav = '<a href="./index.php"><img class="logo" src="./pic/logo.png"></img></a>
			<div class="imageDiv">
			 <a href="./list_puzzles.php"><input class="headerButton" type="image" src="./pic/list.png"></a>
			 <a href="./add_puzzle.php"><input class="headerButton" type="image" src="./pic/addPuzzle.png"></a>
			 <a href="./addWordPair.php"><input class="headerButton" type="image" src="./pic/addWord.png"></a>
			 <a href="./login.php"><input class="headerButton" type="image" src="./pic/login.png"></a>
		 </div>
		 <div class="divTitle"><font class="font">Name in Synonyms</font></div>
		 <br>';
		}
		return $topNav;
	}
?>
