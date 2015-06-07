

//*************************************************************
// Function to create list of tests on page
//*************************************************************
function createTestList() {
  var ul = document.getElementById('test-list')
  
  //Remove old list from page
  for (var i=ul.childNodes.length-1; i >= 0; i--) {
    ul.removeChild(ul.childNodes[i]);
  }  
  
  //Add a new row
  var row = document.createElement('div');
  row.className = 'row';
  ul.appendChild(row);

  //Add a horizontal rule
  var lineBreak = document.createElement('hr');
  row.appendChild(lineBreak);

  

  //Create new list on page
  
  //Add a new row for headers
  var row = document.createElement('div');
  row.className = 'row';
  ul.appendChild(row);


  //Create Headers for table
  var colEditHead = document.createElement('div');
  colEditHead.className = 'col-md-2';
  row.appendChild(colEditHead);

  // var colResultsHead = document.createElement('div');
  // colResultsHead.className = 'col-md-2';
  // row.appendChild(colResultsHead);

  var colTitleHead = document.createElement('div');
  colTitleHead.className = 'col-md-6';
  var titleText = document.createTextNode('Test Name');
  titleText.style = 'bold';
  colTitleHead.appendChild(titleText);
  row.appendChild(colTitleHead);

  var colCreatedByHead = document.createElement('div');
  colCreatedByHead.className = 'col-md-2';
  var createdText = document.createTextNode('Created By');
  colCreatedByHead.appendChild(createdText);
  row.appendChild(colCreatedByHead);

  var colDel = document.createElement('div');
  colDel.className = 'col-md-2';
  row.appendChild(colDel);

  for (var i=0; i < ajaxTestList.length; i++){

    //add a new row
    row = document.createElement('div');
    row.className = 'row';
    ul.appendChild(row);
    //ul.appendChild(row);

    //Add a horizontal rule
    var lineBreak = document.createElement('hr');
    row.appendChild(lineBreak);

    //Create a view scores button for each item
    
    // var colResults = document.createElement('div');
    // colResults.className = 'col-md-2';
    // row.appendChild(colResults);

    // var resultsBtn = document.createElement('button');
    // var btnTxt = document.createTextNode("Student Scores");
    // resultsBtn.appendChild(btnTxt);
    // resultsBtn.id = 'select' + ajaxTestList[i].id;
    // resultsBtn.name = ajaxTestList[i].id;
    // resultsBtn.onclick = function(){window.studentScores(this.name)}; //window is required due to scoping
    // colResults.appendChild(resultsBtn);
   

   //Create a take test button for each item
    var colEdit = document.createElement('div');
    colEdit.className = 'col-md-2';
    row.appendChild(colEdit);

    var takeTest = document.createElement('button');
    var takeTxt = document.createTextNode("Take Test");
    takeTest.appendChild(takeTxt);
    takeTest.id = 'select' + ajaxTestList[i].id;
    takeTest.name = ajaxTestList[i].id;
    takeTest.onclick = function(){window.takeTest(this.name)}; //window is required due to scoping
    colEdit.appendChild(takeTest);


    //Create text for each test
    var colTitle = document.createElement('div');
    colTitle.className = 'col-md-6';
    row.appendChild(colTitle);

    var node = document.createElement('div');
    var nodeText = document.createTextNode(ajaxTestList[i].testName);
    node.appendChild(nodeText);
    colTitle.appendChild(node);

    //Create text for each test creator
    var colCreatedBy = document.createElement('div');
    colCreatedBy.className = 'col-md-2';
    row.appendChild(colCreatedBy);

    var createdBy = document.createElement('div');
    var createdByTxt = document.createTextNode(ajaxTestList[i].user);
    createdBy.appendChild(createdByTxt);
    colCreatedBy.appendChild(createdBy);

    

    

    }
}

