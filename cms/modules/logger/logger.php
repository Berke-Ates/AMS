<?php

class Logger{

  public static function log($text, $level = "INFO", $path = "Unknown"){
    $root = ModMan::getRoot("logger");
    $log = fopen($root . "logs.txt", "a");
    $text = Logger::logify($text,$level,$path);
    fwrite($log, "\n". $text);
    fclose($log);
  }

  public static function logJS($text, $level = "INFO", $path = "Unknown"){
    $text = Logger::logify($text,$level,$path);
    echo '<script>console.log("'.$text.'")</script>';
  }

  private static function logify($text, $level, $path){
    $config =  ModMan::getConfig("logger");
    $text = "[".$path."] " . "[".$level."] " . $text;
    if($config->timestamp){
      $text = "[".date(DATE_RFC822)."] " . $text;
    }
    return $text;
  }

}

?>
