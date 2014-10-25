<?php
	if(isset($_POST["points"]) && isset($_POST["user"]) && isset($_POST["opponent"]) && isset($_POST["word"]) && isset($_POST["message"])) {
		$combinedo = $_POST["opponent"] . "," . $_POST["user"];
		$combinedu = $_POST["user"] . "," . $_POST["opponent"];
		$points = json_decode($_POST["points"]);
		$word = $_POST["word"];
		$message = $_POST["message"];
		if($message == "[Optional message]") {
			$message = "";
		}
		$pointsArray = $points->{'items'};
		$string = "";
		for($i = 0; $i < count($pointsArray); $i++) {
			$string .= $pointsArray[$i][0] . "," . $pointsArray[$i][1] . "\n";
		}
		$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
				or die ("Couldn't connect");
				$db = "draw_something";
				mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
				
		mysql_query("UPDATE drawings SET names='$combinedo', points='$string', word='$word', guessed='f', message='$message' WHERE names='$combinedu'");
	}
?>