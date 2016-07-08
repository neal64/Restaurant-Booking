<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 20.01.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
include_once('lethe.php'); cors();
include_once(LETHE.DIRECTORY_SEPARATOR.'lib/lethe.class.php');
$SERVER_MODE = true;

error_reporting(E_ALL);

/* Request */
$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));
$rt = ((!isset($_GET['rt']) || empty($_GET['rt'])) ? '':trim($_GET['rt'])); # Verification Code
$id = ((!isset($_GET['id']) || empty($_GET['id'])) ? '':trim($_GET['id'])); # Specific ID / Key
$sid = ((!isset($_GET['sid']) || empty($_GET['sid'])) ? '':trim($_GET['sid'])); # Subscriber ID / Key
$oid = ((!isset($_GET['oid']) || empty($_GET['oid'])) ? '':trim($_GET['oid'])); # Organization ID / Key
$redu = ((!isset($_GET['redu']) || empty($_GET['redu'])) ? '':trim($_GET['redu'])); # Redirect URL

/* Subscription */
if($pos=='subscribe'){
	$errText = '';
	$saveMod = true;
	if(!isDemo('lethe_form')){die(errMod(letheglobal_demo_mode_active,'danger'));}
	
		if(isset($_POST['lethe_form']) && !empty($_POST['lethe_form'])){
			
			/* Load Organization Settings */
			if(!isset($_POST['lethe_oid']) || $_POST['lethe_oid']==''){
				die(errMod('Settings Could Not Be Loaded!','danger'));
			}else{
				$chkOrg = $myconn->prepare("SELECT ID,public_key FROM ". db_table_pref ."organizations WHERE public_key=?") or die(mysqli_error($myconn));
				$chkOrg->bind_param('s',$_POST['lethe_oid']);
				$chkOrg->execute();
				$chkOrg->store_result();
				if($chkOrg->num_rows==0){
					die(errMod('Settings Could Not Be Loaded!','danger'));
				}else{
					$srOrg = new Statement_Result($chkOrg);
					$chkOrg->fetch();
					include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'inc/org_set.php');
				}
			}
			
			/* Source Limit */
			$sourceLimit = calcSource($srOrg->Get('ID'),'subscribers');
			
			$opForm = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribe_forms WHERE form_id=? AND OID=". $srOrg->Get('ID') ."") or die(mysqli_error($myconn));
			$opForm->bind_param('s',$_POST['lethe_form']);
			$opForm->execute();
			$opForm->store_result();
			if($opForm->num_rows==0){
				$errText = '<div class="alert alert-danger">'. letheglobal_subscribe_form_error .'</div>';
			}else{
				$sr = new Statement_Result($opForm);
				$opForm->fetch();
				
				/* Form Errors */
				$formErrors = explode("[@]",$sr->Get('form_errors'));
				
				/* Stopped Subscription */
				if(intval($sr->Get('subscription_stop'))!=0){
					die(errMod(showIn($formErrors[3],'page'),'danger')); # Custom Error (Subscription Stopped)				
				}
				
				/* Fetch Form Variables */
				$opFields = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_form_fields WHERE FID=". $sr->Get('ID') ." ORDER BY sorting ASC") or die(mysqli_error($myconn));
				$errText = '';
				$saveList = array();
				$GRP = 0;
				
				# Check Group Availability
				$opGrp = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". intval($srOrg->Get('ID')) ." AND ID=". $sr->Get('form_group') ."") or die(mysqli_error($myconn));
				if(mysqli_num_rows($opGrp)==0){
					# There no group found, Add to Ungroup
					$GRP = getOrgData($srOrg->Get('ID'),0);
				}else{
					$GRP = $sr->Get('form_group');
				}
				
				$saveList['GID']['data'] = $GRP;
				$saveList['GID']['type'] = 'number';
				$saveList['GID']['label'] = 'Group';
				while($opFieldsRs = $opFields->fetch_assoc()){
					/* Check errors */
					if(isset($_POST[$opFieldsRs['field_name']])){
						if($opFieldsRs['field_required']==1){ # Required Fields
							if(empty($_POST[$opFieldsRs['field_name']])){
								$errText.='* '. showIn($opFieldsRs['field_error'],'page') .'<br>';
							}else{
								/* E-Mail */
								if($opFieldsRs['field_type']=='email'){
									if(!mailVal($_POST['LetheForm_Mail'])){
										$errText.='* '. showIn($opFieldsRs['field_error'],'page') .'<br>';
									}else{
										/* Exists Data Control */
										$chkData = $myconn->prepare("SELECT ID,OID,subscriber_mail FROM ". db_table_pref ."subscribers WHERE OID=". $srOrg->Get('ID') ." AND subscriber_mail=?") or die(mysqli_error($myconn));
										$chkData->bind_param('s',$_POST['LetheForm_Mail']);
										$chkData->execute();
										$chkData->store_result();
											if($chkData->num_rows!=0){
												$errText.=showIn($formErrors[1],'page').'<br>'; # Custom Form Errors (Mail already exists)
											}else{
												/* Blacklist Check */
												$chkDataBlk = $myconn->prepare("SELECT ID,OID,email,ipAddr FROM ". db_table_pref ."blacklist WHERE OID=". $srOrg->Get('ID') ." AND (email=? OR ipAddr='". $_SERVER['REMOTE_ADDR'] ."')") or die(mysqli_error($myconn));
												$chkDataBlk->bind_param('s',$_POST['LetheForm_Mail']);
												$chkDataBlk->execute();
												$chkDataBlk->store_result();
													if($chkDataBlk->num_rows!=0){
														$errText.=showIn($formErrors[2],'page').'<br>'; # Custom Form Errors (Banned Mail)
													}else{
														$saveList[$opFieldsRs['field_name']]['data'] = $opFieldsRs['field_save'];
														$saveList[$opFieldsRs['field_name']]['type'] = 'email';
														$saveList[$opFieldsRs['field_name']]['label'] = $opFieldsRs['field_label'];
													}
													$chkDataBlk->close();
											}
										$chkData->close();
									}
								}
								/* reCaptcha */
								else if($opFieldsRs['field_type']=='recaptcha'){
									
									# Disable for Admin
									if(!isLogged()){
								
										# reCaptcha Data
										$reCaptData = explode("@",$opFieldsRs['field_data']);
										
										# reCaptcha API V2
										if($reCaptData[0]=='v2'){
											
										$siteKey = lethe_google_recaptcha_public;
										$secret = lethe_google_recaptcha_private;
										
										if(!isset($_POST['g-recaptcha-response'])){
											$errText.=showIn($opFieldsRs['field_error'],'page') .'<br>';
										}else{
											require_once(LETHE.DIRECTORY_SEPARATOR.'lib/reCaptcha/ReCaptchaV2/autoload.php');
											$recaptcha = new \ReCaptcha\ReCaptcha($secret);
											$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
											if (!$resp->isSuccess()){
												$errText.=showIn($opFieldsRs['field_error'],'page') .'<br>';
											}
										}
										
										# reCaptcha API V1
										}else{
											require_once(LETHE.DIRECTORY_SEPARATOR.'lib/reCaptcha/recaptchalib.php');
											$privatekey = lethe_google_recaptcha_private;
											
											$resp = recaptcha_check_answer ($privatekey,
																		$_SERVER["REMOTE_ADDR"],
																		$_POST["recaptcha_challenge_field"],
																		$_POST["recaptcha_response_field"]);
											if (!$resp->is_valid) {
												$errText.=showIn($opFieldsRs['field_error'],'page') .'<br>';
											}
											
										}
									
									
									}
								}
								/* add - remove */
								else if($opFieldsRs['field_type']=='addremove'){
								
									$saveMod = (($_POST[$opFieldsRs['field_name']]=='REM') ? false:true);
									
								/* For Others */
								}else{
									$saveList[$opFieldsRs['field_name']]['data'] = $opFieldsRs['field_save'];
									$saveList[$opFieldsRs['field_name']]['type'] = $opFieldsRs['field_type'];
									$saveList[$opFieldsRs['field_name']]['label'] = $opFieldsRs['field_label'];
								}
								
							}
						}else{
							$saveList[$opFieldsRs['field_name']]['data'] = $opFieldsRs['field_save'];
							$saveList[$opFieldsRs['field_name']]['type'] = $opFieldsRs['field_type'];
							$saveList[$opFieldsRs['field_name']]['label'] = $opFieldsRs['field_label'];
							
						}
					}else{
						if($opFieldsRs['field_required']==1){
							$errText.='* '. showIn($opFieldsRs['field_error'],'page') .'<br>';
						}else{
							$_POST[$opFieldsRs['field_name']] = null;
						}
					}
				} $opFields->free();
				
				/* Check Subscriber Existed */
				
				
				/* Save Data */
				if($errText==''){ # No Error
					if($saveMod){ #Subscribe
					
						/* Limit Control */
						if(!limitBlock($sourceLimit,$LETHE_ORG_SETS['set_org_max_subscriber'])){
							die(errMod(showIn($formErrors[3],'page'),'danger')); # Custom Error (Subscription Stopped for Limit Exceeded)
						}
						
						/* Saving */
						//print_r($saveList);
						$ads = new lethe();
						$ads->OID = $srOrg->Get('ID');
						$ads->subscribeData = $saveList;
					
						if($ads->addSubscriber()){
						$subThank = showIn($sr->Get('form_success_text'),'page');
						$formSuccTxt = $sr->Get('form_success_url_text');
						$formSuccURL = $sr->Get('form_success_url');
							if(!empty($formSuccTxt) && !empty($formSuccURL)){
								$subThank.='<a href="'. showIn($sr->Get('form_success_url'),'input') .'">'. showIn($sr->Get('form_success_url_text'),'page') .'</a>';
								if($sr->Get('form_success_redir') && !isLogged()){
									$subThank.= '<script>setTimeout("window.location=\''. showIn($sr->Get('form_success_url'),'input') .'\'",'. ($sr->Get('form_success_redir')*1000) .');</script>';
								}
							}
						$errText = (errMod($subThank,'success'));
						}else{ # Subscribe Error
							echo(errMod($ads->errPrint,'danger'));
						}
					}
				}else{
					if($saveMod){ # Subscribe
						$errText = (errMod($errText,'danger'));
					}else{ # Unsubscribe Error
						# Unsubscribing Action Does Not Require Other Field Validations
						# But Other Field Errors Will Not Appear!
						$smail = ((isset($_POST['LetheForm_Mail']) && mailVal($_POST['LetheForm_Mail'])) ? trim($_POST['LetheForm_Mail']):NULL);
						$rems = new lethe();
						$rems->OID = $srOrg->Get('ID');
						$res = $rems->getUnsubscribing($smail,0,$LETHE_ORG_SETS['set_org_after_unsubscribe']);
						if($res){
							$errText = (errMod(showIn($formErrors[4],'page').'!<script>$("#'. $sr->Get('form_id') .'")[0].reset();</script>','success'));
						}else{
							$errText = (errMod('Unsubscribing Error!<script>$("#'. $sr->Get('form_id') .'")[0].reset();</script>','success'));
						}
					}
				}
				
			} $opForm->close();
		}else{
			$errText = '<div class="alert alert-danger">'. letheglobal_subscribe_form_error .'</div>';
		}	
		
	echo($errText);
}

