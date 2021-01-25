<?php
AjaxMan::add("echo", "echoPhp");
function echoPhp(){
  AjaxMan::ret($_POST['msg']);
}
?>
