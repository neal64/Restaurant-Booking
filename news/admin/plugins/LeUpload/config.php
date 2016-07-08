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
include_once(dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'lethe.php');

/* Auth Control */
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_auth.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/org_set.php');

/* Permissions */
$LEUPLOAD_MIMES = array();
$LEUPLOAD_MAX_UPL = 2097152; # 2MB

/* Listing */
$LEUPLOAD_PERPAGE_LIST = 7;

/* Upload Area */
define('LEUPLOAD_STORE',set_org_resource);
if(!file_exists(LEUPLOAD_STORE)){die('Organization Folder is Not Exists!');}

/* Storage Size */
define('LEUPLOAD_STORAGE_SIZE',GetDirectorySize(LEUPLOAD_STORE));
define('LEUPLOAD_STORAGE_FILE_COUNT',count(glob(LEUPLOAD_STORE."/*",GLOB_BRACE)));

/* Allowed Image File List */
$LEUPLOAD_UPLOAD_LIST = array(
								'jpg'=>array('image/jpeg'),
								'bmp'=>array('image/x-ms-bmp'),
								'gif'=>array('image/gif'),
								'png'=>array('image/png'),
								'doc'=>array('application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
								'pdf'=>array('application/pdf'),
								'xls'=>array('application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
								'zip'=>array('application/zip'),
								'txt'=>array('text/plain')
							  );
							  
/* Full Doc List */
$LEUPLOAD_INFO_FILES = array_merge(array_keys($LEUPLOAD_UPLOAD_LIST));

/* Load Mime Types */
foreach($LEUPLOAD_UPLOAD_LIST as $k=>$v){
	foreach($v as $b=>$a){
		$LEUPLOAD_MIMES[] = $a;
	}
}
?>