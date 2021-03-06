<?php
class Admin_User{

  public static function init(){
    AjaxMan::add("admin_login", "Admin_User::login");
    AjaxMan::add("admin_logout", "Admin_User::logout");
    AjaxMan::add("admin_resetPW", "Admin_User::resetPW");
    AjaxMan::add("admin_changePW", "Admin_User::changePW");
    AjaxMan::add("admin_delUser", "Admin_User::delUser");
    AjaxMan::add("admin_addUser", "Admin_User::addUser");
    AjaxMan::add("admin_editUser", "Admin_User::editUser");
  }

  public static function prep(){
    $root = ModMan::getRoot("admin");
    Builder::addPart("admin_login", $root . "parts/login.phtml");
    Builder::addPart("admin_pwReset", $root . "parts/pwReset.phtml");
    Builder::addPart("admin_pwChange", $root . "parts/pwChange.phtml");
    Builder::addPart("admin_denied", $root . "parts/accessDenied.phtml");

    Builder::addJS($root . "assets/user.js");
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
    AjaxMan::ret(["success" => false, "msg" => "Username does not exist"]);
  }

  public static function logout(){
    unset($_SESSION['admin']['userID']);
    AjaxMan::ret("Logged out");
  }

  public static function resetPW(){
    $UN = $_POST['username'];
    $conf = ModMan::getConfig("admin");

    foreach($conf->users as $user){
      if($user->username == $UN){
        $key = password_hash(time(), PASSWORD_DEFAULT);
        $user->pwRec = $key;
        ModMan::setConfig("admin", $conf);

        $link = "https://" . $conf->domain . $conf->root . "?loc=admin&admin_loc=passwordChange&US=" . $user->username . "&key=" . $key;
        $msg = '<html><head></head><body>Click on this link to set a new password: <a href="'.$link.'">Change password</a></body></html>';
        $header =
          'MIME-Version: 1.0' . "\r\n" .
          'Content-type: text/html; charset=utf-8' . "\r\n" .
          'From: AMS <AMS@' . $conf->domain . '>'
        ;

        $mailSucc = mail($user->email,"Password recovery",$msg,$header,'-f AMS@' . $conf->domain);
        if(!$mailSucc){
          $errorMessage = error_get_last()['message'];
          Logger::log("Email delivery failed: " . $errorMessage,"ERROR","Admin",false);
          AjaxMan::ret(["success" => false, "msg" => "Email delivery failed"]);
        }

        AjaxMan::ret(["success" => true, "msg" => "Email sent"]);
      }
    }

    AjaxMan::ret(["success" => false, "msg" => "Username does not exist"]);
  }

  public static function changePW(){
    $UN = $_POST['username'];
    $PW = $_POST['pw'];
    $PW2 = $_POST['pw2'];
    $key = $_POST['key'];
    $conf = ModMan::getConfig("admin");

    if($PW != $PW2){ AjaxMan::ret(["success" => false, "msg" => "Passwords don't match"]); }

    foreach($conf->users as $user){
      if($user->username == $UN){
        if($user->pwRec != "" && $user->pwRec == $key){
          $user->password = password_hash($PW, PASSWORD_DEFAULT);
          $user->pwRec = "";
          ModMan::setConfig("admin", $conf);
          AjaxMan::ret(["success" => true, "msg" => "Password changed"]);
        }
        AjaxMan::ret(["success" => false, "msg" => "Key mismatch"]);
      }
    }

    AjaxMan::ret(["success" => false, "msg" => "Username does not exist"]);
  }

  public static function getUser(){
    if(!isset($_SESSION['admin']['userID'])){ return NULL; }
    $users = ModMan::getConfig("admin")->users;
    foreach($users as $user){
      if($user->ID != $_SESSION['admin']['userID']){ continue; }
      return $user;
    }
  }

  public static function delUser(){
    Admin_User::checkAccess("admin",1);
    $id = $_POST['id'];
    $conf = ModMan::getConfig("admin");

    for ($i = 0; $i < count($conf->users); $i++) {
      if($conf->users[$i]->ID == $id){
        unset($conf->users[$i]);
        $conf->users = array_values($conf->users);
        ModMan::setConfig("admin", $conf);
        AjaxMan::ret(["success" => true, "msg" => "User deleted"]);
      }
    }

    AjaxMan::ret(["success" => false, "msg" => "User does not exist"]);
  }

  public static function addUser(){
    Admin_User::checkAccess("admin",1);
    $UN = $_POST['username'];
    $PW = password_hash($_POST['pw'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $conf = ModMan::getConfig("admin");
    $maxID = 0;

    foreach($conf->users as $user){
      $maxID = max($maxID, $user->ID);
      if($user->username != $UN){ continue; }
      AjaxMan::ret(["success" => false, "msg" => "Username already exists"]);
    }

    $maxID++;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ AjaxMan::ret(["success" => false, "msg" => "Please enter a valid email address"]); }

    $newUser = ["ID" => $maxID, "username" => $UN, "email" => $email, "password" => $PW, "pwRec" => "", "useBlacklist" => false, "accList" => []];
    array_push($conf->users, $newUser);
    ModMan::setConfig("admin",$conf);
    AjaxMan::ret(["success" => true, "msg" => "User added", "id" => $maxID]);
  }

  public static function editUser(){
    Admin_User::checkAccess("admin",1);
    $ID = $_POST['id'];
    $UN = $_POST['username'];
    $PW = password_hash($_POST['pw'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $conf = ModMan::getConfig("admin");

    foreach($conf->users as $user){
      if($user->ID == $ID || $user->username != $UN){ continue; }
      AjaxMan::ret(["success" => false, "msg" => "Username already exists"]);
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ AjaxMan::ret(["success" => false, "msg" => "Please enter a valid email address"]); }

    foreach($conf->users as $user){
      if($user->ID == $ID){
        $user->username = $UN;
        $user->password = $PW;
        $user->email = $email;
        ModMan::setConfig("admin",$conf);
        AjaxMan::ret(["success" => true, "msg" => "User edited"]);
      }
    }

    AjaxMan::ret(["success" => false, "msg" => "User does not exist"]);
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
