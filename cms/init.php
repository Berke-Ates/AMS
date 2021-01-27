<?php
	session_start();
  include("cms/modman.php");

  // include all modules
  foreach (ModMan::getModRoots() as $module) {
    if(!ModMan::check($module,true)){ continue; }
    $config = ModMan::getConfig($module);

    if(!$config->enabled){ continue; }
    if(!ModMan::checkDeps($module, true)){ continue; }

    ModMan::load($module);
  }

	ModMan::init();
?>
