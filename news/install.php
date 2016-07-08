<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 14.03.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
include_once("lib/functions.php");
include_once("lib/lethe.class.php");
include_once('admin/classes/class.chronos.php');

define('LETHE',dirname(__FILE__));

# Load Defaults
define('LETHE_VERSION','2.0');
define('DEFAULT_LANG','en'); # Lethe Installation Language
include_once("admin/language/sirius_conf.php");
$sirius->langFiles[] = "organizations_back.php";
$sirius->langFiles[] = "settings_back.php";
$sirius->langFiles[] = "settings_back.php";
$sirius->langFiles[] = "subscribers_back.php";
$sirius->loadLanguages(); # Load Globals
define('lethe_sidera_helper',1);
define('SIDERA_HELPER_URL','https://poin.tips/p/artlantis/');
include_once('lib/common.php');

$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));
$install = ((!isset($_GET['install']) || empty($_GET['install'])) ? false:true);

# XMLHTTP Tests
if($pos=='DBTEST'){

	$getErr = 0;
	if(!isset($_POST['db_host']) || empty($_POST['db_host'])){$getErr++;}else{$db_host = trim($_POST['db_host']);}
	if(!isset($_POST['db_name']) || empty($_POST['db_name'])){$getErr++;}else{$db_name = trim($_POST['db_name']);}
	if(!isset($_POST['db_login']) || empty($_POST['db_login'])){$getErr++;}else{$db_login = trim($_POST['db_login']);}
	if(!isset($_POST['db_pass']) || empty($_POST['db_pass'])){$db_pass='';}else{$db_pass = trim($_POST['db_pass']);}
	
	if($getErr!=0){die('NO');}else{
		
		$myconns = new mysqli($db_host,$db_login,$db_pass,$db_name) or die('NO');
		die('OK');
		
	}
	
}

