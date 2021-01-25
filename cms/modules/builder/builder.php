<?php
  class Builder{

    private static $jsArr = [];
    private static $djsArr = [];
    private static $cssArr = [];
    private static $dssArr = [];
    private static $fontArr = [];
    private static $locArr = [];

    public static function addLoc($name, $path){
      if(isset(Builder::$locArr[$name])){
        Logger::log("Location [".$name."] already exists: Old path: [".Builder::$locArr[$name]."], new path: [".$path."]", "ERROR", "Builder", $config->verbose);
        return;
      }
      Builder::$locArr[$name] = $path;
    }

    public static function addJS($path){
      array_push(Builder::$jsArr, $path);
    }

    public static function addCSS($path){
      array_push(Builder::$cssArr, $path);
    }

    public static function addDJS($path){
      array_push(Builder::$djsArr, $path);
    }

    public static function addDSS($path){
      array_push(Builder::$dssArr, $path);
    }

    public static function addFont($path){
      array_push(Builder::$fontArr, $path);
    }

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
      if( file_exists($loc) ){
        include($loc);
        Logger::log("Loaded Site: " . $loc, "INFO", "Builder", $config->verbose);
        return;
      } else if( file_exists("build/sites/". $loc . "/" . $loc .".phtml") ){
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
      foreach (Builder::$jsArr as $file) {
        echo('<script src="'. $file .'"></script>');
      }

      // All module djs
      foreach (Builder::$djsArr as $file) {
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

      $loc = Builder::getLoc();
      if( file_exists($loc) ){ return; }
      // Site specific
      foreach (glob("build/sites/".$loc."/*.js") as $file) {
        echo('<script src="'. $file .'"></script>');
      }

      // Site specific djs
      foreach (glob("build/sites/".$loc."/*.js.php") as $file) {
        echo('<script jsSrc="'.$file.'">');
        include($file);
        echo('</script>');
      }
    }

    public static function loadCSS(){
      // All module css
      foreach(Builder::$cssArr as $file){
        echo('<link rel="stylesheet" href="' . $file . '">');
      }

      // All module dss
      foreach(Builder::$dssArr as $file){
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

      $loc = Builder::getLoc();
      if( file_exists($loc) ){ return; }
      // site specific
      foreach(glob("build/sites/".$loc."/*.css") as $file){
        echo('<link rel="stylesheet" href="' . $file . '">');
      }

      // site specific dss
      foreach(glob("build/sites/".$loc."/*.css.php") as $file){
        echo('<style cssSrc="'.$file.'">');
        include($file);
        echo('</style>');
      }
    }

    public static function loadFonts(){
      foreach(Builder::$fontArr as $file){
        echo('<style fontSrc="'.$file.'">');
        echo('@font-face{');
        echo('font-family: "' . pathinfo($file)["filename"] . '";');
        echo('src: url("' . $file . '");');
        echo('}');
        echo('</style>');
      }

      foreach(glob("assets/fonts/*.ttf") as $file){
        echo('<style fontSrc="'.$file.'">');
        echo('@font-face{');
        echo('font-family: "' . pathinfo($file)["filename"] . '";');
        echo('src: url("' . $file . '");');
        echo('}');
        echo('</style>');
      }

      foreach(glob("assets/fonts/*.otf") as $file){
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
      if(isset(Builder::$locArr[$loc])){
        return Builder::$locArr[$loc];
      }
      if(!file_exists("build/sites/". $loc . "/" . $loc .".phtml") && !file_exists("build/sites/". $loc . "/" . $loc .".html")){
        $loc = $config->page404;
      }
      return $loc;
    }

  }
?>
