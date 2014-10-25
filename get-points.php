<?php
	header("Content-Type: text/plain");
	$combinedu = $_GET["user"] . "," . $_GET["opponent"];
	$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
				or die ("Couldn't connect");
				$db = "draw_something";
				mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
	$rows = mysql_query("SELECT points, message FROM drawings WHERE names='$combinedu'");
	print mysql_result($rows, 0, "message") . "\n" . mysql_result($rows, 0, "points");

?>