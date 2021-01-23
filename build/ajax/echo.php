<?php
// Ajax functions //
function echoAJ(){
  $msg = $_POST['msg'];
  returnAjax('success',$msg);
}

?>
