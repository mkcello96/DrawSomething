<?php
if(isset($_POST["user"]) && isset($_POST["opponent"]) && isset($_POST["correct"]) && isset($_POST["word"]) && isset($_POST["time"])) {
		$user = $_POST["user"];
		$opponent = $_POST["opponent"];
		$combinedu = $user . "," . $opponent;
		$guessed = $_POST["correct"];
		$word = $_POST["word"];
		$time = $_POST["time"];
		$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
				or die ("Couldn't connect");
				$db = "draw_something";
				mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
		$matches = 0;
		if($guessed == "c") {
				$rows = mysql_query("SELECT matches FROM drawings WHERE names='$combinedu'");
				$uscore = mysql_query("SELECT points FROM users WHERE user='$user'");
				$uscore = mysql_result($uscore, 0) + 1;
				mysql_query("UPDATE users SET points='$uscore' WHERE user='$user'");
				
				$oscore = mysql_query("SELECT points FROM users WHERE user='$opponent'");
				$oscore = mysql_result($oscore, 0) + 1;
				mysql_query("UPDATE users SET points='$oscore' WHERE user='$opponent'");
				
				$matches = mysql_result($rows, 0);
				$matches++;
		}
		
		mysql_query("UPDATE drawings SET guessed='t', guessedlast='$guessed', wordlast='$word', time='$time', matches='$matches' WHERE names='$combinedu'");
		#update points
		#update number of drawings w/ other user (0 if w)
}
?>