/* Unsubscription */
else if($pos=='unsubscribe'){
	
	# Load Error Style
	echo('<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">');
	
	if(DEMO_MODE){
		die(errMod(letheglobal_demo_mode_active,'danger'));
	}else{
		/* Load Organization Settings */
		if(!isset($oid) || $oid==''){
			die(errMod('Settings Could Not Be Loaded!','danger'));
		}else{
			$chkOrg = $myconn->prepare("SELECT ID,public_key FROM ". db_table_pref ."organizations WHERE public_key=?") or die(mysqli_error($myconn));
			$chkOrg->bind_param('s',$oid);
			$chkOrg->execute();
			$chkOrg->store_result();
			if($chkOrg->num_rows==0){
				die(errMod('Settings Could Not Be Loaded!','danger'));
			}else{
				$srOrg = new Statement_Result($chkOrg);
				$chkOrg->fetch();
				include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'inc/org_set.php');
				
				# Load Lethe Class
				$unSubLethe = new lethe();
				$unSubLethe->OID = $srOrg->Get('ID');
				
				# Get System Templates
				$sysTemps = array();
				$scRepl = array();
				$opTemps = $myconn->query("SELECT * FROM ". db_table_pref ."templates WHERE OID=". $srOrg->Get('ID') ." AND (temp_type='unsubscribe' OR temp_type='norecord' OR temp_type='erroroccurred')") or die(mysqli_error($myconn));
				while($opTempsRs = $opTemps->fetch_assoc()){
					$sysTemps[$opTempsRs['temp_type']]['data'] = $opTempsRs['temp_contents'];
					$scRepl[] = $opTempsRs['temp_contents'];
				} $opTemps->free();
				$sysTemps = $unSubLethe->shortReplaces($sysTemps);
				
				# Find Subscriber
				$opSub = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". $srOrg->Get('ID') ." AND subscriber_key=? AND subscriber_active=1") or die(mysqli_error($myconn));
				$opSub->bind_param('s',$sid);
				$opSub->execute();
				$opSub->store_result();
				if($opSub->num_rows==0){
					echo($sysTemps['norecord']['data']);
				}else{
										
					$srSub = new Statement_Result($opSub);
					$opSub->fetch();
					$smail = $srSub->Get('subscriber_mail');
					$sname = $srSub->Get('subscriber_name');
					
					# Open Newsletter
					$opCamp = $myconn->prepare("SELECT * FROM ". db_table_pref ."campaigns WHERE OID=". $srOrg->Get('ID') ." AND campaign_key=?") or die(mysqli_error($myconn));
					$opCamp->bind_param('s',$id);
					$opCamp->execute();
					$opCamp->store_result();
					$campIDs = 0;
					if($opCamp->num_rows==0){
						# There no found campaign its could be deleted
						# Unsubscribing report will not add as a campaign leaves
						$campIDs = 0;
					}else{
						# Campaign found, unsubscribing action will be apply, also campaign will reported
						$srCmp = new Statement_Result($opCamp);
						$opCamp->fetch();
						$campIDs = $srCmp->Get('ID');
					} $opCamp->close();
					
					# Check Unsubscriber Table
					$chkUST = $myconn->prepare("SELECT * FROM ". db_table_pref ."unsubscribes WHERE OID=". $srOrg->Get('ID') ." AND CID=". $campIDs ." AND subscriber_mail=?") or die(mysqli_error($myconn));
					$chkUST->bind_param('s',$smail);
					$chkUST->execute();
					$chkUST->store_result();
					if($chkUST->num_rows!=0){
						$chkUST->close(); $myconn->close();
						die($sysTemps['norecord']['data']);
					}
					
					# Run Unsubscribing Action
					$res = $unSubLethe->getUnsubscribing($smail,$campIDs,$LETHE_ORG_SETS['set_org_after_unsubscribe']);
					if($res){					
						# Successfully Applied, Load Template
						$successData = $sysTemps['unsubscribe']['data'];
						$sf = array('{SUBSCRIBER_NAME}','{SUBSCRIBER_MAIL}');
						$sr = array($sname,$smail);
						$successData = str_replace($sf,$sr,$successData);
						echo($successData);
					}else{
						# Error Occurred
						echo($sysTemps['erroroccurred']['data']);
					}
				}
				$opSub->close();
				
			}
		}
	}
}

