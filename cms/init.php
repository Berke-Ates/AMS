<?php
	session_start();
  include("cms/modman.php");

  // include all modules
  $modules = array_slice(scandir("cms/modules"),2);
  foreach ($modules as $module) {
    if(!ModMan::check($module,true)){ continue; }
    $config = ModMan::getConfig($module);

    if(!$config->enabled){ continue; }
    if(!ModMan::checkDeps($module, true)){ continue; }

    ModMan::load($module);
  }

  Logger::logJS("hey");
?>