//*************************************************************
// Function to create list of tests on page
//*************************************************************
function showScoresList() {
  var ul = document.getElementById('test-list')
  
  //Remove old list from page
  for (var i=ul.childNodes.length-1; i >= 0; i--) {
    ul.removeChild(ul.childNodes[i]);
  }  
  
  //Add a new row
  var row = document.createElement('div');
  row.className = 'row';
  ul.appendChild(row);

  //Add a horizontal rule
  var lineBreak = document.createElement('hr');
  row.appendChild(lineBreak);

  //Create new list on page
  
  //Add a new row for headers
  var row = document.createElement('div');
  row.className = 'row';
  ul.appendChild(row);


  //Create Headers for table
  var colEditHead = document.createElement('div');
  colEditHead.className = 'col-md-2';
  row.appendChild(colEditHead);


  var colTitleHead = document.createElement('div');
  colTitleHead.className = 'col-md-6';
  var titleText = document.createTextNode('Test Name');
  titleText.style = 'bold';
  colTitleHead.appendChild(titleText);
  row.appendChild(colTitleHead);

  var colCreatedByHead = document.createElement('div');
  colCreatedByHead.className = 'col-md-2';
  var createdText = document.createTextNode('Created By');
  colCreatedByHead.appendChild(createdText);
  row.appendChild(colCreatedByHead);

  var colDel = document.createElement('div');
  colDel.className = 'col-md-2';
  row.appendChild(colDel);

  for (var i=0; i < ajaxTestList.length; i++){

    //add a new row
    row = document.createElement('div');
    row.className = 'row';
    ul.appendChild(row);
    //ul.appendChild(row);

    //Add a horizontal rule
    var lineBreak = document.createElement('hr');
    row.appendChild(lineBreak);

    //Create a view scores button for each item
    
    // var colResults = document.createElement('div');
    // colResults.className = 'col-md-2';
    // row.appendChild(colResults);

    // var resultsBtn = document.createElement('button');
    // var btnTxt = document.createTextNode("Student Scores");
    // resultsBtn.appendChild(btnTxt);
    // resultsBtn.id = 'select' + ajaxTestList[i].id;
    // resultsBtn.name = ajaxTestList[i].id;
    // resultsBtn.onclick = function(){window.studentScores(this.name)}; //window is required due to scoping
    // colResults.appendChild(resultsBtn);
   

   //Create a show results button for each item
    var colEdit = document.createElement('div');
    colEdit.className = 'col-md-2';
    row.appendChild(colEdit);

    var showTest = document.createElement('button');
    var showTxt = document.createTextNode("Show Results");
    showTest.appendChild(showTxt);
    showTest.id = 'select' + ajaxTestList[i].id;
    showTest.name = ajaxTestList[i].id;
    showTest.onclick = function(){window.showTestResults(this.name)}; //window is required due to scoping
    colEdit.appendChild(showTest);


    //Create text for each test
    var colTitle = document.createElement('div');
    colTitle.className = 'col-md-6';
    row.appendChild(colTitle);

    var node = document.createElement('div');
    var nodeText = document.createTextNode(ajaxTestList[i].testName);
    node.appendChild(nodeText);
    colTitle.appendChild(node);

    //Create text for each test creator
    var colCreatedBy = document.createElement('div');
    colCreatedBy.className = 'col-md-2';
    row.appendChild(colCreatedBy);

    var createdBy = document.createElement('div');
    var createdByTxt = document.createTextNode(ajaxTestList[i].user);
    createdBy.appendChild(createdByTxt);
    colCreatedBy.appendChild(createdBy);

    

    

    }
}

//*************************************************************
// Function to delete a test from Database using AJAX
//*************************************************************
function deleteTest(testId){
  console.log('delete: ' + testId);

  var req = new XMLHttpRequest();
  if(!req){
    throw 'Unable to create HttpRequest.';
  }
  var url = 'testCenter.php';
  var params = {
    deleteTest: testId
  };
  url += '?' + urlStringify(params);
  
  //Ajax call
  req.onreadystatechange = function(){
    if(this.readyState === 4 && this.status == 200){
      //all done and no data to be received
      //response = JSON.parse(this.responseText);
      

      //update page - this only works after previous delete
      
      //createTestList();

      //createTestList();
      console.log('delete should be complete');
      location.reload();

      var readyStateCheckInterval = setInterval(function() {
      if (document.readyState === "complete") {
          clearInterval(readyStateCheckInterval);
          console.log('ready state is complete');
          
          console.log('creating test list!!!');
          createTestList();    
          //getTests();
      }
    }, 10);
      // console.log('creating test list!!!');
      // createTestList();
      getTests();
    }

  };
  console.log('got here');
  req.open('GET', url);  //I can't get this to work with POST!!!
  req.send();
  console.log('and got here');
  


  return true;
}

