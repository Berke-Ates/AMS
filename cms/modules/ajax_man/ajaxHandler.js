function getAjax(funcName,formData,callback){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
      let response = this.responseText;
      response = JSON.parse(response);
      callback(response);
    }
  };
  xhttp.open("POST", "?loc=ajax_man&ajax_func="+funcName, true);
  xhttp.send(formData);
}
