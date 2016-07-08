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
$pgnt = true;
if($page_main=='autoresponder'){
if(!permCheck($p)){
	echo(errMod(letheglobal_you_are_not_authorized_to_view_this_page,'danger'));
}else{
	
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));
/* Mod Settings */
include_once('mod.common.php');
$mod_confs = $lethe_modules[recursive_array_search('lethe.autoresponder',$lethe_modules)];
$pg_title = $mod_confs['title'];
$pg_nav_buts = '';
$errText = '';

/* Demo Check */
if(!isDemo('addAutoresponder,editAutoresponder')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

/* Source Limit */
$sourceLimit = calcSource(set_org_id,'autoresponder');

/* Default Values */
$ar_action = ((!isset($_POST['ar_action']) || !is_numeric($_POST['ar_action']) || $_POST['ar_action']==999) ? 999:intval($_POST['ar_action']));
$ar_time = 1;
$ar_time_type = 'MINUTE';
$ar_start_date = date('Y-m-d H:i:s');
$ar_end_date = strtotime(date('Y-m-d H:i:s'));
$ar_weeks = '1,1,1,1,1,1,1';
$ar_end = 0;

/* Add Autoresponder */
if(isset($_POST['addAutoresponder'])){
if(limitBlock($sourceLimit,set_org_max_autoresponder)){
	if(!isset($_POST['groups']) || !is_array($_POST['groups'])){$errText.='* '. autoresponder_please_choose_a_group .'<br>';}
	if(!isset($_POST['launch_date']) || empty($_POST['launch_date'])){$errText.='* '. autoresponder_please_choose_a_launch_date .'<br>';}else{
		if((!isset($_POST['launch_hour']) || empty($_POST['launch_hour'])) && (!isset($_POST['launch_min']) || empty($_POST['launch_min']))){$errText.='* '. autoresponder_invalid_launch_date .'<br>';}else{
			$genDate = $_POST['launch_date'] . ' ' . $_POST['launch_hour'] . ':' . $_POST['launch_min'] . ':00';
			$genDate = date('Y-m-d H:i:s',strtotime($genDate));
			if($genDate<date('Y-m-d H:i:s')){
				$errText.='* '. autoresponder_launch_date_expired .'<br>';
			}
		}
	}
	if(!isset($_POST['subject']) || empty($_POST['subject'])){$errText.='* '. autoresponder_please_enter_a_subject .'<br>';}
	if(!isset($_POST['details']) || empty($_POST['details'])){$errText.='* '. autoresponder_please_enter_details .'<br>';}
	if(!isset($_POST['alt_details']) || empty($_POST['alt_details'])){$_POST['alt_details']=null;}
	if(!isset($_POST['attach']) || empty($_POST['attach'])){$_POST['attach']=null;}
	if(!isset($_POST['webOpt']) || empty($_POST['webOpt'])){$webOpt=0;}else{$webOpt=1;}
	
	$orgSubAccs = explode(',',set_org_submission_account);
	if(!isset($_POST['campaign_sender_title']) || empty($_POST['campaign_sender_title'])){$errText.='* '. letheglobal_please_enter_a_sender_title .'<br>';}
	if(!isset($_POST['campaign_reply_mail']) || !mailVal($_POST['campaign_reply_mail'])){$errText.='* '. letheglobal_please_enter_a_reply_mail .'<br>';}
	if(!isset($_POST['subAcc']) || !in_array(intval($_POST['subAcc']),$orgSubAccs)){$errText.='* '. letheglobal_invalid_submission_account .'<br>';}
	
	/* Action Field Controls Start */
	if(!isset($_POST['ar_action']) || !is_numeric($_POST['ar_action']) || $_POST['ar_action']==999){
		$errText.='* '. autoresponder_please_choose_an_action .'<br>';
	}else{
		# Action 1
		if($_POST['ar_action']==0){
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
						/* Defaults for others */
						$ar_end_date = strtotime(date('Y-m-d H:i:s'));
						$ar_weeks = "1,1,1,1,1,1,1";
						$ar_end = 0;
					}
				}
			}
		}
		# Action 2
		else if($_POST['ar_action']==1){
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
						/* Defaults for others */
						$ar_end_date = strtotime(date('Y-m-d H:i:s'));
						$ar_weeks = "1,1,1,1,1,1,1";
						$ar_end = 0;
					}
				}
			}
		}
		# Action 2
		else if($_POST['ar_action']==2){
			if(!isset($_POST['ar_end_date']) || !is_array($_POST['ar_end_date']) || count($_POST['ar_end_date'])!=3){
				$errText.='* '. autoresponder_please_enter_a_end_date .'<br>';
			}else{
				$timeData = $_POST['ar_end_date'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData) || !array_key_exists(2,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_end_date .'<br>';
				}else{
					$ar_end_date = strtotime($timeData[0].' '. $timeData[1] .':'. $timeData[2] .':00');
				}
			}
			
			# *
			if(isset($_POST['ar_end']) && $_POST['ar_end']=='YES'){$ar_end=1;}else{$ar_end=0;}
			
			# *
			# week list
			$ar_weeks = array();
			$_POST['ar_weeks'] = ((!isset($_POST['ar_weeks']) || !is_array($_POST['ar_weeks'])) ? array():$_POST['ar_weeks']);
			foreach($LETHE_WEEK_NAMES['normal'] as $k=>$v){
				if(in_array($k,$_POST['ar_weeks'])){
					$ar_weeks[] = 1;
				}else{
					$ar_weeks[] = 0;
				}
			}
			$ar_weeks = implode(',',$ar_weeks);
			
			# *
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
					}
				}
			}
		}
		# Action 3
		else if($_POST['ar_action']==3){
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
						/* Defaults for others */
						$ar_end_date = strtotime(date('Y-m-d H:i:s'));
						$ar_weeks = "1,1,1,1,1,1,1";
						$ar_end = 0;
					}
				}
			}
		}
	}
	/* Action Field Controls End */
	
	if($errText==''){
		$genDate = $_POST['launch_date'] . ' ' . $_POST['launch_hour'] . ':' . $_POST['launch_min'] . ':00';
		$genDate = date('Y-m-d H:i:s',strtotime($genDate));
		$campKey = encr($genDate.set_org_id.LETHE_AUTH_ID.uniqid(true));
		$addCampaign = $myconn->prepare("INSERT INTO 
														". db_table_pref ."campaigns
												 SET
														OID=". set_org_id .",
														UID=". LETHE_AUTH_ID .",
														subject=?,
														details=?,
														alt_details=?,
														launch_date=?,
														attach=?,
														webOpt=". $webOpt .",
														campaign_key=?,
														campaign_type=1,
														campaign_pos=0,
														campaign_sender_title=?,
														campaign_reply_mail=?,
														campaign_sender_account=?
										") or die(mysqli_error($myconn));
		$addCampaign->bind_param('ssssssssi',
											$_POST['subject'],
											$_POST['details'],
											$_POST['alt_details'],
											$genDate,
											$_POST['attach'],
											$campKey,
											$_POST['campaign_sender_title'],
											$_POST['campaign_reply_mail'],
											$_POST['subAcc']
								);
		if($addCampaign->execute()){
			/* Add Groups */
			$CID = $myconn->insert_id;
			$addGrps = $myconn->prepare("INSERT INTO
														". db_table_pref ."campaign_groups
												 SET
														OID=". set_org_id .",
														CID=?,
														GID=?
										") or die(mysqli_error($myconn));
			foreach($_POST['groups'] as $k=>$v){
				$addGrps->bind_param('ii',$CID,$v);
				$addGrps->execute();
			}
			$addGrps->close();
			
			/* Add AR Conditions */
			$ar_week_list = explode(',',$ar_weeks);
			foreach($ar_week_list as $k=>$v){$_POST['ar_week'.$k]=$v;}
			$addCond = $myconn->prepare("INSERT INTO 
														". db_table_pref ."campaign_ar 
												 SET		
														OID=". set_org_id .",
														CID=". $CID .",
														ar_type=?,
														ar_time=?,
														ar_time_type=?,
														ar_end_date=?,
														ar_week_0=?,
														ar_week_1=?,
														ar_week_2=?,
														ar_week_3=?,
														ar_week_4=?,
														ar_week_5=?,
														ar_week_6=?,
														ar_end=?
												") or die(mysqli_error($myconn));
			$arEndtoSave = date('Y-m-d H:i:s',$ar_end_date);
			$addCond->bind_param('iissiiiiiiii',
												$_POST['ar_action'],
												$ar_time,
												$ar_time_type,
												$arEndtoSave,
												$_POST['ar_week0'],$_POST['ar_week1'],$_POST['ar_week2'],$_POST['ar_week3'],$_POST['ar_week4'],$_POST['ar_week5'],$_POST['ar_week6'],
												$ar_end
												);
			$addCond->execute();
			$addCond->close();
			
			/* Add to Cron Command */
			$buildCron = new lethe();
			$buildCron->chronosMin = "*";
			$buildCron->chronosURL = "'".lethe_root_url.'chronos/lethe.tasks.php?ID='.$CID."' > /dev/null 2>&1";
			$genComm = $buildCron->buildChronos();
			$addCron = $myconn->prepare("INSERT INTO ". db_table_pref ."chronos SET OID=". set_org_id .", CID=". $CID .", pos=0, cron_command=?, launch_date=?");
			$addCron->bind_param('ss',$genComm,$genDate);
			$addCron->execute();
			$addCron->close();
		}
		$addCampaign->close();
		unset($_POST);
		$ar_action=999;
		$errText = errMod(letheglobal_recorded_successfully,'success');
	}else{
		$errText = errMod($errText,'danger');
	}
}else{$errText=errMod(letheglobal_limit_exceeded,'danger');}
}

/* Edit Autoresponder */
if(isset($_POST['editAutoresponder'])){
	
	
	/* Delete */
	if(isset($_POST['del']) && $_POST['del']=='YES'){
		$myconn->query("DELETE FROM ". db_table_pref ."tasks WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("DELETE FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("DELETE FROM ". db_table_pref ."unsubscribes WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("UPDATE ". db_table_pref ."chronos SET pos=1 WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("DELETE FROM ". db_table_pref ."campaigns WHERE OID=". set_org_id ." AND ID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("DELETE FROM ". db_table_pref ."campaign_ar WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
		
		header('Location: ?p=autoresponder/tasks');
		die();
	}
	
	/* Reset */
	if(isset($_POST['resetCamp']) && $_POST['resetCamp']=='YES'){
		$myconn->query("DELETE FROM ". db_table_pref ."tasks WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("DELETE FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND CID=". $ID ."") or die(mysqli_error($myconn));
	}
	
	if(!isset($_POST['groups']) || !is_array($_POST['groups'])){$errText.='* '. autoresponder_please_choose_a_group .'<br>';}
	if(!isset($_POST['launch_date']) || empty($_POST['launch_date'])){$errText.='* '. autoresponder_please_choose_a_launch_date .'<br>';}else{
		if((!isset($_POST['launch_hour']) || empty($_POST['launch_hour'])) && (!isset($_POST['launch_min']) || empty($_POST['launch_min']))){$errText.='* '. autoresponder_invalid_launch_date .'<br>';}else{
			$genDate = $_POST['launch_date'] . ' ' . $_POST['launch_hour'] . ':' . $_POST['launch_min'] . ':00';
			$genDate = date('Y-m-d H:i:s',strtotime($genDate));
		}
	}
	if(!isset($_POST['subject']) || empty($_POST['subject'])){$errText.='* '. autoresponder_please_enter_a_subject .'<br>';}
	if(!isset($_POST['details']) || empty($_POST['details'])){$errText.='* '. autoresponder_please_enter_details .'<br>';}
	if(!isset($_POST['alt_details']) || empty($_POST['alt_details'])){$_POST['alt_details']=null;}
	if(!isset($_POST['attach']) || empty($_POST['attach'])){$_POST['attach']=null;}
	if(!isset($_POST['webOpt']) || empty($_POST['webOpt'])){$webOpt=0;}else{$webOpt=1;}
	
	$orgSubAccs = explode(',',set_org_submission_account);
	if(!isset($_POST['campaign_sender_title']) || empty($_POST['campaign_sender_title'])){$errText.='* '. letheglobal_please_enter_a_sender_title .'<br>';}
	if(!isset($_POST['campaign_reply_mail']) || !mailVal($_POST['campaign_reply_mail'])){$errText.='* '. letheglobal_please_enter_a_reply_mail .'<br>';}
	if(!isset($_POST['subAcc']) || !in_array(intval($_POST['subAcc']),$orgSubAccs)){$errText.='* '. letheglobal_invalid_submission_account .'<br>';}
	
	/* Action Field Controls Start */
	if(!isset($_POST['ar_action']) || !is_numeric($_POST['ar_action']) || $_POST['ar_action']==999){
		$errText.='* '. autoresponder_please_choose_an_action .'<br>';
	}else{
		# Action 1
		if($_POST['ar_action']==0){
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
						/* Defaults for others */
						$ar_end_date = strtotime(date('Y-m-d H:i:s'));
						$ar_weeks = "1,1,1,1,1,1,1";
						$ar_end = 0;
					}
				}
			}
		}
		# Action 2
		else if($_POST['ar_action']==1){
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
						/* Defaults for others */
						$ar_end_date = strtotime(date('Y-m-d H:i:s'));
						$ar_weeks = "1,1,1,1,1,1,1";
						$ar_end = 0;
					}
				}
			}
		}
		# Action 2
		else if($_POST['ar_action']==2){
			if(!isset($_POST['ar_end_date']) || !is_array($_POST['ar_end_date']) || count($_POST['ar_end_date'])!=3){
				$errText.='* '. autoresponder_please_enter_a_end_date .'<br>';
			}else{
				$timeData = $_POST['ar_end_date'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData) || !array_key_exists(2,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_end_date .'<br>';
				}else{
					$ar_end_date = strtotime($timeData[0].' '. $timeData[1] .':'. $timeData[2] .':00');
				}
			}
			
			# *
			if(isset($_POST['ar_end']) && $_POST['ar_end']=='YES'){$ar_end=1;}else{$ar_end=0;}
			
			# *
			# week list
			$ar_weeks = array();
			$_POST['ar_weeks'] = ((!isset($_POST['ar_weeks']) || !is_array($_POST['ar_weeks'])) ? array():$_POST['ar_weeks']);
			foreach($LETHE_WEEK_NAMES['normal'] as $k=>$v){
				if(in_array($k,$_POST['ar_weeks'])){
					$ar_weeks[] = 1;
				}else{
					$ar_weeks[] = 0;
				}
			}
			$ar_weeks = implode(',',$ar_weeks);
			
			# *
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
					}
				}
			}
		}
		# Action 3
		else if($_POST['ar_action']==3){
			if(!isset($_POST['ar_time']) || !is_array($_POST['ar_time']) || count($_POST['ar_time'])!=2){
				$errText.='* '. autoresponder_please_enter_a_time .'<br>';
			}else{
				$timeData = $_POST['ar_time'];
				if(!array_key_exists(0,$timeData) || !array_key_exists(1,$timeData)){
					$errText.='* '. autoresponder_please_enter_a_time .'<br>';
				}else{
					if(!is_numeric($timeData[0]) || !array_key_exists($timeData[1],$LETHE_AR_TIME_TYPES)){
						$errText.='* '. autoresponder_please_enter_a_time .'<br>';
					}else{
						$ar_time = intval($timeData[0]);
						$ar_time_type = $timeData[1];
						/* Defaults for others */
						$ar_end_date = strtotime(date('Y-m-d H:i:s'));
						$ar_weeks = "1,1,1,1,1,1,1";
						$ar_end = 0;
					}
				}
			}
		}
	}
	/* Action Field Controls End */
	
	if($errText==''){
		$genDate = $_POST['launch_date'] . ' ' . $_POST['launch_hour'] . ':' . $_POST['launch_min'] . ':00';
		$genDate = date('Y-m-d H:i:s',strtotime($genDate));
		$campKey = encr($genDate.set_org_id.LETHE_AUTH_ID.uniqid(true));
		$addCampaign = $myconn->prepare("UPDATE 
														". db_table_pref ."campaigns
												 SET
														subject=?,
														details=?,
														alt_details=?,
														launch_date=?,
														attach=?,
														webOpt=". $webOpt .",
														campaign_type=1,
														campaign_pos=0,
														campaign_sender_title=?,
														campaign_reply_mail=?,
														campaign_sender_account=?
											WHERE
														OID=". set_org_id ."
											AND
														ID=?
										") or die(mysqli_error($myconn));
		$addCampaign->bind_param('sssssssii',
											$_POST['subject'],
											$_POST['details'],
											$_POST['alt_details'],
											$genDate,
											$_POST['attach'],
											$_POST['campaign_sender_title'],
											$_POST['campaign_reply_mail'],
											$_POST['subAcc'],
											$ID
								);
		if($addCampaign->execute()){
			/* Update Groups */
			$CID = $ID;
			$addGrps = $myconn->prepare("INSERT INTO
														". db_table_pref ."campaign_groups
												 SET
														OID=". set_org_id .",
														CID=?,
														GID=?
										") or die(mysqli_error($myconn));
			foreach($_POST['groups'] as $k=>$v){
				if(cntData("SELECT * FROM ". db_table_pref ."campaign_groups WHERE OID=". set_org_id ." AND CID=". $CID ." AND GID=". $v ."")==0){
					$addGrps->bind_param('ii',$CID,$v);
					$addGrps->execute();
				}
			}
			$addGrps->close();
			
			/* Remove Groups Unselect */
			$opGrps = $myconn->query("SELECT * FROM ". db_table_pref ."campaign_groups WHERE OID=". set_org_id ." AND CID=". $CID ."") or die(mysqli_error($myconn));
			while($opGrpsRs = $opGrps->fetch_assoc()){
				if(!in_array($opGrpsRs['GID'],$_POST['groups'])){
					$myconn->query("DELETE FROM ". db_table_pref ."campaign_groups WHERE ID=". $opGrpsRs['ID'] ."") or die(mysqli_error($myconn));
				}
			} $opGrps->free();
			
			/* Add AR Conditions */
			$ar_week_list = explode(',',$ar_weeks);
			foreach($ar_week_list as $k=>$v){$_POST['ar_week'.$k]=$v;}
			$addCond = $myconn->prepare("UPDATE 
														". db_table_pref ."campaign_ar 
											SET		
														ar_time=?,
														ar_time_type=?,
														ar_end_date=?,
														ar_week_0=?,
														ar_week_1=?,
														ar_week_2=?,
														ar_week_3=?,
														ar_week_4=?,
														ar_week_5=?,
														ar_week_6=?,
														ar_end=?
										   WHERE
														OID=". set_org_id ."
											 AND
														CID=". $CID ."
												") or die(mysqli_error($myconn));
			$arEndtoSave = date('Y-m-d H:i:s',$ar_end_date);
			$addCond->bind_param('issiiiiiiii',
												$ar_time,
												$ar_time_type,
												$arEndtoSave,
												$_POST['ar_week0'],$_POST['ar_week1'],$_POST['ar_week2'],$_POST['ar_week3'],$_POST['ar_week4'],$_POST['ar_week5'],$_POST['ar_week6'],
												$ar_end
												);
			$addCond->execute();
			$addCond->close();
			
			/* Update Cron Command */
			$updCron = $myconn->prepare("UPDATE ". db_table_pref ."chronos SET launch_date=? WHERE OID=". set_org_id ." AND CID=". $CID ."");
			$updCron->bind_param('s',$genDate);
			$updCron->execute();
			$updCron->close();
		}
		$addCampaign->close();
		unset($_POST);
		$errText = errMod(letheglobal_updated_successfully,'success');
	}else{
		$errText = errMod($errText,'danger');
	}
}
?>

<!-- AUTORESPONDER START -->
<?php if($page_sub=='tasks'){
		echo('<h1>'. $pg_title .' 
		<span class="help-block text-primary">'. autoresponder_campaigns .'</span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
if(DEMO_MODE){
	echo('<span class="help-block">'. letheglobal_stats_was_randomly_generated_for_demo .'!</span>');
}	 
	?>
<!-- Autoresponder List Start -->	
		<script src="Scripts/jquery.countdown.min.js"></script>
		<table class="footable table table-striped">
			<thead>
				<tr>
					<th><?php echo(autoresponder_campaigns);?></th>
					<th><?php echo(autoresponder_launch_date);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_status);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_sent);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_bounces);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_unsubscribe);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_opens);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_clicks);?></th>
				</tr>
			</thead>
			<tbody>
