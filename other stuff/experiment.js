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
  var url = 'experiment.php';
  var params = {
    showTests: 'showSomeTests'
  };
  url += '?' + urlStringify(params);
  
  //Ajax call
  req.onreadystatechange = function(){
    if(this.readyState === 4){
      ajaxTestList = JSON.parse(this.responseText);
      
      //Create list of gists with only required information
      // for (var i = 0; i < ajaxGistList.length; i++){
      //   var gistDesc = ajaxGistList[i].description;
      //   var gistUrl = ajaxGistList[i].html_url;
      //   var gistId = ajaxGistList[i].id;
      //   var gistEntry = new gists(gistDesc, gistUrl, gistId);
      //   gistList.gists.push(gistEntry);
      // }
      // //Re-render list of gists
      // createGistList();
      console.log(ajaxTestList[0].testName);
    }
  };
  req.open('GET', url);
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
