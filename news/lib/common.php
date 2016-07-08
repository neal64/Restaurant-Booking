<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 31.10.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+

/* Organization Settings */
$LETHE_ORG_SETS = array();

/* Permissions */
$LETHE_PERMISSIONS = array();

/* Permissions */
$LETHE_PERMISSIONS_LIST = array();

/* Mail Types */
$LETHE_MAIL_TYPE = array('HTML','Text');

/* Mail Methods */
$LETHE_MAIL_METHOD = array('SMTP','PHPMail','Amazon SES','Mandrill','SendGrid');

/* Amazon SES Host */
# email-smtp.us-east-1.amazonaws.com, email-smtp.us-west-2.amazonaws.com, email-smtp.eu-west-1.amazonaws.com
define('LETHE_AWS_HOST','email-smtp.us-west-2.amazonaws.com');

/* Mailer Engine */
$LETHE_MAIL_ENGINE = array();

/* Connection Secure */
$LETHE_MAIL_SECURE = array('Off','SSL','TLS');

/* Connection Secure */
$LETHE_BOUNCE_ACC = array('POP3','IMAP');

/* After Unsubscribe Actions */
$LETHE_AFTER_UNSUBSCRIBE = array(letheglobal_mark_it_inactive,letheglobal_remove_from_database,letheglobal_move_to_unsubscribe_group);

/* Verification Methods */
$LETHE_VERIFICATION_TYPE = array('Off','Single Opt-in','Double Opt-in');

/* Management Types Disabled 1-2 For Lite*/
$LETHE_MANAGEMENT_TYPE = array('User');

/* Load Types */
$LETHE_LOAD_TYPES = array(letheglobal_all,
						  letheglobal_active,
						  letheglobal_active . ' + '.letheglobal_single_verified,
						  letheglobal_active . ' + '.letheglobal_single_verified . ' + '.letheglobal_double_verified
						  );
						  
/* Organization Set Values */
$LETHE_ORG_SET_VALS = array(
							'org_max_user',
							'org_max_newsletter',
							'org_max_autoresponder',
							'org_max_subscriber',
							'org_max_subscriber_group',
							'org_max_subscribe_form',
							'org_max_blacklist',
							'org_max_template',
							'org_max_daily_limit',
							'org_standby_organization',
							'org_submission_account',
							'org_sender_title',
							'org_reply_mail',
							'org_test_mail',
							'org_timezone',
							'org_after_unsubscribe',
							'org_verification',
							'org_random_load',
							'org_load_type',
							'org_max_shortcode',
							'org_max_disk_quota'
							);
							
/* System Short Codes */
$LETHE_SYSTEM_SHORTCODES = array(
								'NEWSLETTER_LINK[TEXT]'=>letheglobal_newsletter_link,
								'RSS_LINK[TEXT]'=>letheglobal_rss_link,
								'UNSUBSCRIBE_LINK[TEXT]'=>letheglobal_unsubscribe_link,
								'TRACK_LINK[TEXT][URL]'=>letheglobal_link_tracker,
								'ORGANIZATION_NAME'=>letheglobal_organization_name,
								'SUBSCRIBER_NAME'=>letheglobal_subscriber_name,
								'SUBSCRIBER_MAIL'=>letheglobal_subscriber_e_mail,
								'SUBSCRIBER_WEB'=>'Subscriber Web',
								'SUBSCRIBER_PHONE'=>letheglobal_subscriber_phone,
								'SUBSCRIBER_COMPANY'=>letheglobal_subscriber_company,
								'CURR_DATE'=>letheglobal_current_date,
								'CURR_MONTH'=>letheglobal_current_month,
								'CURR_YEAR'=>letheglobal_current_year,
								'VERIFY_LINK[TEXT]'=>letheglobal_can_only_be_used_in_new_subscriptions,
								'LETHE_SAVE_TREE'=>letheglobal_save_trees
								);
								
/* Organization Disk Quota */
$LETHE_ORG_DISK_QUOTA_LIST = array(
								0,
								1048576,
								5242880,
								10485760,
								26214400,
								52428800,
								104857600,
								262144000,
								524288000,
								1073741824,
								2147483648
								);
							  
/* Subscribe Save Areas */
$LETHE_SUBSCRIBE_SAVE_FIELDS = array(
									  'subscriber_full_data'=>letheglobal_full_data_column,
									  'subscriber_name'=>letheglobal_name_column,
									  'subscriber_web'=>letheglobal_web_column,
									  'subscriber_date'=>letheglobal_date_column,
									  'subscriber_phone'=>letheglobal_phone_column,
									  'subscriber_company'=>letheglobal_company_column,
									  'subscriber_mail'=>letheglobal_company_column
									);
									
/* Export / Import File Types */
$LETHE_EXP_IMP_MIMES = array('text/plain','text/csv','application/csv');

/* Import Max File Size */
$LETHE_MAX_IMPORT_FILE_SIZE = 10485760; # 10MB

/* Campaign Status */
$LETHE_CAMPAIGN_STATUS = array(
								array('name'=>letheglobal_pending,'icon'=>'glyphicon glyphicon-time text-primary'),
								array('name'=>letheglobal_sending,'icon'=>'glyphicon glyphicon-send text-warning'),
								array('name'=>letheglobal_stopped,'icon'=>'glyphicon glyphicon-pause text-danger'),
								array('name'=>letheglobal_completed,'icon'=>'glyphicon glyphicon-ok text-success'),
								);
