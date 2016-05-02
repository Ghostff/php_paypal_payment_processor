<?php
	//using a simple trick to redirect or block direct access to this script.
	//Apache RR (.htaccess) can also do this trick more better
	if(!$included){ 
		header('location: 404.html');
		die();
	}
	$host 	= "localhost"; //Host address
	$user 	= "root"; //Name of database user
	$pass 	= ""; //Password for database user
	$name 	= "pp_payment_proc"; //Name of Database
	$mysqli = new mysqli($host, $user, $pass, $name);
	GLOBAL $mysqli;
	if(mysqli_connect_errno()) {
		echo "Connection Failed here: " . mysqli_connect_errno();
		exit();
	}
	const SITE_NAME			= 'mysitename';
	const PAYMETN_TYPE		= 'donate';
	const DEFAULT_LANG 		= "EN";
	const SAVE_PROD_DATA	= true;
	const SAVE_USER_DATA 	= true;
	const MAIL_RECEIPT 		= true;
	define('PP_CANCELED', 'www.'.SITE_NAME.'.com/stats.php?h=');
	define('PP_SUCCESS',  'www.'.SITE_NAME.'.com/stats.php?u=');
?>