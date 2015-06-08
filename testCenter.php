<?php
session_start();
include 'storedInfo.php';
	
	
	//****************************************************************************
	//Connect to database
	//****************************************************************************
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "levyp-db", $myDbPassword, "levyp-db");
	if ($mysqli->connect_errno) {
    //echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	else {
		//echo "connected!<br>";
	}
	//**********************************************************
	//Check if database exists and if not create it
	//**********************************************************
	if (!$mysqli->query("DROP TABLE IF EXISTS test") ||
    	!$mysqli->query("CREATE TABLE testCenterTestsDb(id INT PRIMARY KEY AUTO_INCREMENT, testName VARCHAR(255),
    				 user VARCHAR(255), type VARCHAR(255), numberOfQuestions INT, numberCorrect INT)")  ) {   
		//echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
	
		//insert 10 columns for questions
		for ($j = 1; $j <=10; $j++){
			$newQ = "Q".$j;
			$newKey = "KEY" . $j;
			$newScore = "Score" . $j;
			$newRA = "R".$j."A";
			$newRB = "R".$j."B";
			$newRC = "R".$j."C";
			$newRD = "R".$j."D";

			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newQ VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newScore VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newKey VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRA VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRB VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRC VARCHAR(255)");
			$mysqli->query("ALTER TABLE testCenterTestsDb ADD $newRD VARCHAR(255)");
		}

	/********************************************************************
	//GET and POST Data for reading and updating database
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
	

	//******************************************************
	//Check if user has taken test for Javascript
	//******************************************************
	if ($_GET[checkIfTaken] !=null){
		//echo 'hello';
		$userName = $_SESSION['username'];
		$allTests = $mysqli->query("SELECT testName FROM testCenterTestsDb
									WHERE (type = 'responses' && user='".$userName."')");
	
		$tests = array();
		while ($curTest = $allTests->fetch_assoc()) {
		  $tests[] = $curTest;
		}
	echo json_encode($tests);

	}
	//******************************************************
	//GET Tests from database for Javascript
	//******************************************************
	if ($_GET[showTests] !=null){
	//echo '<br>Data was received<br>';
		$allTests = $mysqli->query("SELECT id, testName, user FROM testCenterTestsDb
									WHERE type = 'questions'");
	
		$tests = array();
		while ($curTest = $allTests->fetch_assoc()) {
		  $tests[] = $curTest;
		}
	echo json_encode($tests);

	}

	//******************************************************
	//GET Tests Responses for student from database for Javascript
	//******************************************************
	if ($_GET[getTestScores] !=null){
		//echo 'hello';
		$userName = $_SESSION['username'];
		$allTests = $mysqli->query("SELECT id, testName, user, numberOfQuestions, numberCorrect FROM testCenterTestsDb
									WHERE (type = 'responses' && user='".$userName."')");
	
		$tests = array();
		while ($curTest = $allTests->fetch_assoc()) {
		  $tests[] = $curTest;
		}
	echo json_encode($tests);

	}

	//******************************************************
	//GET All Test Responses for Teacher from database for Javascript
	//******************************************************
	if ($_GET[getTeacherTestScores] !=null){
		//echo 'hello';
		$userName = $_SESSION['username'];
		$allTests = $mysqli->query("SELECT id, testName, user, numberOfQuestions, numberCorrect FROM testCenterTestsDb
									WHERE (type = 'responses')");
	
		$tests = array();
		while ($curTest = $allTests->fetch_assoc()) {
		  $tests[] = $curTest;
		}
	echo json_encode($tests);

	}

	//************************************************************
	//GET Questions from database for Javascript
	//************************************************************
	if ($_GET[editQuestions] !=null) {
	
		$allQuestions = $mysqli->query("SELECT * FROM testCenterTestsDb 
		                         WHERE id = $_GET[editQuestions]");

		$questions = array();

		while ($curQuestion = $allQuestions->fetch_assoc()) {
		  $questions[] = $curQuestion;
		}
		echo json_encode($questions);
	}	
		

	/**************************************************************************
	//Update database based on POST data
	**************************************************************************/
	
	//***********************************
	//New Test to be added to database
	//**********************************
	$errorTestName = array("Please enter a test name as a string of up to 255 characters", false);
	$errorTestUser = array("User input is invalid", false);
	$errorTestType = array("Please enter a test type as either questions, key, or response.", false);


	if ($_POST[newTestName] != null){
		$newTestName = $_POST[newTestName];
		$newTestUser = $_SESSION['username'];
		$newTestType = 'questions'; //$_POST[newTestType];
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
		if (!($stmt->bind_param("sssi", $_POST[newTestName], $newTestUser, $newTestType, $newTestNumberQs))) {
			echo "Binding Parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		if (!($stmt->execute())) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		}

		
		//return user to original location
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
 	    $filePath = implode('/',$filePath);
 	    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
 	 	header("Location: {$redirect}/teacher.html", true);
 	 	die();
	}
	
	//******************************************************************
	//Record a new test submission from student
	//*****************************************************************
	if ($_POST[takeTestName] != null){
		$takeTestName = $_POST[takeTestName];
		$takeTestUser = $_SESSION['username'];
		$takeTestType = 'responses'; //$_POST[newTestType];
		$takeTestNumberQs = 0; // (int)$_POST[newTestNumberQs];

		//echo 'adding new test';
		//error messages
		$errorTestName = array("Please enter a test name as a string of up to 255 characters", false);
		$errorTestUser = array("User input is invalid", false);
		$errorTestType = array("Please enter a test type as either questions, key, or response.", false);

		//Check that the test name was properly input
		if (!is_string($takeTestName) || strlen($takeTestName) > 255 || strlen($takeTestName) <= 0){
			$errorTestName[1] = true;
		}

		
		//Add new test to database if no input errors were found
		if ($errorTestName[1] == false && $errorTestUser[1] == false && $errorTestType[1] == false) {
			if (!($stmt = $mysqli->prepare("INSERT INTO testCenterTestsDb(testName, user, type, numberOfQuestions) VALUES (?, ?, ?, ?)"))) {
				echo "Prepare failed: (" . $mysqli->errno .") " . $mysqli->error;	
			}
		if (!($stmt->bind_param("sssi", $_POST[takeTestName], $takeTestUser, $takeTestType, $takeTestNumberQs))) {
			echo "Binding Parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		if (!($stmt->execute())) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		}

		//Add responses to test
		$i = 1;
		$KEYNum = "KEY" . $i;
		while ($_POST[$KEYNum]){
			//Insert responses

			$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET $KEYNum=?, numberOfQuestions=?
								 WHERE (testName=? && user=?)");
			$stmt->bind_param("siss", $_POST[$KEYNum], $i, $_POST[takeTestName], $takeTestUser);
			$stmt->execute();

			$i++;
			$KEYNum = "KEY" . $i;
		}
		
		//Grade Test and copy question and response values over to response record
		$i = 1;
		$QNum = "Q" . $i;
		$ScoreNum = "Score" . $i;
		$KEYNum = "KEY" . $i;
		$RANum = "R" . $i . "A";
		$RBNum = "R" . $i . "B";
		$RCNum = "R" . $i . "C";
		$RDNum = "R" . $i . "D";
		
		$testType = 'questions';
		$testName = $_POST[takeTestName];
		$numberCorrect = 0;
		$correctString = 'correct';


		$answerKeys = $mysqli->query("SELECT * FROM testCenterTestsDb 
		                         WHERE (testName='".$testName."' && type='".$testType."')");


		while ($answerKey = $answerKeys->fetch_assoc()) {
		  while ($_POST[$KEYNum]){
			
				$correctAnswer = $answerKey[$KEYNum];
		
				if ($correctAnswer == $_POST[$KEYNum]){
				 	$correctString = 'correct';
				 	$numberCorrect++;
				 	$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET numberCorrect=?, $QNum = ?, $ScoreNum=?, $RANum=?, $RBNum=?, $RCNum=?, $RDNum=?
								 WHERE (testName=? && user=?)");
					$stmt->bind_param("issssssss", $numberCorrect, $answerKey[$QNum], $correctString, $answerKey[$RANum], $answerKey[$RBNum], $answerKey[$RCNum], $answerKey[$RDNum], $_POST[takeTestName], $takeTestUser);
					$stmt->execute();
				 	
				 }
				 else {
				 	$correctString = 'incorrect';
				 	$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET numberCorrect=?, $QNum = ?, $ScoreNum=?, $RANum=?, $RBNum=?, $RCNum=?, $RDNum=?
								 WHERE (testName=? && user=?)");
					$stmt->bind_param("issssssss", $numberCorrect, $answerKey[$QNum], $correctString, $answerKey[$RANum], $answerKey[$RBNum], $answerKey[$RCNum], $answerKey[$RDNum], $_POST[takeTestName], $takeTestUser);
					$stmt->execute();
				 }
			//}

			$i++;
			$QNum = "Q" . $i;
			$ScoreNum = "Score" . $i;
			$KEYNum = "KEY" . $i;
			$RANum = "R" . $i . "A";
			$RBNum = "R" . $i . "B";
			$RCNum = "R" . $i . "C";
			$RDNum = "R" . $i . "D";
			}
		 }

		//return user to original location
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
 	    $filePath = implode('/',$filePath);
 	    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
 	 	header("Location: {$redirect}/student.html", true);
 	 	die();

	}

	//Delete Test
	if ($_GET[deleteTest] != null){
		$idDelete = $_GET[deleteTest];
		$stmt = $mysqli->prepare("DELETE FROM testCenterTestsDb WHERE id = ?");
		$stmt->bind_param("i", $idDelete);
		$stmt->execute();
	}


	//*********************************************************************
	//Add new question from Post Data
	//********************************************************************
	if ($_POST[newQuestion] != null) {
		//echo 'new question is being added!<br>';
		
		//get number of questions currently in test
		$numberOfQuestions = 0;
		$Tests = $mysqli->query("SELECT numberOfQuestions FROM testCenterTestsDb 
		                         WHERE id = $_POST[editTestId]");

		while ($test = $Tests->fetch_assoc()) {
			$numberOfQuestions = $test[numberOfQuestions];
		}

		$newQuestionNumber = $numberOfQuestions + 1;
		$questionNumber = 'Q' . $newQuestionNumber;
		$KEYNumber = 'KEY' . $newQuestionNumber;
		$responseA = 'R' . $newQuestionNumber . 'A';
		$responseB = 'R' . $newQuestionNumber . 'B';
		$responseC = 'R' . $newQuestionNumber . 'C';
		$responseD = 'R' . $newQuestionNumber . 'D';
		$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET $questionNumber=?, $KEYNumber=?, $responseA=?, $responseB=?, $responseC=?, $responseD=?
								 WHERE id = ?");
		$stmt->bind_param("ssssssi", $_POST[newQuestion], $_POST[answerKey],$_POST[answerA], $_POST[answerB], $_POST[answerC], $_POST[answerD], $_POST[editTestId]);
		$stmt->execute();

		//update number of questions in database
		$numberOfQuestions++;
		$stmt = $mysqli->prepare("UPDATE testCenterTestsDb SET numberOfQuestions=? WHERE id = ?");
		$stmt->bind_param("ii", $numberOfQuestions, $_POST[editTestId]);
		$stmt->execute();

		//return user to original location
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
 	    $filePath = implode('/',$filePath);
 	    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
 	 	header("Location: {$redirect}/teacher.html?edit=" . $_POST[editTestId], true);
 	 	die();	
	}

?>