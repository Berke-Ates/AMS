<?php include("cms/init.php");?>

<!DOCTYPE html>
<html>
	<head>
		<?php Builder::loadPart("header"); ?>
		<?php Builder::loadCSS(); ?>
		<?php Builder::loadFonts(); ?>
	</head>
	<body>
		<?php Builder::loadPart("topbar"); ?>
		<?php Builder::loadSite(); ?>
		<?php Builder::loadPart("footer"); ?>
		<?php Builder::loadPart("js"); ?>
		<?php Builder::loadJS(); ?>
	</body>
</html>
