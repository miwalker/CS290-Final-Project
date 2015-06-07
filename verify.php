<?php

// adds new user info to database
function funcSignUpAdd($mysqli, $username, $password, $firstname) {
	// prepare sql statement
	if (!($stmt = $mysqli->prepare("INSERT INTO Accounts (`Username`, `Password`, `FirstName`) VALUES (?, ?, ?);"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// bind statement parameters
	if (!$stmt->bind_param("sss", $username, $password, $firstname)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return;
		}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}
}

// checks if username already exists
function funcSignUp($mysqli, $username, $password, $firstname) {

	// prepare sql statement
	if (!($stmt = $mysqli->prepare("SELECT `Username` FROM Accounts WHERE `Username` = ?;"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// bind statement parameters
	if (!$stmt->bind_param("s", $username)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return;
		}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// declare variables
	$out_Username = NULL;

	// bind statement results to variables
	if (!$stmt->bind_result($out_Username)) {
    	echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    	return;
	}

	// if username already taken
	if ($stmt->fetch()) {
		// return failed message
		echo 'Username already taken. Please select a different username';
		return;
	}

	// else return success message and create account
	echo 'Account Created! Please sign in with your new credentials.';
	funcSignUpAdd($mysqli, $username, $password, $firstname);
	return;
	
}



function funcSignIn($mysqli, $username, $password) {

	// prepare sql statement
	if (!($stmt = $mysqli->prepare("SELECT `Username`, `Password` FROM Accounts WHERE `Username` = ? AND `Password` = ?;"))) {
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// bind statement parameters
	if (!$stmt->bind_param("ss", $username, $password)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return;
		}

	// execute statement
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	return;
	}

	// declare variables
	$out_Username = NULL;
	$out_Password = NULL;

	// bind statement results to variables
	if (!$stmt->bind_result($out_Username, $out_Password)) {
    	echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    	return;
	}

	// if username and password match
	if ($stmt->fetch()) {
		// return success message
		echo "$out_Username";
		return;
	}

	// else return fail message
	echo 'Fail';
	return;
}







// *** BEG OF MAIN ***

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "walkermi-db", "f87vujfeRS7iLV7o", "walkermi-db");
if (!$mysqli || $mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if ($_POST["signUp"] == "1") {
	funcSignUp($mysqli, $_POST["signUpUsername"], $_POST["signUpPassword"], $_POST["signUpFirstname"]);
}

elseif ($_POST["signIn"] == "1") {
	funcSignIn($mysqli, $_POST["signInUsername"], $_POST["signInPassword"]);
}

// *** END OF MAIN ***

?>