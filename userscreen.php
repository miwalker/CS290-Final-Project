<?php

include 'password1.php';


function funcClearDestination($mysqli, $clearUsername) {

	// prepare sql statement
	if (!($stmt = $mysqli->prepare("DELETE FROM Destination WHERE Username = ?;"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// bind statement parameters
	if (!$stmt->bind_param("s", $clearUsername)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return;
		}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	return;
}




function funcInsertDestination($mysqli, $insertLocation, $insertCountry, $insertDays, $publicBool, $insertUsername) {

	if ($insertDays == "0") {
		echo '<br><p><font color="red">Days to Stay must be greater than 0</font></p>';
		return;
	}

	if ($insertLocation == "") {
		echo '<br><p><font color="red">Location field cannot be empty</font></p>';
		return;
	}

	if ($insertCountry == "") {
		echo '<br><p><font color="red">Country field cannot be empty</font></p>';
		return;
	}

	// convert string value to numerical
	if ($publicBool == "1") {
		$tempBool = 1;
	}
	else {
		$tempBool = 0;
	}

	// prepare sql statement
	if (!($stmt = $mysqli->prepare("INSERT INTO Destination (`Username`, `Public`, `Location`, `Country`, `Days_to_Stay`) VALUES (?, ?, ?, ?, ?);"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// bind statement parameters
	if (!$stmt->bind_param("sissi", $insertUsername, $tempBool, $insertLocation, $insertCountry, $insertDays)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return;
		}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	return;
}


function funcDisplayPrivateDestination($mysqli, $displayUsername) {

	echo '	<br>
			<h3>Your Private Destination Bucket List</h3>
			<table>
				<tr>
					<th>Location</th>
					<th>Country</th>
					<th>Days to Stay</th>
					<th>Private</th>
				</tr>';

	// prepare sql statement
	if (!($stmt = $mysqli->prepare("SELECT Location, Country, Days_to_Stay, Public FROM Destination WHERE Username = ?;"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// bind statement parameters
	if (!$stmt->bind_param("s", $displayUsername)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return;
		}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// declare variables
	$out_Location = NULL;
	$out_Country = NULL;
	$out_DaysToStay = NULL;
	$out_Public = NULL;

	// bind statement results to variables
	if (!$stmt->bind_result($out_Location, $out_Country, $out_DaysToStay, $out_Public)) {
    	echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    	return;
	}

	// loop through statement results and fill table with rows and data
	while ($stmt->fetch()) {
		$pubBool;
		if ($out_Public == 1) {
			$pubBool = "No";
		}
		else {
			$pubBool = "Yes";
		}
		echo  " <tr>
					<td>$out_Location</td>
					<td>$out_Country</td>
					<td>$out_DaysToStay</td>
					<td>$pubBool</td>
				</tr>";
	}

	// end table element
	echo '	</table>';

	return;
}


function funcDisplayPublicDestination($mysqli) {

	echo '	<br>
			<br>
			<h3 id="publicTable">Public Destination Bucket List</h3>
			<table id="publicTable">
				<tr>
					<th>Username</th>
					<th>Location</th>
					<th>Country</th>
					<th>Days to Stay</th>
				</tr>';

	// prepare sql statement
	if (!($stmt = $mysqli->prepare("SELECT Username, Location, Country, Days_to_Stay FROM Destination WHERE `Public` = 1;"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// declare variables
	$out_Username = NULL;
	$out_Location = NULL;
	$out_Country = NULL;
	$out_DaysToStay = NULL;
	

	// bind statement results to variables
	if (!$stmt->bind_result($out_Username, $out_Location, $out_Country, $out_DaysToStay)) {
    	echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    	return;
	}

	// loop through statement results and fill table with rows and data
	while ($stmt->fetch()) {
		echo  " <tr>
					<td>$out_Username</td>
					<td>$out_Location</td>
					<td>$out_Country</td>
					<td>$out_DaysToStay</td>
				</tr>";
	}

	// end table element
	echo '	</table>';

	return;
}

// create html element
echo '
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    	<title>CS290 Final Project Login</title>
		<link rel="stylesheet" href="finalstyle.css">
  </head>

  <body>
 ';

// start session
session_start();

// if user clicked logout, ends session, redirects to login screen
if(isset($_GET['action']) && $_GET['action'] == 'end') {
		echo "<p>You are now logged out.</p>";
		echo '<p>Click <a href="http://web.engr.oregonstate.edu/~walkermi/finallogin.php">here</a> to access the login screen.</p>';
		session_unset();
		session_destroy();
		die();
	}

// if user tried to access page without previously logging in
if((!isset($_SESSION['username'])) && !($_POST['loggedIn'] === "1")) {
	echo '<p>You must login first. Click <a href="http://web.engr.oregonstate.edu/~walkermi/finallogin.php">here</a> to access the login screen.</p>';
}

// if logged in
elseif (session_status() == PHP_SESSION_ACTIVE) {
	// if user just logged in set session variable username
	if ($_POST["loggedIn"] === "1") {
		$_SESSION['username'] = $_POST['userNameSession'];
	}
	$username = $_SESSION['username'];
	
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "walkermi-db", $password, "walkermi-db");
	if (!$mysqli || $mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	// display unique page greeting to user and add div spacer
	echo '
	<div id="greet"><h1> Hello ';
	echo "$username";
	echo '!</h1></div>

	<div id="spacer"></div>';

	echo "<br><img src=\"uploads/$username.jpg\" width=\"150\" height=\"150\"><br><br>";


	// form to add a profile picture
	// based off example at http://www.w3schools.com/php/php_file_upload.asp
	echo '
	<form action="userscreen.php" method="post" enctype="multipart/form-data">
    Upload profile picture (jpg only):<br>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
    <input type="hidden" name="MAX_FILE_SIZE" value="30000">
	</form>';
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
   		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    	if($check !== false) {
        	$uploadOk = 1;
    	} 
    	else {
        	echo "File is not an image.";
       		$uploadOk = 0;
    	}
	}	
	// Check file size
	if (isset($_POST["submit"]) && $_FILES["fileToUpload"]["size"] > 500000) {
    	echo "Sorry, your file is too large.";
    	$uploadOk = 0;
	}
	// Allow certain file formats
	if(isset($_POST["submit"]) && $imageFileType != "jpg") {
    	echo "Sorry, only JPG files are allowed.";
    	$uploadOk = 0;
	}	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
   		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} elseif(isset($_POST["submit"])) {
    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "uploads/$username.jpg")) {
        	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    	} else {
        	echo "Sorry, there was an error uploading your file.";
    	}
	}

	// clears users list
	if ($_POST["clearDestination"] == "1") {
		funcClearDestination($mysqli, $username);
	}

	// display site title
	echo '<h2>Vacation Destination Bucket List';

	// from to add to bucket list
	echo '
	<br>
	<h3>Add to Your Vacation Destination Bucket List</h3>
 			<form action="userscreen.php" method="post">
 				<p>Location <input type="text" name="insertLocation" maxlength="15"></p>
 				<p>Country <input type="text" name="insertCountry" maxlength="15"></p>
 				<p>Days to Stay <input type="number" name="insertDays" max="100"></p>
 				<input type="hidden" name="publicBool" value="0">
 				<p><input type="checkbox" name="publicBool" value="1">Add to Public Bucket List</p>
 				<input type="submit" value="Add to Destination Bucket List!">
 				<input type="hidden" name="insertDestination" value="1">
			</form>';

	if ($_POST["insertDestination"] == "1") {
		funcInsertDestination($mysqli, $_POST["insertLocation"], $_POST["insertCountry"], $_POST["insertDays"], $_POST["publicBool"], $username);
	}

	// displays private list
	funcDisplayPrivateDestination($mysqli, $username);

	// form for user to clear private list
	echo '
	<h5>Clear Your Destination Bucket List</h5>
 			<form action="userscreen.php" method="post">
 				<input type="submit" value="Clear List">
 				<input type="hidden" name="clearDestination" value="1">
			</form>';

	// displays public list
	funcDisplayPublicDestination($mysqli);
}

else {
	echo 'PHP Session could not be established';
}

// display link to logout
echo '<br><br>
	<div id="foot"><h3>Click <a href="http://web.engr.oregonstate.edu/~walkermi/userscreen.php?action=end">here</a> to logout</h3></div>
  </body>
</html>
';



?>