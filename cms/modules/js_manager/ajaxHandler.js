function getAjax(funcName,args,callback){
  var xhttp = new XMLHttpRequest();
  var request = "?";
  for(var i = 0; i < args.length-1; i += 2){
    request += "&" + args[i] + "=" + args[i+1];
  }
  xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
      let response = this.responseText;
      response = JSON.parse(response);
      callback(response);
    }
  };
  xhttp.open("POST", "?loc=ajax&func="+funcName, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(request);
}

function getAjaxFD(funcName,formData,callback){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
      let response = this.responseText;
      response = JSON.parse(response);
      callback(response);
    }
  };
  xhttp.open("POST", "?loc=ajax&func="+funcName, true);
  xhttp.send(formData);
}
