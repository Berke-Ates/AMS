<?php
class Admin_Config{

  public static function init(){
    AjaxMan::add("admin_editConfig", "Admin_Config::editConfig");
  }

  public static function getConfig($mod){
    $conf = ModMan::getConfig("admin");
    $BUjson = '{"icon": "fas fa-puzzle-piece", "name": "'.ucfirst($mod).'", "links":[], "immutableKeys":[]}';
    $json = $BUjson;
    $path = "cms/modules/" . $mod . "/admin_config.json";
    if(file_exists($path)){ $json = file_get_contents($path); }

    $config = json_decode($json);
    if($config === null){
      $config = json_decode($BUjson);
      Logger::log("Invalid JSON in admin_config of: " . $mod,"ERROR","Admin",$conf->verbose);
    }

    if(!isset($config->icon)){
      $config->icon = "fas fa-puzzle-piece";
      Logger::log("Icon undefinded in admin_config of: " . $mod,"WARNING","Admin",$conf->verbose);
    }

    if(!isset($config->links)){
      $config->links = [];
      Logger::log("Links undefinded in admin_config of: " . $mod,"WARNING","Admin",$conf->verbose);
    }

    if(!isset($config->name)){
      $config->name = ucfirst($mod);
      Logger::log("Name undefinded in admin_config of: " . $mod,"WARNING","Admin",$conf->verbose);
    }

    $config->mod = $mod;
    if(!isset($config->immutableKeys)){ $config->immutableKeys = []; }
    $config->immutableKeys = array_merge($config->immutableKeys, ["entry","init","dependencies"]);

    if($conf->autoConfig){
      $linkAdd = '[{"name": "Config", "link": "admin/parts/config.phtml&mod='.$mod.'"}]';
      $config->links = array_merge($config->links, json_decode($linkAdd));
    }

    if($conf->autoReadme){
      $linkAdd = '[{"name": "Readme", "link": "admin/parts/readme.phtml&mod='.$mod.'"}]';
      $config->links = array_merge($config->links, json_decode($linkAdd));
    }

    return $config;
  }

  public static function getConfigs(){
    $adConfs = [];
    foreach(ModMan::getModRoots() as $module){
      if(ModMan::getConfig("admin")->showDisabledModules || ModMan::getConfig($module)->enabled){
        array_push($adConfs, Admin_Config::getConfig($module));
      }
    }
    return $adConfs;
  }

  public static function editConfig(){
    $mod = $_POST["mod"];
    Admin_User::checkAccess($mod,1);
    $keypath = json_decode($_POST["path"]);
    $val = json_decode($_POST["val"]);

    $config = ModMan::getConfigAssoc($mod);
    $adConfig = Admin_Config::getConfig($mod);

    $immut = [];
    if(isset($adConfig->immutableKeys)){ $immut = $adConfig->immutableKeys;  }
    if(isset($adConfig->hiddenKeys)){ $immut = array_merge($immut, $adConfig->hiddenKeys);  }

    foreach($keypath as $key){
      if(in_array($key, $immut, true)){
        Logger::log("Tried editing immutable keys: mod: " . $mod . ", key: " . $key,"WARNING","Admin",false);
        AjaxMan::ret(["success" => false, "msg" => "Keypath contains immutable or hidden keys"]);
      }
    }

    $config = array_replace_recursive($config, Admin_Config::confRecRep($keypath, $val));
    ModMan::setConfig($mod, $config);
    AjaxMan::ret(["success" => true, "msg" => "Config edited"]);
  }

  private static function confRecRep($keypath, $val, $id = 0){
    if($id == count($keypath)-1){
      return array($keypath[$id] => $val);
    } else {
      return array($keypath[$id] => Admin_Config::confRecRep($keypath, $val, $id+1));
    }
  }

}

 ?>