<?php 
$limit=15;
			 
$rndQry = '';
$opCamp = $myconn->query("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". set_org_id ." AND campaign_type=1 ". $rndQry ."") or die(mysqli_error($myconn));

$pgGo = ((!isset($_GET["pgGo"]) || !is_numeric($_GET["pgGo"])) ? 1 : intval($_GET["pgGo"]));
$count		 = mysqli_num_rows($opCamp);
$total_page	 = ceil($count / $limit);
$dtStart	 = ($pgGo-1)*$limit;

if($count==0){echo('<tr><td colspan="8">'. errMod(letheglobal_record_not_found,'danger') .'</td></tr>');}else{

$opRecs = $myconn->query("SELECT 
										C.ID,C.OID,C.UID,C.subject,C.launch_date,C.campaign_type,C.campaign_pos,
										CG.CID,CG.GID,
										COUNT(S.ID) AS SUBCNT,
										S.subscriber_mail AS SMAIL,
										COUNT(T.ID) AS SENTCNT,
										TAR.ar_type, TAR.ar_week_1, TAR.ar_week_2, TAR.ar_week_3, TAR.ar_week_4, TAR.ar_week_5, TAR.ar_week_6, TAR.ar_week_0,
										TAR.ar_end_date, TAR.ar_end,
										(SELECT COUNT(ID) FROM ". db_table_pref ."reports WHERE CID=C.ID AND pos=2) AS BOUNCE,
										(SELECT COUNT(ID) FROM ". db_table_pref ."reports WHERE CID=C.ID AND pos=1) AS OPENS,
										(SELECT COUNT(ID) FROM ". db_table_pref ."reports WHERE CID=C.ID AND pos=0) AS CLICKS,
										(SELECT COUNT(ID) FROM ". db_table_pref ."unsubscribes WHERE CID=C.ID) AS UNSUB
							  FROM 
										". db_table_pref ."campaigns AS C
										JOIN ". db_table_pref ."campaign_groups AS CG ON C.ID = CG.CID
										JOIN ". db_table_pref ."subscribers AS S ON CG.GID = S.GID
										LEFT OUTER JOIN ". db_table_pref ."tasks AS T ON (T.CID = C.ID) AND (T.subscriber_mail = S.subscriber_mail)
										JOIN ". db_table_pref ."campaign_ar AS TAR ON (TAR.CID = C.ID)
							 WHERE 
										(C.OID=". set_org_id .")
							   AND
										(C.campaign_type=1)
										". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
										". $rndQry ."
						   GROUP BY 
										C.ID
						   ORDER BY
										C.launch_date ASC, C.campaign_pos ASC
							 LIMIT 
										$dtStart,$limit
										
										") or die(mysqli_error($myconn));
while($opRecsRs = $opRecs->fetch_assoc()){
$unSub = $opRecsRs['UNSUB']; # From Unsubscribe List
$totalSub = $opRecsRs['SUBCNT'];
$totalSubU = ($opRecsRs['SUBCNT']-$unSub);
$sentCnt = $opRecsRs['SENTCNT'];
$unsentCnt = $totalSubU-$sentCnt;
$clicks = $opRecsRs['CLICKS'];
$opens = $opRecsRs['OPENS'];
$bounces = $opRecsRs['BOUNCE'];
# *************** FOR DEMO ************************
if(DEMO_MODE){
	$unSub = rand(200,1000); # Total Unsubscriber
	$totalSub = rand(25000,70000); # Total Subscriber
	$totalSubU = ($totalSub-$unSub);
	$sentCnt = rand(14000,50000); # Sent
	$unsentCnt = $totalSub-$sentCnt; # Unsent
	$bounces = rand(900,1800); # Bounces
	$opens = rand(10000,30000); # Opens
	$nonopens = $totalSub-$opens; # Non-Opens
	$clicks = rand(4000,9000); # Clicks (Thats will not affect to score)
}
# *************** FOR DEMO ************************
$campEff = "". $totalSub .",". $sentCnt .",". $unSub .",". $bounces .",". $opens .",". $clicks ."";
?>
				<tr>
					<td>
						<a href="?p=autoresponder/edit&amp;ID=<?php echo($opRecsRs['ID']);?>"><?php echo(showIn($opRecsRs['subject'],'page'));?></a><br>
						<a href="?p=autoresponder/reports&amp;ID=<?php echo($opRecsRs['ID']);?>" class="tooltips text-warning" title="<?php echo(autoresponder_detailed_reports);?>"><span class="glyphicon glyphicon-stats"></span></a> 
						<?php if($opRecsRs['ar_type']==2){?><a href="javascript:;" data-camp-eff="<?php echo($campEff);?>" data-camp-id="<?php echo($opRecsRs['ID']);?>" class="tooltips text-success efficiency" title="<?php echo(autoresponder_productivity_analysis);?>"><span class="glyphicon glyphicon-comment"></span></a><?php }?>
						<a href="javascript:;" title="<?php echo($LETHE_AR_TYPES[$opRecsRs['ar_type']]);?>" class="tooltips"><span class="glyphicon glyphicon-briefcase"></span></a><br>
						<?php 
						if($opRecsRs['ar_type']==2){
						$arDet = '<p>';
						foreach($LETHE_WEEK_NAMES['short'] as $k=>$v){
							$arDet.='<span class="label label-'. (($opRecsRs['ar_week_'.$k]==1) ? 'success':'default') .'">'. $v .'</span> ';
						}
						$arDet.='</p><p>'.getMyLimits($sentCnt,$totalSubU,false).'</p>';
						$arDet.='<p>'. letheglobal_total .': <span class="label label-danger tooltips" title="'. autoresponder_except_unsubscriptions .'">'. $totalSubU .'</span> <span class="label label-default tooltips" title="'. autoresponder_total_selected_subscribers .'">'. $totalSub .'</span></p>';
						echo($arDet);
						}?>
					</td>
					<td>
						<?php 
						$ardates = '<span class="label label-info">'. letheglobal_start .': '. setMyDate($opRecsRs['launch_date'],2) .'</span><br><span class="label label-default" data-countdown="'. setMyDate($opRecsRs['launch_date'],6) .'"></span>';
						if($opRecsRs['ar_type']==2){
							$ardates .= '<br><span class="label label-warning">'. letheglobal_end .': '.setMyDate($opRecsRs['ar_end_date'],2).'</span><br><span class="label label-default" data-countdown="'. ((setMyDate($opRecsRs['ar_end_date'],6)!='-') ? setMyDate($opRecsRs['ar_end_date'],6):'') .'"></span>';
							$ardates .= '<br><br>'. autoresponder_finish_the_campaign .': ' . (($opRecsRs['ar_end']) ? '<span class="glyphicon glyphicon-ok text-success"></span>':'<span class="glyphicon glyphicon-remove text-danger"></span>');
						}
						echo($ardates);
						?>
					</td>
					<td class="sAlignC"><span class="tooltips <?php echo($LETHE_CAMPAIGN_STATUS[$opRecsRs['campaign_pos']]['icon']);?>" title="<?php echo($LETHE_CAMPAIGN_STATUS[$opRecsRs['campaign_pos']]['name']);?>"></span></td>
					<td class="sAlignC">
						<span class="label label-success"><?php echo($sentCnt);?></span><br>
						<?php if($opRecsRs['ar_type']==2){echo('<span class="label label-default">'. $unsentCnt .'</span>');}?>
					</td>
					<td class="sAlignC"><?php echo($bounces);?></td>
					<td class="sAlignC"><?php echo((($opRecsRs['ar_type']!=1) ? $unSub:'-'));?></td>
					<td class="sAlignC"><?php echo($opens);?></td>
					<td class="sAlignC"><?php echo($clicks);?></td>
				</tr>
<?php } $opRecs->free();}?>

			</tbody>
			<tfoot>
			<tr>
				<td colspan="8">
					<?php $pgVar='?p='. $p;include_once("inc/inc_pagination.php");?>
				</td>
			</tr>
			</tfoot>
		</table>
		<script type="text/javascript" src="Scripts/Chart.min.js"></script>
		<script type="text/javascript" src="Scripts/jquery.countTo.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
				/* Time Remaning */
				$('[data-countdown]').each(function() {
				  var $this = $(this), finalDate = $(this).data('countdown');
				  $this.countdown(finalDate, function(event) {
					$this.html(event.strftime('%D <?php echo(letheglobal_day);?> %H:%M:%S'));
				  });
				});
				
				/* Efficiency */
				$(".efficiency").click(function(){
					var campID = $(this).data("camp-id");
					var effStat = $(this).data("camp-eff");
					$.fancybox({
						type:'ajax',
						href:'modules/lethe.autoresponder/act.xmlhttp.php?pos=efficiency&effData='+effStat+'&ID='+campID,
						minWidth:250,
						height:420,
						autoSize:false
					});
				});
			});
		</script>
