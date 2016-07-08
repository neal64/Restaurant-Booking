<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | LeUpload - Lethe Newsletter Upload Plugin                              |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       1.0                                                      |
# | Last modified 07.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once('config.php');
$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));

/* Remove File */
if($pos=='remfile'){
	$fn = ((!isset($_GET['fn']) || empty($_GET['fn'])) ? 'fn-none':trim($_GET['fn']));
	$file = LEUPLOAD_STORE.DIRECTORY_SEPARATOR.$fn;
	if(file_exists($file)){
		unlink($file);
	}
}
?>