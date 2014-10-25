<?php
		$wordsex = file("words.txt");
		//$wordsex = explode(" ", $wordsex);
		$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
					or die ("Couldn't connect");
					$db = "draw_something";
					mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
					
		
		for($i = 0; $i < count($wordsex); $i++) {
			$thisword = strtolower($wordsex[$i]);
			#mysql_query("INSERT IGNORE INTO words VALUES('$thisword')");
			print $wordsex[$i];
		}
?>

