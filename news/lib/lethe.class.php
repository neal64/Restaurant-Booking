<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 13.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+

class lethe{

	public $OID = 0; # Organization ID
	public $UID = 0; # User ID
	public $ID = 0; # Specific ID
	public $SUBID = 0; # Subscriber ID
	public $OSMID = 0; # Organization Submission Account ID
	public $admin_area = 0; # If Called as 1 All Actions Will Work Only Admin Area
	public $errPrint = ''; # Error Outputs
	public $auth_mode = 0; # Authorization Mode 0-User, 1-Admin, 2-Super Admin
	public $isPrimary = 0; # Primary / System Record Controller
	public $billingDate = 0; # Billing Period (For Lethe PRO)
	public $orgTag = ''; # Organization Tag
	public $public_key = ''; # Organization Public Key
	public $private_key = ''; # Organization Private Key
	public $public_registration = 0; # Front-End Subscribe Actions
	public $isSuccess = 0; # Successfull Actions
	public $isMaster = 0; # System Controls
	public $subscribeData = ''; # Full Subscribe JSON Data
	public $onInstall = false;
	
	/* Submission Data */
					public $sub_from_title = ''; # Submission Account From Title
					public $sub_from_mail = ''; # Submission Account From E-Mail
					public $sub_reply_mail = ''; # Submission Account Reply E-Mail, (Organizations Can Use)
					public $sub_test_mail = ''; # Submission Account Test E-Mail, (Organizations Can Use)
					public $sub_mail_type = ''; # Submission Account Mail Content Type HTML or Text
					public $sub_send_method = ''; # Submission Account Sending Method, SMTP, PHP, AmazonSES etc.
					public $sub_mail_engine = ''; # Submission Account Mail Sender Engine phpMailer, Swiftmail etc.
					public $sub_smtp_host = ''; # Submission Account SMTP Host IP or address
					public $sub_smtp_port = ''; # Submission Account SMTP Port Number
					public $sub_smtp_user = ''; # Submission Account SMTP Username
					public $sub_smtp_pass = ''; # Submission Account SMTP Password
					public $sub_smtp_secure = ''; # Submission Account SMTP Secure Connection Mode; SSL, TLS 
					public $sub_smtp_auth = ''; # Submission Account SMTP Connection Auth Mode
					public $sub_aws_access_key = ''; # Submission Account AmazonSES Access Key
					public $sub_aws_secret_key = ''; # Submission Account AmazonSES Secret Key
					public $sub_mandrill_user = ''; # Submission Account Mandrill APP Username
					public $sub_mandrill_key = ''; # Submission Account Mandrill APP Key
					public $sub_sendgrid_user = ''; # Submission Account SendGrid Username
					public $sub_sendgrid_pass = ''; # Submission Account SendGrid Pass
					public $sub_dkim_active = ''; # Submission Account DKIM Controller, Active / Inactive
					public $sub_dkim_domain = ''; # Submission Account DKIM Domain Information
					public $sub_dkim_private = ''; # Submission Account DKIM Private Key
					public $sub_dkim_selector = ''; # Submission Account DKIM DNS Selector
					public $sub_dkim_passphrase = ''; # Submission Account DKIM Secret Pass For Generated Key
					public $sub_isDebug = 1; # Submission Account Debug Mode On / Off
					
					public $sub_mail_subject = ''; # Submission Account E-Mail Subject
					public $sub_mail_body = ''; # Submission Account E-Mail Body
					public $sub_mail_altbody = ''; # Submission Account E-Mail Alternative Body
					public $sub_mail_extra = ''; # Submission Account E-Mail Body Extra Contents
					public $sub_mail_id = ''; # Submission Account Unique E-Mail ID
					public $sub_mail_attach = ''; # Submission Account E-Mail Attachment
					public $sub_mail_receiver = array(); # Submission Account Receiver Data
					public $sub_success = true; # Submission limit controller, If limit is exceeded lethe_sender will return false
					public $sendingErrors = '';
					
					public $bounceKey = '';
					public $bounceMail = '';
					public $bounceAction = 0; # 0 - Remove, 1 - Remove / Blacklist, 2 - Unsubscribe
					
	/* Reports */
	public $reportCID = 0;
	public $reportPos = 0;
	public $reportIP = '0.0.0.0';
	public $reportMail = null;
	public $reportBounceType = 'unknown';
	public $reportExtraInfo = ''; # Clicked URL
					
	/* Chronos Command */
	public $chronosMin = '*';
	public $chronosHour = '*';
	public $chronosDay = '*';
	public $chronosMonth = '*';
	public $chronosWeek = '*';
	public $chronosCommand = "curl -s";
	public $chronosURL = "";
	
	
	/* General Settings */
	public function letheSettings(){
		
		$this->errPrint = '';
		if(!isset($_POST['lethe_default_lang']) || empty($_POST['lethe_default_lang'])){$this->errPrint.='* Please Choose a Language<br>';}
		if(!isset($_POST['lethe_default_timezone']) || empty($_POST['lethe_default_timezone'])){$this->errPrint.='* Please Choose a Timezone<br>';}
		if(!isset($_POST['lethe_root_url']) || empty($_POST['lethe_root_url'])){$this->errPrint.='* Please Enter Your Lethe URL<br>';}else{
			$letheURL = $_POST['lethe_root_url'];
			$letheURL = ((substr($letheURL,-1)!='/') ? $letheURL.'/':$letheURL);
		}
		if(!isset($_POST['lethe_admin_url']) || empty($_POST['lethe_admin_url'])){$this->errPrint.='* Please Enter Your Lethe Admin URL<br>';}else{
			$letheAURL = $_POST['lethe_admin_url'];
			$letheAURL = ((substr($letheAURL,-1)!='/') ? $letheAURL.'/':$letheAURL);
		}
		if(!isset($_POST['lethe_debug_mode']) || empty($_POST['lethe_debug_mode'])){$lethe_debug_mode=0;}else{$lethe_debug_mode=1;}
		if(!isset($_POST['lethe_system_notices']) || empty($_POST['lethe_system_notices'])){$lethe_system_notices=0;}else{$lethe_system_notices=1;}
		if(!isset($_POST['lethe_sidera_helper']) || empty($_POST['lethe_sidera_helper'])){$lethe_sidera_helper=0;}else{$lethe_sidera_helper=1;}
		if(!isset($_POST['lethe_theme']) || empty($_POST['lethe_theme'])){$this->errPrint.='* Please Choose a Theme<br>';}
		if(!isset($_POST['lethe_google_recaptcha_public']) || empty($_POST['lethe_google_recaptcha_public'])){$this->errPrint.='* Please Enter a reCaptcha Public Key<br>';}
		if(!isset($_POST['lethe_google_recaptcha_private']) || empty($_POST['lethe_google_recaptcha_private'])){$this->errPrint.='* Please Enter a reCaptcha Private Key<br>';}
		if(!isset($_POST['lethe_save_tree_text']) || empty($_POST['lethe_save_tree_text'])){$lethe_save_tree = '';}else{$lethe_save_tree=str_replace("'","’",$_POST['lethe_save_tree_text']);}
		if(!isset($_POST['lethe_save_tree_on']) || empty($_POST['lethe_save_tree_on'])){$lethe_save_tree_on=0;}else{$lethe_save_tree_on=1;}
		if(!isset($_POST['lethe_license_key']) || empty($_POST['lethe_license_key'])){$this->errPrint.='* Please Enter a License Key<br>';}
		$lethePowered = '<p><small>Lethe Newsletter &amp; Mailing System v'. LETHE_VERSION .' &copy; '. date("Y") .'</small></p><p><small>Artlantis Design Studio <a href="http://www.artlantis.net/" target="_blank">http://www.artlantis.net/</a></p><p>Lethe Mailing System <a href="http://www.newslether.com/" target="_blank">http://www.newslether.com/</a></small></p>';
		
		
		if($this->errPrint==''){
			
			$confList = '';
			$confList.= "<?php\n";
			$confList .= "/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       ". LETHE_VERSION ."                                                      | */
/*  | Last modified ". date('d.m.Y') ."                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */";
			$confList .= "\n\n";
			$confList .= "# General Settings\n";
			$confList .= "\$LETHE_SETS['lethe_default_lang'] = '". mysql_prep($_POST['lethe_default_lang']) ."';\n";
			$confList .= "\$LETHE_SETS['lethe_default_timezone'] = '". mysql_prep($_POST['lethe_default_timezone']) ."';\n";
			$confList .= "\$LETHE_SETS['lethe_root_url'] = '". $letheURL ."';\n";
			$confList .= "\$LETHE_SETS['lethe_admin_url'] = '". $letheAURL ."';\n";
			$confList .= "\$LETHE_SETS['lethe_debug_mode'] = ". $lethe_debug_mode .";\n";
			$confList .= "\$LETHE_SETS['lethe_system_notices'] = ". $lethe_system_notices .";\n";
			$confList .= "\$LETHE_SETS['lethe_sidera_helper'] = ". $lethe_sidera_helper .";\n";
			$confList .= "\$LETHE_SETS['lethe_theme'] = '". mysql_prep($_POST['lethe_theme']) ."';\n";
			$confList .= "\$LETHE_SETS['lethe_google_recaptcha_public'] = '". mysql_prep($_POST['lethe_google_recaptcha_public']) ."';\n";
			$confList .= "\$LETHE_SETS['lethe_google_recaptcha_private'] = '". mysql_prep($_POST['lethe_google_recaptcha_private']) ."';\n";
			$confList .= "\$LETHE_SETS['lethe_powered_text'] ='". base64_encode($lethePowered) ."';\n";
			$confList .= "\$LETHE_SETS['lethe_save_tree'] ='". $lethe_save_tree ."';\n";
			$confList .= "\$LETHE_SETS['lethe_save_tree_on'] = ". $lethe_save_tree_on .";\n";
			$confList .= "\$LETHE_SETS['lethe_license_key'] = '". mysql_prep($_POST['lethe_license_key']) ."';\n";
			$confList .= "\n\n";
			$confList .= "foreach(\$LETHE_SETS as \$k=>\$v){if(!defined(\$k)){define(\$k,\$v);}}";
			$confList .= "\n";
			$confList .= "?>";
			
			$pathw = LETHE.DIRECTORY_SEPARATOR.'lib/lethe.sets.php';
			if (!file_exists ($pathw) ) {
				@touch ($pathw);
			}
			
			$conc=@fopen ($pathw,'w');
			if (!$conc) {
				$this->errPrint = errMod('Setting File Cannot Be Open','danger');
				return false;
			}else{
				#************* Writing *****
				if (fputs ($conc,$confList) ){
					if(!$this->onInstall){
						header('Location: ?p=settings/general');
						return true;
						die();
					}else{
						return true;
					}
				}else {
					$this->errPrint = errMod('Settings Could Not Be Written!','danger');
				}
				fclose($conc);
				#************* Writing End **
			}
			
		}else{
			$this->errPrint = errMod($this->errPrint,'danger');
		}
		
	}
	
