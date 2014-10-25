<?php
if (!isset($_POST["name"]) || !isset($_POST["password"]) || !isset($_POST["repassword"]) ) {
	header("HTTP/1.1 400 Invalid Request");
	die("An HTTP error 400 (invalid request) occurred.");
} elseif($_POST["password"] != $_POST["repassword"]) {
	header("Location: index-login.php?nonmatch=true");
} elseif(strlen($_POST["password"]) > 12 || strlen($_POST["password"]) < 1 || strlen($_POST["name"]) > 12 || strlen($_POST["name"]) < 1) {
	header("Location: index-login.php?length=true");
} elseif(!preg_match("/^[a-zA-Z0-9]+$/", $_POST["name"])) {
	header("Location: index-login.php?badchar=true");
} else {
	$name = $_POST["name"];
	$password = $_POST["password"];
	$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
			or die ("Couldn't connect");
			$db = "draw_something";
			mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
			
		$rows = mysql_query("SELECT user FROM users");
		$duplicate = false;
		for($i = 0; $i < mysql_num_rows($rows); $i++) {
			if($name == mysql_result($rows, $i)) {
				$duplicate = true;
			}
		}
			
	if(!$duplicate) {
		mysql_query("INSERT INTO users (user, password) VALUES('$name','$password')");
		session_start();
		$_SESSION["loggedin"] = true;
		$_SESSION["user"] = $name;
		header("Location: index.php");
	} else {
		header("Location: index-login.php?duplicate=true");
	}
}
	
?>