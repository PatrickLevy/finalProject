<?php
session_start();

//********************************************************
//Include private information about database
//********************************************************
include 'storedInfo.php';


//***********************************************************
//Check for request to logout out the user and redirect
//***********************************************************
if ($_GET) {
	foreach($_GET as $key => $value){
		if ($key == 'action' && $value == 'logout'){
				session_destroy();
				$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
				$filePath = implode('/',$filePath);
				$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
				header("Location: {$redirect}/login.php", true);
			}
		}
	}
//**********************************************************
//Check for login cases
//**********************************************************
if ($_POST){
	
	//Check for empty inputs
	foreach($_POST as $Key => $value) {
		if (empty($value)){
			echo "Please enter a username and password.  Click ";
			echo "<a href=\"login.php\">here</a>";
			echo " to return to the login screen.";
			$_SESSION['validLogin'] = false;
			session_destroy();
			break;
		}
	}
	//*****************************************************************************
	//Existing User
	//*****************************************************************************
	//An existing user is attempting to login and there are no others users logged in
	if (empty($_SESSION['username'])) {
		foreach($_POST as $key => $value){
			if ($key == 'username' && !empty($value)) {
				$username = $value;
				//echo "a username was entered";
					
			}
			if ($key == 'password' && !empty($value)) {
				$password = $value;
				//echo "a passoword was entered";
					
			}
			
			if (!empty($username) && !empty($password)){
				$correctLogin = true;
				$_SESSION = array();
				$_SESSION['username'] = $username;
				$_SESSION['visits'] = 0;
				//$_SESSION['validLogin'] = true;
				$_SESSION['newUser'] = false;
			}
		}
	}
	//An existing user is logged in but it is not the current user
	else if (!empty($_SESSION['username']) ) {
		foreach($_POST as $key => $value) {
			if ($key == 'username' && !empty($value)) {
				$username = $value;
				//echo "a username was entered";
					
			}
			if ($key == 'password' && !empty($value)) {
				$password = $value;
				//echo "a password was entered";
					
			}
			
			if (!empty($username) && !empty($password)){
				$correctLogin = true;
				$_SESSION = array();
				$_SESSION['username'] = $username;
				$_SESSION['visits'] = 0;
				//$_SESSION['validLogin'] = true;
			}

			if ($_SESSION['username'] != $username){
				session_destroy();
				session_start();
				//echo "destroying old session<br>";
				$_SESSION['username'] = $username;
				$_SESSION['visits'] = 0;
				//$_SESSION['validLogin'] = true;
				$_SESSION['newUser'] = false;
			}
		}
	}
	//*****************************************************************************
	//New User - a new user is attempting to register
	//*****************************************************************************
	if (empty($_SESSION['newUsername']) ) {
		foreach($_POST as $key => $value) {
			if ($key == 'newUsername' && !empty($value)) {
				$username = $value;
				//echo "a username was entered";
					
			}
			if ($key == 'newPassword' && !empty($value)) {
				$password = $value;
				//echo "a password was entered";
					
			}
			if ($key == 'newRole' && !empty($value)) {
				$role = $value;
				//echo "a password was entered";
					
			}
			
			if (!empty($username) && !empty($password) && !empty($role)) {
				$correctLogin = true;
				$_SESSION = array();
				$_SESSION['username'] = $username;
				$_SESSION['visits'] = 0;
				//$_SESSION['validLogin'] = true;
				$_SESSION['role'] = $role;
				$_SESSION['newUser'] = true;
			}

			if ($_SESSION['username'] != $username){
				session_destroy();
				session_start();
				//echo "destroying old session<br>";
				$_SESSION['username'] = $username;
				$_SESSION['visits'] = 0;
				//$_SESSION['validLogin'] = true;
				$_SESSION['role'] = $role;
				$_SESSION['newUser'] = true;
			}
		}
	}
}



//**********************************************************
//Connect to user database
//**********************************************************
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "levyp-db", $myDbPassword, "levyp-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {
	//echo "connected!<br>";
}
//**********************************************************
//Check if user database exists and if not create it
//**********************************************************
if (!$mysqli->query("DROP TABLE IF EXISTS test") ||
    !$mysqli->query("CREATE TABLE testCenterUserDb(id INT PRIMARY KEY AUTO_INCREMENT, username VARCHAR(255) UNIQUE,
    				 password VARCHAR(255), role VARCHAR(255)    )")  ) {   
	//echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

//***********************************************************************
//An existing user is logging in, check password in database and set role
//***********************************************************************
if ($_SESSION["newUser"] != true){
  //Search users
  $users = $mysqli->query("SELECT username, password, role FROM testCenterUserDb");

  //Check found users for matching users and passwords
  while ($currentUser = $users->fetch_assoc()){
  	  if ($currentUser["username"] == $username){
	 	  //echo "user found!<br>";
		  if ($currentUser["password"] == $password){
			  //echo "password match!<br>";
			  $_SESSION['validLogin'] = true;
			  $_SESSION['role'] = $currentUser["role"];
			  break;
		  }
		  else {
			  //echo "Username and passwords do not match!<br>";
			  $_SESSION['validLogin'] = false;
		  }
	  }
	  else {
		  //echo "Username not found.<br>";
		  $_SESSION['validLogin'] = false;
	  }

  } 

}


//*****************************************************************
//Add new users to the database
//*****************************************************************
else if ($_SESSION["newUser"] == true){

	//Search users
    $users = $mysqli->query("SELECT username, password, role FROM testCenterUserDb");

    //Check if the username has already been used:
    while ($currentUser = $users->fetch_assoc()){
  	  if ($currentUser["username"] == $username){
	 	//echo "username already used!<br>";
		$_SESSION['validLogin'] = false;
		break;
		}
	  else {
        $_SESSION['validLogin'] = true;
	  }
	  
    } 

	

	if ($_SESSION['validLogin'] == true && $_SESSION['newUser'] == true && ($username != null || $password != null)) {
		//echo "adding new user";
		if (!($stmt = $mysqli->prepare("INSERT INTO testCenterUserDb(username, password, role) VALUES (?, ?, ?)"))) {
			echo "Prepare failed: (" . $mysqli->errno .") " . $mysqli->error;	
		}
		if (!($stmt->bind_param("sss", $username, $password, $_SESSION['role']))) {
			echo "Binding Parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!($stmt->execute())) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
}

//***************************************************************
//Display content to logged in user
//***************************************************************

//Redirect user to proper site
if ($_SESSION['validLogin'] == true && $_SESSION['role'] == 'Teacher'){
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
 	    $filePath = implode('/',$filePath);
 	    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
 	 	header("Location: {$redirect}/teacher.html", true);
 	 	die();
}

else if ($_SESSION['validLogin'] == true && $_SESSION['role'] == 'Student'){
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
 	    $filePath = implode('/',$filePath);
 	    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
 	 	header("Location: {$redirect}/student.html", true);
 	 	die();
}



//***********************************************************************
//Redirect users who are not logged in properly to the login page
//***********************************************************************
else if(session_status() == PHP_SESSION_ACTIVE) {
		$_SESSION = array();
		session_destroy();
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
		$filePath = implode('/',$filePath);
		$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
		header("Location: {$redirect}/login.php", true);
		die();
}

echo '</body>
</html>';
?>
