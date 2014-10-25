<?php
	header("Content-Type: text/plain");
	if(isset($_GET["user"])) {
		$user = $_GET["user"];
		$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
					or die ("Couldn't connect");
					$db = "draw_something";
					mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
					
		$rows = mysql_query("SELECT names FROM drawings WHERE names LIKE '$user%'");
	
		if(mysql_num_rows($rows) > 0) {
			$names = explode(",", mysql_result($rows, 0));
			print $names[1];
		}
	}
?>
