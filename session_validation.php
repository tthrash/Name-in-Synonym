<?php
	/**
	 * Returns true if user has a valid
	 * session else false
	 */
	function sessionExists() {
		if ((isset($_SESSION['valid_user'])) && (($_SESSION['valid_user']) != null))  {
			return true;
		}
		else {
			return false;
		}
	}
	/**
	 * Returns true if user has a valid session
	 * and if the user is an admin
	 */
	function adminSessionExists() {
		if ((isset($_SESSION['valid_admin'])) && (($_SESSION['valid_admin']) != null)) {
			return true;
		}
		else{
			return false;
		}
	}
	/**
	 * Generates topnav to display admin topnav
	 * if admin is logged in else displays
	 * normal navbar
	 */
	function getTopNav() {
		$topNav = "";
		if (adminSessionExists()) {
			$topNav = '<a href="./index.php"><img class="logo" src="./pic/logo.png" /></a>
				<font class="font">Name in Synonyms</font>
				<a href="./admin.php"><button id="admin" class="navOption">Admin</button></a>
				<a href="./list_puzzles.php"><button id="list" class="navOption">List</button></a>
				<a href="./add_puzzle.php"><button id="addword" class="navOption">Add<br> A<br> Puzzle</button></a>
				<a href="./addWordPair.php"><button id="addpuzzle" class="navOption">Add<br> Word<br> Pairs</button></a>
				<a href="./logout.php"><button id="logout" name ="logout" class="navOption">Logout</button></a>';
		} else if (sessionExists()) {
			$topNav = '<a href="./index.php"><img class="logo" src="./pic/logo.png" /></a>
				<font class="font">Name in Synonyms</font>
				<a href="./list_puzzles.php"><button id="list" class="navOption">List</button></a>
				<a href="./add_puzzle.php"><button id="addword" class="navOption">Add<br> A<br> Puzzle</button></a>
				<a href="./addWordPair.php"><button id="addpuzzle" class="navOption">Add<br> Word<br> Pairs</button></a>
				<a href="./logout.php?&button=logout"><button id="logout" name="logout" class="navOption">Logout</button></a>';
		}
		else{
			$topNav = '<a href="./index.php"><img class="logo" src="./pic/logo.png" /></a>
				<font class="font">Name in Synonyms</font>
				<a href="./list_puzzles.php"><button id="list" class="navOption">List</button></a>
				<a href="./add_puzzle.php"><button id="addword" class="navOption">Add<br> A<br> Puzzle</button></a>
				<a href="./login.php"><button id="login" class="navOption">Login</button></a>';
			
		}
		return $topNav;
	}
?>