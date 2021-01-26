<?php
	include("cms/init.php");

 	Builder::startHead();
		Builder::loadPart("header");
		Builder::loadCSS();
 		Builder::loadFonts();

 	Builder::startBody();
		Builder::loadPart("topbar");
		Builder::loadSite();
		Builder::loadPart("footer");
		Builder::loadPart("js");
		Builder::loadJS();

 	Builder::end();
?>
