<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 10-09-2014 03.28.09                                      |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
ob_start();
//ini_set('zlib.output_compression', 1);
header("Content-Type: text/html; charset=UTF-8");
$errText = null;
define('LETHE_VERSION','2.0');

/* Path Info */
define('LETHE',dirname(__FILE__)); # Lethe Directory
define('LETHE_ADMIN',LETHE.DIRECTORY_SEPARATOR.'admin'); # Lethe Admin Directory
define('LETHE_RESOURCE',LETHE.DIRECTORY_SEPARATOR.'resources'); # Lethe Resource Directory
# define('LETHE_EXPORT',LETHE_ADMIN.DIRECTORY_SEPARATOR.'export'); # Lethe Export / Import Directory
define('LETHE_MODULES',LETHE_ADMIN.DIRECTORY_SEPARATOR.'modules'); # Lethe Module Files
define('LETHE_ENGINES',LETHE_ADMIN.DIRECTORY_SEPARATOR.'engine'); # Lethe Engine Files
define('LETHE_LANGUAGES',LETHE_ADMIN.DIRECTORY_SEPARATOR.'language'); # Lethe Language Files
define('LETHE_KEY_STORE',LETHE_ADMIN.DIRECTORY_SEPARATOR.'lethe.keys'); # Lethe Key Files

/* General Settings */
$LETHE_SETS = array();
include_once('lib/lethe.sets.php'); # Lethe System Settings (Writable)

/* Default Settings */
date_default_timezone_set(lethe_default_timezone); # Lethe System Timezone
define('DEFAULT_LANG',lethe_default_lang); # Lethe Default Language
$cnsLang = ((!isset($_COOKIE["letheLng"]) || is_null($_COOKIE["letheLng"])) ? DEFAULT_LANG:$_COOKIE["letheLng"]);
define('DEMO_MODE',0); # Demo Mode On/Off
define('PRO_MODE',0); # Pro Mode On/Off (If you do not have the pro version it may give errors Default is 0 for normal version)
define('LETHE_POWERED',lethe_powered_text);
$SERVER_MODE = false; # Don't change this value!

/* Sidera Helper */
define('SIDERA_HELPER',1); # Helper Icons (Not Active)
define('SIDERA_HELPER_URL','https://poin.tips/p/artlantis/'); # Helper Pop Url (Not Active)

/* Common URLs */
define('LETHE_API_URI',lethe_root_url.'lethe.api.php');

/* Error Handling */
error_reporting((lethe_debug_mode) ? E_ALL:0);
ini_set('display_errors', (lethe_debug_mode) ? '1':'0');

/* Language Loader */
include_once(LETHE_LANGUAGES.'/sirius_conf.php');
$sirius->loadLanguages(); # Load Globals

/* Common Settings */
include_once('lib/common.php');

/* Engine Loader */
include_once('lib/engine.php');

/* Database Configurations */
include_once('lib/lethe.config.php');

/* Database Connection */
$myconn = new mysqli(db_host,db_login,db_pass,db_name) or die('DB Connection Error');
$myconn->set_charset('utf8');

/* Load Functions */
include_once('lib/functions.php');
?>