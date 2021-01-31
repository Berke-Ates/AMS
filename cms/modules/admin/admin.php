<?php
class Admin{

  public static function init(){
    $root = ModMan::getRoot("admin");
    Builder::addLoc("admin", $root . "adminpage.phtml");
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
    if( empty($loc) || $loc == "" ){ $loc = "admin/dashboard.phtml"; }
    return $loc;
  }

  public static function getAdminConfigs(){
    $adConfs = [];
    foreach(ModMan::getModRoots() as $module){
      $json = '{"icon": "fas fa-puzzle-piece", "name": "'.ucfirst($module).'", "links":[]}';
      $path = "cms/modules/" . $module . "/admin_config.json";
      if(file_exists($path)){ $json = file_get_contents($path); }
      $config = json_decode($json);
      // TODO: Check if $config valid
      $config->mod = $module;
      $linkAdd = '[{"name": "Config", "link": "admin/config.phtml&mod='.$module.'"}]';
      $config->links = array_merge($config->links, json_decode($linkAdd));
      array_push($adConfs, $config);
    }
    return $adConfs;
  }

}
 ?>
