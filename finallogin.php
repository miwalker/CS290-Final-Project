<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    	<title>CS290 Final Project Login</title>
		<link rel="stylesheet" href="finalstyle.css">
  </head>

  <body>
  	<div id="greet"><h1>Destination Bucket List</h1><br></div>
  	<div id="spacer"></div>
  	<h3>Sign Up</h3>
  	<form action="verify.php" method="post" id="SignUpForm">
 			<p>Username <input type="text" name="signUpUsername"></p>
 			<p>Password <input type="password" name="signUpPassword"></p>
 			<p>First Name <input type="text" name="signUpFirstname"></p>
		</form>
		<button type="submit" id="SignUpFormButton"form="SignUpForm" value="Submit">Sign Up!</button>
		<br>
		<br>
		<br>
		<h3>Sign In</h3>
		<form action="verify.php" method="post" id="SignInForm">
 			<p>Username <input type="text" name="signInUsername"></p>
 			<p>Password <input type="password" name="signInPassword"></p>
		</form>
		<button type="submit" id="SignInFormButton"form="SignInForm" value="Submit">Sign In</button><br><br>
		<div id="foot"></div>
  </body>
  <script src="finalscript.js"></script>
</html>


<?php

?>