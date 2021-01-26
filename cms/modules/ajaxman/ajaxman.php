<?php
class AjaxMan{
  private static $ajaxArr = [];

  public static function init(){
    $root = ModMan::getRoot("ajaxman");
    Builder::addJS($root . "ajaxHandler.js");
    Builder::addLoc("ajaxman", $root . "ajaxHandler.php");
  }

  public static function add($name, $func){
    $config = ModMan::getConfig("ajaxman");

    if(isset(AjaxMan::$ajaxArr[$name])){
      Logger::log("Ajax function [".$name."] already exists: Old func: [".AjaxMan::$ajaxArr[$name]."], new func: [".$func."]", "ERROR", "AjaxMan", $config->verbose);
      return;
    }

    AjaxMan::$ajaxArr[$name] = $func;
  }


  public static function exec(){
    $name = $_GET['ajaxfunc'];
    if(isset(AjaxMan::$ajaxArr[$name])){
      if(is_callable(AjaxMan::$ajaxArr[$name])){
        call_user_func(AjaxMan::$ajaxArr[$name]);
      } else {
        Logger::log("Ajax function not found: " . $name,"ERROR","AjaxMan",false);
        AjaxMan::ret("Function not found");
      }
    } else {
      Logger::log("Ajax function not bound: " . $name,"WARNING","AjaxMan",false);
      AjaxMan::ret("Function not bound");
    }
  }

  public static function ret($msg){
    ob_end_clean();
    echo json_encode($msg);
    die();
  }

}
?>
