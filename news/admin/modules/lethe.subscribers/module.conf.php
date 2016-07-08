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
$sirius->langFiles[] = "subscribers_back.php";
$sirius->loadLanguages();
$lethe_modules[] = array(
								'title'=>subscribers_subscribers,
								'mod_id'=>'lethe.subscribers',
								'sort'=>3,
								'icon'=>'glyphicon glyphicon-user',
								'page'=>'?p=subscribers',
								'contents'=>array(
													'pg1'=>array(
																'title'=>subscribers_subscribers,
																'icon'=>'glyphicon glyphicon-user',
																'page'=>'?p=subscribers/subscriber/list'
																),
													'pg2'=>array(
																'title'=>subscribers_groups,
																'icon'=>'glyphicon glyphicon-th-list',
																'page'=>'?p=subscribers/groups'
																),
													'pg3'=>array(
																'title'=>subscribers_add_subscriber,
																'icon'=>'glyphicon glyphicon-plus',
																'page'=>'?p=subscribers/subscriber/add'
																),
													'pg4'=>array(
																'title'=>subscribers_subscribe_forms,
																'icon'=>'glyphicon glyphicon-edit',
																'page'=>'?p=subscribers/forms/list'
																),
													'pg5'=>array(
																'title'=>subscribers_blacklist,
																'icon'=>'glyphicon glyphicon-ban-circle',
																'page'=>'?p=subscribers/blacklist'
																),
													'pg6'=>array(
																'title'=>subscribers_export.' / '.subscribers_import,
																'icon'=>'glyphicon glyphicon-transfer',
																'page'=>'?p=subscribers/exp-imp'
																),
												)
						);
						
/* Permission Pages */
$LETHE_PERMISSIONS_LIST['subscribers/subscriber/edit'] = subscribers_subscribers.' &gt; '.letheglobal_edit;
$LETHE_PERMISSIONS_LIST['subscribers/subscriber/add'] = subscribers_subscribers.' &gt; '.letheglobal_add;
$LETHE_PERMISSIONS_LIST['subscribers/subscriber/list'] = subscribers_subscribers.' &gt; '.letheglobal_list;
$LETHE_PERMISSIONS_LIST['subscribers/forms/add'] = subscribers_subscribe_forms.' &gt; '.letheglobal_add;
$LETHE_PERMISSIONS_LIST['subscribers/forms/edit'] = subscribers_subscribe_forms.' &gt; '.letheglobal_edit;
?>