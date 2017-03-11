<!DOCTYPE html>
<html>
</head Content-Type: text/html; Charset=UTF-8>
<link rel="stylesheet" type="text/css" href="mystyle.css">

<title>Final Project</title>
</head Content-Type: text/html; Charset=UTF-8>
<style type="text/css">
.header{
	border-radius: 25px;
	height: 150px;
	width: 1500px;
	background-color: #92d050;
}
.logo{
	height: 130px;
	margin-top: 10px;
	margin-left: 3px;
	float: left;
}
.divTitle{
	position: relative;
	top: 30px;
	height: 30px;
	margin-left: 270px;
	width: 600px;
}
.imageDiv{
	position: relative;
	top: 5px;
	height: 14px;
	margin-left: 900px;
	width: 580px;
}
.font{
	font-size: 70px;
}
.inputDiv{
	border-radius: 25px;
	height: 150px;
	width: 900px;
	border-width: 3px;
    border-style: solid;
	margin-top: 80px;
	margin-left: 300px;
}
#textbox{
	background-color: transparent;
    border: 0px solid;
    outline: none;
	margin-left: 15px;
	margin-top: 10px;
	height:130px;
	width:800px;
	font-size: 50px;
}
.showMe{
	margin-left: 600px;
}


</style>
<body>
  <h2>Final Project</h2>
  <h3>Team: DOLPHIN</h3>
  <h3>Dennis Lee, Gary Webb, Prashant Shrestha, Tyler Thrash</h3>
  <br><br><br>

  <div class="header">
  	<a href="http://puzzles.thisisjava.com/"><img src="./pic/logo.png" class="logo"></img></a>
  	<div class="imageDiv">
  		<input type="image" src="./pic/list.png" height="140px">
  		<input type="image" src="./pic/addWord.png" height="140px">
  		<input type="image" src="./pic/addPuzzle.png" height="140px">
  		<input type="image" src="./pic/login.png" height="140px">
  	</div>
  	<div class="divTitle"><font class="font">Name in Synonyms</font></div>
  	<br>	
  </div>
  <div class="inputDiv"><input type="textbox" id="textbox" value="Enter your Name to see the Puzzle" onclick="clearFields();"></input></div>
  <br>
  <input type="image" src="./pic/showMe.png" class="showMe">


<script type="text/javascript">
	function clearFields(){
		document.getElementById("textbox").value = "";
	}
</script>




</body>
</html>