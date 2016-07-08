<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 23.01.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
ob_start();
/* Blacklist Reasons */
$LETHE_BLACKLIST_REASON = array(letheglobal_other,'Bounce','Spam','API');
									
/* Subscribe Field Types */
$LETHE_SUBSCRIBE_FIELD_TYPES = array(
										'text'=>letheglobal_text,
										'email'=>letheglobal_e_mail,
										'phone'=>letheglobal_phone,
										'number'=>letheglobal_number,
										'date'=>letheglobal_date,
										'textarea'=>letheglobal_textarea,
										'select'=>letheglobal_select,
										'checkbox'=>letheglobal_check,
										'radio'=>letheglobal_radio,
										'url'=>'URL',
										'submit'=>letheglobal_submit,
										'recaptcha'=>'reCaptcha',
										'addremove'=>letheglobal_add_remove
									);
									
/* Subscribe Error Outputs */
$LETHE_SUBSCRIBE_ERRORS = array(
									array(letheglobal_form_error,'Incorrect Subscription Form'),
									array(letheglobal_e_mail_record_exists,'Your Mail Already Exists'),
									array(letheglobal_e_mail_banned,'You cannot add your mail, you’ve been banned.'),
									array(letheglobal_subscription_is_stopped,'Subscription is stopped!'),
									array(letheglobal_your_e_mail_successfully_removed,'Your E-Mail Successfully Removed!'),
								);
								
/* Subscribe Form Views */
$LETHE_SUBSCRIBE_FORM_VIEWS = array(subscribers_vertical,subscribers_horizontal,subscribers_table);

/* Subscribe Form Types */
$LETHE_SUBSCRIBE_FORM_TYPES = array(subscribers_form,subscribers_api);

/* Subscribe Form reCaptcha Languages */
$LETHE_SUBSCRIBE_FORM_RECAPTCHA_LANG = array(
												'en'=>'English (US)',
												'en-GB'=>'English (UK)',
												'ar'=>'Arabic',
												'bg'=>'Bulgarian',
												'ca'=>'Catalan',
												'zh-CN'=>'Chinese (Simplified)',
												'zh-TW'=>'Chinese (Traditional)',
												'hr'=>'Croatian',
												'cs'=>'Czech',
												'da'=>'Danish',
												'nl'=>'Dutch',
												'fil'=>'Filipino',
												'fi'=>'Finnish',
												'fr'=>'French',
												'fr-CA'=>'French (Canadian)',
												'de'=>'German',
												'de-AT'=>'German (Austria)',
												'de-CH'=>'German (Switzerland)',
												'el'=>'Greek',
												'iw'=>'Hebrew',
												'hi'=>'Hindi',
												'hu'=>'Hungarian',
												'id'=>'Indonesian',
												'it'=>'Italian',
												'ja'=>'Japanese',
												'ko'=>'Korean',
												'lv'=>'Latvian',
												'lt'=>'Lithuanian',
												'no'=>'Norwegian',
												'fa'=>'Persian',
												'pl'=>'Polish',
												'pt'=>'Portuguese',
												'pt-BR'=>'Portuguese (Brazil)',
												'pt-PT'=>'Portuguese (Portugal)',
												'ro'=>'Romanian',
												'ru'=>'Russian',
												'sr'=>'Serbian',
												'sk'=>'Slovak',
												'sl'=>'Slovenian',
												'es'=>'Spanish',
												'es-419'=>'Spanish (Latin America)',
												'sv'=>'Swedish',
												'th'=>'Thai',
												'tr'=>'Turkish',
												'uk'=>'Ukrainian',
												'vi'=>'Vietnamese'
											);

/* Import 3th Part List */
$LETHE_IMPORT_PART_SOFTWARES = array(
										'wordpress'=>array(
															'name'=>'Wordpress (Users)',
															'table'=>'users',
															'field_name1'=>'display_name',
															'field_name2'=>'',
															'field_email'=>'email'
															),
										'opencart'=>array(
															'name'=>'Opencart (Customers)',
															'table'=>'customer',
															'field_name'=>'firstname',
															'field_name2'=>'lastname',
															'field_email'=>'email'
															),
										'opencart2'=>array(
															'name'=>'Opencart (Users)',
															'table'=>'user',
															'field_name'=>'firstname',
															'field_name2'=>'lastname',
															'field_email'=>'email'
															),
										'prestashop'=>array(
															'name'=>'Prestashop (Customers)',
															'table'=>'customer',
															'field_name'=>'firstname',
															'field_name2'=>'lastname',
															'field_email'=>'email'
															),
										'prestashop2'=>array(
															'name'=>'Prestashop (Employee)',
															'table'=>'employee',
															'field_name'=>'firstname',
															'field_name2'=>'lastname',
															'field_email'=>'email'
															),
										'magento'=>array(
															'name'=>'Magento (Newsletter)',
															'table'=>'newsletter_subscriber',
															'field_name'=>'',
															'field_name2'=>'',
															'field_email'=>'subscriber_email'
															),
										'magento2'=>array(
															'name'=>'Magento (Admin)',
															'table'=>'admin_user',
															'field_name'=>'firstname',
															'field_name2'=>'lastname',
															'field_email'=>'email'
															),
										'oscommerce'=>array(
															'name'=>'osCommerce (Customers)',
															'table'=>'customers',
															'field_name'=>'customers_firstname',
															'field_name2'=>'customers_lastname',
															'field_email'=>'customers_email_address'
															),
										'joomla'=>array(
															'name'=>'Joomla (Users)',
															'table'=>'users',
															'field_name'=>'name',
															'field_name2'=>'',
															'field_email'=>'email'
															),
										'phpbb'=>array(
															'name'=>'phpBB (Users)',
															'table'=>'users',
															'field_name'=>'username',
															'field_name2'=>'',
															'field_email'=>'user_email'
															),
										'lethe'=>array(
															'name'=>'Old Lethe',
															'table'=>'newsletter_subscribers',
															'field_name'=>'sub_name',
															'field_name2'=>'',
															'field_email'=>'sub_mail'
															)
									);
									
/* Import / Export Models */
$LETHE_IMP_EXP_MODELS = array(
										'model1'=>'"Name" <mail@address>',
										'model2'=>'<mail@address>',
										'model3'=>'mail@address',
										'model4'=>'name{SEPARATOR}mail@address',
										'model5'=>'mail@address{SEPARATOR}name{SEPARATOR}surname'
									);
/* Import / Export Separators */
$LETHE_IMP_EXP_SEPARATORS = array(
										'sep1'=>', ('. subscribers_comma .')',
										'sep2'=>'; ('. subscribers_semi_colon .')',
										'sep3'=>'('. subscribers_line_break .')'
									);
									
/* Import Pagination */
$LETHE_IMP_LOAD_PAGE = 500;

/* Export Pagination */
$LETHE_EXP_LOAD_PAGE = 200;

/* CSV Import Max Load */
$LETHE_CSV_LOAD_PAGE = 3000;
?>