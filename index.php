<?php
	session_start();
	$link = mysql_connect("/*ADD YOUR DATABASE LOGIN INFO HERE/*"))
				or die ("Couldn't connect");
				$db = "draw_something";
				mysql_select_db($db) or die("Could not select the database '" . $db . "'.  Are you sure it exists?");
	if(!isset($_SESSION["loggedin"])) {
		header("Location: index-login.php");
	}
	$user = $_SESSION["user"];
	$coins = mysql_query("SELECT points FROM users WHERE user='$user'");
	$coins = mysql_result($coins, 0);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>draw_something</title>
		<script src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js" type="text/javascript"></script>
		<link href="index.css" type="text/css" rel="stylesheet" />
		<script src="index.js" type="text/javascript"></script>
		<script src="draw.js" type="text/javascript"></script>
		
		<link rel="shortcut icon" type="image/x-icon" href="http://students.washington.edu/kershm/draw/pics/favicon.ico">
	</head>
	<body>
		
		<canvas id="drawspace" width="600" height="500">
			Your browser does not support the canvas element.
		</canvas>
		<div id="color">
			<h2>Colors</h2>
			<label><input id="white" type="radio" name="color" /><em>eraser</em></br></label>
			<label><input id="black" type="radio" name="color" checked="checked"/><img src="pics/black.JPG" alt="black" /></br></label>
			<?php if($coins >= 80) { ?>
				<label><input id="grey" type="radio" name="color"/><img src="pics/grey.JPG" alt="grey" /></br></label>
			<?php } ?>
			<?php if($coins >= 120) { ?>
				<label><input id="palegoldenrod" type="radio" name="color"/><img src="pics/palegoldenrod.JPG" alt="tan" /></br></label>
			<?php }
			if($coins >= 40) { ?>
				<label><input id="saddlebrown" type="radio" name="color"/><img src="pics/saddlebrown.JPG" alt="brown" /></br></label>
			<?php } ?>
			<label><input id="red" type="radio" name="color"/><img src="pics/red.JPG" alt="red" /></br></label>
			<?php if($coins >= 100) { ?>
				<label><input id="orange" type="radio" name="color"/><img src="pics/orange.JPG" alt="orange" /></br></label>
			<?php } ?>
			<label><input id="yellow" type="radio" name="color" /><img src="pics/yellow.JPG" alt="yellow" /></br></label>
			<?php if($coins >= 160) { ?>
			<label><input id="lightgreen" type="radio" name="color" /><img src="pics/lightgreen.JPG" alt="lightgreen" /></br></label>
			<?php } 
			if($coins >= 20) { ?>
				<label><input id="green" type="radio" name="color" /><img src="pics/green.JPG" alt="green" /></br></label>
			<?php }
			if($coins >= 180) { ?>
				<label><input id="lightblue" type="radio" name="color" /><img src="pics/lightblue.JPG" alt="lightblue" /></br></label>
			<?php } ?>	
			<label><input id="blue" type="radio" name="color" /><img src="pics/blue.JPG" alt="blue" /></br></label>
			<?php if($coins >= 60) { ?>
				<label><input id="purple" type="radio" name="color" /><img src="pics/purple.JPG" alt="purple" /></br></label>
			<?php }
			if($coins >= 140) { ?>
			<label><input id="pink" type="radio" name="color"/><img src="pics/pink.JPG" alt="pink" /></br></label>
			<?php } ?>	
			<button id="background">Set Background</button><br/>
			<button id="clear">Clear Screen</button>
		</div>
		
		<div id="size">
			<h2>Thickness</h2>
			<label><input id="5" type="radio" name="size" checked="checked"/><img class="thick" src="pics/thin.JPG" alt="thin" /></br></label>
			<label><input id="10" type="radio" name="size"/><img class="thick" src="pics/thick.JPG" alt="thick" /></br></label>
			<label><input id="15" type="radio" name="size"/><img class="thick" src="pics/thicker.JPG" alt="thicker" /></br></label>
			<label><input id="50" type="radio" name="size"/><img class="thick" src="pics/thickest.JPG" alt="thickest" /></label>
		</div>
		
		<div id="leftcontent">
			<div>
				<h1>draw_something</h1>
				<p>Welcome, <span id="currentuser"><?= $user ?></span></p>
				<p><img class="thick" src="pics/coin.jpg" alt="coins:" /> <span id="coins"><?= $coins ?></span></p>
				<p><a href="findpeeps.php">Find Users</a></p>
				<p><a href="logout.php">Log Out</a></p>
			</div>
			<div>
				<h3 id="highlight">Current Games:</h3>
				<div id="currentgames">
				<?php 
				$rows = mysql_query("SELECT names, word, guessed, guessedlast, wordlast, time, matches FROM drawings WHERE names LIKE '$user%'");
				
				for($i = 0; $i < mysql_num_rows($rows); $i++) { 
					$names = explode(",", mysql_result($rows, $i, "names"));
					if($names[0] == $user) { ?>
						<div id="<?="div" . $names[1]?>" class="user">
							<p><?=$names[1]?> - turn <span id="<?="matches" . $names[1]?>"><?= mysql_result($rows, $i, "matches") ?></span></p>
							<?php 
							if(mysql_result($rows, $i, "guessed") == "f") { 
								if(mysql_result($rows, $i, "guessedlast") == "c") { ?>
									<p class="correct <?="correct" . $names[1]?>">Guessed <?= mysql_result($rows, $i, "wordlast")?> correct in</p>
									<?php
										if(mysql_result($rows, $i, "time") >= 60) { ?>
											<p class="correct <?="correct" . $names[1]?>"><?= (int)(mysql_result($rows, $i, "time") / 60)?> minutes and </p>
									<?php } ?>
									<p class="correct <?="correct" . $names[1]?>"><?= mysql_result($rows, $i, "time") % 60?> seconds</p>
								<?php } elseif(mysql_result($rows, $i, "guessedlast") == "w") { ?>
									<p class="incorrect <?="incorrect" . $names[1]?>">Passed '<?= mysql_result($rows, $i, "wordlast")?>' after</p>
									<?php
										if(mysql_result($rows, $i, "time") >= 60) { ?>
											<p class="incorrect <?="incorrect" . $names[1]?>"><?=(int)(mysql_result($rows, $i, "time") / 60)?> minutes and </p>
									<?php } ?>
									<p class="incorrect <?="incorrect" . $names[1]?>"><?= mysql_result($rows, $i, "time") % 60?> seconds</p>
								<?php } ?>
								
								<button id="<?="play" . $names[1]?>">Play</button>
							<?php }
							if(mysql_result($rows, $i, "guessed") != "f") { 
								$words = mysql_query("SELECT words FROM words");
								$numwords = mysql_num_rows($words) - 1;
								$one = rand(0, $numwords);
								$two = rand(0, $numwords);
								while($one == $two) {
									$two = rand(0, $numwords);
								}
								$three = rand(0, $numwords);
								while($two == $three || $one == $three) {
									$three = rand(0, $numwords);
								}
								
								?>
								<!--display "c(orrect)" or "w(rong)" here -->
								<!--also put new list of words here-->
								<select id="<?="words" . $names[1]?>">
									<option selected="selected">choose a word</option>
									<option><?= mysql_result($words, $one) ?></option>
									<option><?= mysql_result($words, $two) ?></option>
									<option><?= mysql_result($words, $three) ?></option>
								</select>
								<button id="<?="send" . $names[1]?>">Send</button>
							<?php } ?>
						</div>
					<?php }
				} 
				
				?> </div>
				<h3>Waiting on: </h3> <?php
				$rows = mysql_query("SELECT names, matches FROM drawings WHERE names LIKE '%,$user'");
				for($i = 0; $i < mysql_num_rows($rows); $i++) { 
					$names = explode(",", mysql_result($rows, $i, "names"));
					if($names[1] == $_SESSION["user"]) { ?>
						<p class="user deleteparent" id="<?="dpar" . $names[0]?>"><?=$names[0]?> - turn <?=mysql_result($rows, $i, "matches")?><button id="<?="dbut" . $names[0]?>" style="display: none">Delete</button></p>
					<?php }
				} ?>
			</div>
		</div>
	</body>

</html>