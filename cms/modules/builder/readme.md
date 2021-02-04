### PHP Functions
Includes all php files in "build/php" on init.

#### `Builder::loadPart($part)`
> `$part::String` The name of the part in the parts folder

Includes the part from "build/parts" and custom parts.

#### `Builder::addPart($name, $path)`
> `$name::String` The name of the part  
`$path::String` The path to the part

Adds the part to the pool of parts. Can be loaded with `Builder::loadPart($part)`.

#### `Builder::loadSite()`
Includes the current site from the "build/sites" folder. The current site is determined by `$_GET['loc']`. Loads the specified homepage if the location is undefined. Loads the specified 404 page if the site could not be found.

#### `Builder::loadJS($user = true)`
> `$user::Boolean` Whether or not it should load the user js as well (site specific and in "assets/js")

Includes all the js files in "assets/js" and as well as the "\*.js.php" files in "assets/js". The "\*.js.php" files will get executed like any other php file and then included as a js. Also includes the site specific js files in the site folder and the js files of the modules.

#### `Builder::loadCSS($user = true)`
> `$user::Boolean` Whether or not it should load the user css as well (site specific and in "assets/css")

Includes all the css files in "assets/css" and as well as the "\*.css.php" files in "assets/css". The "\*.css.php" files will get executed like any other php file and then included as a css. Also includes the site specific css files in the site folder and the css files of the modules.

#### `Builder::loadFonts($user = true)`
> `$user::Boolean` Whether or not it should load the user fonts as well (site specific and in "assets/fonts")

Includes all the fonts in "assets/fonts". Must be a "\*.ttf" or "\*.otf" file.

#### `Builder::getLoc()`
> `$return::String` The name of the current location

Returns the name of the current location for site specific functions (e.g. including a js only on a couple sites). Will return a path instead if a custom location has been defined by `Builder::addLoc($name,$path)`.

#### `Builder::addJS($path)`
> `$path::String` The path to the js file

Allows you to add any js file to the queue. Will be loaded by `Builder::loadJS()`.

#### `Builder::addCSS($path)`
> `$path::String` The path to the css file

Allows you to add any css file to the queue. Will be loaded by `Builder::loadCSS()`.

#### `Builder::addDJS($path)`
> `$path::String` The path to the "\*.js.php" file

Allows you to add any "\*.js.php" file to the queue. Will be loaded by `Builder::loadJS()`.

#### `Builder::addDSS($path)`
> `$path::String` The path to the "\*.css.php" file

Allows you to add any "\*.css.php" file to the queue. Will be loaded by `Builder::loadCSS()`.

#### `Builder::addFont($path)`
> `$path::String` The path to the font file

Allows you to add any font to the queue. Will be loaded by `Builder::loadFonts()`.

#### `Builder::addLoc($name,$path)`
> `$name::String` The location name  
`$path::String` The path to the html/phtml/php

Allows you to add custom locations. These have priority over the sites in the sites folder.

#### `Builder::addBodyClass($class)`
> `$class::String` The class to add to the body tag

Allows you to add classes to the body tag for various css rules (for example a dark theme).

#### `Builder::startHead()`

Starts the head section of the html.

#### `Builder::startBody()`

Closes the head section and starts the body section.

#### `Builder::end()`

Closes the body section and ends the php execution.

#### `Builder::clear()`

Clears all the html that has been generated so far.
