<?php include("cms/php/init.php");?>
<?php loadCore("locationDetector");?>

<!DOCTYPE html>
<html>
	<head>
		<?php loadPart("header"); ?>
		<?php loadCore("cssLoader"); ?>
	</head>
	<body>
		<?php loadPart("topbar"); ?>
		<?php loadCore("siteRedirector"); ?>
		<?php loadPart("footer"); ?>
		<?php loadPart("js"); ?>
		<?php loadCore("jsLoader"); ?>
	</body>
</html>
