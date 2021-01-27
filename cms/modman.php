<?php

class ModMan{
  private static $initArr = [];

  public static function check($mod, $verbose = false){
    $json = file_get_contents(ModMan::getRoot($mod) . "config.json");

    if($json === false){
      if($verbose){ echo '<script>console.log("['.$mod.'] missing config")</script>'; }
      return false;
    }

    $config = json_decode($json);

    if($config === null){
      if($verbose){ echo '<script>console.log("['.$mod.'] config json invalid")</script>'; }
      return false;
    }

    if(!isset($config->entry)){
      if($verbose){ echo '<script>console.log("['.$mod.'] config missing [entry]")</script>'; }
      return false;
    }

    if(!isset($config->enabled) ){
      if($verbose){ echo '<script>console.log("['.$mod.'] config missing [enabled]")</script>'; }
      return false;
    }

    return true;
  }

  public static function getModRoots(){
    return array_slice(scandir("cms/modules"),2);
  }

  public static function getRoot($mod){
    return "cms/modules/" . $mod . "/";
  }

  public static function getConfig($mod){
    return json_decode(file_get_contents(ModMan::getRoot($mod) . "config.json"));
  }

  public static function setConfig($mod,$config){
    file_put_contents(ModMan::getRoot($mod) . "config.json", json_encode($config));
  }

  public static function checkDeps($mod, $verbose = false){
    $config = ModMan::getConfig($mod);
    if(!isset($config->dependencies)){ return true; }

    $missingDeps = "";
    foreach($config->dependencies as $dep) {
      if(!ModMan::check($dep) || !ModMan::getConfig($dep)->enabled){
        $missingDeps = $missingDeps . "[".$dep."] ";
        continue;
      }
    }

    if($missingDeps != ""){
      if($verbose){ echo '<script>console.log("['.$mod.'] missing dependencies: '.$missingDeps.'")</script>'; }
      return false;
    }

    return true;
  }

  public static function load($mod){
    $config = ModMan::getConfig($mod);
    include(ModMan::getRoot($mod) . $config->entry);
    if(isset($config->init) ){
      ModMan::addInit($config->init);
    }
  }

  private static function addInit($func){
    array_push(ModMan::$initArr, $func);
  }

  public static function init(){
    foreach (ModMan::$initArr as $func) {
      call_user_func($func);
    }
    ModMan::$initArr = [];
  }

}

?>
