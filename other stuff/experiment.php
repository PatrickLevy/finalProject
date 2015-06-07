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
	//Experimental GET Tests from database
	//******************************************************
	if ($_GET[showTests] !=null){
	//echo '<br>Data was received<br>';
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb");
	
		$tests = array();
		while ($curTest = $allTests->fetch_assoc()) {
		  $tests[] = $curTest;
		}
	echo json_encode($tests);

	}

	//Get Tests from Database
	if (($_GET[filter] != null) && ($_GET[filter] != 'All')) { 
		$filterBy = $_GET[filter];
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb 
		                         WHERE category = '".$filterBy."' ");
	}
	else {
		$allTests = $mysqli->query("SELECT id, testName, user, type FROM testCenterTestsDb");
	}

	

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
	}






?>