<?php
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 02.06.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */

# General Settings
$LETHE_SETS['lethe_default_lang'] = 'en';
$LETHE_SETS['lethe_default_timezone'] = 'UTC';
$LETHE_SETS['lethe_root_url'] = 'https://lee/lethe/';
$LETHE_SETS['lethe_admin_url'] = 'https://lee/lethe/admin/';
$LETHE_SETS['lethe_debug_mode'] = 0;
$LETHE_SETS['lethe_system_notices'] = 1;
$LETHE_SETS['lethe_sidera_helper'] = 1;
$LETHE_SETS['lethe_theme'] = 'lumen';
$LETHE_SETS['lethe_google_recaptcha_public'] = 'RECAPTCHA_PUBLIC_KEY';
$LETHE_SETS['lethe_google_recaptcha_private'] = 'RECAPTCHA_PRIVATE_KEY';
$LETHE_SETS['lethe_powered_text'] ='PHA+PHNtYWxsPkxldGhlIE5ld3NsZXR0ZXIgJmFtcDsgTWFpbGluZyBTeXN0ZW0gdjIuMCAmY29weTsgMjAxNTwvc21hbGw+PC9wPjxwPjxzbWFsbD5BcnRsYW50aXMgRGVzaWduIFN0dWRpbyA8YSBocmVmPSJodHRwOi8vd3d3LmFydGxhbnRpcy5uZXQvIiB0YXJnZXQ9Il9ibGFuayI+aHR0cDovL3d3dy5hcnRsYW50aXMubmV0LzwvYT48L3A+PHA+TGV0aGUgTWFpbGluZyBTeXN0ZW0gPGEgaHJlZj0iaHR0cDovL3d3dy5uZXdzbGV0aGVyLmNvbS8iIHRhcmdldD0iX2JsYW5rIj5odHRwOi8vd3d3Lm5ld3NsZXRoZXIuY29tLzwvYT48L3NtYWxsPjwvcD4=';
$LETHE_SETS['lethe_save_tree'] ='<img alt="" src="https://lee/lethe/resources/system/savepaper.png" style="float:left; height:50px; margin:0px 10px; width:50px" /><span style="color:green;font-weight:bold;">Save a Tree!</span><br>Please don’t print this e-mail unless you really need to!';
$LETHE_SETS['lethe_save_tree_on'] = 1;
$LETHE_SETS['lethe_license_key'] = 'LICENSE_KEY';


foreach($LETHE_SETS as $k=>$v){if(!defined($k)){define($k,$v);}}
?>