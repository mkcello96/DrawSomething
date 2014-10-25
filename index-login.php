<?php
	session_start();
	if(isset($_SESSION["loggedin"])) {
		header("Location: index.php");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>draw_something</title>
		<link href="index.css" type="text/css" rel="stylesheet" />
		<link rel="shortcut icon" type="image/x-icon" href="http://students.washington.edu/kershm/pics/favicon.ico">
	</head>
	<body>
		
		<div id="leftcontent">
			<div>
				<h1>draw_something</h1>
			</div>
		</div>
		<div id="login">
		<?php
			$error = "";
			if(isset($_GET["notfound"])) {
				$error = "The user specified was not found.";
			} elseif(isset($_GET["incorrectpass"])) {
				$error = "The password for that user was incorrect.";
			} elseif(isset($_GET["nonmatch"])) {
				$error = "The passwords do not match";
			} elseif(isset($_GET["length"])) {
				$error = "Passwords and usernames must be between 1 and 12 characters long";
			} elseif(isset($_GET["duplicate"])) {
				$error = "That username has already been chosen.";
			} elseif(isset($_GET["badchar"])) {
				$error = "Usernames can only contain letters and numbers.";
			}
			if ($error != "") { ?>
				<p class="error"><?= $error ?></p>
		<?php } ?>
			<p>Returning User</p>
			<div class="login">
				<form id="loginform" action="login.php" method="post">
					<div><input id="name" name="name" type="text" size="12" autofocus="autofocus" /> <strong>User Name</strong></div>
					<div><input id="password" name="password" type="password" size="12" /> <strong>Password</strong></div>
					<div><input id="submitbutton" type="submit" value="Log in" /></div>
				</form>
			</div>
			<p>New User</p>
			<div class="login">
				<form id="loginform" action="new-login.php" method="post">
					<div><input id="name" name="name" type="text" size="12" /> <strong>User Name</strong></div>
					<div><input id="password" name="password" type="password" size="12" /> <strong>Password</strong></div>
					<div><input id="repassword" name="repassword" type="password" size="12" /> <strong>Re-enter Password</strong></div>
					<div><input id="submitbutton" type="submit" value="Log in" /></div>
				</form>
			</div>
		</div>
	</body>

</html>