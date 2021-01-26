<?php
  class Builder{
    private static $jsArr = [];
    private static $djsArr = [];
    private static $cssArr = [];
    private static $dssArr = [];
    private static $fontArr = [];
    private static $locArr = [];

    public static function init(){
      foreach(glob("build/php/*.php") as $file){ include($file); }
    }

    public static function addLoc($name, $path){
      if(isset(Builder::$locArr[$name])){
        Logger::log("Location [".$name."] already exists: Old path: [".Builder::$locArr[$name]."], new path: [".$path."]", "ERROR", "Builder", $config->verbose);
        return;
      }
      Builder::$locArr[$name] = $path;
    }

    public static function addJS($path){ array_push(Builder::$jsArr, $path); }
    public static function addCSS($path){ array_push(Builder::$cssArr, $path); }
    public static function addDJS($path){ array_push(Builder::$djsArr, $path); }
    public static function addDSS($path){ array_push(Builder::$dssArr, $path); }
    public static function addFont($path){ array_push(Builder::$fontArr, $path); }

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

    public static function loadJS($user = true){
      foreach(Builder::$jsArr as $file){ Builder::echoJS($file); }
      foreach(Builder::$djsArr as $file){ Builder::echoDJS($file); }

      if(!$user){ return; }
      foreach(glob("assets/js/*.js") as $file){ Builder::echoJS($file); }
      foreach(glob("assets/js/*.js.php") as $file){ Builder::echoDJS($file); }

      $loc = Builder::getLoc();
      if( file_exists($loc) ){ return; }
      foreach(glob("build/sites/".$loc."/*.js") as $file){ Builder::echoJS($file); }
      foreach(glob("build/sites/".$loc."/*.js.php") as $file){ Builder::echoDJS($file); }
    }

    private static function echoJS($path){
      echo('<script src="'. $path .'"></script>');
    }

    private static function echoDJS($path){
      echo('<script jsSrc="'.$path.'">');
      include($path);
      echo('</script>');
    }

    public static function loadCSS($user = true){
      foreach(Builder::$cssArr as $file){ Builder::echoCSS($file); }
      foreach(Builder::$dssArr as $file){ Builder::echoDSS($file); }

      if(!$user){ return; }
      foreach(glob("assets/css/*.css") as $file){ Builder::echoCSS($file); }
      foreach(glob("assets/css/*.css.php") as $file){ Builder::echoDSS($file); }

      $loc = Builder::getLoc();
      if( file_exists($loc) ){ return; }
      foreach(glob("build/sites/".$loc."/*.css") as $file){ Builder::echoCSS($file); }
      foreach(glob("build/sites/".$loc."/*.css.php") as $file){ Builder::echoDSS($file); }
    }

    private static function echoCSS($path){
      echo('<link rel="stylesheet" href="' . $path . '">');
    }

    private static function echoDSS($path){
      echo('<style cssSrc="'.$path.'">');
      include($path);
      echo('</style>');
    }

    public static function loadFonts($user = true){
      foreach(Builder::$fontArr as $file){ Builder::echoFont($file); }
      if(!$user){ return; }
      foreach(glob("assets/fonts/*.ttf") as $file){ Builder::echoFont($file); }
      foreach(glob("assets/fonts/*.otf") as $file){ Builder::echoFont($file); }
    }

    private static function echoFont($path){
      echo('<style fontSrc="'.$path.'">');
      echo('@font-face{');
      echo('font-family: "' . pathinfo($path)["filename"] . '";');
      echo('src: url("' . $path . '");');
      echo('}');
      echo('</style>');
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

    public static function clear(){ ob_end_clean(); }
    public static function startHead(){ echo('<!DOCTYPE html><html><head>'); }
    public static function startBody(){ echo('</head><body>'); }
    public static function end(){ echo('</body></html>'); die(); }

  }
?>
