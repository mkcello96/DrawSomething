<?php
session_start();
$combined = $_SESSION["user"] . "," . $_GET["user"];
$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
				or die ("Couldn't connect");
				$db = "draw_something";
				mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
mysql_query("INSERT INTO drawings (names) VALUES('$combined')");
header("Location: index.php");

?>
