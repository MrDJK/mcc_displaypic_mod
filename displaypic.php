<?php
//MCC login script//
require (dirname(__file__) .'/config.php');
global $_CONFIG;
define("MONO_ON", 1);
require "class/class_db_".$_CONFIG['driver'].".php";
$db=new database;
$db->configure($_CONFIG['hostname'],
 $_CONFIG['username'],
 $_CONFIG['password'],
 $_CONFIG['database'],
 $_CONFIG['persistent']);
$db->connect();
$c=$db->connection_id;
//End//
$path = 'userdps/';//Change userdps to reflect any changes made in the preferences function.

/* Magic Quotes = BAD!!! Correcting issue if it's enabled. */
if ( get_magic_quotes_gpc () ) {
	if(!function_exists('mqCallback')) {
		function mqCallback(&$var) {
			if(get_magic_quotes_gpc()) 
				$var = stripslashes($var);
		}
		if(count($_GET)) {
			array_walk ($_GET, 'mqCallback');
		}
		if ( count ( $_POST ) ) {
			array_walk ($_POST, 'mqCallback');
		}
	}
}
/* END */
if(!$_GET['view']) 
	exit;

$val = filter_var($_GET['view'], FILTER_SANITIZE_STRING);
$attempt = $db->query("SELECT `mime`, `name` FROM `displaypics` WHERE (`id` = '".$db->escape($val)."')");
if (!$db->num_rows($attempt)) 
	exit;
else {
	$im = $db->fetch_row($try);
	header('Content-Type: '.$im['mime']);
	if($im['mime'] == 'image/jpeg' || $im['mime'] == 'image/jpg') {
		$image = imagecreatefromjpeg($path.$im['name']);
		imagejpeg($image);
	} else if ($im['mime'] == 'image/png') {
		$image = imagecreatefrompng($path.$im['name']);
		imagepng($image);
	}
	imagedestroy($image);
	exit;
}
