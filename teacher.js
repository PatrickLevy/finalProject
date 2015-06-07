

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

  //Allow user to add a new test
  var newTestItem = document.createElement('li');
  newTestItem.style = 'list-style-type:none';
  ul.appendChild(newTestItem);

  //Create a field for the test name
  var newTestForm = document.createElement('form')
  newTestForm.method = 'post';
  newTestForm.action = 'testCenter.php';


  var newTestName = document.createElement('input');
  newTestName.name = 'newTestName';
  newTestName.type = 'text';
  newTestName.placeholder = 'Add a New Test';

  var submitTest =  document.createElement('input');
  submitTest.type = 'submit';
  submitTest.value = 'Add Test';

  newTestForm.appendChild(newTestName);
  newTestForm.appendChild(submitTest);
  ul.appendChild(newTestForm);


  //Create new list on page
  
  //Add a new row for headers
  var row = document.createElement('div');
  row.className = 'row';
  ul.appendChild(row);

  //Add a horizontal rule
  var lineBreak = document.createElement('hr');
  row.appendChild(lineBreak);

  //Create Headers for table
  var colEditHead = document.createElement('div');
  colEditHead.className = 'col-md-2';
  row.appendChild(colEditHead);

  var colResultsHead = document.createElement('div');
  colResultsHead.className = 'col-md-2';
  row.appendChild(colResultsHead);

  var colTitleHead = document.createElement('div');
  colTitleHead.className = 'col-md-4';
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
    var colResults = document.createElement('div');
    colResults.className = 'col-md-2';
    row.appendChild(colResults);

    var resultsBtn = document.createElement('button');
    var btnTxt = document.createTextNode("Student Scores");
    resultsBtn.appendChild(btnTxt);
    resultsBtn.id = 'select' + ajaxTestList[i].id;
    resultsBtn.name = ajaxTestList[i].id;
    resultsBtn.onclick = function(){window.studentScores(this.name)}; //window is required due to scoping
    colResults.appendChild(resultsBtn);
   

   //Create an edit button for each item
    var colEdit = document.createElement('div');
    colEdit.className = 'col-md-2';
    row.appendChild(colEdit);

    var editTest = document.createElement('button');
    var editTxt = document.createTextNode("Edit Questions");
    editTest.appendChild(editTxt);
    editTest.id = 'select' + ajaxTestList[i].id;
    editTest.name = ajaxTestList[i].id;
    editTest.onclick = function(){window.editQuestions(this.name)}; //window is required due to scoping
    colEdit.appendChild(editTest);


    //Create text for each test
    var colTitle = document.createElement('div');
    colTitle.className = 'col-md-4';
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

    //Create a delete button for each item
    var colDeleteTest = document.createElement('div');
    colDeleteTest.className = 'col-md-2';
    row.appendChild(colDeleteTest);

    var deleteTest = document.createElement('button');
    var deleteTxt = document.createTextNode("Delete Test");
    deleteTest.appendChild(deleteTxt);
    deleteTest.id = 'delete' + ajaxTestList[i].id;
    deleteTest.name = ajaxTestList[i].id;
    deleteTest.onclick = function(){window.deleteTest(this.name)}; //window is required due to scoping
    colDeleteTest.appendChild(deleteTest);

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
function editQuestions(testId) {
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

    for (i = 1; i <= ajaxQuestionList[0].numberOfQuestions; i++){
      
      var questionKey = "Q" + i;
      var answerAKey = "R" + i + "A";
      var answerBKey = "R" + i + "B";
      var answerCKey = "R" + i + "C";
      var answerDKey = "R" + i + "D";
      var correctAnswer = "KEY" + i;

      //Append new question
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

      //Append Correct Answer
      var newItemKey = document.createElement('li');
      var newTxtKey = document.createTextNode('Correct Answer: ' + ajaxQuestionList[0][correctAnswer]);
      newItemKey.appendChild(newTxtKey);
      newQuestionItem.appendChild(newItemKey);

      //Append a delete button
      // var newDeleteBtn = document.createElement('button')
      // var delQstTxt = document.createTextNode("Delete Question");
      // newDeleteBtn.appendChild(delQstTxt);
      // newQuestionItem.appendChild(newDeleteBtn);

      var lineBreak = document.createElement('hr');
      newQuestionItem.appendChild(lineBreak);

      //Append the entire question/repsonse group to list
      ul.appendChild(newQuestionItem); 
    }

  //Allow user to add a new question

  var newQuestionItem = document.createElement('li');
  newQuestionItem.style = 'list-style-type:none';
  ul.appendChild(newQuestionItem);

  //Create a field for the test name
  var newQuestionForm = document.createElement('form');
  newQuestionForm.name = 'editTestId';  //does this do anything?
  //newQuestionForm.value = ajaxQuestionList[0].id;
  //newQuestionForm.name = ajaxQuestionList[0].id;
  //console.log(ajaxQuestionList[0].id);
  newQuestionForm.method = 'post';
  newQuestionForm.action = 'testCenter.php';
  //newQuestionForm.onsubmit = addNewQuestion();

  //include a hidden form so that the test name is sent
  var newQuestionId = document.createElement('input');
  newQuestionId.type = 'hidden';
  newQuestionId.name = 'editTestId';
  newQuestionId.value = ajaxQuestionList[0].id;
  

  var newQuestionName = document.createElement('input');
  //newQuestionName.name = '&editTestId='+ajaxQuestionList[0].id+'newQuestion';
  newQuestionName.name = 'newQuestion';
  newQuestionName.type = 'text';
  newQuestionName.placeholder = 'Add a New Question';

  var newResponseAName = document.createElement('input');
  newResponseAName.name = 'answerA';
  newResponseAName.type = 'text';
  newResponseAName.placeholder = 'A: Response';

  var newResponseBName = document.createElement('input');
  newResponseBName.name = 'answerB';
  newResponseBName.type = 'text';
  newResponseBName.placeholder = 'B: Response';

  var newResponseCName = document.createElement('input');
  newResponseCName.name = 'answerC';
  newResponseCName.type = 'text';
  newResponseCName.placeholder = 'C: Response';

  var newResponseDName = document.createElement('input');
  newResponseDName.name = 'answerD';
  newResponseDName.type = 'text';
  newResponseDName.placeholder = 'D: Response';

  //Add select menu for inputting the correct answer
  var selectAnswer = document.createElement('select');
  selectAnswer.name = 'answerKey';
  
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


  var submitQuestion =  document.createElement('input');
  submitQuestion.type = 'submit';
  submitQuestion.value = 'Add Question';

  newQuestionForm.appendChild(newQuestionId);
  newQuestionForm.appendChild(newQuestionName);
  newQuestionForm.appendChild(newResponseAName);
  newQuestionForm.appendChild(newResponseBName);
  newQuestionForm.appendChild(newResponseCName);
  newQuestionForm.appendChild(newResponseDName);
  
  newQuestionForm.appendChild(submitQuestion);
  newQuestionForm.appendChild(selectAnswer);
  ul.appendChild(newQuestionForm);



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