/* Verification */
else if($pos=='verification'){
	# Verification Code is subscriber_verify_key For Single-opt-in
	# Verification Code is encr(subscriber_verify_key) For Double-opt-in
	
	if(DEMO_MODE){
		die(errMod(letheglobal_demo_mode_active,'danger'));
	}else{
		/* Load Organization Settings */
		if(!isset($oid) || $oid==''){
			die(errMod('Settings Could Not Be Loaded!','danger'));
		}else{
			$chkOrg = $myconn->prepare("SELECT ID,public_key FROM ". db_table_pref ."organizations WHERE public_key=?") or die(mysqli_error($myconn));
			$chkOrg->bind_param('s',$oid);
			$chkOrg->execute();
			$chkOrg->store_result();
			if($chkOrg->num_rows==0){
				die(errMod('Settings Could Not Be Loaded!','danger'));
			}else{
				$srOrg = new Statement_Result($chkOrg);
				$chkOrg->fetch();
				include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'inc/org_set.php');
				
				# Organization Verification Mode
				# 0 - No Verify, 1 - Single Opt-in, 2 - Double Opt-in
				$orgVerifMode = (int)$LETHE_ORG_SETS['set_org_verification'];
				
				# Load Lethe Class
				$unSubLethe = new lethe();
				$unSubLethe->OID = $srOrg->Get('ID');
				
				# Get System Templates
				$sysTemps = array();
				$scRepl = array();
				$opTemps = $myconn->query("SELECT * FROM ". db_table_pref ."templates WHERE OID=". $srOrg->Get('ID') ." AND (temp_type='thank' OR temp_type='norecord' OR temp_type='erroroccurred' OR temp_type='alreadyverified')") or die(mysqli_error($myconn));
				while($opTempsRs = $opTemps->fetch_assoc()){
					$sysTemps[$opTempsRs['temp_type']]['data'] = $opTempsRs['temp_contents'];
					$scRepl[] = $opTempsRs['temp_contents'];
				} $opTemps->free();
				$sysTemps = $unSubLethe->shortReplaces($sysTemps);
				
				# Print Already Verified Template If Organization Does Not Use Verification System
				if($orgVerifMode==0){
					die($sysTemps['alreadyverified']['data']);
				}
				
				# Find Subscriber
				$opSub = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". $srOrg->Get('ID') ." AND subscriber_key=?") or die(mysqli_error($myconn));
				$opSub->bind_param('s',$sid);
				$opSub->execute();
				$opSub->store_result();
				if($opSub->num_rows==0){
					echo($sysTemps['norecord']['data']);
				}else{
										
					$srSub = new Statement_Result($opSub);
					$opSub->fetch();
					$smail = $srSub->Get('subscriber_mail');
					$sname = $srSub->Get('subscriber_name');
					$sweb = $srSub->Get('subscriber_web');
					$sphone = $srSub->Get('subscriber_phone');
					$scompany = $srSub->Get('subscriber_company');
					$sverify = $srSub->Get('subscriber_verify');
					$sverify_key = $srSub->Get('subscriber_verify_key');
					$subIDs = $srSub->Get('ID');
					
					# Template Replaces for Subscriber
					$ftem = array('{SUBSCRIBER_NAME}','{SUBSCRIBER_MAIL}','{SUBSCRIBER_WEB}','{SUBSCRIBER_PHONE}','{SUBSCRIBER_COMPANY}');
					$rtem = array(showIn($sname,'page'),
								  showIn($smail,'page'),
								  showIn($sweb,'page'),
								  showIn($sphone,'page'),
								  showIn($scompany,'page')
								  );
					foreach($sysTemps as $k=>$v){
						$sysTemps[$k]['data'] = str_replace($ftem,$rtem,$sysTemps[$k]['data']);
					}
					
					# Subscriber is not verified
					if($sverify==0){
						# Check Key
						if($rt!=$sverify_key){
							echo($sysTemps['erroroccurred']['data']);
						}else{
							# Subscriber Mark As Single Opt-in
							$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_verify=1 WHERE ID=". $subIDs ."") or die(mysqli_error($myconn));
							
							# Send Double Opt-in Mail If Organization is Using Double Verification
							if($orgVerifMode==2){
								$unSubLethe->SUBID = $subIDs;
								$unSubLethe->sendVerify(2);
							}
							
							# Print Thank Template
							echo($sysTemps['thank']['data']);
							
						}			
					
					# Subscriber is single verified
					}else if($sverify==1){
						
						# Check If Double Opt-in Active for Organization
						if($orgVerifMode==2){
							# Check Key
							if($rt!=encr($sverify_key)){
								echo($sysTemps['erroroccurred']['data']);
							}else{
								# Subscriber Mark As Double Opt-in
								$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_verify=2 WHERE ID=". $subIDs ."") or die(mysqli_error($myconn));
								
								# Print Thank Template
								echo($sysTemps['thank']['data']);
								
							}
						}else{
							# Only Single Opt-in Works, Print Already Verified Template
							die($sysTemps['alreadyverified']['data']);
						}
						
					# Subscriber is double verified
					}else if($sverify==2){
						# Print Already Verified Template
						die($sysTemps['alreadyverified']['data']);
					}
					

				}
				$opSub->close();
				
			}
		}
	}
	
}

