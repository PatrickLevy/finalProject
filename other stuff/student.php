<?php
session_start();
include 'storedInfo.php';
// echo '<!-- Latest compiled and minified CSS -->';
// echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">';

//*******************************************************************************
//Display content to users with a valid login and session status as a student
//*******************************************************************************
if(session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION['validLogin']) && $_SESSION['role'] == 'Student') {
	echo "<h1>Testing Center - Student</h1>";
	echo "Welcome, " . $_SESSION['username'] . "!<br><br>";
	echo "Click <a href=\"home.php?action=logout\">here</a>";
	echo " to logout.<br><br>";
	// echo "You are currently located on the student page.<br><br>";
	// echo "Click ";
	// echo "<a href=\"home.php\">here</a>";
	// echo " to go back to home.php";

	//Connect to database
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "levyp-db", $myDbPassword, "levyp-db");
	if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	else {
		//echo "connected!<br>";
	}
	//**********************************************************************
	//Get Tests from Database
	//********************************************************************
	if (($_GET[filter] != null) && ($_GET[filter] != 'All')) { 
		$filterBy = $_GET[filter];
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb 
		                         WHERE category = '".$filterBy."' ");
	}
	else {
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb");
	}

	//********************************************************************
	//Display current tests in database
	//*********************************************************************
	echo '<h2>Current Tests in Database</h2>';

	//Print header row
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>' . 'Test Name' . '</th>';
	echo '<th>' . 'Teacher' . '</th>';
	echo '<th>' . 'Take Test' . '</th>';
	echo '</tr>';
	
	

	//Print tests in database

	while ($row = $allTests->fetch_assoc()) {
		$id = $row["id"];
		echo '<tr>';
		echo '<td>' . $row["testName"] . '</td>';
		echo '<td>' . $row["user"] . '</td>';
		
		echo '<td>' . 
			 '<form action = "" method="get">' .
			   "<button name='takeTest' value='".$id."'>Take Test</button>" .
			 '</form>' . 
			 '</td>';

		echo '</tr>';
	}
	echo '</table>';


	/********************************************************************
	//GET and POST Data for update of database
	********************************************************************/
	class data {
		function __construct($type){
			$this->Type = $type;
		}
		public $Type;
		public $parameters = array();

	}
	//GET
	$getData = new data('GET');

	if ($_GET){
		foreach($_GET as $key => $value){
			$getData->parameters[$key] = $value;
		}
	}
	else {
		$getData->parameters = null;
	}

	//POST
	$postData = new data('POST');

	if ($_POST){
		foreach($_POST as $key => $value){
			$postData->parameters[$key] = $value;
		}
	}
	else {
		$postData->parameters = null;
	}

	//**************************************************************************
	//Allow user to submit answers for a test
	//**************************************************************************

	//Show questions for selected test	
		echo '<p>';
		echo '<h4>Please answer the following questions:</h4>';
		
		if($_GET[takeTest] != null) {

			//Get the number of questions
			$questions = $mysqli->query("SELECT * FROM testCenterTestsDb 
		    	                     WHERE id = $_GET[takeTest]");

			while ($question = $questions->fetch_assoc()) {
				$numberOfQuestions = $question[numberOfQuestions];
			}
		
			//Get the questions and responses
			$questions = $mysqli->query("SELECT * FROM testCenterTestsDb 
		    	                     WHERE id = $_GET[takeTest]");

			while ($question = $questions->fetch_assoc() ) {
			
				for ($questionNumber = 1; $questionNumber <=$numberOfQuestions; $questionNumber++){
				
					//Initialize indices
					$questionKey = 'Q' . $questionNumber;
					$responseA = 'R' . $questionNumber . 'A';
					$responseB = 'R' . $questionNumber . 'B';
					$responseC = 'R' . $questionNumber . 'C';
					$responseD = 'R' . $questionNumber . 'D';
					
					echo '<ul>';
					echo '<li>';
					echo "Question " . $questionNumber . ": " . "$question[$questionKey]";
					echo '</li>';
					echo '<li>';
					echo "Answer A: " . "$question[$responseA]";
					echo '</li>';
					echo '<li>';
					echo "Answer B: " . "$question[$responseB]";
					echo '</li>';
					echo '<li>';
					echo "Answer C: " . "$question[$responseC]";
					echo '</li>';
					echo '<li>';
					echo "Answer D: " . "$question[$responseD]";
					echo '</li>';
					echo '</ul>';
				}
				
			}
		}



}//end of logged in user section

//*******************************************************************************
//Redirect users without proper login
//*******************************************************************************
else{
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