<!-- Autoresponder List End -->
<?php }else if($page_sub=='add'){
		echo('<h1>'. $pg_title .' 
		<span class="help-block text-primary">'. autoresponder_add_campaign .'</span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. sh('pRP9MnRKno') . letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_autoresponder) .'</div></div>
			   </div>');
			 
	/* Load Groups for All Sections */
	$listGrps = array();
	$opGroups = $myconn->query("SELECT 
										SG.*,
										(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE GID=SG.ID) AS sbr_cnt
								  FROM 
										". db_table_pref ."subscriber_groups AS SG
								 WHERE 
										OID=". set_org_id ." 
								   AND
										isUnsubscribe=0

										". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
							  ORDER BY
										group_name
								   ASC
								") or die(mysqli_error($myconn));
		while($opGroupsRs = $opGroups->fetch_assoc()){
			$listGrps[] = $opGroupsRs;
		} $opGroups->free();
	?>
<!-- Autoresponder Add Start -->
<?php if(limitBlock($sourceLimit,set_org_max_autoresponder)){?>	
<script>
	var customMCEchar='<?php echo(LOADED_LANG);?>';
	var miniPAN=true;
</script>
<script src="Scripts/tinymce/tinymce.min.js"></script>
<script src="Scripts/tinymce/tinymce.custom.js"></script>
<script src="Scripts/leUpload.js"></script>
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="css/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
	<form name="newAutoresponder" id="newAutoresponder" action="" method="POST">
		<div class="form-group">
			<label for="subAcc"><?php echo(sh('nJ0gxmZBRV').autoresponder_submission_account);?></label>
			<?php 
				$allowedAccs = explode(',',set_org_submission_account);
				$allowedAccs = 'ID='.implode(' OR ID=',$allowedAccs);
				$getAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE isActive=1 AND (".$allowedAccs.") ORDER BY acc_title ASC") or die(mysqli_error($myconn));
			?>
			<ul class="list-unstyled list-inline">
			<li>
			<select name="subAcc" id="subAcc" class="form-control autoWidth">
				<?php 
					while($getAccRs = $getAcc->fetch_assoc()){
						echo('<option value="'. $getAccRs['ID'] .'"'. ((isseter('subAcc')) ? formSelector($getAccRs['ID'],$_POST['subAcc'],0):'') .'>'. showIn($getAccRs['acc_title'],'page') .'</option>');
					} $getAcc->free();
				?>
			</select>
			</li>
			<li><a href="javascript:;" data-target=".subAccInfo" class="toggler btn btn-info"><?php echo(letheglobal_details);?> <span class="glyphicon glyphicon-chevron-down"></span></a></li>
			</ul>
			<div class="row">
				<div class="col-md-5 subAccInfo sHide">
					<div class="well"></div>
				</div>
				<script>$(document).ready(function(){
							/* Load Selected Account */
							var currSel = $("#subAcc option:selected").val();
							getAjax('.subAccInfo .well','act.xmlhttp.php?pos=getSubInfos&ID='+currSel,'<i class="glyphicon glyphicon-refresh spin"></i>');
							/* Re-load Info */
							$("#subAcc").on('change',function(){
								var regSel = $(this).find('option:selected').val();
								getAjax('.subAccInfo .well','act.xmlhttp.php?pos=getSubInfos&ID='+regSel,'<i class="glyphicon glyphicon-refresh spin"></i>');
							});
						});</script>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label for="campaign_sender_title"><?php echo(sh('kfMTnyvW8x').autoresponder_sender_title);?></label>
					<input type="text" name="campaign_sender_title" id="campaign_sender_title" class="form-control" value="<?php echo(((isseter('campaign_sender_title')) ? showIn($_POST['campaign_sender_title'],'input'):''));?>">
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label for="campaign_reply_mail"><?php echo(sh('zIo5YkkltJ').autoresponder_reply_to);?></label>
					<input type="email" name="campaign_reply_mail" id="campaign_reply_mail" class="form-control" value="<?php echo(((isseter('campaign_reply_mail')) ? showIn($_POST['campaign_reply_mail'],'input'):''));?>">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="groups"><?php echo(sh('PcjUqAToJg').letheglobal_groups);?></label>
			<select name="groups[]" id="groups" class="form-control" style="max-width:300px;" multiple>
				<?php foreach($listGrps as $k=>$v){
					echo('<option value="'. $v['ID'] .'"'. formSelector(((isset($_POST['groups'])) ? $_POST['groups']:''),$v['ID'],5) .'>'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
				}?>
			</select>
		</div>
		<div class="form-group">
			<label for="launch_date"><?php echo(sh('1wSIiYORBr').autoresponder_launch_date);?></label><span class="clearfix"></span>
			<input type="date" name="launch_date" id="launch_date" class="form-control autoWidth sInline" size="25" placeholder="DD-MM-YYYY" value="<?php echo(((isseter('launch_date')) ? showIn($_POST['launch_date'],'input'):''));?>">
			<select name="launch_hour" id="launch_hour" class="form-control autoWidth sInline">
			<?php for($i=0;$i<=23;$i++){
				$stHr = date('H',strtotime($i.':00'));
				echo('<option value="'. $stHr .'"'. ((isseter('launch_hour')) ? formSelector($stHr,$_POST['launch_hour'],0):'') .'>'. $stHr .'</option>');
			}?>
			</select>
			<select name="launch_min" id="launch_min" class="form-control autoWidth sInline">
			<?php for($i=0;$i<=59;$i++){
				$stHr = date('i',strtotime('00:'.$i));
				echo('<option value="'. $stHr .'"'. ((isseter('launch_min')) ? formSelector($stHr,$_POST['launch_min'],0):'') .'>'. date('i',strtotime('00:'.$i)) .'</option>');
			}?>
			</select>
			<script>$(document).ready(function(){$('#launch_date').datepicker({dateFormat: 'dd-mm-yy'});});</script>
		</div>
		<!-- ar actions -->
		<div class="form-group">
			<label for="ar_action"><?php echo(sh('D7gLUPWhvd').autoresponder_action);?></label><span class="clearfix"></span>
			<select name="ar_action" id="ar_action" class="form-control autoWidth">
			<option value="999"><?php echo(letheglobal_choose);?></option>
				<?php foreach($LETHE_AR_TYPES as $k=>$v){
					echo('<option value="'. $k .'"'. formSelector($ar_action,$k,0) .'>'. $v .'</option>');
				}?>
			</select>
		</div>
		<div class="ar-action-fields">
			<div class="well">
				<div class="ar-act">
				
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$("#ar_action").change(function(){
					getAjax('.ar-act','modules/lethe.autoresponder/act.xmlhttp.php?pos=aractions&ppos='+ $(this).val() +'','<span class="spin glyphicon glyphicon-refresh"></span>');
				});
			});
		</script>
		<!-- ar actions -->
		<div class="form-group">
			<label for="subject"><?php echo(sh('eGB6awJ442').autoresponder_subject);?></label><span class="clearfix"></span>
			<input type="text" name="subject" id="subject" class="form-control" value="<?php echo(((isseter('subject')) ? showIn($_POST['subject'],'input'):''));?>">
		</div>
		<div class="form-group">
			<label for="template"><?php echo(sh('GZtuTKDgwD').autoresponder_templates);?></label><span class="clearfix"></span>
			<button name="template" id="template" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-picture"></span> <?php echo(autoresponder_template_list);?></button>
		</div>
		<div class="form-group">
			<label for="sc-lists"><?php echo(sh('44ql7ZGaYA').letheglobal_short_codes);?> <a href="javascript:;" class="sc-opener"><span class="glyphicon glyphicon-chevron-down"></span></a></label>
			<div id="sc-box" class="sHide">
				<div class="well"><?php echo(scList('details'));?></div>
			</div>
		</div>
		<div class="form-group">
			<label for="details"><?php echo(sh('stciLi8JoO').autoresponder_details);?></label>
			<textarea name="details" id="details" class="form-control mceEditor"><?php echo(((isseter('details')) ? showIn($_POST['details'],'htmledit'):''));?></textarea>
		</div>
		<div class="form-group">
			<label for="alt_details"><?php echo(sh('VDLWE62wgZ').autoresponder_alternative_content);?></label>
			<textarea name="alt_details" id="alt_details" class="form-control"><?php echo(((isseter('alt_details')) ? showIn($_POST['alt_details'],'page'):''));?></textarea>
		</div>
		<div class="form-group">
			<label for="attach"><?php echo(sh('7X4K6TxZYk').autoresponder_attachments);?></label>
			<div class="input-group">
				<input type="url" class="form-control autoWidth" id="attach" name="attach" size="40" value="<?php echo((isseter('attach')) ? showIn($_POST['attach'],'input'):'');?>"> <span class="input-group-btn autoWidth"><button class="btn btn-default leupload_link" data-leupload-opener="fancybox" data-leupload-model="default" data-leupload-form="attach" data-leupload-platform="normal" type="button">leUpload</button></span>
			</div>
		</div>
		<div class="form-group">
			<label for="webOpt"><?php echo(sh('jqn7yrsCQv').autoresponder_web_option);?></label>
			<div>
			<input type="checkbox" name="webOpt" id="webOpt" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($_POST['webOpt'])) ? ' checked':''));?>>
			</div>
		</div>
		<div class="form-group test-result">
		
		</div>
		<div class="form-group">
			<hr>
			<button type="button" class="LethePreview btn btn-warning"><span class="glyphicon glyphicon-eye-open"></span> <?php echo(letheglobal_preview);?></button> 
			<button type="submit" name="addAutoresponder" id="addAutoresponder" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo(letheglobal_save);?></button> 
			<button type="button" name="sendTest" id="sendTest" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <?php echo(letheglobal_test);?></button> 
		</div>
	</form>
	
	<script>
		$(document).ready(function(){
			/* Load Templates */
			$("#template").click(function(){
				$.fancybox({
					type:'ajax',
					href:'modules/lethe.autoresponder/act.xmlhttp.php?pos=templist',
					width:300
				});
			});
			/* Send Test */
			$("#sendTest").click(function(){
				/* Init Tinymce */
				tinyMCE.triggerSave(true,true);
				$(".test-result").html('<span class="spin glyphicon glyphicon-refresh"></span>');
				$.ajax({
					url : "modules/lethe.autoresponder/act.xmlhttp.php?pos=sendtest",
					type: "POST",
					data : $("#newAutoresponder").serialize(),
					contentType: "application/x-www-form-urlencoded",
					success: function(data, textStatus, jqXHR)
					{
						$(".test-result").html(data);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$(".test-result").html('<div class="alert alert-danger"><?php echo(letheglobal_error_occured);?></div>');
					}
				});
			});
			/* Load Values */
			getAjax('.ar-act','modules/lethe.autoresponder/act.xmlhttp.php?pos=aractions&ppos=<?php echo($ar_action);?>&ar_time=<?php echo($ar_time);?>&ar_time_type=<?php echo($ar_time_type);?>&ar_start_date=<?php echo($ar_start_date);?>&ar_end_date=<?php echo($ar_end_date);?>&ar_weeks=<?php echo($ar_weeks);?>&ar_end=<?php echo($ar_end);?>','<span class="spin glyphicon glyphicon-refresh"></span>');
		});
	</script>
