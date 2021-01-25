<?php
ModMan::addInit("AjaxMan::init");

class AjaxMan{
  private static $ajaxArr = [];

  public static function init(){
    $root = ModMan::getRoot("ajax_man");
    Builder::addJS($root . "ajaxHandler.js");
    Builder::addLoc("ajax_man", $root . "ajaxHandler.php");
  }

  public static function add($name, $func){
    $config = ModMan::getConfig("ajax_man");

    if(isset(AjaxMan::$ajaxArr[$name])){
      Logger::log("Ajax function [".$name."] already exists: Old func: [".AjaxMan::$ajaxArr[$name]."], new func: [".$func."]", "ERROR", "AjaxMan", $config->verbose);
      return;
    }

    AjaxMan::$ajaxArr[$name] = $func;
  }


  public static function exec(){
    $name = $_GET['ajax_func'];
    if(isset(AjaxMan::$ajaxArr[$name])){
      call_user_func(AjaxMan::$ajaxArr[$name]);
    } else {
      Logger::log("Ajax function not found: " . $name,"WARNING","AjaxMan",false);
      AjaxMan::ret("Function not found");
    }
  }

  public static function ret($msg){
    ob_end_clean();
    echo json_encode($msg);
    die();
  }

}
?>