# Install
if($install){
	echo('<script>$("#myLethe").attr("disabled",false);</script>');
	# Connect to DB
	$getErr = 0;
	if(!isset($_POST['db_host']) || empty($_POST['db_host'])){$getErr++;}else{$db_host = trim($_POST['db_host']);}
	if(!isset($_POST['db_name']) || empty($_POST['db_name'])){$getErr++;}else{$db_name = trim($_POST['db_name']);}
	if(!isset($_POST['db_login']) || empty($_POST['db_login'])){$getErr++;}else{$db_login = trim($_POST['db_login']);}
	if(!isset($_POST['db_pass']) || empty($_POST['db_pass'])){$db_pass='';}else{$db_pass = trim($_POST['db_pass']);}
	if(!isset($_POST['db_prefix']) || empty($_POST['db_prefix'])){$db_prefix='lethe_';}else{$db_prefix = trim($_POST['db_prefix']);}
	if($getErr!=0){die('<div class="alert alert-danger">Database Connection Error!</div>');}else{
		$myconn = new mysqli($db_host,$db_login,$db_pass,$db_name) or die('<div class="alert alert-danger">Database Connection Error!</div>');
	}
	
	$errors = '';
	define('db_table_pref',$db_prefix);
	define('set_org_id',1);
	define('LETHE_RESOURCE',LETHE.DIRECTORY_SEPARATOR.'resources'); # Lethe Resource Directory
	include_once('admin/modules/lethe.subscribers/mod.common.php');
	
	# Create Tables
	# Blacklist
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."blacklist` (
					`ID` bigint(15) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `ipAddr` varchar(255) NOT NULL,
					  `reasons` int(11) NOT NULL,
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Blacklist Table Could Not Be Created!','danger'));
	# Campaigns
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."campaigns` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `UID` int(11) NOT NULL,
					  `subject` varchar(255) NOT NULL,
					  `details` text NOT NULL,
					  `alt_details` text,
					  `launch_date` datetime NOT NULL,
					  `attach` varchar(255) DEFAULT NULL,
					  `webOpt` tinyint(2) NOT NULL DEFAULT '0',
					  `campaign_key` varchar(50) DEFAULT NULL,
					  `campaign_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Newsletter 1=Autoresponder',
					  `campaign_pos` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Pending, 1=Sending, 2=Stopped, 3=Completed',
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `campaign_sender_title` varchar(255) NOT NULL,
					  `campaign_reply_mail` varchar(255) NOT NULL,
					  `campaign_sender_account` int(11) NOT NULL DEFAULT '0'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Campaigns Table Could Not Be Created!','danger'));
	# Campaigns AR
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."campaign_ar` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `CID` int(11) NOT NULL,
					  `ar_type` tinyint(2) NOT NULL COMMENT '0-After Subscription, 1-After Unsubscription, 2-Specific Date, 3-Special Date',
					  `ar_time` smallint(5) NOT NULL DEFAULT '1' COMMENT 'Number as 1 minute, 1hour',
					  `ar_time_type` varchar(30) NOT NULL COMMENT 'MINUTE, HOUR, DAY, MONTH, YEAR',
					  `ar_end_date` datetime NOT NULL,
					  `ar_week_0` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Sunday',
					  `ar_week_1` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Monday',
					  `ar_week_2` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Tuesday',
					  `ar_week_3` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Wednesday',
					  `ar_week_4` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Thursday',
					  `ar_week_5` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Friday',
					  `ar_week_6` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Saturday',
					  `ar_end` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'On/Off'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Autoresponder Table Could Not Be Created!','danger'));
	# Campaigns Groups
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."campaign_groups` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL DEFAULT '0',
					  `CID` int(11) NOT NULL DEFAULT '0',
					  `GID` int(11) NOT NULL DEFAULT '0'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Campaign Group Table Could Not Be Created!','danger'));
	# Chronos
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."chronos` (
					`ID` bigint(20) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `CID` int(11) NOT NULL,
					  `pos` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-In Process, 1-Flag for Remove',
					  `cron_command` tinytext NOT NULL,
					  `launch_date` datetime NOT NULL,
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `SAID` int(11) NOT NULL DEFAULT '0' COMMENT 'Submission Account'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Chronos Table Could Not Be Created!','danger'));
	# Organizations
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."organizations` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `orgTag` varchar(30) NOT NULL,
					  `orgName` varchar(255) NOT NULL,
					  `addDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `billingDate` date NOT NULL,
					  `isActive` tinyint(2) NOT NULL DEFAULT '0',
					  `public_key` varchar(50) NOT NULL,
					  `private_key` varchar(50) NOT NULL,
					  `isPrimary` tinyint(2) NOT NULL DEFAULT '0',
					  `ip_addr` varchar(50) NOT NULL,
					  `api_key` varchar(50) NOT NULL,
					  `daily_sent` int(11) NOT NULL DEFAULT '0',
					  `daily_reset` datetime NOT NULL,
					  `rss_url` varchar(255) NOT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Organization Table Could Not Be Created!','danger'));
	# Organization Settings
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."organization_settings` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL DEFAULT '0',
					  `set_key` varchar(255) NOT NULL,
					  `set_val` text NOT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Organization Settings Table Could Not Be Created!','danger'));
	# Reports
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."reports` (
					`ID` bigint(20) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `CID` int(11) NOT NULL,
					  `pos` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Click, 1=Open, 2=Bounce',
					  `ipAddr` varchar(30) NOT NULL,
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `email` varchar(100) NOT NULL,
					  `hit_cnt` int(11) NOT NULL DEFAULT '0',
					  `bounceType` varchar(50) NOT NULL DEFAULT 'unknown',
					  `extra_info` text NOT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Report Table Could Not Be Created!','danger'));
	# Short Codes
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."short_codes` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL DEFAULT '0',
					  `code_key` varchar(255) NOT NULL,
					  `code_val` varchar(255) NOT NULL,
					  `isSystem` tinyint(2) NOT NULL DEFAULT '0',
					  `UID` int(11) NOT NULL DEFAULT '0'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Short Code Table Could Not Be Created!','danger'));
	# Submission Accounts
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."submission_accounts` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `acc_title` varchar(255) NOT NULL,
					  `daily_limit` int(11) NOT NULL,
					  `daily_sent` int(11) NOT NULL,
					  `daily_reset` datetime NOT NULL,
					  `limit_range` int(11) NOT NULL DEFAULT '1440' COMMENT 'Limit range saved as minute',
					  `send_per_conn` int(11) NOT NULL,
					  `standby_time` int(11) NOT NULL,
					  `systemAcc` tinyint(2) NOT NULL,
					  `isDebug` tinyint(2) NOT NULL,
					  `isActive` tinyint(2) NOT NULL,
					  `from_title` varchar(255) NOT NULL,
					  `from_mail` varchar(100) NOT NULL,
					  `reply_mail` varchar(100) NOT NULL,
					  `test_mail` varchar(100) NOT NULL,
					  `mail_type` tinyint(2) NOT NULL,
					  `send_method` tinyint(2) NOT NULL,
					  `mail_engine` varchar(30) NOT NULL,
					  `smtp_host` varchar(100) NOT NULL,
					  `smtp_port` int(5) NOT NULL,
					  `smtp_user` varchar(100) NOT NULL,
					  `smtp_pass` varchar(100) NOT NULL,
					  `smtp_secure` tinyint(2) NOT NULL DEFAULT '0',
					  `pop3_host` varchar(100) NOT NULL,
					  `pop3_port` int(5) NOT NULL,
					  `pop3_user` varchar(100) NOT NULL,
					  `pop3_pass` varchar(100) NOT NULL,
					  `pop3_secure` tinyint(2) NOT NULL DEFAULT '0',
					  `imap_host` varchar(100) NOT NULL,
					  `imap_port` int(5) NOT NULL,
					  `imap_user` varchar(100) NOT NULL,
					  `imap_pass` varchar(100) NOT NULL,
					  `imap_secure` tinyint(2) NOT NULL DEFAULT '0',
					  `smtp_auth` tinyint(2) NOT NULL,
					  `bounce_acc` tinyint(2) NOT NULL,
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `aws_access_key` varchar(100) DEFAULT NULL,
					  `aws_secret_key` varchar(100) DEFAULT NULL,
					  `account_id` varchar(50) NOT NULL,
					  `dkim_active` tinyint(2) NOT NULL DEFAULT '0',
					  `dkim_domain` varchar(255) DEFAULT NULL,
					  `dkim_private` text,
					  `dkim_selector` varchar(255) DEFAULT NULL,
					  `dkim_passphrase` varchar(255) DEFAULT NULL,
					  `bounce_actions` text NOT NULL,
					  `mandrill_user` varchar(255) DEFAULT NULL,
					  `mandrill_key` varchar(255) DEFAULT NULL,
					  `sendgrid_user` varchar(100) DEFAULT NULL,
					  `sendgrid_pass` varchar(100) DEFAULT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Submission Account Table Could Not Be Created!','danger'));
	# Subscribers
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."subscribers` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) DEFAULT NULL,
					  `GID` int(11) DEFAULT NULL,
					  `subscriber_name` varchar(255) DEFAULT NULL,
					  `subscriber_mail` varchar(50) DEFAULT NULL,
					  `subscriber_web` varchar(255) DEFAULT NULL,
					  `subscriber_date` datetime DEFAULT NULL,
					  `subscriber_phone` varchar(50) DEFAULT NULL,
					  `subscriber_company` varchar(255) DEFAULT NULL,
					  `subscriber_full_data` text,
					  `subscriber_active` tinyint(2) DEFAULT NULL,
					  `subscriber_verify` tinyint(2) DEFAULT NULL,
					  `subscriber_key` varchar(50) DEFAULT NULL,
					  `add_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
					  `ip_addr` varchar(20) DEFAULT NULL,
					  `subscriber_verify_key` varchar(50) NOT NULL,
					  `subscriber_verify_sent_interval` datetime NOT NULL,
					  `local_country` varchar(30) NOT NULL DEFAULT 'N/A',
					  `local_country_code` varchar(5) NOT NULL DEFAULT 'N/A',
					  `local_city` varchar(30) NOT NULL DEFAULT 'N/A',
					  `local_region` varchar(30) NOT NULL DEFAULT 'N/A',
					  `local_region_code` varchar(5) NOT NULL DEFAULT 'N/A'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Subscribers Table Could Not Be Created!','danger'));
	# Subscriber Groups
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."subscriber_groups` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `UID` int(11) NOT NULL,
					  `group_name` varchar(255) NOT NULL,
					  `isUnsubscribe` tinyint(2) NOT NULL DEFAULT '0',
					  `isUngroup` tinyint(2) NOT NULL DEFAULT '0'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Subscriber Group Table Could Not Be Created!','danger'));
	# Subscribe Forms
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."subscribe_forms` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `form_name` varchar(255) NOT NULL,
					  `form_id` varchar(50) NOT NULL,
					  `form_type` tinyint(2) NOT NULL,
					  `form_success_url` varchar(255) DEFAULT NULL,
					  `form_success_url_text` varchar(255) DEFAULT NULL,
					  `form_success_text` varchar(255) DEFAULT NULL,
					  `form_success_redir` int(11) NOT NULL DEFAULT '0',
					  `form_remove` tinyint(2) NOT NULL DEFAULT '0',
					  `add_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
					  `isSystem` tinyint(2) NOT NULL DEFAULT '0',
					  `form_view` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Vertical, 1=Horizontal, 2=Table',
					  `isDraft` tinyint(2) NOT NULL DEFAULT '1',
					  `include_jquery` tinyint(2) NOT NULL DEFAULT '1',
					  `include_jqueryui` tinyint(2) NOT NULL DEFAULT '1',
					  `form_group` int(11) NOT NULL DEFAULT '0',
					  `form_errors` tinytext NOT NULL,
					  `subscription_stop` tinyint(2) NOT NULL DEFAULT '0',
					  `UID` int(11) NOT NULL DEFAULT '0'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Subscribe Forms Table Could Not Be Created!','danger'));
	# Subscribe Form Fields
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."subscribe_form_fields` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `FID` int(11) NOT NULL,
					  `field_label` varchar(255) NOT NULL,
					  `field_name` varchar(30) NOT NULL,
					  `field_type` varchar(30) NOT NULL,
					  `field_required` tinyint(2) NOT NULL,
					  `field_pattern` varchar(255) DEFAULT NULL,
					  `field_placeholder` varchar(255) DEFAULT NULL,
					  `sorting` int(11) NOT NULL DEFAULT '0',
					  `field_data` varchar(255) DEFAULT NULL,
					  `field_static` tinyint(2) NOT NULL DEFAULT '0',
					  `field_save` varchar(20) NOT NULL,
					  `field_error` varchar(255) DEFAULT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Subscribe Form Fields Table Could Not Be Created!','danger'));
	# Tasks
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."tasks` (
				`ID` bigint(20) NOT NULL AUTO_INCREMENT,
				  `OID` int(11) NOT NULL,
				  `CID` int(11) NOT NULL,
				  `subscriber_mail` varchar(100) NOT NULL,
				  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
				  ,PRIMARY KEY (`ID`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Tasks Table Could Not Be Created!','danger'));
	# Templates
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."templates` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `UID` int(11) NOT NULL,
					  `temp_name` varchar(255) NOT NULL,
					  `temp_contents` longtext NOT NULL,
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `temp_prev` varchar(255) DEFAULT '',
					  `temp_type` varchar(20) NOT NULL DEFAULT 'normal',
					  `isSystem` tinyint(2) NOT NULL DEFAULT '0',
					  `temp_id` varchar(50) DEFAULT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Templates Table Could Not Be Created!','danger'));
	# Unsubscribes
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."unsubscribes` (
					`ID` bigint(20) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `CID` int(11) NOT NULL DEFAULT '0',
					  `subscriber_mail` varchar(100) NOT NULL,
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die(errMod('Unsubscribers Table Could Not Be Created!','danger'));
	# Users
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."users` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL DEFAULT '0',
					  `real_name` varchar(100) NOT NULL,
					  `mail` varchar(100) NOT NULL,
					  `pass` varchar(50) NOT NULL,
					  `auth_mode` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=User, 1=Admin, 2=Super Admin',
					  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `last_login` datetime NOT NULL,
					  `isActive` tinyint(2) NOT NULL DEFAULT '0',
					  `isPrimary` tinyint(2) NOT NULL DEFAULT '0',
					  `session_token` varchar(50) NOT NULL,
					  `session_time` datetime NOT NULL,
					  `private_key` varchar(50) NOT NULL,
					  `public_key` varchar(50) NOT NULL,
					  `user_spec_view` tinyint(2) NOT NULL DEFAULT '0'
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('Users Table Could Not Be Created!','danger'));
	# User Permissions
	$myconn->query("CREATE TABLE IF NOT EXISTS `". $db_prefix ."user_permissions` (
					`ID` int(11) NOT NULL AUTO_INCREMENT,
					  `OID` int(11) NOT NULL,
					  `UID` int(11) NOT NULL,
					  `perm` varchar(255) NOT NULL
					  ,PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;") or die(errMod('User Permissions Table Could Not Be Created!','danger'));

	# System Settings
	if(!isset($_POST['lethe_default_lang']) || empty($_POST['lethe_default_lang'])){$errors.='* Please Choose a Default Language';}
	if(!isset($_POST['lethe_default_timezone']) || empty($_POST['lethe_default_timezone'])){$errors.='* Please Choose a Timezone<br>';}else{
		@date_default_timezone_set(trim($_POST['lethe_default_timezone']));
	}
	if(!isset($_POST['lethe_root_url']) || empty($_POST['lethe_root_url'])){$errors.='* Please Enter Your Lethe URL<br>';}else{
		$_POST['lethe_save_tree_text'] = '<img alt="" src="'. $_POST['lethe_root_url'] .'resources/system/savepaper.png" style="float:left; height:50px; margin:0px 10px; width:50px" /><span style="color:green;font-weight:bold;">Save a Tree!</span><br>Please don’t print this e-mail unless you really need to!';
		define('lethe_root_url',$_POST['lethe_root_url']);
	}
	if(!isset($_POST['lethe_admin_url']) || empty($_POST['lethe_admin_url'])){$errors.='* Please Enter Your Lethe Admin URL<br>';}else{
		define('lethe_admin_url',$_POST['lethe_admin_url']);
	}
	if(!isset($_POST['lethe_theme']) || empty($_POST['lethe_theme'])){$errors.='* Please Choose a Theme<br>';}
	if(!isset($_POST['lethe_license_key']) || empty($_POST['lethe_license_key'])){
		$errors.='* Please Enter a License Key<br>';
	}else{
		if(!_iscurl()){
			$errors.='* cURL extension not active on your server!<br>';
		}else{
			$licenseVerify = curl_get_result('http://www.newslether.com/resources/feeds/lethe.license.php?key='.urlencode(trim($_POST['lethe_license_key'])));
			if($licenseVerify != 'VALID_LICENSE'){
				$errors.='* Invalid License Key<br>';
			}
		}
	}
	if(!isset($_POST['lethe_google_recaptcha_public']) || empty($_POST['lethe_google_recaptcha_public'])){$_POST['lethe_google_recaptcha_public']='RECAPTCHA_PUBLIC_KEY';}
	if(!isset($_POST['lethe_google_recaptcha_private']) || empty($_POST['lethe_google_recaptcha_private'])){$_POST['lethe_google_recaptcha_private']='RECAPTCHA_PRIVATE_KEY';}
	$_POST['lethe_debug_mode']='';
	$_POST['lethe_system_notices']='YES';
	$_POST['lethe_sidera_helper']='YES';
	$_POST['lethe_save_tree_on'] = 'YES';
	
	# Submission Account
	if(cntData("SELECT * FROM ". $db_prefix ."submission_accounts WHERE systemAcc=1")==0){
		$_POST['acc_title'] = '# Server 1'; $_POST['daily_limit'] = 500; $_POST['spec_limit_range'] = 1440; $_POST['send_per_conn'] = 50; $_POST['standby_time'] = 1; $_POST['systemAcc'] = "YES"; $_POST['debug'] = ''; $_POST['active'] = 'YES'; $_POST['from_title'] = 'Test Sender'; $_POST['from_mail'] = 'sender@example.com'; $_POST['reply_mail'] = 'reply@example.com'; $_POST['test_mail'] = 'test@example.com'; $_POST['mail_type'] = 0; $_POST['send_method'] = 0; $_POST['mail_engine'] = 'phpmailer'; $_POST['smtp_host'] = 'mail.example.com'; $_POST['smtp_port'] = 587; $_POST['smtp_user'] = 'sender@example.com'; $_POST['smtp_pass'] = 'TestSMTP'; $_POST['smtp_secure'] = 0; $_POST['pop3_host'] = 'mail.example.com'; $_POST['pop3_port'] = 110; $_POST['pop3_user'] = 'sender@example.com'; $_POST['pop3_pass'] = 'TestSMTP'; $_POST['pop3_secure'] = 0; $_POST['imap_host'] = 'mail.example.com'; $_POST['imap_port'] = 143; $_POST['imap_user'] = 'sender@example.com'; $_POST['imap_pass'] = 'TestSMTP'; $_POST['imap_secure'] = 0; $_POST['smtp_auth'] = ''; $_POST['bounce_acc'] = 0; $_POST['dkimactive'] = '';
		$subAcc = new lethe();
		$subAcc->onInstall = true;
		$subAcc->addSubAccount();
	}else{
		$opSubAcc = $myconn->query("SELECT * FROM ". $db_prefix ."submission_accounts WHERE systemAcc=1");
		if(mysqli_num_rows($opSubAcc)==0){
			$errors.='* Submission Account Error<br>';
		}else{
			$opSubAccRs = $opSubAcc->fetch_assoc();
		}
	}
	
	# Organization
	if(!isset($_POST['org_name']) || empty($_POST['org_name'])){$errors.= '* '. organizations_please_enter_a_organization_name .'<br>';}
	if(!isset($_POST['org_sender_title']) || empty($_POST['org_sender_title'])){$errors.= '* '. organizations_please_enter_a_sender_title .'<br>';}
	if(!isset($_POST['org_reply_mail']) || !mailVal($_POST['org_reply_mail'])){$errors.= '* '. organizations_invalid_reply_mail .'<br>';}
	if(!isset($_POST['org_test_mail']) || !mailVal($_POST['org_test_mail'])){$errors.= '* '. organizations_invalid_test_mail .'<br>';}
	if(!isset($_POST['org_timezone']) || empty($_POST['org_timezone'])){$errors.= '* '. organizations_please_choose_a_timezone .'<br>';}
	if(!isset($_POST['org_after_unsubscribe']) || !is_numeric($_POST['org_after_unsubscribe'])){$errors.= '* '. organizations_please_choose_a_unsubscribe_action .'<br>';}
	if(!isset($_POST['org_verification']) || !is_numeric($_POST['org_verification'])){$errors.= '* '. organizations_please_choose_a_verification_method .'<br>';}
	if(!isset($_POST['org_random_load']) || empty($_POST['org_random_load'])){$_POST['org_random_load']=1;}
	if(!isset($_POST['org_load_type']) || !is_numeric($_POST['org_load_type'])){$errors.= '* '. organizations_please_choose_a_load_type .'<br>';}
	
	# Users
	if(!isset($_POST['usr_name']) || empty($_POST['usr_name'])){
		$errors.='* '. letheglobal_please_enter_a_name .'<br>';
	}
	if(!isset($_POST['usr_mail']) || !mailVal($_POST['usr_mail'])){
		$errors.='* '. letheglobal_invalid_e_mail_address .'<br>';
	}else{
		if(cntData("SELECT ID,mail FROM ". $db_prefix ."users WHERE mail='". mysql_prep($_POST['usr_mail']) ."'")!=0){
			$errors.='* '. letheglobal_e_mail_already_exists .'<br>';
		}
	}
	if(!isset($_POST['usr_pass']) || empty($_POST['usr_pass'])){
		$errors.='* '. letheglobal_please_enter_password .'<br>';
	}else{
		$passLenth = isToo($_POST['usr_pass'],letheglobal_password.' ',5,30);
		if($passLenth!=''){
			$errors.='* '. $passLenth .'<br>';
		}else{
			if(!isset($_POST['usr_pass2']) || ($_POST['usr_pass2']!=$_POST['usr_pass'])){
				$errors.='* '. letheglobal_passwords_mismatch .'<br>';
			}
		}
	}

			
	if($errors==''){
		
		# Update DB File
		$confList = '<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified '. date('m.d.Y') .'                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+';
		$confList .= "
define('db_host','". trim($db_host) ."');
define('db_name','". trim($db_name) ."');
define('db_login','". trim($db_login) ."');
define('db_pass','". trim($db_pass) ."');
define('db_set_name','utf8');
define('db_set_charset','utf8');
define('db_set_collation','utf8_general_ci');
define('db_table_pref','". trim($db_prefix) ."');
?>";
			$pathw = LETHE.DIRECTORY_SEPARATOR.'lib/lethe.config.php';
			if (!file_exists ($pathw) ) {
				@touch ($pathw);
			}
			$conc=@fopen ($pathw,'w');
			if (!$conc) {
				die(errMod('DB Settings Could Not Be Write','danger'));
			}else{
				if (fputs ($conc,$confList) ){
					
				}else{
					die(errMod('DB Settings Could Not Be Write','danger'));
				}
			}
		
		
		# Update Settings
		$setLethe = new lethe();
		$setLethe->onInstall = true;
		$setLethe->letheSettings();
		
		# Organization
		if(cntData("SELECT ID FROM ". $db_prefix ."organizations")==0){
			$orgLethe = new lethe();
			$orgLethe->onInstall = true;
			$orgLethe->addOrganization();
		}
		
		# User
		if(cntData("SELECT ID FROM ". $db_prefix ."users")==0){
			$opOr = $myconn->query("SELECT ID FROM ". $db_prefix ."organizations");
			$opOrRs = $opOr->fetch_assoc();
			
			$usrLethe = new lethe();
			$usrLethe->onInstall = true;
			$usrLethe->isMaster=1;
			$usrLethe->auth_mode=2;
			$usrLethe->isPrimary=1;
			$usrLethe->OID = $opOrRs['ID'];
			$usrLethe->addUser();
		}
		
		# Main Cron
		$letChr = new Crontab();
		$mainCroner = "* * * * * curl -s '". lethe_root_url ."chronos/lethe.php' >/dev/null 2>&1";
		$letChr->addJob($mainCroner);
		
		die('<script>$("#myLethe").attr("disabled",true);</script>'.errMod('Lethe Successfully Installed on Your System!<br>Dont Forget to Remove <strong>install.php</strong> File!','success'));
	}else{
		die('<script>$("#myLethe").attr("disabled",false);</script>'.errMod($errors,'danger'));
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Lethe Newsletter &amp; Mailing System Installation</title>

<!-- styles -->
<link rel="stylesheet" href="admin/bootstrap/dist/css/bootstrap.min.css">
<link href="admin/css/ionCheck/ion.checkRadio.css" rel="stylesheet" type="text/css">
<link href="admin/css/ionCheck/ion.checkRadio.cloudy.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="admin/css/jquery.switchButton.css">
<link rel="stylesheet" href="admin/css/jquery.fancybox.css">
<link rel="stylesheet" href="admin/css/lethe.css">
<link rel="stylesheet" href="admin/bootstrap/dist/css/lumen_bootstrap.min.css">

<!-- scripts -->
<script src="admin/Scripts/jquery-1.11.1.min.js"></script>

<?php 
if(version_compare(PHP_VERSION, '5.4.0')<0){
	echo('<script>alert("Your PHP Version Does Not Meet System Requirements\nYou cannot continue installation!");</script>');
}
?>
</head>
<body>
<div class="getTheme"></div>
<!-- page content -->
<div id="lethe" class="container">
	<div id="lethe-head">
		<a href="http://www.newslether.com/" target="_blank" id="letheLogo">Lethe<span>Mailing System</span></a>
	</div>
	
	<div class="panel panel-default">
	  <div class="panel-body">
		
		<form name="install" id="install" action="javascript:;" method="POST">
		
		<!-- install form start -->
		<div id="carousel" class="carousel slide" data-ride="carousel">
		  <div class="carousel-inner" role="listbox">
			<!-- Welcome -->
			<div class="item active">
				<h3 class="text-primary">Installation</h3><hr>
				<div class="txxs" style="overflow-x:auto; height:300px;">
					<h3>About</h3> <p>Lethe PHP Newsletter &amp; Mailing System is a full-featured newsletter PHP based script which fulfils all subscribers, emails, marketing and newsletter related needs for both personal and business environments.</p> <p>It has robust, efficient and unique features! This is an all-in-one newsletter tool for your site can be configured to behave as desired and it will provide the best experience for your email subscribers at the same time.</p> <p>Lethe works the way you do so you can focus on creating newsletters and giving your website the necessary exposure!</p> <p>Please follow steps for Lethe installation.</p> <h3>Support and Customization</h3> <p>If you have trouble with installation your can contact with our support team. <a href="mailto:contact@artlantis.net">contact@artlantis.net</a></p> <p>Lethe was developed by Artlantis Design Studio, if you want to modify that script you can contact with developer partnership. <a href="mailto:developer@artlantis.net">developer@artlantis.net</a></p> <p>- Official Lethe Site: <a href="http://www.newslether.com" target="_blank">http://www.newslether.com</a></p> <p>- Artlantis Design Studio: <a href="http://www.artlantis.net" target="_blank">http://www.artlantis.net</a></p> <h3>License Summary</h3> <ul> <li>Non-commercial use only</li> <li>Cannot modify source-code for any purpose (cannot create derivative works)</li> <li>Support provided as follows :<br />- Responding to questions or problems regarding the item and its features<br />- Fixing bugs and reported issues<br />- Providing updates to ensure compatibility with new software versions<br /><span style="text-decoration: underline;">* Item support does not include:</span> <br />- Customization and installation services <br />- Support for third party software and plug-ins</li> <li>Parts of the software are provided under separate licenses, as follows: <br />- <a href="http://codecanyon.net/licenses/terms/regular" target="_blank">Envato Codecanyon Regular License</a><br />- <a href="http://codecanyon.net/licenses/terms/extended" target="_blank">Envato Codecanyon Extended License</a></li> </ul> <h3>Terms and Conditions</h3> <ol style="list-style-type: undefined;"> <li>The Regular License grants you, the purchaser, an ongoing, non-exclusive, worldwide license to make use of the digital work (Item) you have selected. Read the rest of this license for the details that apply to your use of the Item, as well as the <a href="http://codecanyon.net/licenses/faq" target="_blank">FAQs</a> (which form part of this license).</li> <li>You are licensed to use the Item to create one single End Product for yourself or for one client (a “single application”), and the End Product can be distributed for Free.</li> <li>An End Product is one of the following things, both requiring an application of skill and effort.<ol style="list-style-type: lower-alpha;"> <li>For an Item that is a template, the End Product is a customised implementation of the Item.</li> <li>For other types of Item, an End Product is a work that incorporates the Item as well as other things, so that it is larger in scope and different in nature than the Item.</li> </ol></li> <li>You can create one End Product for a client, and you can transfer that single End Product to your client for any fee. This license is then transferred to your client.</li> <li>You can make any number of copies of the single End Product, as long as the End Product is distributed for Free.</li> <li>You can modify or manipulate the Item. You can combine the Item with other works and make a derivative work from it. The resulting works are subject to the terms of this license. You can do these things as long as the End Product you then create is one that’s permitted under clause 3.</li> <li>You can’t Sell the End Product, except to one client. (If you or your client want to Sell the End Product, you will need the Extended License.)</li> <li>You can’t re-distribute the Item as stock, in a tool or template, or with source files. You can’t do this with an Item either on its own or bundled with other items, and even if you modify the Item. You can’t re-distribute or make available the Item as-is or with superficial modifications. These things are not allowed even if the re-distribution is for Free.</li> <li>You can’t use the Item in any application allowing an end user to customise a digital or physical product to their specific needs, such as an “on demand”, “made to order” or “build it yourself” application. You can use the Item in this way only if you purchase a separate license for each final product incorporating the Item that is created using the application.</li> <li>Although you can modify the Item and therefore delete unwanted components before creating your single End Product, you can’t extract and use a single component of an Item on a stand-alone basis.</li> <li>You must not permit an end user of the End Product to extract the Item and use it separately from the End Product.</li> <li>You can’t use an Item in a logo, trademark, or service mark.</li> <li>For some Items, a component of the Item will be sourced by the author from elsewhere and different license terms may apply to the component, such as someone else’s license or an open source or creative commons license. If so, the component will be identified by the author in the Item’s description page or in the Item’s downloaded files. The other license will apply to that component instead of this license. This license will apply to the rest of the Item.</li> <li>For some items, a GNU General Public License (GPL) or another open source license applies. The open source license applies in the following ways:<ol style="list-style-type: lower-alpha;"> <li>Some Items, even if entirely created by the author, may be partially subject to the open source license: a ‘split license’ applies. This means that the open source license applies to an extent that’s determined by the open source license terms and the nature of the Item, and this license applies to the rest of the Item.</li> <li>For some Items, the author may have chosen to apply a GPL license to the entire Item. This means that the relevant GPL license will apply to the entire Item instead of this license.</li> </ol></li> <li>You can only use the Item for lawful purposes. Also, if an Item contains an image of a person, even if the Item is model-released you can’t use it in a way that creates a fake identity, implies personal endorsement of a product by the person, or in a way that is defamatory, obscene or demeaning, or in connection with sensitive subjects.</li> <li>Items that contain digital versions of real products, trademarks or other intellectual property owned by others have not been property released. These Items are licensed on the basis of editorial use only. It is your responsibility to consider whether your use of these Items requires a clearance and if so, to obtain that clearance from the intellectual property rights owner.</li> <li>This license applies in conjunction with the <a href="http://codecanyon.net/legal/marketplace" target="_blank">Envato Market Terms</a> for your use of Envato Market. If there is an inconsistency between this license and the Envato Market Terms, this license will apply to the extent necessary to resolve the inconsistency.</li> <li>This license can be terminated if you breach it. If that happens, you must stop making copies of or distributing the End Product until you remove the Item from it.</li> <li>The author of the Item retains ownership of the Item but grants you the license on these terms. This license is between the author of the Item and you. Envato Pty Ltd is not a party to this license or the one giving you the license.</li> </ol> <p>Terms and Conditions was created 15 Feb 2015 via Envato. License referenced by Envato Regular License. If you purchase item with Extended License, <a href="http://codecanyon.net/licenses/terms/extended" target="_blank"><strong>Extended License</strong></a> substances shall be accepted.</p>
				</div>
				
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			<!-- Requirements -->
			<div class="item">
				<h3 class="text-primary">Requirements<span class="text-muted pull-right">STEP 1</span></h3><hr>

				<div class="row"><div class="col-md-5">
				<table class="table table-striped" width="500">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Required</th>
							<th>System</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo(sh('pX6gY1bgOl'));?>PHP Version</td>
							<td>5.4+</td>
							<td>
								<?php echo(getBullets(((version_compare(PHP_VERSION, '5.4.0') >=0) ? 1:0)));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('GAYM2EWrXQ'));?>MySQLi Extension</td>
							<td>Yes</td>
							<td>
								<?php echo(getBullets( ((extension_loaded ('mysqli')) ? 1:0) ));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('maz8jKQgpO'));?>Script Timeout</td>
							<td>allowed to change</td>
							<td>
								<?php 
								$currTimeLimit = @set_time_limit(300);
								$changedTimeLimit = ini_get('max_execution_time');
								echo(getBullets( (($changedTimeLimit==300) ? 1:0) ));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('6mxg6vwg4n'));?>Curl Extension</td>
							<td>Yes</td>
							<td>
								<?php echo(getBullets( (( _iscurl() ) ? 1:0) ));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('KX1M7KaMmV'));?>fopen Extension</td>
							<td>Yes</td>
							<td>
								<?php echo(getBullets( (( function_exists('fopen') ) ? 1:0) ));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('ZVKMZN1MLA'));?>Shell Access</td>
							<td>Yes</td>
							<td>
								<?php //echo(getBullets( (( shell_exec('crontab -l') ) ? 1:0) ));
								echo(getBullets(1));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('2WzrLvZ8m4'));?>Writable Folders</td>
							<td>Yes</td>
							<td>
								<?php 
									$writableList = array(
															LETHE.DIRECTORY_SEPARATOR.'resources',
															LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.config.php',
															LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.sets.php'
														  );
									$isWritable = 0;
									foreach($writableList as $k=>$v){
										if(is_writable($v)){
											$isWritable++;
										}
									}
									echo(getBullets( (( $isWritable==count($writableList) ) ? 1:0) ));
								?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('PzWM4Kerqx'));?>IMAP Open</td>
							<td>Yes</td>
							<td>
								<?php echo(getBullets( (( function_exists('imap_open') ) ? 1:0) ));?>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
