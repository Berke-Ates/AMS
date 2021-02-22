<?php
class Admin_UI{

  public static function init(){
    AjaxMan::add("admin_setDarkMode", "Admin_UI::setDarkMode");
  }

  public static function prep(){
    $root = ModMan::getRoot("admin");

    Builder::addFont($root . "assets/Quantum.otf");
    Builder::addPart("admin_start", $root . "parts/start.phtml");
    Builder::addPart("admin_end", $root . "parts/end.phtml");
    Builder::addPart("admin_title", $root . "parts/title.phtml");
    Builder::addPart("admin_breadcrumbs", $root . "parts/breadcrumbs.phtml");
    Builder::addPart("admin_card", $root . "parts/card.phtml");
    Builder::addPart("admin_footer", $root . "parts/footer.phtml");
    Builder::addPart("admin_navbar", $root . "parts/navbar.phtml");
    Builder::addPart("admin_header", $root . "parts/header.phtml");

    Builder::addCSS("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");
    Builder::addCSS("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css");
    Builder::addCSS($root . "assets/style.css");
    Builder::addCSS($root . "assets/custom.css");
    Builder::addCSS($root . "assets/dark-theme.css");
    Builder::addCSS($root . "assets/darkswitch.css");
    Builder::addCSS($root . "assets/editableTable.css");
    Builder::addCSS($root . "assets/snackbar.min.css");

    Builder::addJS("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js");
    Builder::addJS("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js");
    Builder::addJS($root . "assets/editableTable.js");
    Builder::addJS($root . "assets/snackbar.min.js");
    Builder::addJS($root . "assets/script.js");

    if($_SESSION['admin']['darkmode']){ Builder::addBodyClass("dark-theme"); }
  }

  public static function addTitle($title){
    Builder::setParam("title", $title);
    Builder::loadPart("admin_title");
  }

  public static function addBreadcrumbs($items){
    Builder::setParam("items", $items);
    Builder::loadPart("admin_breadcrumbs");
  }

  public static function addCard($title,$icon,$content,$size = ""){
    Builder::setParam("title", $title);
    Builder::setParam("icon", $icon);
    Builder::setParam("content", $content);
    Builder::setParam("size", $size);
    Builder::loadPart("admin_card");
  }

  public static function setDarkMode(){
    if($_POST['mode'] == "false"){ $_SESSION['admin']['darkmode'] = false; }
    else { $_SESSION['admin']['darkmode'] = true; }
    AjaxMan::ret("Darkmode saved: " . $_POST['mode']);
  }

}
 ?>
