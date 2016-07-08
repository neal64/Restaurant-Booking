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
$sirius->langFiles[] = "newsletter_back.php";
$sirius->loadLanguages();
$lethe_modules[] = array('title'=>newsletter_newsletter,
						 'mod_id'=>'lethe.newsletter',
						 'sort'=>1,
						 'icon'=>'glyphicon glyphicon-envelope',
						 'page'=>'?p=newsletter',
						 'contents'=>array(
											'pg1'=>array(
														'title'=>newsletter_campaigns,
														'icon'=>'glyphicon glyphicon-send',
														'page'=>'?p=newsletter/campaigns'
														),
											'pg2'=>array(
														'title'=>letheglobal_create,
														'icon'=>'glyphicon glyphicon-plus',
														'page'=>'?p=newsletter/add'
														),
										)
						);
						
/* Permission Pages */
$LETHE_PERMISSIONS_LIST['newsletter/edit'] = newsletter_newsletter.' &gt; '.letheglobal_edit;
$LETHE_PERMISSIONS_LIST['newsletter/reports'] = newsletter_newsletter.' &gt; '.letheglobal_reports;
?>