//*************************************************************************
//Function to display and edit the questions of a test
//**************************************************************************
function takeTest(testId) {
  console.log('edit questions:' + testId)
  var ul = document.getElementById('test-list')
  
    //Remove old list from page
    for (var i=ul.childNodes.length-1; i >= 0; i--) {
      ul.removeChild(ul.childNodes[i]);
    }

    //Get questions for selected test
    getQuestions(testId);  
    console.log(ajaxQuestionList);

    //Display questions
    console.log(ajaxQuestionList[0].numberOfQuestions);

      var saveAnswersForm = document.createElement('form');
      saveAnswersForm.name = 'saveAnswers';  //does this do anything?
      saveAnswersForm.method = 'post';
      saveAnswersForm.action = 'testCenter.php';
      

      //include a hidden form so that the test name is sent
      var newAnswerId = document.createElement('input');
      newAnswerId.type = 'hidden';
      newAnswerId.name = 'takeTestName';
      newAnswerId.value = ajaxQuestionList[0].testName;
      console.log(ajaxQuestionList[0].testName);
      saveAnswersForm.appendChild(newAnswerId);

    for (i = 1; i <= ajaxQuestionList[0].numberOfQuestions; i++){
      
      var questionKey = "Q" + i;
      var answerAKey = "R" + i + "A";
      var answerBKey = "R" + i + "B";
      var answerCKey = "R" + i + "C";
      var answerDKey = "R" + i + "D";
      var studentAnswer = "KEY" + i;

      //Append question
      var newQuestionItem = document.createElement('li');
      var newQuestionTxt = document.createTextNode(i + ': ' + ajaxQuestionList[0][questionKey]);
      newQuestionItem.appendChild(newQuestionTxt);

      //Append response A
      var newResponseItemA = document.createElement('li');
      var newResponseTxtA = document.createTextNode('A: ' + ajaxQuestionList[0][answerAKey]);
      newResponseItemA.appendChild(newResponseTxtA);
      newQuestionItem.appendChild(newResponseItemA);

      //Append response B
      var newResponseItemB = document.createElement('li');
      var newResponseTxtB = document.createTextNode('B: ' + ajaxQuestionList[0][answerBKey]);
      newResponseItemB.appendChild(newResponseTxtB);
      newQuestionItem.appendChild(newResponseItemB);

      //Append response C
      var newResponseItemC = document.createElement('li');
      var newResponseTxtC = document.createTextNode('C: ' + ajaxQuestionList[0][answerCKey]);
      newResponseItemC.appendChild(newResponseTxtC);
      newQuestionItem.appendChild(newResponseItemC);

      //Append response D
      var newResponseItemD = document.createElement('li');
      var newResponseTxtD = document.createTextNode('D: ' + ajaxQuestionList[0][answerDKey]);
      newResponseItemD.appendChild(newResponseTxtD);
      newQuestionItem.appendChild(newResponseItemD);

      //Append a form for collecting responses
      
      


      //Add select menu for inputting the correct answer
        var selectAnswer = document.createElement('select');
        selectAnswer.name = 'KEY' + i;
        
        var optionA = document.createElement('option');
        optionA.value = 'a';
        optionA.text = 'Correct Answer: A';
        selectAnswer.appendChild(optionA);
        
        var optionB = document.createElement('option');
        optionB.value = 'b';
        optionB.text = 'Correct Answer: B';
        selectAnswer.appendChild(optionB);

        var optionC = document.createElement('option'); 
        optionC.value = 'c';
        optionC.text = 'Correct Answer: C';
        selectAnswer.appendChild(optionC);

        var optionD = document.createElement('option'); 
        optionD.value = 'd';
        optionD.text = 'Correct Answer: D';
        selectAnswer.appendChild(optionD);

        newQuestionItem.appendChild(selectAnswer);

        // var submitQuestion =  document.createElement('input');
        // submitQuestion.type = 'submit';
        // submitQuestion.value = 'Save Answer';

        // newQuestionItem.appendChild(selectAnswer);
        // newQuestionItem.appendChild(submitQuestion);

      //Add a line break between questions
      var lineBreak = document.createElement('hr');
      newQuestionItem.appendChild(lineBreak);

      //Append the entire question/response group to list
      ul.appendChild(newQuestionItem);
      saveAnswersForm.appendChild(newQuestionItem);
    }
        var submitQuestion =  document.createElement('input');
        submitQuestion.type = 'submit';
        submitQuestion.value = 'Save All Test Answers';

        ul.appendChild(saveAnswersForm);
        saveAnswersForm.appendChild(submitQuestion);
  



}

