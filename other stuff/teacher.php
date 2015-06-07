<?php
session_start();
include 'storedInfo.php';
// echo '<!-- Latest compiled and minified CSS -->';
// echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">';

//*******************************************************************************
//Display content to users with a valid login
//*******************************************************************************
if(session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION['validLogin'])&& $_SESSION['role'] == 'Teacher') {
	//Welcome user
	echo "<h1>Testing Center - Teacher</h1>";
	echo "Welcome, " . $_SESSION['username'] . "!<br><br>";
	echo "Click <a href=\"home.php?action=logout\">here</a>";
	echo " to logout.<br><br>";
	
	//Connect to database
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "levyp-db", $myDbPassword, "levyp-db");
	if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	else {
		//echo "connected!<br>";
	}
	//**********************************************************
	//Check if database exists and if not create it
	//**********************************************************
	if (!$mysqli->query("DROP TABLE IF EXISTS test") ||
    	!$mysqli->query("CREATE TABLE testCenterTestsDb(id INT PRIMARY KEY AUTO_INCREMENT, testName VARCHAR(255) UNIQUE,
    				 user VARCHAR(255), type VARCHAR(255), numberOfQuestions INT)")  ) {   
		//echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
	
	//insert 10 columns for questions
		for ($j = 1; $j <=10; $j++){
			$newQ = "Q".$j;
			$newRA = "R".$j."A";
			$newRB = "R".$j."B";
			$newRC = "R".$j."C";
			$newRD = "R".$j."D";

			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newQ VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRA VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRB VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRC VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRD VARCHAR(255)");
		}

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

	/**************************************************************************
	//Update database based on POST data
	**************************************************************************/

	//New Test to be added to database
	$errorTestName = array("Please enter a test name as a string of up to 255 characters", false);
	$errorTestUser = array("User input is invalid", false);
	$errorTestType = array("Please enter a test type as either questions, key, or response.", false);


	if ($_POST[newTestName] != null){
		$newTestName = $_POST[newTestName];
		$newTestUser = $_SESSION['username'];
		$newTestType = $_POST[newTestType];
		$newTestNumberQs = 0; // (int)$_POST[newTestNumberQs];
		

		//Check that the test name was properly input
		if (!is_string($newTestName) || strlen($newTestName) > 255 || strlen($newTestName) <= 0){
			$errorTestName[1] = true;
		}

		
		//Add new test to database if no input errors were found
		if ($errorTestName[1] == false && $errorTestUser[1] == false && $errorTestType[1] == false) {
			if (!($stmt = $mysqli->prepare("INSERT INTO testCenterTestsDb(testName, user, type, numberOfQuestions) VALUES (?, ?, ?, ?)"))) {
				echo "Prepare failed: (" . $mysqli->errno .") " . $mysqli->error;	
			}
		if (!($stmt->bind_param("sssi", $_POST[newTestName], $newTestUser, $_POST[newTestType], $newTestNumberQs))) {
			echo "Binding Parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		if (!($stmt->execute())) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		}
	}

	//Delete All Tests
	if ($_POST[delete] == "deleteAllTests"){
		$mysqli->query("DROP TABLE IF EXISTS testCenterTestsDb");
		header ('Location: teacher.php');
	}

	//Delete Test
	if ($_POST[delete] != null){
		$idDelete = $_POST[delete];
		$stmt = $mysqli->prepare("DELETE FROM testCenterTestsDb WHERE id = ?");
		$stmt->bind_param("i", $idDelete);
		$stmt->execute();
	}

	// //Check in/out videos
	// if ($_POST[rentChange] != null){
	// 	$idCheckInOut = $_POST[rentChange];
	// 	//this should be updated to 1)prepare 2)bind 3)execute
	// 	$selectionIsRented = $mysqli->query("SELECT rented FROM testCenterTestsDb WHERE id = '".$idCheckInOut."' ")->fetch_object()->rented;
	// 	if ($selectionIsRented == 0){
	// 		$mysqli->query("UPDATE testCenterTestsDb SET rented = 1 WHERE (id = '".$idCheckInOut."' && rented = 0) ");
	// 	}
	// 	else {
	// 		$mysqli->query("UPDATE testCenterTestsDb SET rented = 0 WHERE (id = '".$idCheckInOut."' && rented = 1) ");
	// 	}
	// }

	//Get Tests from Database
	if (($_GET[filter] != null) && ($_GET[filter] != 'All')) { 
		$filterBy = $_GET[filter];
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb 
		                         WHERE category = '".$filterBy."' ");
	}
	else {
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb");
	}

	/************************************************
	//Read category types from database
	************************************************/
	// $categoryList = array();
	// $categories = $mysqli->query("SELECT DISTINCT category FROM testCenterTestsDb");
	// while ($currentCat = $categories->fetch_assoc()) {
	// 	if ($currentCat["category"] != "" || $currentCat["category"] != null){
	// 		array_push($categoryList, $currentCat["category"]);
	// 	}
	// }

	/***************************************************
	//Display current tests in database
	***************************************************/
	// echo 
	// '<!DOCTYPE html>
	// <html lang="en">
	// <head>
	// <meta charset="utf-8" />
	// <title>Test Center</title>
	// </head>
	// <body>
	// <h1>Testing Center - Teacher</h1>';
	echo '<h2>Current Tests in Database</h2>';

	/****************************************************
	//Dropdown for filtering results
	*****************************************************/

	// echo '<h2>Current Inventory</h2><p>';
	// echo '<table>';
	// echo '<tr>';
	// echo '<td>Filter by category:</td>';
	// echo '<td>'; 
	// echo '<form action="" method="get">';
	// echo '<select name="filter">';
	// echo   '<option value="All">All</option>';
	// foreach ($categoryList as &$type){
	// 	echo '<option value=' . $type . '>'.$type.'</option>';
	// }
	// unset($type); 
		
	// echo  '</select>
	// 	  <td>
	// 	  <input type="submit" value="Submit">
	// 	  </td>
	// 	  </form>';	
	// echo '</table>';

	// echo '<h4>Currently filtering by: ' . $_GET[filter] . '</h4>';

	/*****************************************************
	//Output current database
	//Citation:  http://www.sanwebe.com/2013/03/basic-php-mysqli-usage
	******************************************************/
	//Print header row
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>' . 'ID' . '</th>';
	echo '<th>' . 'Test Name' . '</th>';
	echo '<th>' . 'Created By' . '</th>';
	echo '<th>' . 'Type' . '</th>';
	echo '<th>' . 'Edit Questions' . '</th>';
	echo '<th>' . 'Delete' . '</th>';
	echo '</tr>';
	
	

	//Print items in database

	while ($row = $allTests->fetch_assoc()) {
		$id = $row["id"];
		echo '<tr>';
		echo '<td>' . $id . '</td>';
		echo '<td>' . $row["testName"] . '</td>';
		echo '<td>' . $row["user"] . '</td>';
		echo '<td>' . $row["type"] . '</td>';
		
		echo '<td>' . 
			 '<form action = "" method="post">' .
			   "<button name='editQuestions' value='".$id."'>Edit Questions</button>" .
			 '</form>' . 
			 '</td>';
		
		echo '<td>' . 
			 '<form action = "" method="post">' .
			   "<button name='delete' value='".$id."'>Delete Test</button>" .
			 '</form>' . 
			 '</td>';

		echo '</tr>';
	}
	echo '</table>';
	// echo '</body>
	// 	  </html>';

	/****************************************************
	//Add New Test Interface
	**************************************************/
	echo '<p>';
	echo '<h3>Add New Test</h3><p>';

	//Check for previous input errors
	if ($errorTestName[1] == true){
		echo $errorTestName[0];
		echo '<p>';
	}
	if ($errorTestUser[1] == true){
		echo $errorTestUser[0];
		echo '<p>';
	}
	if ($errorTestType[1] == true){
		echo $errorTestType[0];
		echo '<p>';
	}

	//Display table for inputting new test text information
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>' . 'New Test Name';
	echo '<th>' . 'Test Type';
	echo '</tr>';
	echo '<tr>';
	echo '<form method="post">' .
		  '<td>' . '<input type="text" name="newTestName" placeholder="New Test Name" required>' . '</td>'.
		  //'<td>' . '<input type="text" name="newVidCategory" placeholder="New Video Category">' . '</td>' .
		  '<td>' . '<input type="text" name="newTestType" placeholder="New Test Type">' . '</td>' .
		  '<td>' . '<input type="submit" value="Add New Test">' . '</td>' .
		  '</form>' .
		  '</table>';

	/********************************************************
	//Delete All Tests
	*******************************************************/
	// echo '<p>';
	// echo  '<form action = "" method="post">' .
	// 	    "<button name='delete' value='deleteAllTests'>Delete All Tests</button>" .
	//       '</form>'; 

	//*******************************************************
	//Edit Selected Test Questions
	//*********************************************************
	if ($_POST[editQuestions] != null){
		$idEditQuestions = $_POST[editQuestions];
		echo '<p>';
		echo '<h3>Add New Questions to Selected Test</h3><p>';
		echo '<table border="1">';
		echo '<tr>';
		echo '<form method="post">' .
		  '<td>' . '<input type="text" name="newQuestion" placeholder="New question text" required>' . '</td>'.
		  '<td>' . '<input type="text" name="answerA" placeholder="Answer A) required">' . '</td>' .
		  '<td>' . '<input type="text" name="answerB" placeholder="Answer B) required">' . '</td>' .
		  '<td>' . '<input type="text" name="answerC" placeholder="Answer C) required">' . '</td>' .
		  '<td>' . '<input type="text" name="answerD" placeholder="Answer D) required">' . '</td>' .
		  '<td>' . "<button name='editTestId' value='".$idEditQuestions."'>Add Question</button>" .
		  //'<td>' . '<input type="submit" value="addNewQuestion">' . '</td>' .
		  '</form>' .
		  '</table>';



	 //FIX ME!!!!!!!!!!!!!!!!!!!!!
	//Determine how many questions are in the current test:
	

	// if ($_POST[editQuestions] != null) { 
	// 	$filterById = $_POST[editQuestions];
	// 	$Test = $mysqli->query("SELECT * FROM testCenterTestsDb 
	// 	                         WHERE id = '".$filterById."' ");
	//}
	
	
	//Show current questions	
		echo '<p>';
		echo '<h4>Current Questions in Test:</h4>';
		

		//Get the number of questions
		$questions = $mysqli->query("SELECT * FROM testCenterTestsDb 
		                         WHERE id = $_POST[editQuestions]");

		while ($question = $questions->fetch_assoc()) {
			$numberOfQuestions = $question[numberOfQuestions];
		}
		
		//Get the questions and responses
		$questions = $mysqli->query("SELECT * FROM testCenterTestsDb 
		                         WHERE id = $_POST[editQuestions]");

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


	} //end of edit test questions section


	//*************************************************************
	//Add new questions to test
	//***************************************************************
	// if ($_POST[newQuestion] !=null) {
	// 	$stmt = $mysqli->prepare("ALTER TABLE testCenterTestsDb ADD id = ?");
	// 	$stmt->bind_param("i", $idDelete);
	// 	$stmt->execute();
	// }

	//Add new question from Post Data
	if ($_POST[newQuestion] != null) {
		//echo 'new question is being added!<br>';
		
		//get number of questions currently in test
		$numberOfQuestions = 0;
		$Tests = $mysqli->query("SELECT numberOfQuestions FROM testCenterTestsDb 
		                         WHERE id = $_POST[editTestId]");

		while ($test = $Tests->fetch_assoc()) {
			$numberOfQuestions = $test[numberOfQuestions];
		}

		//WHERE category = '".$filterBy."' ");

		$newQuestionNumber = $numberOfQuestions + 1;
		$questionNumber = 'Q' . $newQuestionNumber;

		$responseA = 'R' . $newQuestionNumber . 'A';
		$responseB = 'R' . $newQuestionNumber . 'B';
		$responseC = 'R' . $newQuestionNumber . 'C';
		$responseD = 'R' . $newQuestionNumber . 'D';
		$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET $questionNumber=?, $responseA=?, $responseB=?, $responseC=?, $responseD=?
								 WHERE id = ?");
		$stmt->bind_param("sssssi", $_POST[newQuestion], $_POST[answerA], $_POST[answerC], $_POST[answerC], $_POST[answerD], $_POST[editTestId]);
		$stmt->execute();

		//update number of questions in database
		$numberOfQuestions++;
		$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET numberOfQuestions=? WHERE id = ?");
		$stmt->bind_param("ii", $numberOfQuestions, $_POST[editTestId]);
		$stmt->execute();

		//reopen edit question
		
		// $_SESSION = array();
		// session_destroy();
		// $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
		// $filePath = implode('/',$filePath);
		// $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
		// header("Location: {$redirect}/login.php", true);
		// die();
		
	}


} //end of valid user code


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