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
$sirius->langFiles[] = "organizations_back.php";
$sirius->loadLanguages();
$lethe_modules[] = array(
						'title'=>organizations_organizations,
						'mod_id'=>'lethe.organizations',
						'sort'=>5,
						'icon'=>'glyphicon glyphicon-book',
						'page'=>'?p=organizations',
						'contents'=>array(
											'pg1'=>array(
														'title'=>organizations_organizations,
														'icon'=>'glyphicon glyphicon-book',
														'page'=>'?p=organizations/organization'
														),
											'pg2'=>array(
														'title'=>organizations_users,
														'icon'=>'glyphicon glyphicon-user',
														'page'=>'?p=organizations/users'
														),
											'pg3'=>array(
														'title'=>organizations_short_codes,
														'icon'=>'glyphicon glyphicon-font',
														'page'=>'?p=organizations/shortcodes'
														)
										)
						);
						
/* Permission Pages */
$LETHE_PERMISSIONS_LIST['organizations/organization/edit'] = organizations_organizations.' &gt; '.letheglobal_edit;
$LETHE_PERMISSIONS_LIST['organizations/users/edit'] = organizations_organizations.' &gt; '.letheglobal_edit_user;
$LETHE_PERMISSIONS_LIST['organizations/users/add'] = organizations_organizations.' &gt; '.letheglobal_add_user;
?>