/* Open Tracker */
else if($pos=='opntrck'){
	# Subscriber data will get by key
	# Campaign data will get by key
	date_default_timezone_set(lethe_default_timezone);
	if(empty($id)){$id='NULL';}
	$opCamp = $myconn->prepare("SELECT * FROM ". db_table_pref ."campaigns WHERE campaign_key=?") or die(mysqli_error($myconn));
	$opCamp->bind_param('s',$id);
	if($opCamp->execute()){
		$opCamp->store_result();
		if($opCamp->num_rows!=0){
			$sr = new Statement_Result($opCamp);
			$opCamp->fetch();
			$campID = $sr->Get('ID');
			$subArray = getSubscriber($sid,2);

			if(is_array($subArray) && count($subArray)!=0){
				# Create Image
				header("Content-type: image/gif");
				header("Content-length: 43");
				$fp = fopen("php://output","wb");
				fwrite($fp,"GIF89a\x01\x00\x01\x00\x80\x00\x00\xFF\xFF",15);
				fwrite($fp,"\xFF\x00\x00\x00\x21\xF9\x04\x01\x00\x00\x00\x00",12);
				fwrite($fp,"\x2C\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02",12);
				fwrite($fp,"\x44\x01\x00\x3B",4);
				fclose($fp);
				
				# Add Report
				$addRpt = new lethe();
				$addRpt->OID = $subArray['subscriber_OID'];
				$addRpt->reportCID = $campID;
				$addRpt->reportPos = 1; # Open
				$addRpt->reportIP = $_SERVER['REMOTE_ADDR'];
				$addRpt->reportMail = $subArray['subscriber_mail'];
				$addRpt->reportBounceType = 'unknown';
				$addRpt->addReport();
			}			
		}
	} $opCamp->close();
}

