<?php
	session_start();
	$user = $_SESSION["user"];
	if(!isset($_SESSION["loggedin"])) {
		header("Location: index-login.php");
	}
	$link = mysql_connect("vergil.u.washington.edu:32479", "root", "cello000001")
		or die ("Couldn't connect");
		$db = "draw_something";
		mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
		$rows = mysql_query("SELECT user FROM users ORDER BY user");
		$gamerows = mysql_query("SELECT names FROM drawings WHERE names LIKE '%,$user' OR names LIKE '$user,%'");
?>


<!DOCTYPE html>
<html>
	<head>
		<link href="index.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="leftcontent">
			<div>
				<h1>draw_something</h1>
			</div>
		</div>
		<h2>Click on a user's name to start a game with them. <a href="index.php">[Back]</a></h2>
		<ul>
		<?php for($i = 0; $i < mysql_num_rows($rows); $i++) { 
			$newuser = true;
			for($j = 0; $j < mysql_num_rows($gamerows); $j++) {
				$names = explode(",", mysql_result($gamerows, $j));
				if($names[0] == mysql_result($rows, $i) || $names[1] == mysql_result($rows, $i)) {
					$newuser = false;
				}
			}
			if($newuser && mysql_result($rows, $i) != $user) { ?>
				<a href="postpeep.php?user=<?=mysql_result($rows, $i)?>"><li><?=mysql_result($rows, $i)?></li></a>
		<?php }
		} ?> 
		</ul>
		
	</body>
</html>