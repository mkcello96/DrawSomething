<?php
if(isset($_POST["user"]) && isset($_POST["opponent"])) {
		$combinedo = $_POST["opponent"] . "," . $_POST["user"];
		$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
				or die ("Couldn't connect");
				$db = "draw_something";
				mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
		mysql_query("DELETE FROM drawings WHERE names='$combinedo'");
}

?>