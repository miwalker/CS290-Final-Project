var httpRequest;
var signUpBool = "1";
var signInBool = "1";

// log in success, redirect user to user content page and send username as post variable
// following example at http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
function logInSuccess(userName) {
	var method = "post"; // Set method to post by default if not specified.
	var path = 'http://web.engr.oregonstate.edu/~walkermi/userscreen.php';
    var form = document.createElement("form");
    var hiddenField = document.createElement("input");
    var hiddenField2 = document.createElement("input");
    var one = "1";
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "loggedIn");
    hiddenField.setAttribute("value", one);
    form.appendChild(hiddenField);

    hiddenField2.setAttribute("type", "hidden");
    hiddenField2.setAttribute("name", "userNameSession");
    hiddenField2.setAttribute("value", userName);
    form.appendChild(hiddenField2);

    document.body.appendChild(form);
    form.submit();
}



// AJAX call alert function
// followed example outlined here: https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started
function signInAlertContents() {
    if (httpRequest.readyState === 4) {
      	if (httpRequest.status === 200) {
      		if (httpRequest.responseText == "Fail") {
      			alert("There was an error with your E-Mail/Password combination. Please try again.");
      		}
      		else {
      			logInSuccess(httpRequest.responseText);
      		}
      	} else {
        	alert('There was a problem with the request.');
      	}
    }
 }


// AJAX call alert function
// followed example outlined here: https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started
function signUpAlertContents() {
    if (httpRequest.readyState === 4) {
      	if (httpRequest.status === 200) {
      		alert(httpRequest.responseText);
      	} else {
        	alert('There was a problem with the request.');
      	}
    }
 }

// hash user's provided password
function hashPassword(password) {
  var hash = 7;
  var i;
  for (i = 0; i < password.length; i++) {
    tempchar = password.charCodeAt(i);
    hash = hash + tempchar;
  }
  return hash;
}

function SignUpFunc() {
  var usernameText = document.getElementsByName('signUpUsername')[0].value;
  var tempPasswordText = document.getElementsByName('signUpPassword')[0].value;
  var firstnameText = document.getElementsByName('signUpFirstname')[0].value;

  // verifies no fields are left empty
  if (usernameText==="" || passwordText==="" || firstnameText==="") {
  	alert("No fields can be left empty. Please try again.");
  	return false;
	}

  if (usernameText.length > 15 || tempPasswordText.length > 15 || firstnameText.length > 15) {
    alert("All fields must be less than 15 characters. Please try again.");
    return false;
  }

  var passwordText = hashPassword(tempPasswordText);

	// AJAX call
	// followed example outlined here: https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      httpRequest = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
      try {
        httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
      } 
      catch (e) {
        try {
          httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } 
        catch (e) {}
      }
    }
    if (!httpRequest) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
    }

    httpRequest.onreadystatechange = signUpAlertContents;
    httpRequest.open('POST', 'http://web.engr.oregonstate.edu/~walkermi/verify.php');
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('signUp=' + encodeURIComponent(signUpBool)+'&signUpUsername=' + encodeURIComponent(usernameText)+'&signUpPassword=' + encodeURIComponent(passwordText)+'&signUpFirstname=' + encodeURIComponent(firstnameText));

  	return false;
}


function SignInFunc() {
  	var usernameText = document.getElementsByName('signInUsername')[0].value;
  	var tempPasswordText = document.getElementsByName('signInPassword')[0].value;
  	// verifies no fields are left empty
  	if (usernameText==="" || passwordText==="") {
  		alert("No fields can be left empty. Please try again.");
  		return false;
	}

  var passwordText = hashPassword(tempPasswordText);

	// AJAX call
	// followed example outlined here: https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      httpRequest = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
      try {
        httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
      } 
      catch (e) {
        try {
          httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } 
        catch (e) {}
      }
    }
    if (!httpRequest) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
    }

    httpRequest.onreadystatechange = signInAlertContents;
    httpRequest.open('POST', 'http://web.engr.oregonstate.edu/~walkermi/verify.php');
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('signIn=' + encodeURIComponent(signInBool)+'&signInUsername=' + encodeURIComponent(usernameText)+'&signInPassword=' + encodeURIComponent(passwordText));

    return false;
}



window.onload = function() {
  	document.getElementById('SignUpFormButton').onclick = SignUpFunc;
  	document.getElementById('SignInFormButton').onclick = SignInFunc;
};