<?php
class Logger{
  public static function log($text, $level = "INFO", $path = "Unknown", $verbose = false){
    $root = ModMan::getRoot("logger");
    $config =  ModMan::getConfig("logger");

    $text = "[".$path."] " . "[".$level."] " . $text;
    if($config->timestamp){
      $text = "[".date(DATE_RFC822)."] " . $text;
    }

    if($verbose){ echo '<script>console.log("'.$text.'")</script>'; };
    if(!$config->savelog){ return; }

    $log = fopen($root . "logs.txt", "a");
    fwrite($log, "\n". $text);
    fclose($log);
  }
}
?>
