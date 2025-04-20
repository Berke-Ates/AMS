<?php

class DBM{
  // The idea behind this database is to store data in this PHP file, which gets included in the main file.
  // This is a simple key-value store that uses an array to store data.
  // When we want to alter the data, we simply modify the array and save the file.
  public static $bucket = array();

  // Function to replace the line containing $bucket in this file with the new data
  public static function save() {
    $bucket = DBM::$bucket;
    $file = __FILE__;
    $content = file_get_contents($file);
    $new_content = preg_replace('/public static \$bucket\s*=\s*array.*?;/s', "public static \$bucket = " . var_export($bucket, true) . ";", $content);
    file_put_contents($file, $new_content);
  }
}

?>