	/* Add New User */
	public function addUser(){
	
		global $myconn;
	
		if(!isset($_POST['usr_name']) || empty($_POST['usr_name'])){
			$this->errPrint.='* '. letheglobal_please_enter_a_name .'<br>';
		}
		if(!isset($_POST['usr_mail']) || !mailVal($_POST['usr_mail'])){
			$this->errPrint.='* '. letheglobal_invalid_e_mail_address .'<br>';
		}else{
			if(cntData("SELECT ID,mail FROM ". db_table_pref ."users WHERE mail='". mysql_prep($_POST['usr_mail']) ."'")!=0){
				$this->errPrint.='* '. letheglobal_e_mail_already_exists .'<br>';
			}
		}
		if(!isset($_POST['usr_pass']) || empty($_POST['usr_pass'])){
			$this->errPrint.='* '. letheglobal_please_enter_password .'<br>';
		}else{
			$passLenth = isToo($_POST['usr_pass'],letheglobal_password.' ',5,30);
			if($passLenth!=''){
				$this->errPrint.='* '. $passLenth .'<br>';
			}else{
				if(!isset($_POST['usr_pass2']) || ($_POST['usr_pass2']!=$_POST['usr_pass'])){
					$this->errPrint.='* '. letheglobal_passwords_mismatch .'<br>';
				}
			}
		}
		
		if($this->isMaster==0){ # Organization User
			//if(!isset($_POST['user_daily_limit']) || !is_numeric($_POST['user_daily_limit'])){$this->errPrint.='* '. organizations_please_enter_a_daily_sending_limit .'<br>';}
			if(!isset($_POST['perm-sel-list']) || empty($_POST['perm-sel-list'])){$this->errPrint.='* '. organizations_please_choose_access_pages .'<br>';}
			if(!isset($_POST['user_auth_mode']) || !is_numeric($_POST['user_auth_mode'])){$this->errPrint.='* '. organizations_select_a_management_type .'<br>';}else{
				/* CSRF Auth Protection */
				if(intval($_POST['user_auth_mode'])>1){
					$this->auth_mode = 0;
				}else{
					$this->auth_mode = intval($_POST['user_auth_mode']);
				}
				
				/* Make Primary For New Organization */
				if(intval($_POST['user_auth_mode'])==1){
					if(cntData("SELECT ID FROM ". db_table_pref ."users WHERE OID=". $this->OID ." AND isPrimary=1")==0){
						$this->isPrimary = 1;
					}else{
						$this->isPrimary = 0;
					}
				}
				
				/* Check Limit */
				$sourceLimit = calcSource($this->OID,'users');
				if(!limitBlock($sourceLimit,set_org_max_user)){$this->errPrint.='* '. letheglobal_limit_exceeded .'<br>';}
			}
		}else{
			$_POST['user_daily_limit'] = 0;
		}
		
		if($this->errPrint==''){
		
			$privateKey = encr(md5(rand().uniqid('youaremylethe',true).sha1(time())));
			$publicKey = encr(uniqid('youaremylethe',true).time().rand());
			$usrPass = encr($_POST['usr_pass']);
			
			$LPRE = $myconn->prepare("INSERT INTO 
													". db_table_pref ."users 
											  SET 
													OID=". $this->OID .",
													real_name=?,
													mail=?,
													pass=?,
													auth_mode=". $this->auth_mode .",
													isActive=1,
													isPrimary=". $this->isPrimary .",
													private_key='". $privateKey ."',
													public_key='". $publicKey ."'
									") or die(mysqli_error($myconn));
			$LPRE->bind_param('sss',$_POST['usr_name'],$_POST['usr_mail'],$usrPass);
			$LPRE->execute();
			$LPRE->close();
			
			$usrID = $myconn->insert_id;
			
			if($this->isMaster==0){ # Organization User
				/* Add Allowed Pages */
				$addPerm = $myconn->prepare("INSERT INTO ". db_table_pref ."user_permissions SET OID=". $this->OID .", UID=?, perm=?") or die(mysqli_error($myconn));
				foreach($_POST['perm-sel-list'] as $k=>$v){
					$pg = str_replace('?p=','',$v);
					$addPerm->bind_param('is',$usrID,$pg);
					$addPerm->execute();
				}
				$addPerm->close();
			}
		
			$this->errPrint = errMod(letheglobal_recorded_successfully.'!','success');
			$this->isSuccess = 1;
			if(!$this->onInstall){
				unset($_POST);
			}
		}else{
			$this->errPrint = errMod($this->errPrint,'danger');
		}
		
		return $this->errPrint;
	
	}

	/* Edit User */
	public function editUser(){
	
		global $myconn;
		
		/* Mode Protector */
		if(LETHE_AUTH_MODE==0){
			$this->UID = LETHE_AUTH_ID;
		}
		
		/* Check User */
		$opUser = $myconn->query("SELECT * FROM ". db_table_pref ."users WHERE OID=". $this->OID ." AND ID=". $this->UID ."") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opUser)==0){$this->errPrint = errMod(letheglobal_record_not_found.'!','danger');return false;}else{
		
			$opUserRs = $opUser->fetch_assoc();
			$this->isPrimary = $opUserRs['isPrimary'];
			
			/* Primary User Checker */
			if(!$opUserRs['isPrimary']){
			
				/* Delete */
				if(isset($_POST['del']) && $_POST['del']=='YES'){
					$myconn->query("DELETE FROM ". db_table_pref ."users WHERE OID=". $this->OID ." AND ID=". $this->UID ."") or die(mysqli_error($myconn));
					header('Location: ?p=settings/users');
					return false; die();
				}
			
				if(isset($_POST['active']) && $_POST['active']=='YES'){$active=1;}else{$active=0;}
			}else{
				$active=1;
			}
	
			if(!isset($_POST['usr_name']) || empty($_POST['usr_name'])){
				$this->errPrint.='* '. letheglobal_please_enter_a_name .'<br>';
			}
			if(!isset($_POST['usr_mail']) || !mailVal($_POST['usr_mail'])){
				$this->errPrint.='* '. letheglobal_invalid_e_mail_address .'<br>';
			}else{
				if(cntData("SELECT ID,OID,mail FROM ". db_table_pref ."users WHERE mail='". mysql_prep($_POST['usr_mail']) ."' AND ID<>". $this->UID ."")!=0){
					$this->errPrint.='* '. letheglobal_e_mail_already_exists .'<br>';
				}
			}
			
			
			if(isset($_POST['usr_pass']) && !empty($_POST['usr_pass'])){
				$passLenth = isToo($_POST['usr_pass'],letheglobal_password.' ',5,30);
				if($passLenth!=''){
					$this->errPrint.='* '. $passLenth .'<br>';
				}else{
					if(!isset($_POST['usr_pass2']) || ($_POST['usr_pass2']!=$_POST['usr_pass'])){
						$this->errPrint.='* '. letheglobal_passwords_mismatch .'<br>';
					}else{
						$_POST['usr_pass'] = encr($_POST['usr_pass']);
					}
				}
			}else{
				$_POST['usr_pass'] = $opUserRs['pass'];
			}
			
			if($this->auth_mode!=2){
				if(!isset($_POST['usr_auth']) || intval($_POST['usr_auth'])>1){
					$this->auth_mode = $opUserRs['auth_mode'];
				}else{
					if(LETHE_AUTH_MODE==0){
						$this->auth_mode = 0;
					}else{
						$this->auth_mode = intval($_POST['usr_auth']);
					}
				}
			}
			
		if($this->isMaster==0){ # Organization User
/* 			if(!isset($_POST['user_daily_limit']) || !is_numeric($_POST['user_daily_limit'])){$this->errPrint.='* '. organizations_please_enter_a_daily_sending_limit .'<br>';}else{
				if(intval($_POST['user_daily_limit'])>set_org_max_daily_limit && intval($_POST['user_daily_limit'])!=0){
					$_POST['user_daily_limit'] = set_org_max_daily_limit;
				}
			} */
			if(!isset($_POST['perm-sel-list']) || empty($_POST['perm-sel-list'])){$this->errPrint.='* '. organizations_please_choose_access_pages .'<br>';}
			if(!isset($_POST['user_auth_mode']) || !is_numeric($_POST['user_auth_mode'])){$this->errPrint.='* '. organizations_select_a_management_type .'<br>';}else{
				/* CSRF Auth Protection */
				if(intval($_POST['user_auth_mode'])>1){
					$this->auth_mode = 0;
				}else{
					$this->auth_mode = intval($_POST['user_auth_mode']);
				}
				
			}
		}else{
			$_POST['user_daily_limit'] = 0;
		}
		
		if(isset($_POST['user_spec_view']) && $_POST['user_spec_view']=='YES'){$user_spec_view=1;}else{$user_spec_view=0;}
			
		/* Update */
			if($this->errPrint==''){
			
				$LPRE = $myconn->prepare("UPDATE 
														". db_table_pref ."users 
											 SET 
														real_name=?,
														mail=?,
														pass=?,
														auth_mode=". $this->auth_mode .",
														isActive=". $active .",
														isPrimary=". $this->isPrimary .",
														user_spec_view=". $user_spec_view ."
										   WHERE
														OID=". $this->OID ."
											 AND
														ID=". $this->UID ."
										") or die(mysqli_error($myconn));
				$LPRE->bind_param('sss',$_POST['usr_name'],$_POST['usr_mail'],$_POST['usr_pass']);
				$LPRE->execute();
				$LPRE->close();
				
			if($this->isMaster==0){ # Organization User
				/* Clear Removed Perms */
				if(isset($_POST['perm-all-list'])){
					$permDel = $myconn->prepare("DELETE FROM ". db_table_pref ."user_permissions WHERE OID=". $this->OID ." AND UID=".$this->UID." AND perm=?") or die(mysqli_error($myconn));
					foreach($_POST['perm-all-list'] as $k=>$v){
						$permDel->bind_param('s',$v);
						$permDel->execute();
					}
					$permDel->close();
				}
				/* Add Allowed Pages */
				$usrID = intval($this->UID);
				$addPerm = $myconn->prepare("INSERT INTO ". db_table_pref ."user_permissions SET OID=". $this->OID .", UID=?, perm=?") or die(mysqli_error($myconn));
				foreach($_POST['perm-sel-list'] as $k=>$v){
					$pg = str_replace('?p=','',$v);
					if(cntData("SELECT ID FROM ". db_table_pref ."user_permissions WHERE OID=". $this->OID ." AND UID=". $usrID ." AND perm='". mysql_prep($pg) ."'")==0){
						$addPerm->bind_param('is',$usrID,$pg);
						$addPerm->execute();
					}
				}
				$addPerm->close();
			}
			
				$this->errPrint = errMod(letheglobal_updated_successfully.'!','success');
				unset($_POST);
			}else{
				$this->errPrint = errMod($this->errPrint,'danger');
			}
			
			return $this->errPrint;
		} $opUser->free();
	
	}