<div class="col-md-5">
				<table class="table table-striped" width="500">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Recommended</th>
							<th>System</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo(sh('o9Kg04EgVx'));?>Script Execute Time</td>
							<td>Unlimited</td>
							<td>
								<?php echo(ini_get('max_execution_time'));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('2AwgKjo8WD'));?>Upload Limit</td>
							<td>40M</td>
							<td>
								<?php echo(ini_get('upload_max_filesize'));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('maz8jKQgpO'));?>Post Max Size</td>
							<td>40M</td>
							<td>
								<?php echo(ini_get('post_max_size'));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('maz8jKQgpO'));?>PHP Mail() function</td>
							<td>if you get errors from SMTP</td>
							<td>
								<?php echo(getBullets(((function_exists('mail')) ? 1:0)));?>
							</td>
						</tr>
						<tr>
							<td><?php echo(sh('m0k8vk9gJw'));?>fgetcsv() function</td>
							<td>for Advanced CSV Imports</td>
							<td>
								<?php echo(getBullets(((function_exists('fgetcsv')) ? 1:0)));?>
							</td>
						</tr>
					</tbody>
				</table>
				<span class="help-block">Above settings is not required, Lethe will work as your system settings. But if you want to import large amount datas, your system may block your actions and its will not work properly.</span>
				</div>
				</div>
				
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			<!-- Database -->
			<div class="item">
				<h3 class="text-primary">Database Settings<span class="text-muted pull-right">STEP 2</span></h3><hr>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="db_host"><?php echo(sh('bjdMPn1g5v'));?>Database Host</label>
							<input type="text" name="db_host" id="db_host" class="form-control">
						</div>
						<div class="form-group">
							<label for="db_name"><?php echo(sh('2KRg1D58ep'));?>Database Name</label>
							<input type="text" name="db_name" id="db_name" class="form-control">
						</div>
						<div class="form-group">
							<label for="db_login"><?php echo(sh('O50rAdzgqo'));?>Database Login</label>
							<input type="text" name="db_login" id="db_login" class="form-control">
						</div>
						<div class="form-group">
							<label for="db_pass"><?php echo(sh('1p6Mm4LrRP'));?>Database Password</label>
							<input type="text" name="db_pass" id="db_pass" class="form-control">
						</div>
						<div class="form-group">
							<label for="db_prefix"><?php echo(sh('1vngRN3gmk'));?>Table Prefix</label>
							<input type="text" name="db_prefix" id="db_prefix" class="form-control" placeholder="lethe_">
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-warning" id="dbTester"><span class="dbRes"><span class="glyphicon glyphicon-link"></span></span> Test Connection</button>
						</div>
					</div>
					<script>
						$(document).ready(function(){
							$("#dbTester").click(function(){
								var dbtestBut = $(this);
								$(".dbRes").html('<span class="spin glyphicon glyphicon-refresh"></span>');
								dbtestBut.attr('disabled',true);
								$.ajax({
									url : "install.php?pos=DBTEST",
									type: "POST",
									data : $("#install").serialize(),
									contentType: "application/x-www-form-urlencoded",
									success: function(data, textStatus, jqXHR)
									{
										if(data=='OK'){
											dbtestBut.removeClass('btn-danger btn-warning');
											dbtestBut.addClass('btn-success');
											$(".dbRes").html('<span class="glyphicon glyphicon-ok"></span>');
										}else{
											dbtestBut.removeClass('btn-success btn-warning');
											dbtestBut.addClass('btn-danger');
											$(".dbRes").html('<span class="glyphicon glyphicon-remove"></span>');
										}
										dbtestBut.attr('disabled',false);
									},
									error: function (jqXHR, textStatus, errorThrown)
									{
										dbtestBut.removeClass('btn-success btn-warning');
										dbtestBut.addClass('btn-danger');
										$(".dbRes").html('<span class="glyphicon glyphicon-remove"></span>');
										dbtestBut.attr('disabled',false);
									}
								});
							});
						});
					</script>
				</div>
				
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			<!-- System Settings -->
			<div class="item">
				<h3 class="text-primary">System Settings<span class="text-muted pull-right">STEP 3</span></h3><hr>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="lethe_default_lang"><?php echo(sh('ZlPryzmM0A').settings_default_language);?></label>
							<select name="lethe_default_lang" id="lethe_default_lang" class="form-control autoWidth">
								<?php foreach($SLNG_LIST as $k=>$v){
									echo('<option value="'. $k .'">'. showIn($v['sname'],'page') .'</option>');
								}?>
							</select>
						</div>
						<div class="form-group">
							<label for="lethe_default_timezone"><?php echo(sh('Y3lrxevM75').settings_default_timezone);?></label>
							<select name="lethe_default_timezone" id="lethe_default_timezone" class="form-control autoWidth">
								<?php 
								$tzones = timezone_list();
								foreach($tzones as $k=>$v){echo('<option value="'. $k .'"'. ((isseter('org_timezone')) ? formSelector($_POST['org_timezone'],$k,0):'') .'>'. $v .'</option>');}?>
							</select>
						</div>
						<div class="form-group">
							<label for="lethe_theme"><?php echo(sh('1vngRNdgmk').settings_default_theme);?></label>
							<select name="lethe_theme" id="lethe_theme" class="form-control autoWidth">
								<?php 
								foreach($LETHE_THEME_LIST as $k=>$v){echo('<option value="'. $k .'"'. ((isseter('lethe_theme')) ? formSelector($_POST['lethe_theme'],$k,0):'') .'>'. $v .'</option>');}?>
							</select>
						</div>
						<div class="form-group">
							<label for="lethe_root_url"><?php echo(sh('pX6gY14gOl'));?>Lethe URL</label>
							<input type="url" value="<?php echo(relDocs(LETHE));?>/" name="lethe_root_url" id="lethe_root_url" class="form-control" placeholder="http://www.example.com/lethe/">
							<span class="help-block"><small>Change if its incorrect. e.g. http://www.example.com/lethe/</small></span>
						</div>
						<div class="form-group">
							<label for="lethe_admin_url"><?php echo(sh('GAYM2EmrXQ'));?>Lethe Admin URL</label>
							<input type="url" name="lethe_admin_url" id="lethe_admin_url" value="<?php echo(relDocs(LETHE));?>/admin/" class="form-control" placeholder="http://www.example.com/lethe/admin/">
							<span class="help-block"><small>Change if its incorrect. e.g. http://www.example.com/lethe/admin/</small></span>
						</div>
						<div class="form-group">
							<label for="lethe_save_tree_on"><?php echo(sh('maz8jKjgpO').settings_save_tree_on);?></label>
							<div>
							<input type="checkbox" name="lethe_save_tree_on" id="lethe_save_tree_on" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($_POST['lethe_save_tree_on']) && $_POST['lethe_save_tree_on']=='YES') ? ' checked':''));?>>
							</div>
						</div>
						<div class="form-group">
							<label for="lethe_google_recaptcha_public"><?php echo(sh('KX1M7K1MmV').'Google reCaptcha Public Key');?></label>
							<input type="text" name="lethe_google_recaptcha_public" id="lethe_google_recaptcha_public" value="<?php echo(((isseter('lethe_google_recaptcha_public')) ? showIn($_POST['lethe_google_recaptcha_public'],'input'):''));?>" class="form-control autoWidth" size="50">
						</div>
						<div class="form-group">
							<label for="lethe_google_recaptcha_private"><?php echo(sh('KX1M7K1MmV').'Google reCaptcha Private Key');?></label>
							<input type="text" name="lethe_google_recaptcha_private" id="lethe_google_recaptcha_private" value="<?php echo(((isseter('lethe_google_recaptcha_private')) ? showIn($_POST['lethe_google_recaptcha_private'],'input'):''));?>" class="form-control autoWidth" size="50">
						</div>
						<div class="form-group">
							<label for="lethe_license_key"><?php echo(sh('VPGMkzEra7').'License Key');?></label>
							<input type="password" name="lethe_license_key" id="lethe_license_key" value="<?php echo(((isseter('lethe_license_key')) ? showIn($_POST['lethe_license_key'],'input'):''));?>" class="form-control autoWidth" size="50">
						</div>
					</div>
				</div>
				
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			<!-- Submission -->
			<div class="item">
			  <h3 class="text-primary">Submission Account<span class="text-muted pull-right">STEP 4</span></h3><hr>
			  
			  <div class="alert alert-info">
				Submission Account will create automatically with default settings<br>
				You must set it with correct informations after logged in.
			  </div>
			  
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			<!-- Organization -->
			<div class="item">
			  <h3 class="text-primary">Organization<span class="text-muted pull-right">STEP 5</span></h3><hr>
			  
				<div role="tabpanel">

				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(organizations_general);?></a></li>
					<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><?php echo(organizations_settings);?></a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
					<!-- GENERAL -->
					<div role="tabpanel" class="tab-pane fade in active" id="general">
						&nbsp;
						<div class="form-group">
							<label for="org_name"><?php echo(sh('G4e9iXSAzy').organizations_organization_name);?></label>
							<input type="text" class="form-control autoWidth" id="org_name" name="org_name" size="40" value="<?php echo((isseter('org_name')) ? showIn($_POST['org_name'],'input'):'');?>">
						</div>
					
					</div>
					<!-- LIMITS -->
					<input type="hidden" name="org_max_disk_quota" value="0">
					<input type="hidden" name="org_max_user" value="0">
					<input type="hidden" name="org_max_newsletter" value="0">
					<input type="hidden" name="org_max_autoresponder" value="0">
					<input type="hidden" name="org_max_subscriber" value="0">
					<input type="hidden" name="org_max_subscriber_group" value="0">
					<input type="hidden" name="org_max_subscribe_form" value="0">
					<input type="hidden" name="org_max_blacklist" value="0">
					<input type="hidden" name="org_max_template" value="0">
					<input type="hidden" name="org_max_shortcode" value="0">
					<input type="hidden" name="org_max_daily_limit" value="0">
					<input type="hidden" name="org_standby_organization" value="0">

					<!-- SETTINGS -->
					<div role="tabpanel" class="tab-pane fade" id="settings">
						&nbsp;
						<input type="hidden" name="org_submission_account" value="1">
						
						<div class="form-group">
							<label for="org_sender_title"><?php echo(sh('uWlPzwExES').organizations_sender_title);?></label>
							<input type="text" class="form-control autoWidth" id="org_sender_title" name="org_sender_title" value="<?php echo((isseter('org_sender_title')) ? showIn($_POST['org_sender_title'],'input'):'');?>">
						</div>
						
						<div class="form-group">
							<label for="org_reply_mail"><?php echo(sh('zIo5YkkltJ').organizations_reply_e_mail);?></label>
							<input type="email" class="form-control autoWidth" id="org_reply_mail" name="org_reply_mail" value="<?php echo((isseter('org_reply_mail')) ? showIn($_POST['org_reply_mail'],'input'):'');?>">
						</div>
						
						<div class="form-group">
							<label for="org_test_mail"><?php echo(sh('bcWtR8fOlU').organizations_test_e_mail);?></label>
							<input type="email" class="form-control autoWidth" id="org_test_mail" name="org_test_mail" value="<?php echo((isseter('org_test_mail')) ? showIn($_POST['org_test_mail'],'input'):'');?>">
						</div>
						
						<div class="form-group">
							<label for="org_timezone"><?php echo(sh('WqUDsK9a6d').organizations_timezone);?></label>
							<select name="org_timezone" id="org_timezone" class="form-control autoWidth">
								<?php 
								$tzones = timezone_list();
								foreach($tzones as $k=>$v){echo('<option value="'. $k .'"'. ((isseter('org_timezone')) ? formSelector($_POST['org_timezone'],$k,0):'') .'>'. $v .'</option>');}?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="org_after_unsubscribe"><?php echo(sh('9AD1ki4Cyo').organizations_after_unsubscribe);?></label>
							<select name="org_after_unsubscribe" id="org_after_unsubscribe" class="form-control autoWidth">
								<?php 
								foreach($LETHE_AFTER_UNSUBSCRIBE as $k=>$v){
									echo('<option value="'. $k .'"'. ((isseter('org_after_unsubscribe',0,1)) ? formSelector($_POST['org_after_unsubscribe'],$k,0):'') .'>'. $v .'</option>');
								}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="org_verification"><?php echo(sh('lTvpd5ypqz').organizations_verification);?></label>
							<select name="org_verification" id="org_verification" class="form-control autoWidth">
								<?php 
								foreach($LETHE_VERIFICATION_TYPE as $k=>$v){
									echo('<option value="'. $k .'"'. ((isseter('org_verification',0,1)) ? formSelector($_POST['org_verification'],$k,0):'') .'>'. $v .'</option>');
								}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="org_random_load"><?php echo(sh('NnedVTtSjA').organizations_random_loader);?></label>
							<div>
							<input type="checkbox" name="org_random_load" id="org_random_load" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($_POST['org_random_load']) && $_POST['org_random_load']=='YES') ? ' checked':''));?>>
							</div>
						</div>
						
						<div class="form-group">
							<label for="org_load_type"><?php echo(sh('07NRNro5bL').organizations_load);?></label>
							<select name="org_load_type" id="org_load_type" class="form-control autoWidth">
								<?php 
								foreach($LETHE_LOAD_TYPES as $k=>$v){
									echo('<option value="'. $k .'"'. ((isseter('org_load_type',0,1)) ? formSelector($_POST['org_load_type'],$k,0):'') .'>'. $v .'</option>');
								}
								?>
							</select>
						</div>
					
					</div>
				  </div>

				</div>
			  
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			<!-- User -->
			<div class="item">
			  <h3 class="text-primary">Administration<span class="text-muted pull-right">STEP 6</span></h3><hr>
			  
				<div class="form-group">
					<label for="usr_name"><?php echo(sh('IiIMsL5qIW').letheglobal_name);?></label>
					<input type="text" name="usr_name" id="usr_name" value="<?php echo(((isset($_POST['usr_name'])) ? showIn($_POST['usr_name'],'input'):''))?>" class="form-control autoWidth">
				</div>
				<div class="form-group">
					<label for="usr_mail"><?php echo(sh('v21Akj0TAh').letheglobal_e_mail);?></label>
					<input type="email" name="usr_mail" id="usr_mail" value="<?php echo(((isset($_POST['usr_mail'])) ? showIn($_POST['usr_mail'],'input'):''))?>" class="form-control autoWidth">
				</div>
				<div class="form-group">
					<label for="usr_pass"><?php echo(sh('XLjyd6v62s').letheglobal_password);?></label>
					<input type="password" name="usr_pass" id="usr_pass" value="" class="form-control autoWidth" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="usr_pass2"><?php echo(sh('9fSmVUpiv3').letheglobal_type_it_again);?></label>
					<input type="password" name="usr_pass2" id="usr_pass2" value="" class="form-control autoWidth" autocomplete="off">
				</div>
			  
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
						<button data-target="#carousel" data-slide="next" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</button>
					</span>
				</div>
			</div>
			
			<!-- Complete -->
			<div class="item">
			  <h3 class="text-primary">Complete<span class="text-muted pull-right">STEP 7</span></h3><hr>
			  
			   <div id="installResult">
			   
			   </div>
			  
				<div class="form-group">
					<button type="submit" name="myLethe" id="myLethe" class="btn btn-success">Install Now!</button>
				</div>
			  
				<div class="carousel-cont"><hr>
					<span class="pull-right">
						<button data-target="#carousel" data-slide="prev" type="button" class="btn btn-success">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</button>
					</span>
				</div>
			</div>
			
		  </div>
		</div>
		<!-- install form end -->		
		
		</form>
		
	  </div>
	</div>
	
	<div class="lethe-footer">
		<p class="text-muted">
			<small>
			Lethe Newsletter &amp; Mailing System &copy; 2015.<br>
			Powered by Artlatis Design Studio
			</small>
		</p>
	</div>
