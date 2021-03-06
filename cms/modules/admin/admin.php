<?php
class Admin{

  private static $injectArr = [];

  public static function init(){
    $root = ModMan::getRoot("admin");
    Builder::addLoc("admin", $root . "parts/adminpage.phtml");

    include($root . "admin_user.php");
    include($root . "admin_config.php");
    include($root . "admin_ui.php");

    Admin_User::init();
    Admin_UI::init();
    Admin_Config::init();
  }

  public static function prep(){
    Admin_User::prep();
    Admin_UI::prep();

    Admin::addInjection("admin/parts/config.phtml");
    Admin::addInjection("admin/parts/readme.phtml");
  }

  public static function getLoc(){
    $loc = "";
    if( isset($_GET['admin_loc']) ){ $loc = $_GET['admin_loc']; }
    if( empty($loc) || $loc == "" ){ $loc = "admin/parts/dashboard.phtml"; }
    return $loc;
  }

  public static function isInjectedLoc(){
    foreach(Admin::$injectArr as $in){ if(Admin::getLoc() == $in){ return true; } }
    return false;
  }

  private static function addInjection($path){ array_push(Admin::$injectArr, $path); }

  public static function addTitle($title){ Admin_UI::addTitle($title); }
  public static function addBreadcrumbs($items){ Admin_UI::addBreadcrumbs($items); }
  public static function addCard($title,$icon,$content,$size = ""){ Admin_UI::addCard($title,$icon,$content,$size); }

  public static function checkAccess($name, $write){ Admin_User::checkAccess($name, $write); }
  public static function hasAccessTo($name, $write){ return Admin_User::hasAccessTo($name, $write); }
}
 ?>
