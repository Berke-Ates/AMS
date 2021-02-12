<?php
class Admin{

  public static function init(){
    $root = ModMan::getRoot("admin");
    Builder::addLoc("admin", $root . "parts/adminpage.phtml");
    AjaxMan::add("admin_login", "Admin::login");
    AjaxMan::add("admin_logout", "Admin::logout");
    AjaxMan::add("admin_editConfig", "Admin::editConfig");
    AjaxMan::add("admin_setDarkMode", "Admin::setDarkMode");

    /*$conf = ModMan::getConfig("admin");
    foreach($conf->users as $user){
      if($user->ID == 0){
        $user->password = password_hash("admin", PASSWORD_DEFAULT);
      }
    }
    ModMan::setConfig("admin", $conf);*/
  }

  public static function prep(){
    $root = ModMan::getRoot("admin");

    Builder::addFont($root . "assets/Quantum.otf");
    Builder::addPart("admin_start", $root . "parts/start.phtml");
    Builder::addPart("admin_end", $root . "parts/end.phtml");
    Builder::addPart("admin_title", $root . "parts/title.phtml");
    Builder::addPart("admin_breadcrumbs", $root . "parts/breadcrumbs.phtml");
    Builder::addPart("admin_card", $root . "parts/card.phtml");
    Builder::addPart("admin_login", $root . "parts/login.phtml");
    Builder::addPart("admin_footer", $root . "parts/footer.phtml");
    Builder::addPart("admin_navbar", $root . "parts/navbar.phtml");
    Builder::addPart("admin_header", $root . "parts/header.phtml");

    Builder::addCSS("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");
    Builder::addCSS("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css");
    Builder::addCSS($root . "assets/style.css");
    Builder::addCSS($root . "assets/custom.css");
    Builder::addCSS($root . "assets/dark-theme.css");
    Builder::addCSS($root . "assets/darkswitch.css");

    Builder::addJS("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js");
    Builder::addJS("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js");
    Builder::addJS($root . "assets/editableTable.js");
    Builder::addJS($root . "assets/script.js");

    if($_SESSION['admin']['darkmode']){ Builder::addBodyClass("dark-theme"); }
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
        array_push($adConfs, Admin::getConfig($module));
      }
    }
    return $adConfs;
  }

  public static function editConfig(){
    Admin::checkAccess();
    $mod = $_POST["mod"];
    $keypath = json_decode($_POST["path"]);
    $val = json_decode($_POST["val"]);

    $config = ModMan::getConfigAssoc($mod);
    $adConfig = Admin::getConfig($mod);

    $immut = [];
    if(isset($adConfig->immutableKeys)){ $immut = $adConfig->immutableKeys;  }
    if(isset($adConfig->hiddenKeys)){ $immut = array_merge($immut, $adConfig->hiddenKeys);  }

    foreach($keypath as $key){
      if(in_array($key, $immut, true)){
        Logger::log("Tried editing immutable keys: mod: " . $mod . ", key: " . $key,"WARNING","Admin",false);
        AjaxMan::ret(["success" => false, "msg" => "Keypath contains immutable or hidden keys"]);
      }
    }

    $config = array_replace_recursive($config, Admin::confRecRep($keypath, $val));
    ModMan::setConfig($mod, $config);
    AjaxMan::ret(["success" => true, "msg" => "Config edited"]);
  }

  private static function confRecRep($keypath, $val, $id = 0){
    if($id == count($keypath)-1){
      return array($keypath[$id] => $val);
    } else {
      return array($keypath[$id] => Admin::confRecRep($keypath, $val, $id+1));
    }
  }

  public static function addTitle($title){
    global $admin_params;
    $admin_params["title"] = $title;
    Builder::loadPart("admin_title");
  }

  public static function addBreadcrumbs($items){
    global $admin_params;
    $admin_params["items"] = $items;
    Builder::loadPart("admin_breadcrumbs");
  }

  public static function addCard($title,$icon,$content){
    global $admin_params;
    $admin_params["title"] = $title;
    $admin_params["icon"] = $icon;
    $admin_params["content"] = $content;
    Builder::loadPart("admin_card");
  }

  public static function setDarkMode(){
    if($_POST['mode'] == "false"){ $_SESSION['admin']['darkmode'] = false; }
    else { $_SESSION['admin']['darkmode'] = true; }
    AjaxMan::ret("Darkmode saved: " . $_POST['mode']);
  }

  public static function login(){
    $UN = $_POST['username'];
    $PW = $_POST['password'];
    $users = ModMan::getConfig("admin")->users;

    $doubleLoginUser = Admin::getUser();
    if($doubleLoginUser != NULL){
      Logger::log("Double login: Username: " . $doubleLoginUser->username,"WARNING","Admin",false);
      AjaxMan::ret(["success" => false, "msg" => "Already logged in"]);
    }

    foreach($users as $user){
      if($user->username != $UN){ continue; }
      if(!password_verify($PW, $user->password)){
        Logger::log("Unsuccessful login attempt: Username: " . $UN,"WARNING","Admin",false);
        AjaxMan::ret(["success" => false, "msg" => "Wrong password"]);
      }
      $_SESSION['admin']['userID'] = $user->ID;
      Logger::log("Logged in: Username: " . $UN,"INFO","Admin",false);
      AjaxMan::ret(["success" => true, "msg" => "Logged in!"]);
    }

    Logger::log("Unsuccessful login attempt: Username: " . $UN,"WARNING","Admin",false);
    AjaxMan::ret(["success" => false, "msg" => "User not found"]);
  }

  public static function logout(){
    unset($_SESSION['admin']['userID']);
    AjaxMan::ret("Logged out");
  }

  public static function getUser(){
    if(!isset($_SESSION['admin']['userID'])){ return NULL; }
    $users = ModMan::getConfig("admin")->users;
    foreach($users as $user){
      if($user->id != $_SESSION['admin']['userID']){ continue; }
      return $user;
    }
  }

  public static function checkAccess(){
    if(Admin::getUser() == NULL){
      Logger::log("Forbidden access attempt by: " . $_SERVER["REMOTE_ADDR"],"WARNING","Admin",false);
      AjaxMan::ret("Access Denied. Attempt logged.");
    }
  }
}
 ?>