/* RSS */
else if($pos=='rss'){
	
	/* Load Organization Settings */
	if(!isset($oid) || $oid==''){
		die(errMod('Settings Could Not Be Loaded!','danger'));
	}else{
		$chkOrg = $myconn->prepare("SELECT ID,public_key FROM ". db_table_pref ."organizations WHERE public_key=?") or die(mysqli_error($myconn));
		$chkOrg->bind_param('s',$oid);
		$chkOrg->execute();
		$chkOrg->store_result();
		if($chkOrg->num_rows==0){
			die(errMod('Settings Could Not Be Loaded!','danger'));
		}else{
			$srOrg = new Statement_Result($chkOrg);
			$chkOrg->fetch();
			include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'inc/org_set.php');
			$orgID = $LETHE_ORG_SETS['set_org_id'];
		}
	}
	
	
	if($orgID!=0){
		
		header ("Content-type: text/xml");
		$rss_title = 'Newsletter RSS';
		$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
		$rssfeed .= '<rss version="2.0">';
		$rssfeed .= '<channel>';
		$rssfeed .= '<title>'. rss_filter($rss_title) .'</title>';
		$rssfeed .= '<link>'. lethe_root_url .'</link>';
		$rssfeed .= '<description>'. rss_filter($rss_title . ' RSS feed') .'</description>';
		$rssfeed .= '<language>en_EN</language>';
		$rssfeed .= '<copyright>Copyright (C) '. date("Y") .' artlantis.net</copyright>';
		
		# Load Campaigns
		# Only Web Opt Active and Sending / Completed Campaigns (Not Autoresponders)
		$opCamp = $myconn->query("SELECT 
											* 
									FROM 
											". db_table_pref ."campaigns 
								   WHERE 
											OID=". $orgID ."
									 AND
											webOpt=1
									 AND
											campaign_type=0
									 AND
											(campaign_pos=1 OR campaign_pos=3)
								ORDER BY
											ID
									DESC
										") or die(mysqli_error($myconn));
										
		while($opCampRs = $opCamp->fetch_assoc()){
			$rssfeed .= '<item>';
			$rssfeed .= '<title>' . rss_filter($opCampRs['subject']) . '</title>';
			$rssfeed .= '<link>'. lethe_root_url .'lethe.newsletter.php?pos=web&amp;id='. $opCampRs['campaign_key'] .'</link>';
			$rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($opCampRs['add_date'])) . '</pubDate>';
			$rssfeed .= '</item>';
		} $opCamp->free();
		
		$rssfeed .= '</channel>';
		$rssfeed .= '</rss>';
		$rssfeed = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $rssfeed);
		echo($rssfeed);
		
	}
	
}

