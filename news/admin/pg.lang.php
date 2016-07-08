<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 17.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lethe.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');

$l = ((!isset($_GET['l']) || empty($_GET['l'])) ? DEFAULT_LANG:trim($_GET['l']));
$p = ((!isset($_GET['p']) || empty($_GET['p'])) ? 'dashboard':trim($_GET['p']));

	/* Check Lang Avability */
	if(!array_key_exists($l,$SLNG_LIST)){
		$l=DEFAULT_LANG;
	}

	/* Create Cookie */
	$sessionTime=time() + (10 * 365 * 24 * 60 * 60);
	$letheCookie = new sessionMaster;
	$letheCookie->sesName = "slang";
	$letheCookie->sesVal = $l;
	$letheCookie->sesTime = $sessionTime;
	$letheCookie->sessMaster();
	$myconn->close();
	header('Location: index.php?p='.$p);
	die();
?>