<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 19.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
$engine_path = LETHE_ENGINES;
$engine_list = array('phpmailer','sendgrid','swiftmailer');
foreach($engine_list as $ek=>$ev){
	if(file_exists($engine_path.'/'. $ev .'/engine.conf.php')){
		include_once($engine_path.'/'. $ev .'/engine.conf.php');
	}
}
/* 	if ($handle = opendir($engine_path)) {
		$blacklist = array('.', '..','_inactive');
		while (false !== ($file = readdir($handle))) {
			if (!in_array($file, $blacklist)) {
				if(file_exists($engine_path.'/'. $file .'/engine.conf.php')){
					include_once($engine_path.'/'. $file .'/engine.conf.php');
				}
			}
		}
		closedir($handle);
	} */
//aasort(&$LETHE_MAIL_ENGINE, 'sort');
?>