	/* Add Submission Account */
	public function addSubAccount(){
	
		global $myconn;
		global $LETHE_BOUNCE_TYPES;
		
		/* General */
		if(!isset($_POST['acc_title']) || empty($_POST['acc_title'])){$this->errPrint .= '* '. settings_please_enter_a_account_title .'<br>';}
		if(!isset($_POST['daily_limit']) || !is_numeric($_POST['daily_limit'])){$this->errPrint .= '* '. settings_please_enter_a_daily_limit .'<br>';}
		if(!isset($_POST['spec_limit_range']) || !is_numeric($_POST['spec_limit_range'])){$_POST['spec_limit_range']=1440;}
		if(!isset($_POST['send_per_conn']) || !is_numeric($_POST['send_per_conn'])){$this->errPrint .= '* '. settings_please_enter_a_per_connection_limit .'<br>';}
		if(!isset($_POST['standby_time']) || !is_numeric($_POST['standby_time'])){$this->errPrint .= '* '. settings_please_enter_a_standby_limit .'<br>';}
		if(isset($_POST['systemAcc']) && $_POST['systemAcc']=='YES'){$systemAcc=1;}else{$systemAcc=0;}
		if(isset($_POST['debug']) && $_POST['debug']=='YES'){$isDebug=1;}else{$isDebug=0;}
		if(isset($_POST['active']) && $_POST['active']=='YES'){$isActive=1;}else{$isActive=0;}
		
		/* Sending */
		if(!isset($_POST['from_title']) || empty($_POST['from_title'])){$this->errPrint .= '* '. settings_please_enter_a_sender_title .'<br>';}
		if(!isset($_POST['from_mail']) || !mailVal($_POST['from_mail'])){$this->errPrint .= '* '. settings_invalid_sender_mail .'<br>';}
		if(!isset($_POST['reply_mail']) || !mailVal($_POST['reply_mail'])){$this->errPrint .= '* '. settings_invalid_reply_mail .'<br>';}
		if(!isset($_POST['test_mail']) || !mailVal($_POST['test_mail'])){$this->errPrint .= '* '. settings_invalid_test_mail .'<br>';}
		if(!isset($_POST['mail_type']) || !is_numeric($_POST['mail_type'])){$this->errPrint .= '* '. settings_please_choose_a_mail_content_type .'<br>';}
		if(!isset($_POST['send_method']) || !is_numeric($_POST['send_method'])){$this->errPrint .= '* '. settings_please_choose_a_sending_method .'<br>';}
		if(!isset($_POST['mail_engine']) || empty($_POST['mail_engine'])){$this->errPrint .= '* '. settings_please_choose_a_mail_engine .'<br>';}
		
		/* Connection SMTP */
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==0){
			if(!isset($_POST['smtp_host']) || empty($_POST['smtp_host'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_server .'<br>';}
			if(!isset($_POST['smtp_port']) || empty($_POST['smtp_port'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_port .'<br>';}
			if(!isset($_POST['smtp_user']) || empty($_POST['smtp_user'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_username .'<br>';}
			if(!isset($_POST['smtp_pass']) || empty($_POST['smtp_pass'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_password .'<br>';}
			if(!isset($_POST['smtp_secure']) || !is_numeric($_POST['smtp_secure'])){$this->errPrint .= '* '. settings_please_choose_a_smtp_encryption .'<br>';}
		}else{
			$_POST['smtp_host']='';
			$_POST['smtp_port']=0;
			$_POST['smtp_user']='';
			$_POST['smtp_pass']='';
			$_POST['smtp_secure']=0;			
		}
		
		# PHP Mail
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==1){
			if(!function_exists('mail')){$this->errPrint .= '* Server does not support PHP mail() !<br>';}
		}
		
		# AWS
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==2){
			if(!isset($_POST['aws_acc_key']) || empty($_POST['aws_acc_key'])){$this->errPrint .= '* '. settings_please_enter_aws_access_key .'<br>';}
			if(!isset($_POST['aws_sec_key']) || empty($_POST['aws_sec_key'])){$this->errPrint .= '* '. settings_please_enter_aws_secret_key .'<br>';}
		}else{
			$_POST['aws_acc_key']='';
			$_POST['aws_sec_key']='';			
		}
		
		# Mandrill
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==3){
			if(!isset($_POST['mandrill_user']) || empty($_POST['mandrill_user'])){$this->errPrint .= '* '. settings_please_enter_a_mandrill_username .'<br>';}
			if(!isset($_POST['mandrill_key']) || empty($_POST['mandrill_key'])){$this->errPrint .= '* '. settings_please_enter_a_mandrill_key .'<br>';}
		}else{
			$_POST['mandrill_user']='';
			$_POST['mandrill_key']='';			
		}
		
		# SendGrid
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==4){
			if(!isset($_POST['sendgrid_user']) || empty($_POST['sendgrid_user'])){$this->errPrint .= '* '. settings_please_enter_a_sendgrid_username .'<br>';}
			if(!isset($_POST['sendgrid_pass']) || empty($_POST['sendgrid_pass'])){$this->errPrint .= '* '. settings_please_enter_a_sendgrid_password .'<br>';}
		}else{
			$_POST['sendgrid_user']='';
			$_POST['sendgrid_pass']='';			
		}
		
		if(!isset($_POST['pop3_host']) || empty($_POST['pop3_host'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_server .'<br>';}
		if(!isset($_POST['pop3_port']) || empty($_POST['pop3_port'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_port .'<br>';}
		if(!isset($_POST['pop3_user']) || empty($_POST['pop3_user'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_username .'<br>';}
		if(!isset($_POST['pop3_pass']) || empty($_POST['pop3_pass'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_password .'<br>';}
		if(!isset($_POST['pop3_secure']) || !is_numeric($_POST['pop3_secure'])){$this->errPrint .= '* '. settings_please_choose_a_pop3_encryption .'<br>';}
		
		if(!isset($_POST['imap_host']) || empty($_POST['imap_host'])){$this->errPrint .= '* '. settings_please_enter_a_imap_server .'<br>';}
		if(!isset($_POST['imap_port']) || empty($_POST['imap_port'])){$this->errPrint .= '* '. settings_please_enter_a_imap_port .'<br>';}
		if(!isset($_POST['imap_user']) || empty($_POST['imap_user'])){$this->errPrint .= '* '. settings_please_enter_a_imap_username .'<br>';}
		if(!isset($_POST['imap_pass']) || empty($_POST['imap_pass'])){$this->errPrint .= '* '. settings_please_enter_a_imap_password .'<br>';}
		if(!isset($_POST['imap_secure']) || !is_numeric($_POST['imap_secure'])){$this->errPrint .= '* '. settings_please_choose_a_imap_encryption .'<br>';}
		
		if(isset($_POST['smtp_auth']) && $_POST['smtp_auth']=='YES'){$smtp_auth=1;}else{$smtp_auth=0;}
		if(!isset($_POST['bounce_acc']) || !is_numeric($_POST['bounce_acc'])){$this->errPrint .= '* '. settings_please_choose_a_bounce_connector .'<br>';}
		
		/* DKIM */
		if(isset($_POST['dkimactive']) && $_POST['dkimactive']=='YES'){
			$dkimactive=1;
			if(!isset($_POST['dkimdomain']) || empty($_POST['dkimdomain'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_domain .'<br>';}
			if(!isset($_POST['dkimprivate']) || empty($_POST['dkimprivate'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_private_key .'<br>';}
			if(!isset($_POST['dkimselector']) || empty($_POST['dkimselector'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_selector .'<br>';}
			if(!isset($_POST['dkimpassphrase']) || empty($_POST['dkimpassphrase'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_passphrase .'<br>';}
		}else{
			$dkimactive=0;
			$_POST['dkimdomain'] = '';
			$_POST['dkimprivate'] = '';
			$_POST['dkimselector'] = '';
			$_POST['dkimpassphrase'] = '';
		}
		
		/* Bounce Actions */
		$bounceActions = array();
		foreach($LETHE_BOUNCE_TYPES as $k=>$v){
			if($this->onInstall){$frmAct=1;}else{
				$frmAct = ((isset($_POST['bounces_'.$k]) && is_numeric($_POST['bounces_'.$k])) ? $_POST['bounces_'.$k]:0);
			}
			$bounceActions[$k] = $frmAct;
		}
		
		$bounceActions = json_encode($bounceActions);
		
		if($this->errPrint==''){
		
			$account_id = encr(uniqid('lethe',true).time().rand());
			$daily_date = date("Y-m-d H:i:s");
			$daily_date = strtotime(date("Y-m-d H:i:s", strtotime($daily_date)) . " +". $_POST['spec_limit_range'] ." minutes");
			if($systemAcc){$myconn->query("UPDATE ". db_table_pref ."submission_accounts  SET systemAcc=0 WHERE ID>0");}
		
			$LTH = $myconn->prepare("INSERT INTO 
													". db_table_pref ."submission_accounts 
											 SET
													acc_title=?,
													daily_limit=?,
													send_per_conn=?,
													standby_time=?,
													systemAcc=". $systemAcc .",
													isDebug=". $isDebug .",
													isActive=". $isActive .",
													from_title=?,
													from_mail=?,
													reply_mail=?,
													test_mail=?,
													mail_type=?,
													send_method=?,
													mail_engine=?,
													smtp_host=?,
													smtp_port=?,
													smtp_user=?,
													smtp_pass=?,
													smtp_secure=?,
													pop3_host=?,
													pop3_port=?,
													pop3_user=?,
													pop3_pass=?,
													pop3_secure=?,
													imap_host=?,
													imap_port=?,
													imap_user=?,
													imap_pass=?,
													imap_secure=?,
													smtp_auth=". $smtp_auth .",
													bounce_acc=?,
													aws_access_key=?,
													aws_secret_key=?,
													dkim_active=". $dkimactive .",
													dkim_domain=?,
													dkim_private=?,
													dkim_selector=?,
													dkim_passphrase=?,
													account_id='". $account_id ."',
													daily_reset='". date("Y-m-d H:i:s",$daily_date) ."',
													limit_range=?,
													bounce_actions=?
													
									") or die(mysqli_error($myconn));
			$LTH->bind_param('siiissssiississisissisissiissssssis',
									$_POST['acc_title'],
									$_POST['daily_limit'],
									$_POST['send_per_conn'],
									$_POST['standby_time'],
									$_POST['from_title'],
									$_POST['from_mail'],
									$_POST['reply_mail'],
									$_POST['test_mail'],
									$_POST['mail_type'],
									$_POST['send_method'],
									$_POST['mail_engine'],
									$_POST['smtp_host'],
									$_POST['smtp_port'],
									$_POST['smtp_user'],
									$_POST['smtp_pass'],
									$_POST['smtp_secure'],
									$_POST['pop3_host'],
									$_POST['pop3_port'],
									$_POST['pop3_user'],
									$_POST['pop3_pass'],
									$_POST['pop3_secure'],
									$_POST['imap_host'],
									$_POST['imap_port'],
									$_POST['imap_user'],
									$_POST['imap_pass'],
									$_POST['imap_secure'],
									$_POST['bounce_acc'],
									$_POST['aws_acc_key'],
									$_POST['aws_sec_key'],
									$_POST['dkimdomain'],
									$_POST['dkimprivate'],
									$_POST['dkimselector'],
									$_POST['dkimpassphrase'],
									$_POST['spec_limit_range'],
									$bounceActions
							);
			if($LTH->execute()){
				$subAccID = $myconn->insert_id;
				# Add Bounce Cron
				$buildCron = new lethe();
				# Check every 5 min
				$buildCron->chronosMin = "*/5";
				$buildCron->chronosURL = "'".lethe_root_url.'chronos/lethe.bounce.php?ID='.$subAccID."' > /dev/null 2>&1";
				$genComm = $buildCron->buildChronos();
				$genDate = date('Y-m-d H:i:s');
				$addCron = $myconn->prepare("INSERT INTO ". db_table_pref ."chronos SET OID=". set_org_id .", SAID=". $subAccID .", pos=0, cron_command=?, launch_date=?");
				$addCron->bind_param('ss',$genComm,$genDate);
				$addCron->execute();
				$addCron->close();
			}
			$LTH->close();
			if(!$this->onInstall){
				unset($_POST);
			}
		
			$this->errPrint = errMod(''. letheglobal_recorded_successfully .'!','success');
		}else{
			$this->errPrint = errMod($this->errPrint,'danger');
		}
	
	}
	
	/* Edit Submission Account */
	public function editSubAccount(){
	
		global $myconn;
		global $LETHE_BOUNCE_TYPES;
		
		/* Open Account */
		$opAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE ID=". $this->ID ."") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opAcc)==0){$this->errPrint=errMod(letheglobal_record_not_found,'danger');}else{
		$opAccRs = $opAcc->fetch_assoc();
		
		/* Delete */
		if(isset($_POST['del']) && $_POST['del']=='YES'){
		
			/* Delete Controls Here */
			if($opAccRs['systemAcc']==1){$this->errPrint .= '* '. settings_system_accounts_cannot_be_deleted .'!<br>';}else{
				$subAccID = $opAccRs['ID'];
				
				if(cntData("SELECT * FROM ". db_table_pref ."organization_settings WHERE set_key='org_submission_account' AND FIND_IN_SET('". $subAccID ."', set_val)")==0){
					
				# Remove Account
				$myconn->query("DELETE FROM ". db_table_pref ."submission_accounts WHERE ID=". $subAccID ."") or die(mysqli_error($myconn));
				
				# Remove Bounce Cron
				$remCron = $myconn->prepare("UPDATE ". db_table_pref ."chronos SET pos=1 WHERE OID=". set_org_id ." AND SAID=?");
				$remCron->bind_param('i',$subAccID);
				$remCron->execute();
				$remCron->close();
				
				//header('Location: ?p=settings/submission');
				
				}else{
					$this->errPrint .= '* '. settings_submission_account_being_used_by_the_organization .'<br>';
				}
			}
		
		}
		
		/* General */
		if(!isset($_POST['acc_title']) || empty($_POST['acc_title'])){$this->errPrint .= '* '. settings_please_enter_a_account_title .'<br>';}
		if(!isset($_POST['daily_limit']) || !is_numeric($_POST['daily_limit'])){$this->errPrint .= '* '. settings_please_enter_a_daily_limit .'<br>';}
		if(!isset($_POST['spec_limit_range']) || !is_numeric($_POST['spec_limit_range'])){$_POST['spec_limit_range']=$opAccRs['limit_range'];}
		if(!isset($_POST['send_per_conn']) || !is_numeric($_POST['send_per_conn'])){$this->errPrint .= '* '. settings_please_enter_a_per_connection_limit .'<br>';}
		if(!isset($_POST['standby_time']) || !is_numeric($_POST['standby_time'])){$this->errPrint .= '* '. settings_please_enter_a_standby_limit .'<br>';}
		if(isset($_POST['systemAcc']) && $_POST['systemAcc']=='YES'){$systemAcc=1;}else{
			/* Check System Accounts */
			if(cntData("SELECT ID FROM ". db_table_pref ."submission_accounts WHERE systemAcc=1 AND ID<>" . $this->ID)==0){
				$systemAcc=1;
			}else{
				$systemAcc=0;
			}
		}
		if(isset($_POST['debug']) && $_POST['debug']=='YES'){$isDebug=1;}else{$isDebug=0;}
		if(isset($_POST['active']) && $_POST['active']=='YES'){$isActive=1;}else{$isActive=0;}
		
		/* Sending */
		if(!isset($_POST['from_title']) || empty($_POST['from_title'])){$this->errPrint .= '* '. settings_please_enter_a_sender_title .'<br>';}
		if(!isset($_POST['from_mail']) || !mailVal($_POST['from_mail'])){$this->errPrint .= '* '. settings_invalid_sender_mail .'<br>';}
		if(!isset($_POST['reply_mail']) || !mailVal($_POST['reply_mail'])){$this->errPrint .= '* '. settings_invalid_reply_mail .'<br>';}
		if(!isset($_POST['test_mail']) || !mailVal($_POST['test_mail'])){$this->errPrint .= '* '. settings_invalid_test_mail .'<br>';}
		if(!isset($_POST['mail_type']) || !is_numeric($_POST['mail_type'])){$this->errPrint .= '* '. settings_please_choose_a_mail_content_type .'<br>';}
		if(!isset($_POST['send_method']) || !is_numeric($_POST['send_method'])){$this->errPrint .= '* '. settings_please_choose_a_sending_method .'<br>';}
		if(!isset($_POST['mail_engine']) || empty($_POST['mail_engine'])){$this->errPrint .= '* '. settings_please_choose_a_mail_engine .'<br>';}
		
		/* Connection SMTP */
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==0){
			if(!isset($_POST['smtp_host']) || empty($_POST['smtp_host'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_server .'<br>';}
			if(!isset($_POST['smtp_port']) || empty($_POST['smtp_port'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_port .'<br>';}
			if(!isset($_POST['smtp_user']) || empty($_POST['smtp_user'])){$this->errPrint .= '* '. settings_please_enter_a_smtp_username .'<br>';}
			if(!isset($_POST['smtp_pass']) || empty($_POST['smtp_pass'])){$_POST['smtp_pass'] = $opAccRs['smtp_pass'];}
			if(!isset($_POST['smtp_secure']) || !is_numeric($_POST['smtp_secure'])){$this->errPrint .= '* '. settings_please_choose_a_smtp_encryption .'<br>';}
		}else{
			$_POST['smtp_host']='';
			$_POST['smtp_port']=0;
			$_POST['smtp_user']='';
			$_POST['smtp_pass']='';
			$_POST['smtp_secure']=0;			
		}
		
		# PHP Mail
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==1){
			if(!function_exists('mail')){$this->errPrint .= '* Server does not support PHP mail() !<br>';}
		}
		
		/* Amazon SES */
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==2){
			if(!isset($_POST['aws_acc_key']) || empty($_POST['aws_acc_key'])){$this->errPrint .= '* '. settings_please_enter_aws_access_key .'<br>';}
			if(!isset($_POST['aws_sec_key']) || empty($_POST['aws_sec_key'])){$this->errPrint .= '* '. settings_please_enter_aws_secret_key .'<br>';}
		}else{
			$_POST['aws_acc_key']='';
			$_POST['aws_sec_key']='';			
		}
		
		# Mandrill
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==3){
			if(!isset($_POST['mandrill_user']) || empty($_POST['mandrill_user'])){$this->errPrint .= '* '. settings_please_enter_a_mandrill_username .'<br>';}
			if(!isset($_POST['mandrill_key']) || empty($_POST['mandrill_key'])){$this->errPrint .= '* '. settings_please_enter_a_mandrill_key .'<br>';}
		}else{
			$_POST['mandrill_user']='';
			$_POST['mandrill_key']='';			
		}
		
		# SendGrid
		if(isset($_POST['send_method']) && intval($_POST['send_method'])==4){
			if(!isset($_POST['sendgrid_user']) || empty($_POST['sendgrid_user'])){$this->errPrint .= '* '. settings_please_enter_a_sendgrid_username .'<br>';}
			if(!isset($_POST['sendgrid_pass']) || empty($_POST['sendgrid_pass'])){$_POST['sendgrid_pass']=$opAccRs['sendgrid_pass'];}
		}else{
			$_POST['sendgrid_user']='';
			$_POST['sendgrid_pass']='';			
		}
		
		if(!isset($_POST['pop3_host']) || empty($_POST['pop3_host'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_server .'<br>';}
		if(!isset($_POST['pop3_port']) || empty($_POST['pop3_port'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_port .'<br>';}
		if(!isset($_POST['pop3_user']) || empty($_POST['pop3_user'])){$this->errPrint .= '* '. settings_please_enter_a_pop3_username .'<br>';}
		if(!isset($_POST['pop3_pass']) || empty($_POST['pop3_pass'])){$_POST['pop3_pass'] = $opAccRs['pop3_pass'];}
		if(!isset($_POST['pop3_secure']) || !is_numeric($_POST['pop3_secure'])){$this->errPrint .= '* '. settings_please_choose_a_pop3_encryption .'<br>';}
		
		if(!isset($_POST['imap_host']) || empty($_POST['imap_host'])){$this->errPrint .= '* '. settings_please_enter_a_imap_server .'<br>';}
		if(!isset($_POST['imap_port']) || empty($_POST['imap_port'])){$this->errPrint .= '* '. settings_please_enter_a_imap_port .'<br>';}
		if(!isset($_POST['imap_user']) || empty($_POST['imap_user'])){$this->errPrint .= '* '. settings_please_enter_a_imap_username .'<br>';}
		if(!isset($_POST['imap_pass']) || empty($_POST['imap_pass'])){$_POST['imap_pass'] = $opAccRs['imap_pass'];}
		if(!isset($_POST['imap_secure']) || !is_numeric($_POST['imap_secure'])){$this->errPrint .= '* '. settings_please_choose_a_imap_encryption .'<br>';}
		
		if(isset($_POST['smtp_auth']) && $_POST['smtp_auth']=='YES'){$smtp_auth=1;}else{$smtp_auth=0;}
		if(!isset($_POST['bounce_acc']) || !is_numeric($_POST['bounce_acc'])){$this->errPrint .= '* '. settings_please_choose_a_bounce_connector .'<br>';}
		
		/* DKIM */
		if(isset($_POST['dkimactive']) && $_POST['dkimactive']=='YES'){
			$dkimactive=1;
			if(!isset($_POST['dkimdomain']) || empty($_POST['dkimdomain'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_domain .'<br>';}
			if(!isset($_POST['dkimprivate']) || empty($_POST['dkimprivate'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_private_key .'<br>';}
			if(!isset($_POST['dkimselector']) || empty($_POST['dkimselector'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_selector .'<br>';}
			if(!isset($_POST['dkimpassphrase']) || empty($_POST['dkimpassphrase'])){$this->errPrint .= '* '. settings_please_enter_a_dkim_passphrase .'<br>';}
		}else{
			$dkimactive=0;
			$_POST['dkimdomain'] = '';
			$_POST['dkimprivate'] = '';
			$_POST['dkimselector'] = '';
			$_POST['dkimpassphrase'] = '';
		}
		
		/* Bounce Actions */
		$bounceActions = array();
		foreach($LETHE_BOUNCE_TYPES as $k=>$v){
			$frmAct = ((isset($_POST['bounces_'.$k]) && is_numeric($_POST['bounces_'.$k])) ? $_POST['bounces_'.$k]:0);
			$bounceActions[$k] = $frmAct;
		}
		
		$bounceActions = json_encode($bounceActions);
		
		if($this->errPrint==''){
		
			# Disable other system account if current account set for system account
			if($systemAcc){$myconn->query("UPDATE ". db_table_pref ."submission_accounts  SET systemAcc=0 WHERE ID>0");}
		
			$LTH = $myconn->prepare("UPDATE 
													". db_table_pref ."submission_accounts 
											 SET
													acc_title=?,
													daily_limit=?,
													send_per_conn=?,
													standby_time=?,
													systemAcc=". $systemAcc .",
													isDebug=". $isDebug .",
													isActive=". $isActive .",
													from_title=?,
													from_mail=?,
													reply_mail=?,
													test_mail=?,
													mail_type=?,
													send_method=?,
													mail_engine=?,
													smtp_host=?,
													smtp_port=?,
													smtp_user=?,
													smtp_pass=?,
													smtp_secure=?,
													pop3_host=?,
													pop3_port=?,
													pop3_user=?,
													pop3_pass=?,
													pop3_secure=?,
													imap_host=?,
													imap_port=?,
													imap_user=?,
													imap_pass=?,
													imap_secure=?,
													smtp_auth=". $smtp_auth .",
													bounce_acc=?,
													aws_access_key=?,
													aws_secret_key=?,
													mandrill_user=?,
													mandrill_key=?,
													sendgrid_user=?,
													sendgrid_pass=?,
													dkim_active=". $dkimactive .",
													dkim_domain=?,
													dkim_private=?,
													dkim_selector=?,
													dkim_passphrase=?,
													limit_range=?,
													bounce_actions=?
													
											WHERE
													ID=?
													
									") or die(mysqli_error($myconn));
			$LTH->bind_param('siiissssiississisissisissiissssssssssisi',
									$_POST['acc_title'],
									$_POST['daily_limit'],
									$_POST['send_per_conn'],
									$_POST['standby_time'],
									$_POST['from_title'],
									$_POST['from_mail'],
									$_POST['reply_mail'],
									$_POST['test_mail'],
									$_POST['mail_type'],
									$_POST['send_method'],
									$_POST['mail_engine'],
									$_POST['smtp_host'],
									$_POST['smtp_port'],
									$_POST['smtp_user'],
									$_POST['smtp_pass'],
									$_POST['smtp_secure'],
									$_POST['pop3_host'],
									$_POST['pop3_port'],
									$_POST['pop3_user'],
									$_POST['pop3_pass'],
									$_POST['pop3_secure'],
									$_POST['imap_host'],
									$_POST['imap_port'],
									$_POST['imap_user'],
									$_POST['imap_pass'],
									$_POST['imap_secure'],
									$_POST['bounce_acc'],
									$_POST['aws_acc_key'],
									$_POST['aws_sec_key'],
									$_POST['mandrill_user'],
									$_POST['mandrill_key'],
									$_POST['sendgrid_user'],
									$_POST['sendgrid_pass'],
									$_POST['dkimdomain'],
									$_POST['dkimprivate'],
									$_POST['dkimselector'],
									$_POST['dkimpassphrase'],
									$_POST['spec_limit_range'],
									$bounceActions,
									$this->ID
							);
			$LTH->execute();
			$LTH->close();
			unset($_POST);
		
			$this->errPrint = errMod(''. letheglobal_updated_successfully .'!','success');
		}else{
			$this->errPrint = errMod($this->errPrint,'danger');
		}
		
		} $opAcc->free();
	
	}
	
	/* Add Organization */
	public function addOrganization(){
	
		global $myconn;
		global $LETHE_ORG_DISK_QUOTA_LIST;
		global $LETHE_ORG_EDITABLE_CODES;
		global $LETHE_SUBSCRIBE_ERRORS;
	
		$this->errPrint = '';
		
		if(!isset($_POST['org_name']) || empty($_POST['org_name'])){$this->errPrint .= '* '. organizations_please_enter_a_organization_name .'<br>';}
		if(!isset($_POST['org_max_user']) || !is_numeric($_POST['org_max_user'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_user_limit .'<br>';}
		if(!isset($_POST['org_max_newsletter']) || !is_numeric($_POST['org_max_newsletter'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_newsletter_limit .'<br>';}
		if(!isset($_POST['org_max_autoresponder']) || !is_numeric($_POST['org_max_autoresponder'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_autoresponder_limit .'<br>';}
		if(!isset($_POST['org_max_subscriber']) || !is_numeric($_POST['org_max_subscriber'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_subscriber_limit .'<br>';}
		if(!isset($_POST['org_max_subscriber_group']) || !is_numeric($_POST['org_max_subscriber_group'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_subscriber_group_limit .'<br>';}
		if(!isset($_POST['org_max_subscribe_form']) || !is_numeric($_POST['org_max_subscribe_form'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_subscribe_form_limit .'<br>';}
		if(!isset($_POST['org_max_blacklist']) || !is_numeric($_POST['org_max_blacklist'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_black_list_limit .'<br>';}
		if(!isset($_POST['org_max_template']) || !is_numeric($_POST['org_max_template'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_template_limit .'<br>';}
		if(!isset($_POST['org_max_shortcode']) || !is_numeric($_POST['org_max_shortcode'])){$this->errPrint .= '* '. organizations_please_enter_maximum_short_code_limit .'<br>';}
		if(!isset($_POST['org_max_daily_limit']) || !is_numeric($_POST['org_max_daily_limit'])){$this->errPrint .= '* '. organizations_please_enter_a_daily_sending_limit .'<br>';}
		if(!isset($_POST['org_standby_organization']) || !is_numeric($_POST['org_standby_organization'])){$this->errPrint .= '* '. organizations_please_enter_a_standby_time_for_organizations .'<br>';}
		if(!isset($_POST['org_submission_account']) || intval($_POST['org_submission_account'])==0){$this->errPrint .= '* '. organizations_please_choose_a_submission_account .'<br>';}
		if(!isset($_POST['org_sender_title']) || empty($_POST['org_sender_title'])){$this->errPrint .= '* '. organizations_please_enter_a_sender_title .'<br>';}
		if(!isset($_POST['org_reply_mail']) || !mailVal($_POST['org_reply_mail'])){$this->errPrint .= '* '. organizations_invalid_reply_mail .'<br>';}
		if(!isset($_POST['org_test_mail']) || !mailVal($_POST['org_test_mail'])){$this->errPrint .= '* '. organizations_invalid_test_mail .'<br>';}
		if(!isset($_POST['org_timezone']) || empty($_POST['org_timezone'])){$this->errPrint .= '* '. organizations_please_choose_a_timezone .'<br>';}
		if(!isset($_POST['org_after_unsubscribe']) || !is_numeric($_POST['org_after_unsubscribe'])){$this->errPrint .= '* '. organizations_please_choose_a_unsubscribe_action .'<br>';}
		if(!isset($_POST['org_verification']) || !is_numeric($_POST['org_verification'])){$this->errPrint .= '* '. organizations_please_choose_a_verification_method .'<br>';}
		if(!isset($_POST['org_random_load']) || empty($_POST['org_random_load'])){$_POST['org_random_load']='';}else{$_POST['org_random_load']=1;}
		if(!isset($_POST['org_load_type']) || !is_numeric($_POST['org_load_type'])){$this->errPrint .= '* '. organizations_please_choose_a_load_type .'<br>';}
		if(!isset($_POST['org_max_disk_quota']) || !in_array($_POST['org_max_disk_quota'],$LETHE_ORG_DISK_QUOTA_LIST)){$this->errPrint .= '* '. organizations_invalid_disk_quota_value .'<br>';}
		
		if($this->errPrint==''){
		
			/* Common Values */
			$this->isPrimary = ((cntData("SELECT * FROM ". db_table_pref ."organizations WHERE isPrimary=1")==0) ? 1:0);
			$billingDate = (($this->billingDate==0) ? '':$this->billingDate);
			$orgTag = (($this->orgTag=='') ? slugify($_POST['org_name'].'-'.substr(encr($_POST['org_name'].time()),0,12)):$this->orgTag);
			$public_key = (($this->public_key=='') ? md5($orgTag.time().rand().$_POST['org_name'].uniqid(true)):$this->public_key);
			$private_key = (($this->private_key=='') ? md5($orgTag.sha1(time().rand().$_POST['org_name'].uniqid(true)).sha1(uniqid(true))):$this->private_key);
			$genAPIKey = sha1($private_key + time() + $_SERVER['REMOTE_ADDR'] + $private_key + $public_key);
			$genAPIKey = substr(base64_encode($genAPIKey),0,32);
			
			# RSS Url
			if(!isset($_POST['org_rss_url']) || empty($_POST['org_rss_url'])){
				# Define as system URL
				$_POST['org_rss_url'] = lethe_root_url.'lethe.newsletter.php?pos=rss&oid='.$public_key;
			}else{
				$_POST['org_rss_url'] = $_POST['org_rss_url'];
			}
			
		
			$addOrg = $myconn->prepare("INSERT INTO 
														". db_table_pref ."organizations
												SET
														orgTag=?,
														orgName=?,
														billingDate=?,
														isActive=1,
														public_key=?,
														private_key=?,
														api_key=?,
														ip_addr=?,
														isPrimary=". $this->isPrimary .",
														rss_url=?
													") or die(mysqli_error($myconn));
			$addOrg->bind_param('ssssssss',
									$orgTag,
									$_POST['org_name'],
									$billingDate,
									$public_key,
									$private_key,
									$genAPIKey,
									$_SERVER['REMOTE_ADDR'],
									$_POST['org_rss_url']
									);
			$addOrg->execute();
			$addOrg->close();
			
			/* Organization ID */
			$orgID = $myconn->insert_id;
			$this->OID = $orgID;
			
			/* Create Folders */
			if(mkdir(LETHE_RESOURCE.DIRECTORY_SEPARATOR.$orgTag,0755)){
				mkdir(LETHE_RESOURCE.DIRECTORY_SEPARATOR.$orgTag.'/expimp',0755);
			}
			
			/* Load Settings */
			global $LETHE_ORG_SET_VALS;
			
			$addSet = $myconn->prepare("INSERT INTO ". db_table_pref ."organization_settings SET set_key=?,set_val=?,OID=?") or die(mysqli_error($myconn));
			foreach($LETHE_ORG_SET_VALS as $k=>$v){
				$addSet->bind_param('ssi',$v,$_POST[$v],$orgID);
				$addSet->execute();
			} $addSet->close();
			
			/* Primary Records */
			# Groups
			$myconn->query("INSERT INTO ". db_table_pref ."subscriber_groups (OID,UID,group_name,isUnsubscribe,isUngroup) VALUES 
				(".$orgID .",0,'Unsubscribes',1,0),
				(".$orgID .",0,'Ungrouped',0,1)
			") or die(mysqli_error($myconn));
			$unGroupID = getOrgData($orgID,0);
			
			# Forms
			$newFormID = "LetheForm_".substr(encr(time().uniqid(true)),0,7);
			
			$defCustErrors = array();
			foreach($LETHE_SUBSCRIBE_ERRORS as $fks=>$fvs){
				$defCustErrors[] = $fvs[1];
			}
			$defCustErrors = implode("[@]",$defCustErrors);
			
			$myconn->query("INSERT INTO 
											". db_table_pref ."subscribe_forms
									SET
											OID=". $orgID .",
											form_name='System Form',
											form_id='". $newFormID ."',
											form_type=0,
											form_success_url=NULL,
											form_success_url_text=NULL,
											form_success_text='Your mail recorded successfully!',
											form_success_redir=0,
											form_remove=0,
											isSystem=1,
											isDraft=0,
											form_errors='". $defCustErrors ."',
											form_group=". $unGroupID ."
							") or die(mysqli_error($myconn));
			$sysFormID = getOrgData($orgID,2);
			$myconn->query("INSERT INTO
											". db_table_pref ."subscribe_form_fields (OID,FID,field_label,field_name,field_type,field_required,field_pattern,field_placeholder,sorting,field_data,field_static,field_save,field_error) VALUES
											(". $orgID .", ". $sysFormID .", 'E-Mail', 'LetheForm_Mail', 'email', 1, NULL, 'E-Mail', 1, NULL, 1, 'subscriber_mail', 'Invalid E-Mail Address'),
											(". $orgID .", ". $sysFormID .", 'Save', 'LetheForm_Save', 'submit', 0, NULL, NULL, 2, NULL, 1, NULL, NULL)
							
							") or die(mysqli_error($myconn));	
						
			# Templates
			$this->createSystemTemplates();
			
			/* Public Registration */
			if($this->public_registration){
				/* Verification Mails Here */
				# Only PRO
			}
			
			if(!$this->onInstall){
				unset($_POST);
			}
			$this->isSuccess=1;
		
			$this->errPrint = errMod(''. letheglobal_recorded_successfully .'!','success');
		}else{
			$this->errPrint = errMod($this->errPrint,'danger');
		
		}
	}
		
	/* Edit Organization */
	public function editOrganization(){
	
		global $myconn;
		
			$private_key = $this->private_key;
			$opOrg = $myconn->prepare("SELECT * FROM ". db_table_pref ."organizations WHERE ID=?") or die(mysqli_error($myconn));
			$opOrg->bind_param('i',$this->OID);
			$opOrg->execute();
			$opOrg->store_result();
			if($opOrg->num_rows==0){
				echo errMod('* '. letheglobal_record_not_found .'','danger');
			}else{
				$sr = new Statement_Result($opOrg);
				$opOrg->fetch();
			}
			
		$this->errPrint = '';
		
		# Remove Organization 
		if(!$sr->Get('isPrimary')){
			if(isset($_POST['del']) && $_POST['del']=='YES'){
				
				# Remove Folder
				deleteAll(LETHE_RESOURCE.DIRECTORY_SEPARATOR.$sr->Get('orgTag'));
							
				# Remove Blacklist
				$myconn->query("DELETE FROM ". db_table_pref ."blacklist WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Remove Autoresponder Actions
				$myconn->query("DELETE FROM ". db_table_pref ."campaign_ar WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Remove Campaign Groups
				$myconn->query("DELETE FROM ". db_table_pref ."campaign_groups WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Remove Campaigns
				$myconn->query("DELETE FROM ". db_table_pref ."campaigns WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Remove Organization Settings
				$myconn->query("DELETE FROM ". db_table_pref ."organization_settings WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Remove Organization
				$myconn->query("DELETE FROM ". db_table_pref ."organizations WHERE ID=". $this->OID ."") or die(mysqli_error($myconn));
				# Reports
				$myconn->query("DELETE FROM ". db_table_pref ."reports WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Short Codes
				$myconn->query("DELETE FROM ". db_table_pref ."short_codes WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Form Fields
				$myconn->query("DELETE FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Forms
				$myconn->query("DELETE FROM ". db_table_pref ."subscribe_forms WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Subscriber Groups
				$myconn->query("DELETE FROM ". db_table_pref ."subscriber_groups WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Subscribers
				$myconn->query("DELETE FROM ". db_table_pref ."subscribers WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Tasks
				$myconn->query("DELETE FROM ". db_table_pref ."tasks WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Templates
				$myconn->query("DELETE FROM ". db_table_pref ."templates WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Unsubscribes
				$myconn->query("DELETE FROM ". db_table_pref ."unsubscribes WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# User Permissions
				$myconn->query("DELETE FROM ". db_table_pref ."user_permissions WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				# Users
				$myconn->query("DELETE FROM ". db_table_pref ."users WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				
				# Remove Cron Tasks
				include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'classes/class.chronos.php');
				$letChr = new Crontab();
				$opCron = $myconn->query("SELECT * FROM ". db_table_pref ."chronos WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				while($opCronRs = $opCron->fetch_assoc()){
					$letChr->removeJob($opCronRs['cron_command']);
				} $opCron->free();
				
				# Remove Crons
				$myconn->query("DELETE FROM ". db_table_pref ."chronos WHERE OID=". $this->OID ."") or die(mysqli_error($myconn));
				
				# Done!
				header('Location: ?p=organizations/organization');
				return false;
				die();
				
			}
		}
		
		if(!isset($_POST['org_name']) || empty($_POST['org_name'])){$this->errPrint .= '* '. organizations_please_enter_a_organization_name .'<br>';}
		
		if(LETHE_AUTH_MODE==2 && PRO_MODE){
			if(!isset($_POST['org_max_user']) || !is_numeric($_POST['org_max_user'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_user_limit .'<br>';}
			if(!isset($_POST['org_max_newsletter']) || !is_numeric($_POST['org_max_newsletter'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_newsletter_limit .'<br>';}
			if(!isset($_POST['org_max_autoresponder']) || !is_numeric($_POST['org_max_autoresponder'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_autoresponder_limit .'<br>';}
			if(!isset($_POST['org_max_subscriber']) || !is_numeric($_POST['org_max_subscriber'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_subscriber_limit .'<br>';}
			if(!isset($_POST['org_max_subscriber_group']) || !is_numeric($_POST['org_max_subscriber_group'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_subscriber_group_limit .'<br>';}
			if(!isset($_POST['org_max_subscribe_form']) || !is_numeric($_POST['org_max_subscribe_form'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_subscribe_form_limit .'<br>';}
			if(!isset($_POST['org_max_blacklist']) || !is_numeric($_POST['org_max_blacklist'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_black_list_limit .'<br>';}
			if(!isset($_POST['org_max_template']) || !is_numeric($_POST['org_max_template'])){$this->errPrint .= '* '. organizations_please_enter_a_maximum_template_limit .'<br>';}
			if(!isset($_POST['org_max_shortcode']) || !is_numeric($_POST['org_max_shortcode'])){$this->errPrint .= '* '. organizations_please_enter_maximum_short_code_limit .'<br>';}
			if(!isset($_POST['org_max_daily_limit']) || !is_numeric($_POST['org_max_daily_limit'])){$this->errPrint .= '* '. organizations_please_enter_a_daily_sending_limit .'<br>';}
			if(!isset($_POST['org_standby_organization']) || !is_numeric($_POST['org_standby_organization'])){$this->errPrint .= '* '. organizations_please_enter_a_standby_time_for_organizations .'<br>';}
		}else{
			$_POST['org_max_user'] = set_org_max_user;
			$_POST['org_max_newsletter'] = set_org_max_newsletter;
			$_POST['org_max_autoresponder'] = set_org_max_autoresponder;
			$_POST['org_max_subscriber'] = set_org_max_subscriber;
			$_POST['org_max_subscriber_group'] = set_org_max_subscriber_group;
			$_POST['org_max_subscribe_form'] = set_org_max_subscribe_form;
			$_POST['org_max_blacklist'] = set_org_max_blacklist;
			$_POST['org_max_template'] = set_org_max_template;
			$_POST['org_max_shortcode'] = set_org_max_shortcode;
			$_POST['org_max_daily_limit'] = set_org_max_daily_limit;
			$_POST['org_standby_organization'] = set_org_standby_organization;
		}
		
		/* Only For Super Admin */
		if(LETHE_AUTH_MODE==2){
			if(!isset($_POST['org_submission_account']) || count($_POST['org_submission_account'])==0){$this->errPrint .= '* '. organizations_please_choose_a_submission_account .'<br>';}else{
				$_POST['org_submission_account'] = implode(',',$_POST['org_submission_account']);
			}
		}else{
			$_POST['org_submission_account'] = set_org_submission_account;
		}
		
			if(!isset($_POST['org_sender_title']) || empty($_POST['org_sender_title'])){$this->errPrint .= '* '. organizations_please_enter_a_sender_title .'<br>';}
			if(!isset($_POST['org_reply_mail']) || !mailVal($_POST['org_reply_mail'])){$this->errPrint .= '* '. organizations_invalid_reply_mail .'<br>';}
			if(!isset($_POST['org_test_mail']) || !mailVal($_POST['org_test_mail'])){$this->errPrint .= '* '. organizations_invalid_test_mail .'<br>';}
			if(!isset($_POST['org_timezone']) || empty($_POST['org_timezone'])){$this->errPrint .= '* '. organizations_please_choose_a_timezone .'<br>';}
			if(!isset($_POST['org_after_unsubscribe']) || !is_numeric($_POST['org_after_unsubscribe'])){$this->errPrint .= '* '. organizations_please_choose_a_unsubscribe_action .'<br>';}
			if(!isset($_POST['org_verification']) || !is_numeric($_POST['org_verification'])){$this->errPrint .= '* '. organizations_please_choose_a_verification_method .'<br>';}
			if(!isset($_POST['org_random_load']) || empty($_POST['org_random_load'])){$_POST['org_random_load']='';}else{$_POST['org_random_load']=1;}
			if(!isset($_POST['org_load_type']) || !is_numeric($_POST['org_load_type'])){$this->errPrint .= '* '. organizations_please_choose_a_load_type .'<br>';}
		
		if($this->errPrint==''){
		
			/* Common Values */
			$this->isPrimary = $sr->Get('isPrimary');
			$billingDate = (($this->billingDate==0) ? '':$this->billingDate);
			$orgTag = (($this->orgTag=='') ? $sr->Get('orgTag'):$this->orgTag);
			$public_key = (($this->public_key=='') ? $sr->Get('public_key'):$this->public_key);
			$private_key = (($this->private_key=='') ? $sr->Get('private_key'):$this->private_key);
			
			# RSS Url
			if(!isset($_POST['org_rss_url']) || empty($_POST['org_rss_url'])){
				# Define as system URL
				$_POST['org_rss_url'] = lethe_root_url.'lethe.newsletter.php?pos=rss&oid='.$public_key;
			}else{
				$_POST['org_rss_url'] = $_POST['org_rss_url'];
			}
			
		
			$addOrg = $myconn->prepare("UPDATE 
														". db_table_pref ."organizations
												SET
														orgTag=?,
														orgName=?,
														billingDate=?,
														isActive=1,
														public_key=?,
														private_key=?,
														rss_url=?
											  WHERE
														ID=". $sr->Get('ID') ."
													") or die(mysqli_error($myconn));
			$addOrg->bind_param('ssssss',
									$orgTag,
									$_POST['org_name'],
									$billingDate,
									$public_key,
									$private_key,
									$_POST['org_rss_url']
									);
			$addOrg->execute();
			$addOrg->close();
			
			/* Organization ID */
			$orgID = $sr->Get('ID');
			
			/* Load Settings */
			global $LETHE_ORG_SET_VALS;
			
			$addSet = $myconn->prepare("UPDATE ". db_table_pref ."organization_settings SET set_val=? WHERE OID=? AND set_key=?") or die(mysqli_error($myconn));
			foreach($LETHE_ORG_SET_VALS as $k=>$v){
				if(!isset($_POST[$v])){$_POST[$v]=constant('set_'.$v);}
				$addSet->bind_param('sis',$_POST[$v],$orgID,$v);
				$addSet->execute();
			} $addSet->close();
					
			unset($_POST);
			$this->isSuccess=1;
		
			$this->errPrint = errMod(''. letheglobal_updated_successfully .'!','success');
		}else{
			$this->errPrint = errMod($this->errPrint,'danger');
		
		}
	
	}
	
	/* System Templates */
	private function createSystemTemplates(){
		
		global $myconn;
		$tempList = array(
							'verification'=>array('name'=>'Verification Mail Template',
													'content'=>'<!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>Lethe Newsletter Verification</title> </head> <body style="margin:0; padding:0; background-color:#EAEEEF; font-family:Tahoma; font-size:12px; color:#000;"> <p>&nbsp;</p> <!-- page content --> <div id="main_lay" style="width: 500px; margin: 50px auto; margin-bottom: 0; padding: 15px; background-color: #fff; -webkit-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); -moz-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); box-shadow: 2px 2px 5px 0px rgba(148,148,148,1);"> <h3>{ORGANIZATION_NAME}<br /><small style="color: #999;">E-Mail Verification</small></h3> <hr style="border: 1px solid #ededed; height: 1px;" /> <p>Hello {SUBSCRIBER_NAME},</p> <p>Welcome to {ORGANIZATION_NAME}! Please take a second to confirm <span style="color: #ec5500;">{SUBSCRIBER_MAIL}</span> as your email address by clicking this link:</p> <p><strong style="color: #0489b1;">{VERIFY_LINK[Click Here!]}</strong></p> <p>Once you do, you will be able to opt-in to notifications of activity and access other features that require a valid email address.</p> <p>Thank You!</p> <hr style="border: 1px solid #ededed; height: 1px;" /> <div style="background-color: #f2f2f2; padding: 7px;"><small> {company_name}<br /> {company_phone_1} - {company_phone_2} </small></div> </div> <div id="ext_lay" style="width: 500px; margin: 2px auto; padding: 15px;"><small>{LETHE_SAVE_TREE}</small></div> <!-- page content --> <p>&nbsp;</p> </body> </html>',
													'prev'=>lethe_admin_url.'images/temp/verification_temp.png'
													),
							'unsubscribe'=>array('name'=>'Unsubscribe Page Template',
													'content'=>'<!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>Lethe Newsletter Unsubscribe</title> </head> <body style="margin:0; padding:0; background-color:#EAEEEF; font-family:Tahoma; font-size:12px; color:#000;"> <p>&nbsp;</p> <!-- page content --> <div id="main_lay" style="width: 500px; margin: 50px auto; margin-bottom: 0; padding: 15px; background-color: #fff; -webkit-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); -moz-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); box-shadow: 2px 2px 5px 0px rgba(148,148,148,1);"> <h3>{ORGANIZATION_NAME}<br /><small style="color: #999;">Unsubscription</small></h3> <hr style="border: 1px solid #ededed; height: 1px;" /> <p>Hello {SUBSCRIBER_NAME},</p> <p>We are sorry to see you go :-(</p> <p>You have been successfully removed from this subscriber list. <br />You will no longer hear from us.</p> <p>{UNSUBSCRIBE_SURVEY}</p> <p>Thank You!</p> <hr style="border: 1px solid #ededed; height: 1px;" /> <div style="background-color: #f2f2f2; padding: 7px;"><small> {company_name}<br /> {company_phone_1} - {company_phone_2} </small></div> </div> <!-- page content --> <p>&nbsp;</p> </body> </html>',
													'prev'=>lethe_admin_url.'images/temp/unsubscribe_temp.png'
													),
							'thank'=>array('name'=>'Subscription Thank Template',
													'content'=>' <!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>Lethe Newsletter Subscription</title> </head> <body style="margin:0; padding:0; background-color:#EAEEEF; font-family:Tahoma; font-size:12px; color:#000;"> <p>&nbsp;</p> <!-- page content --> <div id="main_lay" style="width: 500px; margin: 50px auto; margin-bottom: 0; padding: 15px; background-color: #fff; -webkit-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); -moz-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); box-shadow: 2px 2px 5px 0px rgba(148,148,148,1);"> <h3>{ORGANIZATION_NAME}<br /><small style="color: #999;">Subscription</small></h3> <hr style="border: 1px solid #ededed; height: 1px;" /> <h1>Thank You!</h1> <p>Hello {SUBSCRIBER_NAME},</p> <p>Thank you for subscribing to our newsletter.</p> <p>Your subscription is now complete!</p> <hr style="border: 1px solid #ededed; height: 1px;" /> <div style="background-color: #f2f2f2; padding: 7px;"><small> {company_name}<br /> {company_phone_1} - {company_phone_2} </small></div> </div> <!-- page content --> <p>&nbsp;</p> </body> </html>',
													'prev'=>lethe_admin_url.'images/temp/thank_temp.png'
													),
							'norecord'=>array('name'=>'No Record Found Template',
													'content'=>'<!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>No Record Found</title> </head> <body style="margin:0; padding:0; background-color:#EAEEEF; font-family:Tahoma; font-size:12px; color:#000;"> <p> </p> <!-- page content --> <div id="main_lay" style="width: 500px; margin: 50px auto; margin-bottom: 0; padding: 15px; background-color: #fff; -webkit-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); -moz-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); box-shadow: 2px 2px 5px 0px rgba(148,148,148,1);"> <h3>{ORGANIZATION_NAME}<br /><small style="color: #999;">Error Occurred<br /></small></h3> <hr style="border: 1px solid #ededed; height: 1px;" /> <h1><span style="color: #ff0000;">There No Record Found!</span></h1> Please try again or contact with web administration.<br /><br />Thank you!<br /><br /><hr style="border: 1px solid #ededed; height: 1px;" /> <div style="background-color: #f2f2f2; padding: 7px;"><small> {ORGANIZATION_NAME}<br /></small></div> </div> <!-- page content --> <p> </p> </body> </html>',
													'prev'=>lethe_admin_url.'images/temp/norecord_temp.png'
													),
							'erroroccurred'=>array('name'=>'Error Occurred Template',
													'content'=>'<!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>Error Occurred</title> </head> <body style="margin:0; padding:0; background-color:#EAEEEF; font-family:Tahoma; font-size:12px; color:#000;"> <p> </p> <!-- page content --> <div id="main_lay" style="width: 500px; margin: 50px auto; margin-bottom: 0; padding: 15px; background-color: #fff; -webkit-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); -moz-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); box-shadow: 2px 2px 5px 0px rgba(148,148,148,1);"> <h3>{ORGANIZATION_NAME}<br /><small style="color: #999;">Error Occurred<br /></small></h3> <hr style="border: 1px solid #ededed; height: 1px;" /> <h1><span style="color: #ff0000;">Error Occurred!</span></h1> There is error occurred while request this page!<br /><br />Please try again or contact with web administration.<br /><br />Thank you!<br /><br /><hr style="border: 1px solid #ededed; height: 1px;" /> <div style="background-color: #f2f2f2; padding: 7px;"><small> {ORGANIZATION_NAME}<br /></small></div> </div> <!-- page content --> <p> </p> </body> </html>',
													'prev'=>lethe_admin_url.'images/temp/erroroccurred_temp.png'
													),
							'alreadyverified'=>array('name'=>'Already Verified Template',
													'content'=>'<!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>Already Verified</title> </head> <body style="margin:0; padding:0; background-color:#EAEEEF; font-family:Tahoma; font-size:12px; color:#000;"> <p> </p> <!-- page content --> <div id="main_lay" style="width: 500px; margin: 50px auto; margin-bottom: 0; padding: 15px; background-color: #fff; -webkit-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); -moz-box-shadow: 2px 2px 5px 0px rgba(148,148,148,1); box-shadow: 2px 2px 5px 0px rgba(148,148,148,1);"> <h3>{ORGANIZATION_NAME}<br /><small style="color: #999;">Subscription</small></h3> <hr style="border: 1px solid #ededed; height: 1px;" /> <h1>You have already verified!</h1> <p>Hello {SUBSCRIBER_NAME},</p> You have already been verified your account. Please remove this mail from your mailbox.<br /><br />Thank you!<br /><hr style="border: 1px solid #ededed; height: 1px;" /> <div style="background-color: #f2f2f2; padding: 7px;"><small> {ORGANIZATION_NAME}<br /></small></div> </div> <!-- page content --> <p> </p> </body> </html>',
													'prev'=>lethe_admin_url.'images/temp/already_verified_temp.png'
													)
						);
						
		$tempQry = $myconn->prepare("INSERT INTO ". db_table_pref ."templates SET OID=". $this->OID .",UID=0,temp_name=?,temp_contents=?,temp_prev=?,temp_type=?,isSystem=1") or die(mysqli_error($myconn));
		foreach($tempList as $k=>$v){
			$tname = $v['name'];
			$tcont = $v['content'];
			$tprev = $v['prev'];
			$ttype = $k;
			$tempQry->bind_param('ssss',
										$tname,$tcont,$tprev,$ttype
									);
			$tempQry->execute();
		}
		$tempQry->close();
		
	}
	
	/* Blacklist Add */
	public function addBlacklist(){
	
		global $myconn;
		
		$chkTbl = $myconn->prepare("SELECT * FROM ". db_table_pref ."blacklist WHERE OID=". $this->OID ." AND email=?") or die(mysqli_error($myconn));
		$chkTbl->bind_param('s',$_POST['new_rec_mail']);
		$chkTbl->execute();
		$chkTbl->store_result();
		if($chkTbl->num_rows==0){
		
		$addRec = $myconn->prepare("INSERT INTO ". db_table_pref ."blacklist SET OID=". $this->OID .", email=?,ipAddr=?,reasons=?") or die(mysqli_error($myconn));
		$addRec->bind_param('ssi',$_POST['new_rec_mail'],$_POST['new_rec_ip'],$_POST['new_rec_reason']);
		$addRec->execute();
		$addRec->close();
		
		} $chkTbl->close();
	
	}
	
	/* Add Subscriber */
	public function addSubscriber(){
	
		global $myconn;
		global $LETHE_SUBSCRIBE_SAVE_FIELDS;
		global $LETHE_ORG_SETS;
	
		if(!is_array($this->subscribeData)){
			$this->errPrint = '* Invalid Datas!';
			return false;
		}else{
			$subData = $this->subscribeData;
			$fullData = array();
			$jsonObject = null;
			$GID = 0;
			if(isset($_POST['LetheForm_Mail'])){
				$jsonObject = $_POST['LetheForm_Mail'];
			}
			
			$save_field_vars = array();
			foreach($subData as $k=>$v){
				if($k=='GID'){
					$GID = $v['data'];
					# Dont Add to JSON
					# $fullData[$jsonObject][] = array('label'=>'Group','content'=>$v['data']);
				}else{
					foreach($LETHE_SUBSCRIBE_SAVE_FIELDS as $a=>$b){
						if($a==$v['data']){
							if($v['data']=='subscriber_full_data'){
								$fullData[$jsonObject][] = array('label'=>$v['label'],'content'=>validateDatas($_POST[$k],$v['type']));
							}else{
								$save_field_vars[$a] = validateDatas($_POST[$k],$v['type']);
								# Dont Add to JSON Saved Fields
								# $fullData[$jsonObject][] = array('label'=>$v['label'],'content'=>validateDatas($_POST[$k],$v['type']));
							}
						}
					}
				}
			}
			
			
			/* Local Data */
			$localData = getMyLocal();
			$fetchLocal_country_name = $localData['country_name'];
			$fetchLocal_country_code = $localData['country_code'];
			$fetchLocal_city_name = $localData['city_name'];
			$fetchLocal_region_name = $localData['region_name'];
			$fetchLocal_region_code = $localData['region_code'];
			
			# Dont Add to JSON
/* 			$fullData[$jsonObject][] = array('label'=>'Country','content'=>$localData['country_name']);
			$fullData[$jsonObject][] = array('label'=>'Country Code','content'=>$localData['country_code']);
			$fullData[$jsonObject][] = array('label'=>'City','content'=>$localData['city_name']);
			$fullData[$jsonObject][] = array('label'=>'Region','content'=>$localData['region_name']);
			$fullData[$jsonObject][] = array('label'=>'Region Code','content'=>$localData['region_code']); */
			
			/* Rendered Data */
			$fullData = json_encode($fullData);
			$subscriber_name = ((array_key_exists('subscriber_name',$save_field_vars)) ? $save_field_vars['subscriber_name']:NULL);
			$subscriber_mail = ((array_key_exists('subscriber_mail',$save_field_vars)) ? $save_field_vars['subscriber_mail']:NULL);
			$subscriber_web = ((array_key_exists('subscriber_web',$save_field_vars)) ? $save_field_vars['subscriber_web']:NULL);
			$subscriber_date = ((array_key_exists('subscriber_date',$save_field_vars)) ? date('Y-m-d H:i:s',strtotime($save_field_vars['subscriber_date'])):NULL);
			$subscriber_phone = ((array_key_exists('subscriber_phone',$save_field_vars)) ? $save_field_vars['subscriber_phone']:NULL);
			$subscriber_company = ((array_key_exists('subscriber_company',$save_field_vars)) ? $save_field_vars['subscriber_company']:NULL);
					
			/* Subscriber Key */
			$subKey = encr('lethe'.time().$fullData.uniqid(true).$subscriber_mail);
			$verifyMod = ((isLogged()) ? 2:(($LETHE_ORG_SETS['set_org_verification']==0) ? 2:0));
			
		
			/* Verification Code */
			$genVerifyKey = encr($subKey.uniqid(true));
			
			$addSub = $myconn->prepare("INSERT INTO 
														". db_table_pref ."subscribers 
												SET
														OID = ". $this->OID .",
														GID=". $GID .",
														subscriber_name=?,
														subscriber_mail=?,
														subscriber_web=?,
														subscriber_date=?,
														subscriber_phone=?,
														subscriber_company=?,
														subscriber_full_data=?,
														subscriber_active=1,
														subscriber_verify=". $verifyMod .",
														subscriber_key='". $subKey ."',
														ip_addr='". getIP() ."',
														subscriber_verify_key=?,
														local_country=?,
														local_country_code=?,
														local_city=?,
														local_region=?,
														local_region_code=?,
														add_date='". date('Y-m-d H:i:s') ."'
														
														
										") or die(mysqli_error($myconn));
			$addSub->bind_param('sssssssssssss',
											$subscriber_name,
											$subscriber_mail,
											$subscriber_web,
											$subscriber_date,
											$subscriber_phone,
											$subscriber_company,
											$fullData,
											$genVerifyKey,
											$fetchLocal_country_name,
											$fetchLocal_country_code,
											$fetchLocal_city_name,
											$fetchLocal_region_name,
											$fetchLocal_region_code
								);
			if(!$addSub->execute()){
				$this->errPrint = '* Subscriber Cannot Be Added to Database!<br>'.mysqli_error($myconn);
				return false;
			}else{
			
				/* Send Verification */
				if(!isLogged()){ # Dont send verification if admin add on panel
					$this->SUBID = $myconn->insert_id;
					$this->sendVerify();
				}
			
			}
			$addSub->close();
			
			return true;
			
		}
	}
	
	/* Remove Subscriber */
	public function removeSubscription($smail,$removeReport=true){
		
		global $myconn;
		$orgIDs = $this->OID;
		
		
		$myconn->query("
			DELETE 
					". db_table_pref ."subscribers,
					". db_table_pref ."tasks,
					". db_table_pref ."unsubscribes,
					". db_table_pref ."reports
			  FROM 
					". db_table_pref ."subscribers
					LEFT JOIN ". db_table_pref ."tasks ON ". db_table_pref ."subscribers.subscriber_mail = ". db_table_pref ."tasks.subscriber_mail
					LEFT JOIN ". db_table_pref ."unsubscribes ON ". db_table_pref ."subscribers.subscriber_mail = ". db_table_pref ."unsubscribes.subscriber_mail
					LEFT JOIN ". db_table_pref ."reports ON ". db_table_pref ."subscribers.subscriber_mail = ". db_table_pref ."reports.email
			 WHERE 
					". db_table_pref ."subscribers.subscriber_mail = '". mysql_prep($smail) ."'
		") or die(mysqli_error($myconn));
		
/* 		# Remove From Subscribers
		$remS = $myconn->prepare("DELETE FROM ". db_table_pref ."subscribers WHERE OID=". $orgIDs ." AND subscriber_mail=?") or die(mysqli_error($myconn));
		$remS->bind_param('s',$smail);
		$remS->execute(); $remS->close();
		
		# Remove From Tasks
		$remS = $myconn->prepare("DELETE FROM ". db_table_pref ."tasks WHERE OID=". $orgIDs ." AND subscriber_mail=?") or die(mysqli_error($myconn));
		$remS->bind_param('s',$smail);
		$remS->execute(); $remS->close();
		
		# Remove From Subscribers
		$remS = $myconn->prepare("DELETE FROM ". db_table_pref ."unsubscribes WHERE OID=". $orgIDs ." AND subscriber_mail=?") or die(mysqli_error($myconn));
		$remS->bind_param('s',$smail);
		$remS->execute(); $remS->close();
		
		# Remove From Reports
		if($removeReport){
			$remS = $myconn->prepare("DELETE FROM ". db_table_pref ."reports WHERE OID=". $orgIDs ." AND email=?") or die(mysqli_error($myconn));
			$remS->bind_param('s',$smail);
			$remS->execute(); $remS->close();
		} */
		
		
	}

	/* Build Subscriber JSON Data */
	public function buildJSON($ID){
		
		global $myconn;
		
		# Get OID If its Not Set (Requires Private Key)
		if($this->OID==0){
			$opOr = $myconn->prepare("SELECT ID,private_key FROM ". db_table_pref ."organizations WHERE private_key=?") or die(mysqli_error($myconn));
			$opOr->bind_param('s',$this->private_key);
			$opOr->execute();
			$opOr->store_result();
			if($opOr->num_rows==0){
				$opOr->close();
				return false;
			}else{
				$oidPVTK = new Statement_Result($opOr);
				$opOr->fetch();
				$opOr->close();
				$this->OID = $oidPVTK->Get('ID');
			}
		}
		
		# Open Subscriber
		$opSub = $myconn->query("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". $this->OID ." AND ID=". intval($ID) ."") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opSub)!=0){
			$opSubRs = $opSub->fetch_assoc();
			$currJson = json_decode($opSubRs['subscriber_full_data'],true);
			
			# Static
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Group','content'=>$opSubRs['GID']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Name','content'=>$opSubRs['subscriber_name']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'E-Mail','content'=>$opSubRs['subscriber_mail']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Web','content'=>$opSubRs['subscriber_web']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Date','content'=>$opSubRs['subscriber_date']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Phone','content'=>$opSubRs['subscriber_phone']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Company','content'=>$opSubRs['subscriber_company']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Country','content'=>$opSubRs['local_country']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Country Code','content'=>$opSubRs['local_country_code']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'City','content'=>$opSubRs['local_city']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Region','content'=>$opSubRs['local_region']);
				$currJson[$opSubRs['subscriber_mail']][]=array('label'=>'Region Code','content'=>$opSubRs['local_region_code']);
				# $staticDts = array('Group','Name','E-Mail','Web','Date','Phone','Company','Country','Country Code','City','Region','Region Code');
					
			
			$newJson = json_encode($currJson);
			return $newJson;
			
		} 
		$opSub->free();
		
	}
	
	/* Unsubscribe Action */
	public function getUnsubscribing($smail,$CID=0,$typ){
		
		global $myconn;
		
		# typ 0 - Mark It Inactive
		# typ 1 - Force Remove
		# typ 2 - Move to Unsubscribe
		
		$keyOrMail = ((!mailVal($smail)) ? false:true); # true is mail control
		
		# Check Record Availability
		$chkRec = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". $this->OID ." AND ". (($keyOrMail) ? 'subscriber_mail':'subscriber_key') ."=?") or die(mysqli_error($myconn));
		$chkRec->bind_param('s',$smail);
		$chkRec->execute();
		$chkRec->store_result();
		if($chkRec->num_rows==0){$chkRec->close();return false;}else{
			$srUns = new Statement_Result($chkRec);
			$chkRec->fetch();
			$chkRec->close();
		}
		
		if($typ==0){
			# If Action is Campaign, Subscriber Will Add to Unsubscribe Reports Table
			$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_active=0 WHERE ID=". intval($srUns->Get('ID')) ."") or die(mysqli_error($myconn));
			if($CID!=0){
				$chkTbl = $myconn->prepare("SELECT * FROM ". db_table_pref ."unsubscribes WHERE OID=". $this->OID ." AND CID=? AND subscriber_mail=?") or die(mysqli_error($myconn));
				$chkTbl->bind_param('is',$CID,$smail);
				$chkTbl->execute(); $chkTbl->store_result();
				if($chkTbl->num_rows==0){
					$addUns = $myconn->prepare("INSERT INTO ". db_table_pref ."unsubscribes SET OID=". $this->OID .", CID=?, subscriber_mail=?, add_date='". date('Y-m-d H:i:s') ."'") or die(mysqli_error($myconn));
					$addUns->bind_param('is',$CID,$smail);
					$addUns->execute();
					$addUns->close();
				} $chkTbl->close();
			}
			return true;
		}
		else if($typ==1){
			# If Action is Campaign, Subscriber Will Add to Unsubscribe Reports Table
			$smail = $srUns->Get('subscriber_mail');
			$this->removeSubscription($smail);
			
			if($CID!=0){
				$chkTbl = $myconn->prepare("SELECT * FROM ". db_table_pref ."unsubscribes WHERE OID=". $this->OID ." AND CID=? AND subscriber_mail=?") or die(mysqli_error($myconn));
				$chkTbl->bind_param('is',$CID,$smail);
				$chkTbl->execute(); $chkTbl->store_result();
				if($chkTbl->num_rows==0){
					$addUns = $myconn->prepare("INSERT INTO ". db_table_pref ."unsubscribes SET OID=". $this->OID .", CID=?, subscriber_mail=?, add_date='". date('Y-m-d H:i:s') ."'") or die(mysqli_error($myconn));
					$addUns->bind_param('is',$CID,$smail);
					$addUns->execute();
					$addUns->close();
				} $chkTbl->close();
			}
			return true;
		}
		else if($typ==2){
			# If Action is Campaign, Subscriber Will Add to Unsubscribe Reports Table
			$opGrp = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". $this->OID ." AND isUnsubscribe=1") or die(mysqli_error($myconn));
			if(mysqli_num_rows($opGrp)==0){$opGrp->free(); return false;}else{
				$opGrpRs = $opGrp->fetch_assoc();
				$GRP = $opGrpRs['ID'];
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET GID=". $GRP ." WHERE ID=". intval($srUns->Get('ID')) ."") or die(mysqli_error($myconn));
			if($CID!=0){
				$chkTbl = $myconn->prepare("SELECT * FROM ". db_table_pref ."unsubscribes WHERE OID=". $this->OID ." AND CID=? AND subscriber_mail=?") or die(mysqli_error($myconn));
				$chkTbl->bind_param('is',$CID,$smail);
				$chkTbl->execute(); $chkTbl->store_result();
				if($chkTbl->num_rows==0){
					$addUns = $myconn->prepare("INSERT INTO ". db_table_pref ."unsubscribes SET OID=". $this->OID .", CID=?, subscriber_mail=?, add_date='". date('Y-m-d H:i:s') ."'") or die(mysqli_error($myconn));
					$addUns->bind_param('is',$CID,$smail);
					$addUns->execute();
					$addUns->close();
				} $chkTbl->close();
			}
				$opGrp->free();
				return true;
			}
		}
		
	}

	/* Verification Mail Sender */
	public function sendVerify($mod=1){
	
		global $myconn;
		global $LETHE_ORG_SETS;
		
		# Mod 1 - Single
		# Mod 2 - Double
		# Only OID and SUBID required for simple verification sender calling, Mod value can be changed into first verification page
		
		/* Load Verification Template */
		$opTemp = $myconn->query("
								   SELECT 
											TEMP.temp_type, TEMP.temp_name, TEMP.temp_contents,
											ORG.ID,ORG.orgName,ORG.public_key AS OPLKEY,
											SBR.ID AS SBRID,
											SBR.subscriber_name, SBR.subscriber_mail, SBR.subscriber_web, SBR.subscriber_date, SBR.subscriber_phone, SBR.subscriber_company,
											SBR.subscriber_verify,SBR.subscriber_verify_key,SBR.subscriber_key,
											ORGSET.OID AS OSOID,
											ORGSET.set_key,ORGSET.set_val
											
								     FROM 
											". db_table_pref ."templates AS TEMP,
											". db_table_pref ."organizations AS ORG,
											". db_table_pref ."organization_settings AS ORGSET,
											". db_table_pref ."subscribers AS SBR
								    WHERE 
											ORG.ID=". $this->OID ." 
									  AND 
											(TEMP.OID=ORG.ID AND TEMP.temp_type='verification')
									  AND
											(SBR.ID=". $this->SUBID .")
									  AND
											(ORGSET.OID=". $this->OID .")
									  AND
											(ORGSET.set_key='org_submission_account' OR ORGSET.set_key='org_sender_title' OR ORGSET.set_key='org_reply_mail')
									
									") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opTemp)==0){
			$opTemp->free();
			return false;
		}else{
			$opTempRs = $opTemp->fetch_assoc();
			
			$replaced = $this->shortReplaces(array($opTempRs['temp_name'],$opTempRs['temp_contents']));
			$mailTitle = $replaced[0];
			$mailBody = $replaced[1];
			
			/* Special System Codes */
			$find = array(
			'{SUBSCRIBER_NAME}',
			'{SUBSCRIBER_MAIL}',
			'{SUBSCRIBER_PHONE}',
			'{SUBSCRIBER_COMPANY}'
			);
			
			$replace = array(
			$opTempRs['subscriber_name'],
			$opTempRs['subscriber_mail'],
			$opTempRs['subscriber_phone'],
			$opTempRs['subscriber_company']
			);
			
			$mailBody = str_replace($find,$replace,$mailBody);
			$mailTitle = str_replace($find,$replace,$mailTitle);
			
			/* Verify Code Replacer */
			$mailBody = preg_replace('#\{?(VERIFY_LINK\[)(.*?)\\]}#','<a href="'. lethe_root_url .'lethe.newsletter.php?pos=verification&amp;oid='. $opTempRs['OPLKEY'] .'&amp;sid='. $opTempRs['subscriber_key'] .'&amp;rt='. (($mod==1) ? $opTempRs['subscriber_verify_key']:encr($opTempRs['subscriber_verify_key'])) .'">$2</a>',$mailBody);
			
			/* Send Mail */

			$subAccs = explode(',',$LETHE_ORG_SETS['set_org_submission_account']);
			if(count($subAccs)<1){return false;}else{
				$OSMIDs = $subAccs[0];
			}
			
			
			$this->OSMID=$OSMIDs;
			$this->sub_from_title = showIn($LETHE_ORG_SETS['set_org_sender_title'],'page');
			$this->sub_reply_mail = showIn($LETHE_ORG_SETS['set_org_reply_mail'],'page');
			$this->orgSubInit(); # Load Submission Settings
			$this->sub_mail_id = md5($opTempRs['subscriber_mail']);
			
			/* Design Receiver Data */
			$rcMail = $opTempRs['subscriber_mail'];
			$rcName = $opTempRs['subscriber_name'];
			$rcSubject = trim($mailTitle);
			$rcBody = $mailBody;
			$rcAltBody = '';
			$recData = array($rcMail=>array(
											'name'=>$rcName,
											'subject'=>$rcSubject,
											'body'=>$rcBody,
											'altbody'=>$rcAltBody,
											)						
							);
			$this->sub_mail_receiver = $recData;
			$this->letheSender();
			if($this->sendPos){
				/* Update Interval */
				$intDate = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')."+2 minutes")); # Next submmission will execute 2 min later
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_verify_sent_interval='". $intDate ."' WHERE ID=". $this->SUBID ."") or die(mysqli_error($myconn));
				$opTemp->free();return true;
			}else{$opTemp->free();return false;}
			
		}
		$opTemp->free();
		return false;
	
	}
	
	/* Short Code Replacer */
	public function shortReplaces($datas=array()){

		global $myconn;
		global $LETHE_ORG_SETS;
		# This function only used for custom codes, system codes will used in newsletter sending actions
		# Datas can be used in array, each keys will return to replaced version like $short[0] - subject , $short[1] - body
		# Called data array keys must be defined for callbacks
		
		/* Load Dynamic Codes */
		$find = array();
		$replace = array();
		$orgName = $LETHE_ORG_SETS['set_org_name'];
		$opCodes = $myconn->query("SELECT 
											*
									 FROM 
											". db_table_pref ."short_codes
									WHERE 
											OID=". $this->OID ."
											
										") or die(mysqli_error($myconn));
		while($opCodesRs = $opCodes->fetch_assoc()){
			$find[] = '{'.$opCodesRs['code_key'].'}';
			$replace[] = $opCodesRs['code_val'] ;
		} $opCodes->free();
		
		# Special System Codes
		# Additional codes could be added here (Different date types etc.)
		$find[] = '{ORGANIZATION_NAME}';
		$find[] = '{CURR_DATE}';
		$find[] = '{CURR_MONTH}';
		$find[] = '{CURR_YEAR}';
		$find[] = '{LETHE_SAVE_TREE}';
		$replace[] = $orgName;
		$replace[] = date("d/m/Y");
		$replace[] = date("m");
		$replace[] = date("Y");
		$replace[] = lethe_save_tree;
		
		foreach($datas as $k=>$v){
			$datas[$k] = str_replace($find,$replace,$v);
		}
		return $datas;

	}
		
	/* E-Mail Sender */
	public function letheSender(){
	
		global $LETHE_MAIL_ENGINE;
		global $myconn;
	
		/* Load Engine */
		if($this->sub_success){
			include_once($LETHE_MAIL_ENGINE[$this->sub_mail_engine]['init']);
		}else{
			$this->sendPos = false;
			return false;
		}
	
	}
	
	/* System E-Mail Sender */
	public function sysSubInit(){
	
		global $myconn;
	
		
			/* Load System Submission Account */
			$opSysSub = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE systemAcc=1") or die(mysqli_error($myconn));
			if(mysqli_num_rows($opSysSub)==0){
				die('Error: System Submission Account Cannot be Loaded!');
			}else{
				$opSysSubRs = $opSysSub->fetch_assoc();
				$this->sub_from_title = $opSysSubRs['from_title'];
				$this->sub_from_mail = $opSysSubRs['from_mail'];
				$this->sub_reply_mail = $opSysSubRs['reply_mail'];
				$this->sub_test_mail = $opSysSubRs['test_mail'];
				$this->sub_mail_type = $opSysSubRs['mail_type'];
				$this->sub_send_method = $opSysSubRs['send_method'];
				$this->sub_mail_engine = $opSysSubRs['mail_engine'];
				$this->sub_smtp_host = $opSysSubRs['smtp_host'];
				$this->sub_smtp_port = $opSysSubRs['smtp_port'];
				$this->sub_smtp_user = $opSysSubRs['smtp_user'];
				$this->sub_smtp_pass = $opSysSubRs['smtp_pass'];
				$this->sub_smtp_secure = $opSysSubRs['smtp_secure'];
				$this->sub_smtp_auth = $opSysSubRs['smtp_auth'];
				$this->sub_aws_access_key = $opSysSubRs['aws_access_key'];
				$this->sub_aws_secret_key = $opSysSubRs['aws_secret_key'];
				$this->sub_mandrill_user = $opSysSubRs['mandrill_user'];
				$this->sub_mandrill_key = $opSysSubRs['mandrill_key'];
				$this->sub_sendgrid_user = $opSysSubRs['sendgrid_user'];
				$this->sub_sendgrid_pass = $opSysSubRs['sendgrid_pass'];
				$this->sub_dkim_active = $opSysSubRs['dkim_active'];
				$this->sub_dkim_domain = $opSysSubRs['dkim_domain'];
				$this->sub_dkim_private = $opSysSubRs['dkim_private'];
				$this->sub_dkim_selector = $opSysSubRs['dkim_selector'];
				$this->sub_dkim_passphrase = $opSysSubRs['dkim_passphrase'];
				$this->sub_isDebug = $opSysSubRs['isDebug'];
				$this->OSMID = $opSysSubRs['ID'];
				
				/* Limit Check */
				if($opSysSubRs['daily_sent']>=$opSysSubRs['daily_limit']){
					$this->sendingErrors = letheglobal_sending_limit_exceeded;
					$this->sub_success = false;
				}
				
				$this->letheSender();
				
			} $opSysSub->free();
		
	}
	
	/* Organization E-Mail Sender */
	public function orgSubInit(){
	
		global $myconn;
	
		
			/* Load System Submission Account */
			$opSysSub = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE ID=". $this->OSMID ." AND isActive=1") or die(mysqli_error($myconn));
			if(mysqli_num_rows($opSysSub)==0){
				die('Error: Submission Account Cannot be Loaded!');
			}else{
				$opSysSubRs = $opSysSub->fetch_assoc();
				$this->sub_from_mail = $opSysSubRs['from_mail'];
				$this->sub_mail_type = $opSysSubRs['mail_type'];
				$this->sub_send_method = $opSysSubRs['send_method'];
				$this->sub_mail_engine = $opSysSubRs['mail_engine'];
				$this->sub_smtp_host = $opSysSubRs['smtp_host'];
				$this->sub_smtp_port = $opSysSubRs['smtp_port'];
				$this->sub_smtp_user = $opSysSubRs['smtp_user'];
				$this->sub_smtp_pass = $opSysSubRs['smtp_pass'];
				$this->sub_smtp_secure = $opSysSubRs['smtp_secure'];
				$this->sub_smtp_auth = $opSysSubRs['smtp_auth'];
				$this->sub_aws_access_key = $opSysSubRs['aws_access_key'];
				$this->sub_aws_secret_key = $opSysSubRs['aws_secret_key'];
				$this->sub_mandrill_user = $opSysSubRs['mandrill_user'];
				$this->sub_mandrill_key = $opSysSubRs['mandrill_key'];
				$this->sub_sendgrid_user = $opSysSubRs['sendgrid_user'];
				$this->sub_sendgrid_pass = $opSysSubRs['sendgrid_pass'];
				$this->sub_dkim_active = $opSysSubRs['dkim_active'];
				$this->sub_dkim_domain = $opSysSubRs['dkim_domain'];
				$this->sub_dkim_private = $opSysSubRs['dkim_private'];
				$this->sub_dkim_selector = $opSysSubRs['dkim_selector'];
				$this->sub_dkim_passphrase = $opSysSubRs['dkim_passphrase'];
				$this->sub_isDebug = $opSysSubRs['isDebug'];
				
				/* Limit Check */
				if($opSysSubRs['daily_sent']>=$opSysSubRs['daily_limit']){
					$this->sendingErrors = letheglobal_sending_limit_exceeded;
					$this->sub_success = false;
				}
				
			} $opSysSub->free();
		
	}
	
	/* Cron Command Builder */
	public function buildChronos(){
		$build = $this->chronosMin.' ';
		$build .= $this->chronosHour.' ';
		$build .= $this->chronosDay.' ';
		$build .= $this->chronosMonth.' ';
		$build .= $this->chronosWeek.' ';
		$build .= $this->chronosCommand.' ';
		$build .= $this->chronosURL;
		//$build .= '> /dev/null 2>&1';
		return $build;
	}
	
	/* Load Organization */
	public function loadOrg($o){
		
		global $orgSets;
		global $myconn;
		
		$opOrg = $myconn->query("SELECT 
											O.*
											
								   FROM 
											". db_table_pref ."organizations AS O
								  WHERE 
											O.ID=". $o ." 
								    AND 
											O.isActive=1
									") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opOrg)==0){return false;}else{
			$opOrgRs = $opOrg->fetch_assoc();
			# Load Settings
			foreach($opOrgRs as $k=>$v){
				$orgSets['set_'.$k] = $v;
			}
			
			$orgSets['set_org_name'] = $opOrgRs['orgName'];
			$orgSets['set_org_rss_url'] = $opOrgRs['rss_url'];
			
			$opSets = $myconn->query("SELECT OID,set_key,set_val FROM ". db_table_pref ."organization_settings WHERE OID=". $opOrgRs['ID'] ."");
			while($opSetsRs = $opSets->fetch_assoc()){
				$orgSets['set_'.$opSetsRs['set_key']] = $opSetsRs['set_val'];
			} $opSets->free();
			$opOrg->free();
			
			/* Check Daily Limit */
			if($orgSets['set_org_max_daily_limit']!=0){
				if($orgSets['set_daily_sent']>=$orgSets['set_org_max_daily_limit']){return false;}
			}
			
			/* Submission Account */
			$subAccs = explode(',',$orgSets['set_org_submission_account']);
			if(count($subAccs)<1){return false;}else{
				$OSMIDs = $subAccs[0];
			}
			$opSubAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE ID=". $OSMIDs ." AND isActive=1 AND daily_sent<=daily_limit") or die(mysqli_error($myconn));
			if(mysqli_num_rows($opSubAcc)==0){return false;} # Submission Account Doesnt Meet Conditions
			else{
				$opSubAccRs = $opSubAcc->fetch_assoc();
				$orgSets['set_send_per_conn'] = $opSubAccRs['send_per_conn'];
				$orgSets['set_standby_time'] = $opSubAccRs['standby_time'];
			} $opSubAcc->free();
			
			@date_default_timezone_set($orgSets['set_org_timezone']); # Org Timezone
			
			return true;
		}
		
	}
	
	/* Add to Report */
	public function addReport(){
		
		global $myconn;
		
		# Check Exists
		$chkRep = $myconn->prepare("SELECT * FROM ". db_table_pref ."reports WHERE OID=? AND pos=? AND email=? AND CID=?") or die(mysqli_error($myconn));
		$chkRep->bind_param('iisi',
									$this->OID,
									$this->reportPos,
									$this->reportMail,
									$this->reportCID
							);
		$chkRep->execute();
		$chkRep->store_result();
		if($chkRep->num_rows==0){ # Add New
			
			$addRep = $myconn->prepare("INSERT INTO 
														". db_table_pref ."reports
												SET
														OID=?,
														CID=?,
														pos=?,
														ipAddr=?,
														email=?,
														bounceType=?,
														extra_info=?
												") or die(mysqli_error($myconn));
			$addRep->bind_param('iiissss',
											$this->OID,
											$this->reportCID,
											$this->reportPos,
											$this->reportIP,
											$this->reportMail,
											$this->reportBounceType,
											$this->reportExtraInfo
									);
			$addRep->execute();
			$addRep->close();
			$chkRep->close();
			return true;
			
		}else{ # Update Hit
			
			$updST = new Statement_Result($chkRep);
			$chkRep->fetch();
			
			$this->reportExtraInfo = $updST->Get('extra_info') . $this->reportExtraInfo;
			$updRep = $myconn->prepare("UPDATE 
														". db_table_pref ."reports
												SET
														hit_cnt=hit_cnt+1, extra_info=?
												WHERE
														OID=? AND pos=? AND email=? AND CID=?
												") or die(mysqli_error($myconn));
			$updRep->bind_param('siisi',
										$this->reportExtraInfo,
										$this->OID,
										$this->reportPos,
										$this->reportMail,
										$this->reportCID
								);
			$updRep->execute();
			$updRep->close();
			$chkRep->close();
			return true;
			
		}
	
	
		
		
	}
	
	/* Bounce Action */
	private function bounceActs(){
		
		# 0 - Remove, 1 - Remove / Blacklist, 2 - Unsubscribe
		if($this->bounceAction==0){
			# Force Remove by Mail
			$this->removeSubscription($this->reportMail,false); # False Dont Remove Reports
			return true;
		}
		else if($this->bounceAction==1){
			# Add to Blacklist
			$_POST['new_rec_mail'] = $this->reportMail;
			$_POST['new_rec_ip'] = '0.0.0.0';
			$_POST['new_rec_reason'] = 1; # Bounce
			$this->addBlacklist();
			
			# Force Remove by Mail
			$this->removeSubscription($this->reportMail,false); # False Dont Remove Reports
			return true;
		}
		else if($this->bounceAction==2){
			# Move to Unsubscribe
			$this->getUnsubscribing($this->reportMail,$this->reportCID,2);
			return true;
		}
		
		return false;
	}
	
	/* Bounce Handler */
	public function bounceHandle(){
		
		global $myconn;
		
		# Open Campaign
		if(!empty($this->bounceKey)){
			$campID = $this->bounceKey;
			
			$opCamp = $myconn->prepare("SELECT * FROM ". db_table_pref ."campaigns WHERE campaign_key=?") or die(mysqli_error($myconn));
			$opCamp->bind_param('s',$campID);
			if($opCamp->execute()){
				$opCamp->store_result();
				if($opCamp->num_rows!=0){
					$srArg = new Statement_Result($opCamp);
					$opCamp->fetch();
					$opCamp->close();
										
					# Add to Reports
					$this->reportCID = $srArg->Get('ID');
					$this->OID = $srArg->Get('OID');
					$this->reportPos = 2; # Bounce
					if($this->addReport()){
						
						# Apply Action
						if($this->bounceActs()){
							return true;
						}else{
							return false;
						}
						
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
		
	}

} # Lethe Class End

class Statement_Result
{
    private $_bindVarsArray = array();
    private $_results = array();

    public function __construct(&$stmt)
    {
        $meta = $stmt->result_metadata();

        while ($columnName = $meta->fetch_field())
            $this->_bindVarsArray[] = &$this->_results[$columnName->name];

        call_user_func_array(array($stmt, 'bind_result'), $this->_bindVarsArray);
       
        $meta->close();
    }
   
    public function Get_Array()
    {
        return $this->_results;   
    }
   
    public function Get($column_name)
    {
        return $this->_results[$column_name];
    }
} 
?>