</div>

<!-- body end -->
<script type="text/javascript">
	/* Pointips */
	var sidera_helper_uri = "//poin.tips/p/artlantis/";
	
	$(document).ready(function(){
		/* Layout */
		$('.carousel').carousel({
			interval: false
		});
		/* Change Theme */
		$("#lethe_theme").on('change',function(){
			var selTheme = $(this).val();
			  $(".getTheme").html('<link type="text/css" rel="stylesheet" href="admin/bootstrap/dist/css/'+ selTheme +'_bootstrap.min.css"></link>');
		});
		
		/* Install */
		$("#myLethe").click(function(e){
			e.preventDefault();
			$("#myLethe").attr('disabled','true');
			$("#installResult").html('<span class="spin glyphicon glyphicon-reload"></span> Please wait..');
			$.ajax({
				url : "install.php?install=true",
				type: "POST",
				data : $("#install").serialize(),
				contentType: "application/x-www-form-urlencoded",
				success: function(data, textStatus, jqXHR)
				{
					$("#installResult").html(data);
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					$("#myLethe").attr('disabled','false');
					$("#installResult").html('<div class="alert alert-danger">There an error occurred while sending form!</div>');
				}
			});
			
		});
	});
</script>
<script src="admin/Scripts/jquery-ui.min.js"></script>
<script src="admin/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="admin/Scripts/ion.checkRadio.min.js"></script>
<script src="admin/Scripts/jquery.switchButton.js"></script>
<script src="admin/Scripts/jquery.fancybox.pack.js"></script>
<script src="admin/Scripts/lethe.js"></script>
</body>
</html>