<?php }else{echo errMod(letheglobal_limit_exceeded,'danger');} # Limit Block?>
<!-- Autoresponder Add End -->
<?php }else if($page_sub=='edit'){
		echo('<h1>'. $pg_title .' 
		<span class="help-block text-primary">'. autoresponder_edit_campaign .'</span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
			 
	/* Load Campaign */
	$opCamp = $myconn->prepare("SELECT 
											C.*,
											NSG.CID,NSG.GID,
											TAR.ar_type, TAR.ar_week_1, TAR.ar_week_2, TAR.ar_week_3, TAR.ar_week_4, TAR.ar_week_5, TAR.ar_week_6, TAR.ar_week_0,
											TAR.ar_end_date, TAR.ar_end, TAR.ar_time, TAR.ar_time_type
								  FROM 
											". db_table_pref ."campaigns AS C,
											". db_table_pref ."campaign_groups AS NSG,
											". db_table_pref ."campaign_ar AS TAR
								 WHERE 
											C.OID=". set_org_id ." 
								   AND
											(NSG.CID=C.ID)
								   AND
											(TAR.CID=C.ID)
								   AND 
											C.ID=?
									") or die(mysqli_error($myconn));
	$opCamp->bind_param('i',$ID);
	$opCamp->execute();
	$opCamp->store_result();
	if($opCamp->num_rows==0){
		echo(errMod(letheglobal_record_not_found,'danger'));
	}else{
		$srCamp = new Statement_Result($opCamp);
		while($rc = $opCamp->fetch()){
			$_POST['groups'][] = $srCamp->Get('GID');		
		}

		$valMods['launch_date'] = date('d-m-Y',strtotime($srCamp->Get('launch_date')));
		$valMods['launch_hour'] = date('H',strtotime($srCamp->Get('launch_date')));
		$valMods['launch_min'] = date('i',strtotime($srCamp->Get('launch_date')));
		
	/* Load Groups for All Sections */
	$listGrps = array();
	$opGroups = $myconn->query("SELECT 
										SG.*,
										(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE GID=SG.ID) AS sbr_cnt
								  FROM 
										". db_table_pref ."subscriber_groups AS SG
								 WHERE 
										OID=". set_org_id ." 
								   AND
										isUnsubscribe=0

										". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
							  ORDER BY
										group_name
								   ASC
								") or die(mysqli_error($myconn));
		while($opGroupsRs = $opGroups->fetch_assoc()){
			$listGrps[] = $opGroupsRs;
		} $opGroups->free();
		
/* Load AR Values */
$ar_action = $srCamp->Get('ar_type');
$ar_time = $srCamp->Get('ar_time');
$ar_time_type = $srCamp->Get('ar_time_type');
$ar_end_date = strtotime($srCamp->Get('ar_end_date'));
$ar_weeks = $srCamp->Get('ar_week_0').','.$srCamp->Get('ar_week_1').','.$srCamp->Get('ar_week_2').','.$srCamp->Get('ar_week_3').','.$srCamp->Get('ar_week_4').','.$srCamp->Get('ar_week_5').','.$srCamp->Get('ar_week_6');
$ar_end = $srCamp->Get('ar_end');
	?>
<!-- Autoresponder Edit Start -->	
<script>
	var customMCEchar='<?php echo(LOADED_LANG);?>';
	var miniPAN=true;
</script>
<script src="Scripts/tinymce/tinymce.min.js"></script>
<script src="Scripts/tinymce/tinymce.custom.js"></script>
<script src="Scripts/leUpload.js"></script>
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="css/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
	<form name="updtAutoresponder" id="updtAutoresponder" action="" method="POST">
		<div class="form-group">
			<label for="subAcc"><?php echo(sh('nJ0gxmZBRV').newsletter_submission_account);?></label>
			<?php 
				$allowedAccs = explode(',',set_org_submission_account);
				$allowedAccs = 'ID='.implode(' OR ID=',$allowedAccs);
				$getAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE isActive=1 AND (".$allowedAccs.") ORDER BY acc_title ASC") or die(mysqli_error($myconn));
			?>
			<ul class="list-unstyled list-inline">
			<li>
			<select name="subAcc" id="subAcc" class="form-control autoWidth">
				<?php 
					while($getAccRs = $getAcc->fetch_assoc()){
						echo('<option value="'. $getAccRs['ID'] .'"'. formSelector($getAccRs['ID'],$srCamp->Get('campaign_sender_account'),0) .'>'. showIn($getAccRs['acc_title'],'page') .'</option>');
					} $getAcc->free();
				?>
			</select>
			</li>
			<li><a href="javascript:;" data-target=".subAccInfo" class="toggler btn btn-info"><?php echo(letheglobal_details);?> <span class="glyphicon glyphicon-chevron-down"></span></a></li>
			</ul>
			<div class="row">
				<div class="col-md-5 subAccInfo sHide">
					<div class="well"></div>
				</div>
				<script>$(document).ready(function(){
							/* Load Selected Account */
							var currSel = $("#subAcc option:selected").val();
							getAjax('.subAccInfo .well','act.xmlhttp.php?pos=getSubInfos&ID='+currSel,'<i class="glyphicon glyphicon-refresh spin"></i>');
							/* Re-load Info */
							$("#subAcc").on('change',function(){
								var regSel = $(this).find('option:selected').val();
								getAjax('.subAccInfo .well','act.xmlhttp.php?pos=getSubInfos&ID='+regSel,'<i class="glyphicon glyphicon-refresh spin"></i>');
							});
						});</script>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label for="campaign_sender_title"><?php echo(sh('kfMTnyvW8x').newsletter_sender_title);?></label>
					<input type="text" name="campaign_sender_title" id="campaign_sender_title" class="form-control" value="<?php echo(showIn($srCamp->Get('campaign_sender_title'),'input'));?>">
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label for="campaign_reply_mail"><?php echo(sh('zIo5YkkltJ').newsletter_reply_to);?></label>
					<input type="email" name="campaign_reply_mail" id="campaign_reply_mail" class="form-control" value="<?php echo(showIn($srCamp->Get('campaign_reply_mail'),'input'));?>">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="groups"><?php echo(sh('PcjUqAToJg').letheglobal_groups);?></label>
			<select name="groups[]" id="groups" class="form-control" style="max-width:300px;" multiple>
				<?php foreach($listGrps as $k=>$v){
					echo('<option value="'. $v['ID'] .'"'. formSelector(((isset($_POST['groups'])) ? $_POST['groups']:''),$v['ID'],5) .'>'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
				}?>
			</select>
		</div>
		<div class="form-group">
			<label for="launch_date"><?php echo(sh('1wSIiYORBr').autoresponder_launch_date);?></label><span class="clearfix"></span>
			<input type="date" name="launch_date" id="launch_date" class="form-control autoWidth sInline" size="25" placeholder="DD-MM-YYYY" value="<?php echo($valMods['launch_date']);?>">
			<select name="launch_hour" id="launch_hour" class="form-control autoWidth sInline">
			<?php for($i=0;$i<=23;$i++){
				$stHr = date('H',strtotime($i.':00'));
				echo('<option value="'. $stHr .'"'. formSelector($stHr,$valMods['launch_hour'],0) .'>'. $stHr .'</option>');
			}?>
			</select>
			<select name="launch_min" id="launch_min" class="form-control autoWidth sInline">
			<?php for($i=0;$i<=59;$i++){
				$stHr = date('i',strtotime('00:'.$i));
				echo('<option value="'. $stHr .'"'. formSelector($stHr,$valMods['launch_min'],0) .'>'. date('i',strtotime('00:'.$i)) .'</option>');
			}?>
			</select>
			<script>$(document).ready(function(){$('#launch_date').datepicker({dateFormat: 'dd-mm-yy'});});</script>
		</div>
		<!-- ar actions -->
		<div class="form-group">
			<label for="ar_action"><?php echo(sh('D7gLUPWhvd').autoresponder_action);?></label><span class="clearfix"></span>
			<input type="hidden" name="ar_action" id="ar_action" value="<?php echo($ar_action);?>">
		</div>
		<div class="ar-action-fields">
			<div class="well">
				<div class="ar-act">
				
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				getAjax('.ar-act','modules/lethe.autoresponder/act.xmlhttp.php?pos=aractions&ppos=<?php echo($ar_action);?>&ar_time=<?php echo($ar_time);?>&ar_time_type=<?php echo($ar_time_type);?>&ar_start_date=<?php echo($ar_start_date);?>&ar_end_date=<?php echo($ar_end_date);?>&ar_weeks=<?php echo($ar_weeks);?>&ar_end=<?php echo($ar_end);?>','<span class="spin glyphicon glyphicon-refresh"></span>');
			});
		</script>
		<!-- ar actions -->
		<div class="form-group">
			<label for="subject"><?php echo(sh('eGB6awJ442').autoresponder_subject);?></label><span class="clearfix"></span>
			<input type="text" name="subject" id="subject" class="form-control" value="<?php echo(showIn($srCamp->Get('subject'),'input'));?>">
		</div>
		<div class="form-group">
			<label for="template"><?php echo(sh('GZtuTKDgwD').autoresponder_templates);?></label><span class="clearfix"></span>
			<button name="template" id="template" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-picture"></span> <?php echo(autoresponder_template_list);?></button>
		</div>
		<div class="form-group">
			<label for="sc-lists"><?php echo(sh('44ql7ZGaYA').letheglobal_short_codes);?> <a href="javascript:;" class="sc-opener"><span class="glyphicon glyphicon-chevron-down"></span></a></label>
			<div id="sc-box" class="sHide">
				<div class="well"><?php echo(scList('details'));?></div>
			</div>
		</div>
		<div class="form-group">
			<label for="details"><?php echo(sh('stciLi8JoO').autoresponder_details);?></label>
			<textarea name="details" id="details" class="form-control mceEditor"><?php echo(showIn($srCamp->Get('details'),'htmledit'));?></textarea>
		</div>
		<div class="form-group">
			<label for="alt_details"><?php echo(sh('VDLWE62wgZ').autoresponder_alternative_content);?></label>
			<textarea name="alt_details" id="alt_details" class="form-control"><?php echo(showIn($srCamp->Get('alt_details'),'page'));?></textarea>
		</div>
		<div class="form-group">
			<label for="attach"><?php echo(sh('7X4K6TxZYk').autoresponder_attachments);?></label>
			<div class="input-group">
				<input type="url" class="form-control autoWidth" id="attach" name="attach" size="40" value="<?php echo(showIn($srCamp->Get('attach'),'input'));?>"> <span class="input-group-btn autoWidth"><button class="btn btn-default leupload_link" data-leupload-opener="fancybox" data-leupload-model="default" data-leupload-form="attach" data-leupload-platform="normal" type="button">leUpload</button></span>
			</div>
		</div>
		<div class="form-group">
			<label for="webOpt"><?php echo(sh('jqn7yrsCQv').autoresponder_web_option);?></label>
			<div>
			<input type="checkbox" name="webOpt" id="webOpt" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector($srCamp->Get('webOpt'),1,1));?>>
			</div>
		</div>
		<div class="form-group">
			<label for="runCamp"><?php echo(sh('MAVvrFmpJ6').letheglobal_run);?></label>
			<div>
			<input type="checkbox" name="runCamp" id="runCamp" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo((($srCamp->Get('campaign_pos')!=2 || $srCamp->Get('campaign_pos')!=3) ? ' checked':''));?>>
			</div>
		</div>
		<div class="form-group">
			<span><?php echo(sh('yUEjjdhSBH'));?></span><label for="del"><?php echo(letheglobal_delete);?></label>
			<input type="checkbox" name="del" id="del" value="YES" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>" class="ionc">
		</div>
		<div class="form-group">
			<span><?php echo(sh('anxXc1c53S'));?></span><label for="resetCamp"><?php echo(letheglobal_reset);?></label>
			<input type="checkbox" name="resetCamp" id="resetCamp" value="YES" class="ionc">
		</div>
		<div class="form-group test-result">
		
		</div>
		<div class="form-group">
			<hr>
			<button type="button" class="LethePreview btn btn-warning"><span class="glyphicon glyphicon-eye-open"></span> <?php echo(letheglobal_preview);?></button> 
			<button type="submit" name="editAutoresponder" id="editAutoresponder" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo(letheglobal_save);?></button> 
			<button type="button" name="sendTest" id="sendTest" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <?php echo(letheglobal_test);?></button> 
		</div>
	</form>
	
	<script>
		$(document).ready(function(){
			/* Load Templates */
			$("#template").click(function(){
				$.fancybox({
					type:'ajax',
					href:'modules/lethe.autoresponder/act.xmlhttp.php?pos=templist',
					width:300
				});
			});
			/* Send Test */
			$("#sendTest").click(function(){
				/* Init Tinymce */
				tinyMCE.triggerSave(true,true);
				$(".test-result").html('<span class="spin glyphicon glyphicon-refresh"></span>');
				$.ajax({
					url : "modules/lethe.autoresponder/act.xmlhttp.php?pos=sendtest",
					type: "POST",
					data : $("#updtAutoresponder").serialize(),
					contentType: "application/x-www-form-urlencoded",
					success: function(data, textStatus, jqXHR)
					{
						$(".test-result").html(data);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$(".test-result").html('<div class="alert alert-danger"><?php echo(letheglobal_error_occured);?></div>');
					}
				});
			});
		});
	</script>
	<?php } $opCamp->close(); # Data exists end?>
<!-- Autoresponder Edit End -->
<?php }else if($page_sub=='reports'){
		echo('<h1>'. $pg_title .' 
		<span class="help-block text-primary">'. letheglobal_reports .'</span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
	?>
<!-- Autoresponder Reports Start -->	
				<div id="periods">
					<form action="?p=autoresponder/reports&amp;ID=<?php echo($ID);?>" method="POST" id="ReportForm">
						<div class="row">
							<div class="col-md-6">
								<ol id="selectable">
									<?php 
									/* Defaults */
									$currMonth = date("n");
									$selectedMonths = array();
										if($currMonth<=3){
											for($i=$currMonth;$i>=1;$i--){
												$selectedMonths[] = $i;
											}
										}else{
											for($i=1;$i<=2;$i++){$selectedMonths[] = date("n", strtotime("-". $i ." months"));}
										}
									$selectedYear = date('Y');
									$listMod = ((!isset($_POST['listMod']) || !is_numeric($_POST['listMod']) || $_POST['listMod']==-1) ? '999':intval($_POST['listMod']));
									if(isset($_GET['lm']) && is_numeric($_GET['lm'])){$listMod = intval($_GET['lm']);}
									/* Get */
									if(isset($_GET['rm']) && !empty($_GET['rm'])){
										$_POST['select-result'] = str_replace('~',',',$_GET['rm']);
									}
									if(isset($_GET['ry']) && !empty($_GET['ry'])){
										$_POST['periodYear'] = trim($_GET['ry']);
									}
									
									/* Post */
									if(isset($_POST['select-result'])){
										$selectedMonths = explode(',',$_POST['select-result']);
									}
									if(isset($_POST['periodYear'])){
										$selectedYear = $_POST['periodYear'];
									}
																	
									for($i=1;$i<=12;$i++){?>
									<li data-month_val="<?php echo($i);?>" class="ui-widget-content<?php echo((in_array($i,$selectedMonths)) ? ' ui-selected':'');?>"><?php echo($LETHE_MONTH_NAMES['short'][$i]);?></li>
									<?php }?>
								</ol>
								<input type="hidden" name="select-result" id="select-result" value="<?php echo((isset($_POST['select-result'])) ? $_POST['select-result']:'');?>">
							</div>
							<div class="col-md-5">
								<select name="periodYear" id="periodYear" class="form-control autoWidth sInline input-sm">
									<?php for($i=date('Y');$i>=date('Y')-5;$i--){
										echo('<option value="'. $i .'"'. formSelector($selectedYear,$i,0) .'>'. $i .'</option>');
									}?>
								</select>
								<select name="listMod" id="listMod" class="form-control autoWidth sInline input-sm">
									<option value="-1"<?php echo(formSelector($listMod,999,0));?>><?php echo(letheglobal_all);?></option>
									<option value="0"<?php echo(formSelector($listMod,0,0));?>><?php echo(letheglobal_clicks);?></option>
									<option value="1"<?php echo(formSelector($listMod,1,0));?>><?php echo(letheglobal_opens);?></option>
									<option value="2"<?php echo(formSelector($listMod,2,0));?>><?php echo(letheglobal_bounces);?></option>
								</select>
								<button type="button" name="getReport" id="getReport" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-stats"></span></button>
							</div>
						</div>
					</form>
				</div><hr>
				
				<script src="Scripts/Chart.min.js"></script>
					<?php 				
					/* Make Graph */
					$table_qry = array();
					$graph_lab = array();
					$graph_data['click'] = array();
					$graph_data['open'] = array();
					$graph_data['bounce'] = array();
					
					/* Add List Query */
					if($listMod!=999){$table_qry[] = " AND (pos=". intval($listMod) .") AND (";}else{$table_qry[] = " AND (pos>=0) AND (";}
					
					/* Single Select Filter */
					if(count($selectedMonths)==1){
					
							$selMon = $selectedMonths[0];
					
							function dates_month($month,$year)
							{
								$num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
								$dates_month=array();
								for($i=1;$i<=$num;$i++)
								{
									$mktime=mktime(0,0,0,$month,$i,$year);
									$date=date("d",$mktime);
									$dates_month[$i]=$date;
								}
								return $dates_month;
							}
							
							$listDays = dates_month($selMon,$selectedYear);
							//print_r(dates_month($selMon,$selectedYear)); 
					
						foreach($listDays as $k=>$v){
							$table_qry[] = " ". ((count($table_qry)>1) ? ' OR ':' ') ." (MONTH(add_date)='". mysql_prep($selMon) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."') ";
							$graph_lab[] = '"'. $LETHE_MONTH_NAMES['short'][$selMon] .' '. $v .'"';
							$graph_data['click'][] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=0 AND CID=". $ID ." AND DAY(add_date)='". mysql_prep($v) ."' AND MONTH(add_date)='". mysql_prep($selMon) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."'");
							$graph_data['open'][] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=1 AND CID=". $ID ." AND DAY(add_date)='". mysql_prep($v) ."' AND MONTH(add_date)='". mysql_prep($selMon) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."'");
							$graph_data['bounce'][] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=2 AND CID=". $ID ." AND DAY(add_date)='". mysql_prep($v) ."' AND MONTH(add_date)='". mysql_prep($selMon) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."'");
						}
					}else{
									
						foreach($selectedMonths as $k=>$v){
							$table_qry[] = " ". ((count($table_qry)>1) ? ' OR ':' ') ." (MONTH(add_date)='". mysql_prep($v) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."') ";
							$graph_lab[] = '"'. $LETHE_MONTH_NAMES['short'][$v] .' '. date("y",strtotime("01-01-".$selectedYear)) .'"';
							$graph_data['click'][] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=0 AND CID=". $ID ." AND MONTH(add_date)='". mysql_prep($v) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."'");
							$graph_data['open'][] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=1 AND CID=". $ID ." AND MONTH(add_date)='". mysql_prep($v) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."'");
							$graph_data['bounce'][] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=2 AND CID=". $ID ." AND MONTH(add_date)='". mysql_prep($v) ."' AND YEAR(add_date)='". mysql_prep($selectedYear) ."'");
						}
					
					}
					
					$table_qry[] = ")";
					
					?>
					
				<div class="row">
					<div class="col-md-2"><h3><span class="label label-success"><?php echo(array_sum($graph_data['click']));?> <?php echo(letheglobal_clicks);?></span> </h3></div>
					<div class="col-md-2"><h3><span class="label label-warning"><?php echo(array_sum($graph_data['open']));?> <?php echo(letheglobal_opens);?></span> </h3></div>
					<div class="col-md-2"><h3><span class="label label-danger"><?php echo(array_sum($graph_data['bounce']));?> <?php echo(letheglobal_bounces);?></span></h3></div>
				</div><hr>
					
					<div class="row"><div class="col-md-12 reports-graph-area">
					<canvas id="myChart" width="1000" height="400"></canvas>
					</div></div>

					<script>
						var ctx = document.getElementById("myChart").getContext("2d");
						var data = {
							labels: [<?php echo(implode(",",$graph_lab));?>],
							datasets: [
								<?php if($listMod==999 || $listMod==2){?>
								{
									label: "<?php echo(letheglobal_bounces);?>",
									fillColor: "rgba(217,83,79,0.2)",
									strokeColor: "rgba(217,83,79,1)",
									pointColor: "rgba(217,83,79,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(217,83,79,1)",
									data: [<?php echo(implode(",",$graph_data['bounce']));?>]
								},
								<?php } if($listMod==999 || $listMod==1){?>
								{
									label: "<?php echo(letheglobal_opens);?>",
									fillColor: "rgba(240,173,78,0.2)",
									strokeColor: "rgba(240,173,78,1)",
									pointColor: "rgba(240,173,78,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(240,173,78,1)",
									data: [<?php echo(implode(",",$graph_data['open']));?>]
								},
								<?php } if($listMod==999 || $listMod==0){?>
								{
									label: "<?php echo(letheglobal_clicks);?>",
									fillColor: "rgba(92,184,92,0.2)",
									strokeColor: "rgba(92,184,92,1)",
									pointColor: "rgba(92,184,92,1)",
									pointStrokeColor: "#fff",
									pointHighlightFill: "#fff",
									pointHighlightStroke: "rgba(92,184,92,1)",
									data: [<?php echo(implode(",",$graph_data['click']));?>]
								}
								<?php }?>
							]
						};
						var options = {
							pointDot : true,
							showTooltips: true,
							scaleStartValue: 0,
							bezierCurve : true,
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
						}
						var myLineChart = new Chart(ctx).Line(data,options);
						
						$(document).ready(function(){
							 $( "#selectable" ).selectable({
							stop: function() {
							var collected = new Array();
							var result = $( "#select-result" ).empty();
							$( ".ui-selected", this ).each(function() {
							var index_val = $(this ).data("month_val");
								collected.push(index_val);
							});
								result.val(collected);
							}
							});
							
							$("#getReport").click(function(){
								if($("#select-result").val()==''){
									alert("Please Choose Months!");
									return false;
								}else{
									$("#ReportForm").submit();
								}
							});
						});

					</script>
				<hr>
				
				<!-- Detailed Table -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bs-table table table-striped table-hover">
                <thead>
                  <tr>
                    <th width="20%"><?php echo(letheglobal_e_mail);?></th>
                    <th width="15%"><?php echo(letheglobal_ip_address);?></th>
                    <th width="2%" class="sAlignC"><?php echo(letheglobal_type);?></th>
                    <th width="20%" class="sAlignC"><?php echo(letheglobal_date);?></th>
					<th width="10%" class="sAlignC"><?php echo(autoresponder_hit);?></th>
					<th width="10%" class="sAlignC"><?php echo(autoresponder_extra_info);?></th>
                  </tr>
                </thead>
                <tbody>
				<?php 
				$catchDates = implode(" ",$table_qry);
				
				$limit = 25;
				((!isset($_GET["pgGo"]) || !is_numeric($_GET["pgGo"])) ? $pgGo = 1 : $pgGo = intval($_GET["pgGo"]));
				 $count		 = mysqli_num_rows($myconn->query("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND CID=". $ID ." ". $catchDates .""));
				 $total_page	 = ceil($count / $limit);
				 $dtStart	 = ($pgGo-1)*$limit;
				$opRep = $myconn->query("SELECT * FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND CID=". $ID ." ". $catchDates ." ORDER BY add_date ASC LIMIT $dtStart,$limit") or die(mysqli_error($myconn));
				while($opRepRs = $opRep->fetch_assoc()){
				?>
                  <tr>
                    <td><?php echo($opRepRs['email']);?></td>
                    <td><?php echo($opRepRs['ipAddr']);?></td>
                    <td align="center"><?php if($opRepRs['pos']==2){echo('<span class="label label-danger">'. letheglobal_bounces .'</span>');}else if($opRepRs['pos']==1){echo('<span class="label label-warning">'. letheglobal_opens .'</span>');}else{echo('<span class="label label-success">'. letheglobal_clicks .'</span>');}?></td>
                    <td align="center"><?php echo(setMyDate($opRepRs['add_date'],2));?></td>
					<td align="center"><span class="label label-info"><?php echo($opRepRs['hit_cnt']);?></span></td>
					<td align="center"><a href="javascript:;" class="extInfs" data-rep-id="<?php echo($opRepRs['ID']);?>"><span class="glyphicon glyphicon-screenshot"></span></a></td>
                  </tr>
				<?php } $opRep->free();?>

                  <tr class="non-striped">
                    <td colspan="6"><?php $pgVar='?p=autoresponder/reports&amp;lm='. intval($listMod) .'&amp;ry='. $selectedYear .'&amp;rm='. implode('~',$selectedMonths) .'&amp;ID='. $ID .''; include_once('inc/inc_pagination.php');?></td>
                  </tr>
               	</tbody>
                </table>

				
				
				</td>
			</tr>
        </table>
				
				
<script>
						$(document).ready(function(){
							$("#selectable").fadeIn();
							$( "#selectable" ).selectable({
							stop: function() {
							var collected = new Array();
							var result = $( "#select-result" ).empty();
							$( ".ui-selected", this ).each(function() {
							var index_val = $(this ).data("month_val");
								collected.push(index_val);
							});
								result.val(collected);
							}
							});
							
							$("#getReport").click(function(){
								if($("#select-result").val()==''){
									alert("Please Choose Months!");
									return false;
								}else{
									$("#ReportForm").submit();
								}
							});
							
							/* Extra Infos */
							$(".extInfs").click(function(){
								var repIDs = $(this).data('rep-id');
								$.fancybox({
									type:'ajax',
									href:'modules/lethe.autoresponder/act.xmlhttp.php?pos=extInfo&ID=' + repIDs,
									width:500,
									height:400,
									autoSize:false
								});
							});
							
						});
</script>
<!-- Autoresponder Reports End -->
<?php }else{
	echo('<h1>'. $pg_title .'</h1><hr><div class="container-fluid"><div class="row">');
	foreach($mod_confs['contents'] as $k=>$v){
		echo('<div class="col-md-2 module-splash">
				<h4><span class="'. $v['icon'] .'"></span></h4>
				<div><a href="'. $v['page'] .'">'. $v['title'] .'</a></div>
			  </div>');
	} echo('</div></div>');
}?>

<!-- AUTORESPONDER END -->

<?php 
echo('
<script>
	$(document).ready(function(){
		$("head title").text("'. showIn($pg_title,'page') .' - "+$("head title").text());
	});
</script>
');

} # Permission Check End
} # Module Load End
?>