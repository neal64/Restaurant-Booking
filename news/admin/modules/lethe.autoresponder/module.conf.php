<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 10.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
$sirius->langFiles[] = "autoresponder_back.php";
$sirius->loadLanguages();
$lethe_modules[] = array(
						'title'=>autoresponder_autoresponder,
						'mod_id'=>'lethe.autoresponder',
						'sort'=>2,
						'icon'=>'glyphicon glyphicon-refresh',
						'page'=>'?p=autoresponder',
						'contents'=>array(
											'pg1'=>array(
														'title'=>autoresponder_tasks,
														'icon'=>'glyphicon glyphicon-briefcase',
														'page'=>'?p=autoresponder/tasks'
														),
											'pg2'=>array(
														'title'=>letheglobal_create,
														'icon'=>'glyphicon glyphicon-plus',
														'page'=>'?p=autoresponder/add'
														),
										)
						);

/* Permission Pages */
$LETHE_PERMISSIONS_LIST['autoresponder/edit'] = autoresponder_autoresponder.' &gt; '.letheglobal_edit;
?>