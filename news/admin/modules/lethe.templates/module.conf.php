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
$sirius->langFiles[] = "templates_back.php";
$sirius->loadLanguages();
$lethe_modules[] = array(
						'title'=>templates_templates,
						'mod_id'=>'lethe.templates',
						'sort'=>4,
						'icon'=>'glyphicon glyphicon-picture',
						'page'=>'?p=templates',
						'contents'=>array(
											'pg1'=>array(
														'title'=>templates_templates,
														'icon'=>'glyphicon glyphicon-eye-open',
														'page'=>'?p=templates/list'
														),
											'pg2'=>array(
														'title'=>letheglobal_create,
														'icon'=>'glyphicon glyphicon-plus',
														'page'=>'?p=templates/add'
														),
											'pg3'=>array(
														'title'=>letheglobal_loader,
														'icon'=>'glyphicon glyphicon-save-file',
														'page'=>'?p=templates/loader'
														),
										)
						);
						
/* Permission Pages */
$LETHE_PERMISSIONS_LIST['templates/edit'] = templates_templates.' &gt; '.letheglobal_edit;

/* Template Types */
$LETHE_TEMPLATE_TYPES = array(
								'normal'=>templates_normal,
								'verification'=>templates_verification_template,
								'unsubscribe'=>templates_unsubscribe_template,
								'thank'=>templates_thank_you_template,
								'norecord'=>templates_no_record_found_template,
								'erroroccurred'=>templates_error_occurred_template,
								'alreadyverified'=>templates_already_verified_template,
							);
?>