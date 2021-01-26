<?php

class Admin{

  public static function init(){
    $root = ModMan::getRoot("admin");
    Builder::addFont($root . "Quantum.otf");
    Builder::addLoc("admin", $root . "adminpage.phtml");
  }



}
 ?>