/* Web View */
else if($pos=='web'){
	$opCamp = $myconn->prepare("SELECT * FROM ". db_table_pref ."campaigns WHERE campaign_key=? AND webOpt=1") or die(mysqli_error($myconn));
	$opCamp->bind_param('s',$id);
	$opCamp->execute();
	$opCamp->store_result();
	if($opCamp->num_rows!=0){
			$sr = new Statement_Result($opCamp);
			$opCamp->fetch();
			$campData = $sr->Get('details');
			$campTitle = $sr->Get('subject');
			$campTitle = clearSCs($campTitle); # Clear Some Short Codes
			$campData = clearSCs($campData,true); # Clear Some Short Codes
			$opSC = new lethe();
			$opSC->OID = $sr->Get('OID');
			
			# Load Organization Data
			$orgSets = array();
			if(!$opSC->loadOrg($sr->Get('OID'))){
				$orgSets['set_public_key'] = '';
			}
			

			$LETHE_ORG_SETS['set_org_name'] = $orgSets['set_org_name'];
			
			
			# Short Codes		
			$campDataSC = $opSC->shortReplaces(array($campData));
			if(is_array($campDataSC) && count($campDataSC)!=0){
				foreach($campDataSC as $k=>$v){
					$campData = $v;
				}
			}
									
			# Dynamics
			$frKeys = array(
								'#\{?(NEWSLETTER_LINK\[(.*?)\])\}#'=>'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=web&amp;id='. $id .'&amp;sid=">$2</a>',
								'#\{?(RSS_LINK\[(.*?)\])\}#'=>'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=rss&amp;oid='. $orgSets['set_public_key'] .'">$2</a>',
								'#<title\b[^>]*>(.*?)<\/title>#im'=>'<title>'. showIn($campTitle,'page') .'</title>'
							);
			$campData = preg_replace(array_keys($frKeys), $frKeys,$campData);
			
			# Track Link
			$campData = preg_replace_callback('#\{?(TRACK_LINK\[(.*?)\]\[(.*?)\])\}#',
											create_function(
												'$matches',
												'return \'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=track&amp;id='. $id .'&amp;sid=&amp;redu=\'. letheURLEnc($matches[3]) .\'" target="_blank">\'. $matches[2] .\'</a>\';'
											)
											,$campData);
			
			# View Hit Will Use For Web?
			# *** currently is not..
			
			echo($campData);
	}else{
		# Page will shown 404 error page if campaign not able for web view
		header('Location: lethe.newsletter.php?pos=');die();
	} $opCamp->close();
}

