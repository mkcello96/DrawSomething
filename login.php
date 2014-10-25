<?php

if (!isset($_POST["name"]) || !isset($_POST["password"])) {
	header("HTTP/1.1 400 Invalid Request");
	die("An HTTP error 400 (invalid request) occurred.");
}

$name = $_POST["name"];
$password = $_POST["password"];
$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
		or die ("Couldn't connect:");
		$db = "draw_something";
		mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
		$rows = mysql_query("SELECT user, password, points FROM users WHERE user='$name'");

if (mysql_num_rows($rows) == 0) {
	header("Location: index-login.php?notfound=true");
} elseif ($password != mysql_result($rows, 0, "password")) {
	header("Location: index-login.php?incorrectpass=true");
} else {
	session_start();
	$_SESSION["loggedin"] = true;
	$_SESSION["user"] = mysql_result($rows, 0, "user");
	header("Location: index.php");
}
?>
