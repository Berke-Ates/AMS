### PHP Functions

#### `Logger::log($text, $level="INFO", $path="Unknown", $verbose=false)`
> `$text::String` The text you want to log  
`$level::String` The log-level (INFO, WARNING, ERROR, etc.)  
`$path::String` The place where the error/warning occured  
`$verbose::Boolean` If true outputs the log in the js console

Simply logs the text into the js console as well as a log-file (optional).