//*************************************************************************
//Function to show the results of a test
//**************************************************************************
function showTestResults(testId) {
  console.log('edit questions:' + testId)
  var ul = document.getElementById('test-list')
  
    //Remove old list from page
    for (var i=ul.childNodes.length-1; i >= 0; i--) {
      ul.removeChild(ul.childNodes[i]);
    }

    //Get questions for selected test
    getQuestions(testId);  
    console.log(ajaxQuestionList);

    //Display questions
    console.log(ajaxQuestionList[0].numberOfQuestions);

      var saveAnswersForm = document.createElement('form');
      saveAnswersForm.name = 'saveAnswers';  //does this do anything?
      saveAnswersForm.method = 'post';
      saveAnswersForm.action = 'testCenter.php';
      

      //include a hidden form so that the test name is sent
      var newAnswerId = document.createElement('input');
      newAnswerId.type = 'hidden';
      newAnswerId.name = 'takeTestName';
      newAnswerId.value = ajaxQuestionList[0].testName;
      console.log(ajaxQuestionList[0].testName);
      saveAnswersForm.appendChild(newAnswerId);

    for (i = 1; i <= ajaxQuestionList[0].numberOfQuestions; i++){
      
      var questionKey = "Q" + i;
      var answerAKey = "R" + i + "A";
      var answerBKey = "R" + i + "B";
      var answerCKey = "R" + i + "C";
      var answerDKey = "R" + i + "D";
      var studentAnswer = "KEY" + i;

      //Append question
      var newQuestionItem = document.createElement('li');
      var newQuestionTxt = document.createTextNode(i + ': ' + ajaxQuestionList[0][questionKey]);
      newQuestionItem.appendChild(newQuestionTxt);

      //Append response A
      var newResponseItemA = document.createElement('li');
      var newResponseTxtA = document.createTextNode('A: ' + ajaxQuestionList[0][answerAKey]);
      newResponseItemA.appendChild(newResponseTxtA);
      newQuestionItem.appendChild(newResponseItemA);

      //Append response B
      var newResponseItemB = document.createElement('li');
      var newResponseTxtB = document.createTextNode('B: ' + ajaxQuestionList[0][answerBKey]);
      newResponseItemB.appendChild(newResponseTxtB);
      newQuestionItem.appendChild(newResponseItemB);

      //Append response C
      var newResponseItemC = document.createElement('li');
      var newResponseTxtC = document.createTextNode('C: ' + ajaxQuestionList[0][answerCKey]);
      newResponseItemC.appendChild(newResponseTxtC);
      newQuestionItem.appendChild(newResponseItemC);

      //Append response D
      var newResponseItemD = document.createElement('li');
      var newResponseTxtD = document.createTextNode('D: ' + ajaxQuestionList[0][answerDKey]);
      newResponseItemD.appendChild(newResponseTxtD);
      newQuestionItem.appendChild(newResponseItemD);

      //Append a form for collecting responses
      
      


      //Add select menu for inputting the correct answer
        var selectAnswer = document.createElement('select');
        selectAnswer.name = 'KEY' + i;
        
        var optionA = document.createElement('option');
        optionA.value = 'a';
        optionA.text = 'Correct Answer: A';
        selectAnswer.appendChild(optionA);
        
        var optionB = document.createElement('option');
        optionB.value = 'b';
        optionB.text = 'Correct Answer: B';
        selectAnswer.appendChild(optionB);

        var optionC = document.createElement('option'); 
        optionC.value = 'c';
        optionC.text = 'Correct Answer: C';
        selectAnswer.appendChild(optionC);

        var optionD = document.createElement('option'); 
        optionD.value = 'd';
        optionD.text = 'Correct Answer: D';
        selectAnswer.appendChild(optionD);

        newQuestionItem.appendChild(selectAnswer);

        // var submitQuestion =  document.createElement('input');
        // submitQuestion.type = 'submit';
        // submitQuestion.value = 'Save Answer';

        // newQuestionItem.appendChild(selectAnswer);
        // newQuestionItem.appendChild(submitQuestion);

      //Add a line break between questions
      var lineBreak = document.createElement('hr');
      newQuestionItem.appendChild(lineBreak);

      //Append the entire question/response group to list
      ul.appendChild(newQuestionItem);
      saveAnswersForm.appendChild(newQuestionItem);
    }
        var submitQuestion =  document.createElement('input');
        submitQuestion.type = 'submit';
        submitQuestion.value = 'Save All Test Answers';

        ul.appendChild(saveAnswersForm);
        saveAnswersForm.appendChild(submitQuestion);
  
}


