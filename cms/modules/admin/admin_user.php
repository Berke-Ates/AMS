<?php
class Admin_User{

  public static function init(){
    AjaxMan::add("admin_login", "Admin_User::login");
    AjaxMan::add("admin_logout", "Admin_User::logout");
  }

  public static function prep(){
    $root = ModMan::getRoot("admin");
    Builder::addPart("admin_login", $root . "parts/login.phtml");
    Builder::addPart("admin_denied", $root . "parts/accessDenied.phtml");
  }

  public static function addDefaultAdmin(){
    $conf = ModMan::getConfig("admin");
    foreach($conf->users as $user){
      if($user->ID == 0){
        $user->password = password_hash("admin", PASSWORD_DEFAULT);
      }
    }
    ModMan::setConfig("admin", $conf);
  }

  public static function login(){
    $UN = $_POST['username'];
    $PW = $_POST['password'];
    $users = ModMan::getConfig("admin")->users;

    $doubleLoginUser = Admin_User::getUser();
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

  public static function isLoggedIn(){ return Admin_User::getUser() != NULL; }

  public static function checkAccess($name, $write){
    if(!Admin_User::hasAccessTo($name, $write)){
      Logger::log("Forbidden access attempt by: " . $_SERVER["REMOTE_ADDR"],"WARNING","Admin",false);
      AjaxMan::ret(["success" => false, "msg" => "Access Denied. Attempt logged"]);
    }
  }

  public static function hasAccessTo($name, $write){
    $user = Admin_User::getUser();
    if($user == NULL){ return false; }
    if($user->useBlacklist){
      foreach($user->accList as $acc){
        if($acc->name == $name && (!$acc->write || $write)){ return false; }
      }
      return true;
    } else {
      foreach($user->accList as $acc){
        if($acc->name == $name && ($acc->write || !$write)){ return true; }
      }
      return false;
    }
  }

}


 ?>
