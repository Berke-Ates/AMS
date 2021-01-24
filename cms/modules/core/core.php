<?php

class Core{
    private static $jsArr = [];
    private static $djsArr = [];
    private static $cssArr = [];
    private static $dssArr = [];
    private static $ajaxArr = [];

    public static function addJS($path){
      array_push(Core::$jsArr, $path);
    }

    public static function addCSS($path){
      array_push(Core::$cssArr, $path);
    }

    public static function addDJS($path){
      array_push(Core::$djsArr, $path);
    }

    public static function addDSS($path){
      array_push(Core::$dssArr, $path);
    }

    public static function addAJAX($path){
      array_push(Core::$ajaxArr, $path);
    }

    public static function getJS(){
      return Core::$jsArr;
    }

    public static function getCSS(){
      return Core::$cssArr;
    }

    public static function getDJS(){
      return Core::$djsArr;
    }

    public static function getDSS(){
      return Core::$dssArr;
    }

    public static function getAJAX(){
      return Core::$ajaxArr;
    }

}
?>