/* Track Link */
else if($pos=='track'){
	date_default_timezone_set(lethe_default_timezone);
	$redu = letheURLEnc($redu,1);

	# Subscriber data will get by key
	# Campaign data will get by key
	if(empty($id)){$id='NULL';}
	$opCamp = $myconn->prepare("SELECT * FROM ". db_table_pref ."campaigns WHERE campaign_key=?") or die(mysqli_error($myconn));
	$opCamp->bind_param('s',$id);
	if($opCamp->execute()){
		$opCamp->store_result();
		if($opCamp->num_rows!=0){
			$sr = new Statement_Result($opCamp);
			$opCamp->fetch();
			$campID = $sr->Get('ID');
			$subArray = getSubscriber($sid,2);
			
			if(is_array($subArray) && count($subArray)!=0){
				
				# Add Report
				$addRpt = new lethe();
				$addRpt->OID = $subArray['subscriber_OID'];
				$addRpt->reportCID = $campID;
				$addRpt->reportPos = 0; # Click
				$addRpt->reportIP = $_SERVER['REMOTE_ADDR'];
				$addRpt->reportMail = $subArray['subscriber_mail'];
				$addRpt->reportBounceType = 'unknown';
				$addRpt->reportExtraInfo = 'URL: '. $redu . PHP_EOL;
				$addRpt->addReport();
				
				# Redirect URL
				header('Location: ' . $redu);
				
			}else{
				die('Error Occurred');
			}			
		}else{
			die('Invalid Campaign');
		}
	} $opCamp->close();

}

