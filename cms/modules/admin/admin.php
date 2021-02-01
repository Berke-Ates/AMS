<?php
class Admin{

  public static function init(){
    $root = ModMan::getRoot("admin");
    Builder::addLoc("admin", $root . "parts/adminpage.phtml");
  }

  public static function prep(){
    $root = ModMan::getRoot("admin");

    Builder::addFont($root . "assets/Quantum.otf");
    Builder::addPart("admin_start", $root . "parts/start.phtml");
    Builder::addPart("admin_end", $root . "parts/end.phtml");

    Builder::addCSS("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");
    Builder::addCSS("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css");
    Builder::addCSS($root . "assets/style.css");
    Builder::addCSS($root . "assets/custom.css");

    Builder::addJS("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js");
    Builder::addJS("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js");
    Builder::addJS($root . "assets/script.js");
  }

  public static function getLoc(){
    $loc = "";
    if( isset($_GET['admin_loc']) ){ $loc = $_GET['admin_loc']; }
    if( empty($loc) || $loc == "" ){ $loc = "admin/parts/dashboard.phtml"; }
    return $loc;
  }

  public static function getConfig($mod){
    $conf = ModMan::getConfig("admin");
    $BUjson = '{"icon": "fas fa-puzzle-piece", "name": "'.ucfirst($mod).'", "links":[]}';
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
    $linkAdd = '[{"name": "Config", "link": "admin/parts/config.phtml&mod='.$mod.'"}, {"name": "Readme", "link": "admin/parts/readme.phtml&mod='.$mod.'"}]';
    $config->links = array_merge($config->links, json_decode($linkAdd));
    return $config;
  }

  public static function getConfigs(){
    $adConfs = [];
    foreach(ModMan::getModRoots() as $module){ array_push($adConfs, Admin::getConfig($module)); }
    return $adConfs;
  }

}
 ?>
