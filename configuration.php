<?php

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);
error_reporting(0);

$CONF = array();

// The MySQL credentials
$CONF['host'] = 'localhost';
$CONF['user'] = 'root';
$CONF['pass'] = '';
$CONF['name'] = 'rest';


// The Installation URL path
$CONF['installation_path'] = 'http://localhost/rest/';
// The Server path
$CONF['server_path'] = dirname(__FILE__);
// Restaurant Favicon
$CONF['favicon_url'] = $CONF['installation_path'] . 'skin/images/favicon1.png';
// Restaurant Logo
$CONF['logo_url'] = $CONF['installation_path'] . 'skin/images/Restaurant.png';



$CONF['restaurant_tables_image0'] = $CONF['installation_path'] . 'skin/images/book-a-table0.jpg';
// Book a table restaurant image for ROOM #1
// Recommended size in px: 1170x546px 
$CONF['restaurant_tables_image'] = $CONF['installation_path'] . 'skin/images/book-a-table.jpg';
// Book a table restaurant image for ROOM #2
// Recommended size in px: 1170x546px 
$CONF['restaurant_tables_image2'] = $CONF['installation_path'] . 'skin/images/book-a-table2.jpg';
// Book a table restaurant image for ROOM #3
// Recommended size in px: 1170x546px 
$CONF['restaurant_tables_image3'] = $CONF['installation_path'] . 'skin/images/book-a-table3.jpg';
// Book a table restaurant image for ROOM #4
// Recommended size in px: 1170x546px 
$CONF['restaurant_tables_image4'] = $CONF['installation_path'] . 'skin/images/book-a-table4.jpg';

$CONF['restaurant_tables_image5'] = $CONF['installation_path'] . 'skin/images/book-a-table5.jpg';

$CONF['restaurant_tables_image6'] = $CONF['installation_path'] . 'skin/images/book-a-table6.jpg';


//Base connexion
$con = mysqli_connect($CONF["host"], $CONF["user"], $CONF["pass"]) or trigger_error($lang['db_imposible_to_connect'] . mysqli_error($con));
mysqli_select_db($con, $CONF["name"]) or trigger_error($lang['db_imposible_to_change_the_db'] . mysqli_error($con));


//get site functions
require_once('system/functions.php');

// Facebook Login configuration - [This feature is COMING SOON] - Don't uncomment these lines
// Create our Application instance (replace this with your appId and secret).
//require('system/facebook/facebook.php');
/*$facebook = new Facebook(array(
  'appId'  => '1486470914967804',
  'secret' => 'a389d1b41f568605e5f1700d687bd5d9',
));*/

// Default value of error
$_SESSION['error'] = "";

?>