/* Month Name List */				
$LETHE_MONTH_NAMES = array(
	'normal'=>array(null,letheglobal_long_january,
					letheglobal_long_february,
					letheglobal_long_march,
					letheglobal_long_april,
					letheglobal_long_may,
					letheglobal_long_june,
					letheglobal_long_july,
					letheglobal_long_august,
					letheglobal_long_september,
					letheglobal_long_october,
					letheglobal_long_november,
					letheglobal_long_december),
	'short'=>array(null,mb_substr(letheglobal_long_january,0,3,"utf-8"),
					mb_substr(letheglobal_long_february,0,3,"utf-8"),
					mb_substr(letheglobal_long_march,0,3,"utf-8"),
					mb_substr(letheglobal_long_april,0,3,"utf-8"),
					mb_substr(letheglobal_long_may,0,3,"utf-8"),
					mb_substr(letheglobal_long_june,0,3,"utf-8"),
					mb_substr(letheglobal_long_july,0,3,"utf-8"),
					mb_substr(letheglobal_long_august,0,3,"utf-8"),
					mb_substr(letheglobal_long_september,0,3,"utf-8"),
					mb_substr(letheglobal_long_october,0,3,"utf-8"),
					mb_substr(letheglobal_long_november,0,3,"utf-8"),
					mb_substr(letheglobal_long_december,0,3,"utf-8"))
);

/* Weeknames */
$LETHE_WEEK_NAMES = array(
	'normal'=>array(letheglobal_sunday,
					letheglobal_monday,
					letheglobal_tuesday,
					letheglobal_wednesday,
					letheglobal_thursday,
					letheglobal_friday,
					letheglobal_saturday),
	'short'=>array(letheglobal_short_sunday,
					letheglobal_short_monday,
					letheglobal_short_tuesday,
					letheglobal_short_wednesday,
					letheglobal_short_thursday,
					letheglobal_short_friday,
					letheglobal_short_saturday)
);

$LETHE_BOUNCE_TYPES = array(
   'antispam'       => array('remove'=>0,'bounce_type'=>'blocked','name'=>'Antispam')
  ,'autoreply'      => array('remove'=>0,'bounce_type'=>'autoreply','name'=>'Autoreply')
  ,'concurrent'     => array('remove'=>0,'bounce_type'=>'soft','name'=>'Concurrent')
  ,'content_reject' => array('remove'=>0,'bounce_type'=>'soft','name'=>'Content Rejected')
  ,'command_reject' => array('remove'=>1,'bounce_type'=>'hard','name'=>'Command Rejected')
  ,'internal_error' => array('remove'=>0,'bounce_type'=>'temporary','name'=>'Internal Error')
  ,'defer'          => array('remove'=>0,'bounce_type'=>'soft','name'=>'Deferred')
  ,'delayed'        => array('remove'=>0,'bounce_type'=>'temporary','name'=>'Delayed')
  ,'dns_loop'       => array('remove'=>1,'bounce_type'=>'hard','name'=>'DNS Loop')
  ,'dns_unknown'    => array('remove'=>1,'bounce_type'=>'hard','name'=>'DNS Unknown')
  ,'full'           => array('remove'=>0,'bounce_type'=>'soft','name'=>'Mailbox Full')
  ,'inactive'       => array('remove'=>1,'bounce_type'=>'hard','name'=>'Mailbox Inactive')
  ,'latin_only'     => array('remove'=>0,'bounce_type'=>'soft','name'=>'Only Latin')
  ,'other'          => array('remove'=>1,'bounce_type'=>'generic','name'=>'Other')
  ,'oversize'       => array('remove'=>0,'bounce_type'=>'soft','name'=>'Oversize')
  ,'outofoffice'    => array('remove'=>0,'bounce_type'=>'soft','name'=>'Out of Office')
  ,'unknown'        => array('remove'=>1,'bounce_type'=>'hard','name'=>'Unknown')
  ,'unrecognized'   => array('remove'=>0,'bounce_type'=>false,'name'=>'Unrecognized')
  ,'user_reject'    => array('remove'=>1,'bounce_type'=>'hard','name'=>'User Reject')
  ,'warning'        => array('remove'=>0,'bounce_type'=>'soft','name'=>'Warning')
);

$LETHE_BOUNCE_ACTIONS = array(letheglobal_remove,letheglobal_remove_and_add_to_blacklist,letheglobal_move_to_unsubscribe_group);

$LETHE_THEME_LIST = array(
							'lumen'=>'Lumen',
							'cerulean'=>'Cerulean',
							'cosmo'=>'Cosmo',
							'cyborg'=>'Cyborg',
							'darkly'=>'Darkly',
							'flatly'=>'Flatly',
							'journal'=>'Journal',
							'paper'=>'Paper',
							'readable'=>'Readable',
							'sandstone'=>'Sandstone',
							'simplex'=>'Simplex',
							'cerulean'=>'Cerulean',
							'slate'=>'Slate',
							'spacelab'=>'SpaceLab',
							'superhero'=>'Super Hero',
							'united'=>'United',
							'yeti'=>'Yeti'
							);
?>