else{ # Invalid Pos / 404
	echo(base64_decode('PCFET0NUWVBFIGh0bWw+CjxodG1sIGxhbmc9ImVuIj4KPGhlYWQ+CjxtZXRhIGNoYXJzZXQ9InV0Zi04Ij4KPHRpdGxlPkxldGhlIE5ld3NsZXR0ZXIgVmVyaWZpY2F0aW9uPC90aXRsZT4KPGxpbmsgaHJlZj0nLy9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PUxvYnN0ZXInIHJlbD0nc3R5bGVzaGVldCcgdHlwZT0ndGV4dC9jc3MnPgo8bGluayBocmVmPScvL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zOjQwMCw2MDAsNzAwJmFtcDtzdWJzZXQ9bGF0aW4sbGF0aW4tZXh0JyByZWw9J3N0eWxlc2hlZXQnIHR5cGU9J3RleHQvY3NzJz4KPHN0eWxlPgojbGV0aGUtaGVhZHt0ZXh0LWFsaWduOmNlbnRlcjsgbWFyZ2luLXRvcDo1MHB4O21hcmdpbi1ib3R0b206NDBweDt9CiNsZXRoZS1mb290ZXIgcHtsaW5lLWhlaWdodDo1cHg7Y29sb3I6IzJDM0U1MDt9CmgzIGF7Zm9udC1zaXplOjgwcHg7IGZvbnQtZmFtaWx5OidMb2JzdGVyJzsgdGV4dC1kZWNvcmF0aW9uOm5vbmU7IGxpbmUtaGVpZ2h0OjAuOTsgY29sb3I6IzJDM0U1MDt9CmgzIGEgc3Bhbntmb250LXNpemU6MjBweDtkaXNwbGF5OmJsb2NrOyBwYWRkaW5nLWxlZnQ6OTVweDsgY29sb3I6IzEzOTg3RX0KPC9zdHlsZT4KPC9oZWFkPgo8Ym9keSBzdHlsZT0ibWFyZ2luOjA7IHBhZGRpbmc6MDsgYmFja2dyb3VuZC1jb2xvcjojRUFFRUVGOyBmb250LWZhbWlseTonVGFob21hJzsgZm9udC1zaXplOjEycHg7IGNvbG9yOiMwMDA7Ij4KCQoJPGRpdiBpZD0ibWFpbl9sYXkiIHN0eWxlPSJ3aWR0aDogNTAwcHg7IG1hcmdpbjogNTBweCBhdXRvOyBtYXJnaW4tYm90dG9tOiAwOyBwYWRkaW5nOiAxNXB4OyBiYWNrZ3JvdW5kLWNvbG9yOiAjZmZmOyAtd2Via2l0LWJveC1zaGFkb3c6IDJweCAycHggNXB4IDBweCByZ2JhKDE0OCwxNDgsMTQ4LDEpOyAtbW96LWJveC1zaGFkb3c6IDJweCAycHggNXB4IDBweCByZ2JhKDE0OCwxNDgsMTQ4LDEpOyBib3gtc2hhZG93OiAycHggMnB4IDVweCAwcHggcmdiYSgxNDgsMTQ4LDE0OCwxKTsiPgoJPGgzPgoJCTxhIGhyZWY9Imh0dHA6Ly93d3cubmV3c2xldGhlci5jb20vIiBpZD0ibGV0aGVMb2dvIiB0YXJnZXQ9Il9ibGFuayI+TGV0aGU8c3Bhbj5NYWlsaW5nIFN5c3RlbTwvc3Bhbj48L2E+Cgk8L2gzPgoJPGhyIHN0eWxlPSJib3JkZXI6IDFweCBzb2xpZCAjZWRlZGVkOyBoZWlnaHQ6IDFweDsiIC8+Cgk8cD5JbnZhbGlkIFJlcXVlc3QhPC9wPgoJPHA+WW91IHR5cGVkIHRoZSBVUkwgaW4gd3Jvbmcgb3IgdGhlIHBhZ2UgaGFzIGJlZW4gbW92ZWQgb3IgcmVtb3ZlZCBmcm9tIHRoZSB3ZWJzaXRlLjwvcD4KCTxwPlBsZWFzZSBjaGVjayB5b3VyIFVSTCBvciBjb250YWN0IHdpdGggd2ViIGFkbWluaXN0cmF0aW9uLjwvcD4KCTxwPlRoYW5rIFlvdSE8L3A+Cgk8aHIgc3R5bGU9ImJvcmRlcjogMXB4IHNvbGlkICNlZGVkZWQ7IGhlaWdodDogMXB4OyIgLz4KCTwvZGl2PgoKPC9ib2R5Pgo8L2h0bWw+'));
}

$myconn->close();
?>