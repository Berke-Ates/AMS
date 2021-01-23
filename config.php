<?php
	// Database Credentials
	$DBhost = 'localhost';
	$DBuser = 'user';
	$DBpw = 'pw';
	$DBname = 'name';

	// General Config
	$AsyncImageLoad = true; // Will load images asynchronous. That means it will show a loading gif until the image is fully loaded. You will find the gif here: "cms/img/loading.gif".
	$DefaultSite = "home"; // The name of the site that will be shown as default
	$Site404 = "404"; // The name of the site that will be shown on a 404 error
	$AdminSite = "admin"; // When you try to access this site, AMS will automaticly redirect you to the AMS-Admin-Page.

	// Language Manager
	$ActivateLanguageManager = true;
	$AutoRedirectLanguage = false; // Remembers the last language setting and applies it automaticly
	$Languages = ["en"]; // Comma seperated: e.g. ["en","fr","it"]
	$DefaultLanguage = "en";
	$StandardLanguage = "std"; // The column name of the text in the phtml-file. Warning: Editing this will reset the database.
	$LanguageManagerTableName = "content";

	// User Manager
	$ActivateUserManager = true;
	$UserManagerTableName = "users";
	$UserRequireActivation = true;
	$UserDefaultGroup = 0; // The higher, the more access someone has
	$UserAdminGroup = 5; // The Group required to access the admin-panel
	$UserUniqueEmails = true; // Set to false if you don't need emails as login method and vice versa
	$UserUniqueUsernames = true; // At least one of those must be set to true

	// Email Manager
	$ActivateEmailManager = true;
	$mailSenderAddress = "your@mail.com";
?>
