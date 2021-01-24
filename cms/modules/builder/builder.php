<?php
  class Builder{

    public static function loadPart($part){
      $config = ModMan::getConfig("builder");
      $path = "build/parts/" . $part . ".phtml";
      if(!file_exists($path)){
        Logger::log("Part not found: " . $part, "ERROR", "Builder", $config->verbose);
        return;
      }
      include($path);
      Logger::log("Loaded Part: " . $part, "INFO", "Builder", $config->verbose);
    }

    public static function loadSite(){
      $loc = Builder::getLoc();
      $config = ModMan::getConfig("builder");
      if( file_exists("build/sites/". $loc . "/" . $loc .".phtml") ){
        include("build/sites/" . $loc . "/" . $loc . ".phtml");
        Logger::log("Loaded Site: " . $loc, "INFO", "Builder", $config->verbose);
        return;
      } else if( file_exists("build/sites/". $loc . "/" . $loc .".html") ){
        include("build/sites/". $loc . "/" . $loc . ".html");
        Logger::log("Loaded Site: " . $loc, "INFO", "Builder", $config->verbose);
        return;
      }
      Logger::log("Site not found: " . $loc, "ERROR", "Builder", $config->verbose);
    }

    public static function loadJS(){
      // All module js
      foreach (Core::getJS() as $file) {
        echo('<script src="'. $file .'"></script>');
      }

      // All module djs
      foreach (Core::getDJS() as $file) {
        echo('<script jsSrc="'.$file.'">');
        include($file);
        echo('</script>');
      }

      // All in js folder
      foreach (glob("assets/js/*.js") as $file) {
        echo('<script src="'. $file .'"></script>');
      }

      // All in js folder djs
      foreach (glob("assets/js/*.js.php") as $file) {
        echo('<script jsSrc="'.$file.'">');
        include($file);
        echo('</script>');
      }

      // Site specific
      foreach (glob("build/sites/".Builder::getLoc()."/*.js") as $file) {
        echo('<script src="'. $file .'"></script>');
      }

      // Site specific djs
      foreach (glob("build/sites/".Builder::getLoc()."/*.js.php") as $file) {
        echo('<script jsSrc="'.$file.'">');
        include($file);
        echo('</script>');
      }
    }

    public static function loadCSS(){
      // All module css
      foreach(Core::getCSS() as $file){
        echo('<link rel="stylesheet" href="' . $file . '">');
      }

      // All module dss
      foreach(Core::getDSS() as $file){
        echo('<style cssSrc="'.$file.'">');
        include($file);
        echo('</style>');
      }

      // All in css folder
      foreach(glob("assets/css/*.css") as $file){
        echo('<link rel="stylesheet" href="' . $file . '">');
      }

      // All in css-folder dss
      foreach(glob("assets/css/*.css.php") as $file){
        echo('<style cssSrc="'.$file.'">');
        include($file);
        echo('</style>');
      }

      // site specific
      foreach(glob("build/sites/".Builder::getLoc()."/*.css") as $file){
        echo('<link rel="stylesheet" href="' . $file . '">');
      }

      // site specific dss
      foreach(glob("build/sites/".Builder::getLoc()."/*.css.php") as $file){
        echo('<style cssSrc="'.$file.'">');
        include($file);
        echo('</style>');
      }
    }

    public static function loadFonts(){
      foreach(glob("assets/fonts/*.ttf") as $file){
        echo('<style fontSrc="'.$file.'">');
        echo('@font-face{');
        echo('font-family: "' . pathinfo($file)["filename"] . '";');
        echo('src: url("' . $file . '");');
        echo('}');
        echo('</style>');
      }
    }

    public static function getLoc(){
      $config = ModMan::getConfig("builder");
      $loc = "";
      if( isset($_GET['loc']) ){ $loc = $_GET['loc']; }
      if( empty($loc) || $loc == "" ){ $loc = $config->homepage; }
      if($loc == "admin"){
        include("cms/admin/index.phtml");
      } else if(!file_exists("build/sites/". $loc . "/" . $loc .".phtml") && !file_exists("build/sites/". $loc . "/" . $loc .".html")){
        $loc = $config->page404;
      }
      return $loc;
    }

  }
?>
