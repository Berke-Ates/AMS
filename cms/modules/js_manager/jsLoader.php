<?php
  // For site specific stuff
  echo('<script>var CMSloc = "' . $CMSloc . '";</script>');

  // Core
  global $AsyncImageLoad;
  if($AsyncImageLoad){
    echo('<script src="cms/js/ail.js"></script>');
  }

  global $ActivateUserManager;
  if($ActivateUserManager){
    echo('<script src="cms/js/userHandler.js"></script>');
  }

  global $ActivateLanguageManager;
  if($ActivateLanguageManager){
    echo('<script src="cms/js/languageHandler.js"></script>');
  }

  echo('<script src="cms/js/ajaxHandler.js"></script>');
  echo('<script src="cms/js/ams.js"></script>');

  // All in js-folder
  foreach (glob("assets/js/*.js") as $file) {
    echo('<script src="'. $file .'"></script>');
  }

  // Site specific
  foreach (glob("build/sites/".$CMSloc."/*.js") as $file) {
    echo('<script src="'. $file .'"></script>');
  }

  global $CMSjsLogs;
  echo($CMSjsLogs);
?>