//********************************************************
// Function to get questions from Database using AJAX
//*************************************************************
function getQuestions(testId) {
  console.log('Getting questions for: ' + testId)
  var req = new XMLHttpRequest();
  if(!req){
    throw 'Unable to create HttpRequest.';
  }
  var url = 'testCenter.php';
  var params = {
    editQuestions: testId
  };
  url += '?' + urlStringify(params);
  
  //Ajax call
  req.onreadystatechange = function(){
    if(this.readyState === 4){
      ajaxQuestionList = JSON.parse(this.responseText);
    }
  };
  req.open('GET', url, false); //changed to asynchronous = false, it would be better to use callbacks
  req.send();
 
}
//*************************************************************
//Call to add a question to a test
//*************************************************************
function addNewQuestion(testId) {
  console.log('trying to add a question');
}

//*************************************************************************
//Function to show student scores of a test
//**************************************************************************
function studentScores(testId) {
  console.log('show scores: ' + testId);
  }

//********************************************************
// Function to get tests from Database using AJAX
//*************************************************************
function getTests(){
  console.log("running getTests()")
  //Delete old gists, if present
  // if (gistList.gists.length !== 0){
  //   gistList.gists = [];
  // }

  var req = new XMLHttpRequest();
  if(!req){
    throw 'Unable to create HttpRequest.';
  }
  var url = 'testCenter.php';
  var params = {
    showTests: 'showSomeTests'
  };
  url += '?' + urlStringify(params);
  
  //Ajax call
  req.onreadystatechange = function(){
    if(this.readyState === 4){
      ajaxTestList = JSON.parse(this.responseText);
      
      //the following gives an error because it runs before ajaxTestList is defined!
      //console.log(ajaxTestList[0].testName);
    }
  };
  req.open('GET', url, false); //changed to asynchronous = false, it would be better to use callbacks
  req.send();

 
}

//*************************************************************
// Function to stringify the url for the ajax call
//*************************************************************
function urlStringify(obj){
  var str = []
  for(var prop in obj){
    var s = encodeURIComponent(prop) + '=' + encodeURIComponent(obj[prop]);
    str.push(s);
  }
  return str.join('&');
}


function logTests(){
  console.log(ajaxTestList);
}




//***********************************************
//Parse Get Data
//Citation:  http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
//************************************************
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


//*************************************************************
//Call to load tests from database after page load
//*************************************************************
window.onload = function() {
  

  //callback method doesn't work unless i use synchronous XMLHttpRequest
  secondFunction();

  function firstFunction(_callback) {
    getTests();
    _callback();
  }

  function secondFunction() {
    firstFunction(function() {
      console.log('done getting tests!!!!');
      createTestList();
    });
  }

  //Check for test edit
  var editId = getParameterByName('edit');
  if (getParameterByName('edit')) {
    console.log(editId);
    getQuestions(editId);
    editQuestions(editId);
  }
  else {
    console.log('edit test was not specified');
  }
  //console.log(getParameterByName(window.location.search));
  


};