<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 08.02.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
header('Access-Control-Allow-Origin: *');
include_once('lethe.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'lib/lethe.class.php');
function jsonRet($s=false,$e='NO_ERROR'){
	$retData = array();
	$retData['success'] = $s;
	$retData['error'] = $e;
	die(json_encode($retData));
}

/* Check Demo */
if(DEMO_MODE){
	jsonRet(false,'DEMO_MODE_ON');
}

/* DATAS */
$fullData = array();
$jsonObject = null;
$actionList = array('add','remove','check','toblacklist','moveto');
$act = ((!isset($_GET['act']) || empty($_GET['act'])) ? '':trim($_GET['act'])); # Action
$pkey = ((!isset($_GET['pkey']) || empty($_GET['pkey'])) ? '':trim($_GET['pkey'])); # Public Key
$akey = ((!isset($_GET['akey']) || empty($_GET['akey'])) ? '':trim($_GET['akey'])); # API Key
$lmail = ((!isset($_GET['lmail']) || empty($_GET['lmail'])) ? '':trim($_GET['lmail'])); # E-Mail
$lgrp = ((!isset($_GET['lgrp']) || !is_numeric($_GET['lgrp'])) ? 0:trim($_GET['lgrp'])); # Group
$lsname = ((!isset($_GET['lsname']) || empty($_GET['lsname'])) ? NULL:trim($_GET['lsname'])); # Subscriber Name
$lsweb = ((!isset($_GET['lsweb']) || empty($_GET['lsweb'])) ? NULL:trim($_GET['lsweb'])); # Subscriber Web
$lsdate = ((!isset($_GET['lsdate']) || empty($_GET['lsdate'])) ? NULL:trim($_GET['lsdate'])); # Subscriber Date
$lsphone = ((!isset($_GET['lsphone']) || empty($_GET['lsphone'])) ? NULL:trim($_GET['lsphone'])); # Subscriber Phone
$lscomp = ((!isset($_GET['lscomp']) || empty($_GET['lscomp'])) ? NULL:trim($_GET['lscomp'])); # Subscriber Company

/* Check Data */
if($pkey==''){jsonRet(false,'INVALID_ORG_KEY');}
if($akey==''){jsonRet(false,'INVALID_API_KEY');}
if($lmail=='' || !mailVal($lmail)){jsonRet(false,'INVALID_EMAIL');}
if(!in_array($act,$actionList)){jsonRet(false,'INVALID_ACTION');}

/* Check Auth */
$opOrg = $myconn->prepare("SELECT * FROM ". db_table_pref ."organizations WHERE public_key=? AND BINARY api_key=?") or die(mysqli_error($myconn));
$opOrg->bind_param('ss',$pkey,$akey);
$opOrg->execute();
$opOrg->store_result();
if($opOrg->num_rows==0){$opOrg->close();jsonRet(false,'INVALID_ORG');}else{

	$sr = new Statement_Result($opOrg);
	$opOrg->fetch();
	$opOrg->close();
	
	if($sr->Get('isActive')==0){
		jsonRet(false,'INVALID_ORG');
	}else{
	
	# LOAD ORG SETTINGS
	$opSC = new lethe();
	$orgSets = array();
	if(!$opSC->loadOrg($sr->Get('ID'))){
		jsonRet(false,'INVALID_ORG');
	}
	
		# ACTIONS START *******************************************************************************************************
		
			# Add
			if($act=='add'){
				
				/* Limit Controller */
				$sourceLimit = calcSource($sr->Get('ID'),'subscribers');
				if(!limitBlock($sourceLimit,$orgSets['set_org_max_subscriber'])){
					jsonRet(false,'SUBSCRIBING_LIMIT_EXCEEDED');
				}
				
				$chkAPI = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". $sr->Get('ID') ." AND subscriber_mail=?") or die(mysqli_error($myconn));
				$chkAPI->bind_param('s',$lmail);
				$chkAPI->execute();
				$chkAPI->store_result();
				if($chkAPI->num_rows==0){
					/* Check Group First */
					if($lgrp==0){
						/* Find Ungroupped ID */
						$opOrgGrp = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". $sr->Get('ID') ." AND isUngroup=1") or die(mysqli_error($myconn));
						$opOrgGrpRs = $opOrgGrp->fetch_assoc();
						$lgrp = $opOrgGrpRs['ID'];
						$opOrgGrp->free();
					}else{
						/* Check Group Owner */
						$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". $sr->Get('ID') ." AND ID=?") or die(mysqli_error($myconn));
						$chkGRP->bind_param('i',$lgrp);
						$chkGRP->execute();
						$chkGRP->store_result();
						if($chkGRP->num_rows==0){
							$chkGRP->close();
							jsonRet(false,'INVALID_GROUP');
						} $chkGRP->close();
					}
					
					/* Check Blacklist */
					$chkBL = $myconn->prepare("SELECT ID FROM ". db_table_pref ."blacklist WHERE OID=". $sr->Get('ID') ." AND email=?") or die(mysqli_error($myconn));
					$chkBL->bind_param('s',$lmail);
					$chkBL->execute();
					$chkBL->store_result();
					if($chkBL->num_rows!=0){
						$chkBL->close();
						jsonRet(false,'EMAIL_IN_BLACKLIST');
					}
					
					# Create Full Data
					$jsonObject = $lmail;
					# JSON Disabled for Static Fields
/* 					$fullData[$jsonObject][] = array('label'=>'Group','content'=>$lgrp);
					$fullData[$jsonObject][] = array('label'=>'E-Mail','content'=>$lmail);
					if(!empty($lsname)) $fullData[$jsonObject][] = array('label'=>'Name','content'=>$lsname);
					if(!empty($lsweb)) $fullData[$jsonObject][] = array('label'=>'Web','content'=>$lsweb);
					if(!empty($lsdate)) $fullData[$jsonObject][] = array('label'=>'Date','content'=>$lsdate);
					if(!empty($lsphone)) $fullData[$jsonObject][] = array('label'=>'Phone','content'=>$lsphone);
					if(!empty($lscomp)) $fullData[$jsonObject][] = array('label'=>'Company','content'=>$lscomp); */
					$fullData = json_encode($fullData);
					$subKey = encr(time().$fullData);
					
					# Add Subscriber **
					$exeAPI = $myconn->prepare("INSERT INTO 
															". db_table_pref ."subscribers
														SET
															OID=". $sr->Get('ID') .",
															GID=?,
															subscriber_mail=?,
															subscriber_name=?,
															subscriber_web=?,
															subscriber_date=?,
															subscriber_phone=?,
															subscriber_company=?,
															subscriber_full_data=?,
															subscriber_active=1,
															subscriber_verify=1,
															subscriber_key='". $subKey ."',
															ip_addr='". $_SERVER['REMOTE_ADDR'] ."'
															") or die(mysqli_error($myconn));
					$exeAPI->bind_param('isssssss',
													$lgrp,
													$lmail,
													$lsname,
													$lsweb,
													$lsdate,
													$lsphone,
													$lscomp,
													$fullData
										);
					$exeAPI->execute();
					$exeAPI->close();
					jsonRet(true,'EMAIL_ADDED');
					
				}else{
					jsonRet(false,'EMAIL_EXISTS');
				} $chkAPI->close();
			}
			
			# Remove
			else if($act=='remove'){
				$remAPI = $myconn->prepare("DELETE FROM ". db_table_pref ."subscribers WHERE OID=". $sr->Get('ID') ." AND subscriber_mail=?") or die(mysqli_error($myconn));
				$remAPI->bind_param('s',$lmail);
				$remAPI->execute();
				$remAPI->close();
				jsonRet(true,'EMAIL_REMOVED');
			}
			
			# Check
			else if($act=='check'){
				$chkAPI = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". $sr->Get('ID') ." AND subscriber_mail=?") or die(mysqli_error($myconn));
				$chkAPI->bind_param('s',$lmail);
				$chkAPI->execute();
				$chkAPI->store_result();
				if($chkAPI->num_rows==0){
					$chkAPI->close();
					jsonRet(false,'EMAIL_NOT_EXISTS');
				}else{
					$chkAPI->close();
					jsonRet(true,'EMAIL_EXISTS');				
				}
			}
			
			# Add to Black List
			else if($act=='toblacklist'){
				
				/* Limit Controller */
				$sourceLimit = calcSource($sr->Get('ID'),'subscriber.blacklist');
				if(!limitBlock($sourceLimit,$orgSets['set_org_max_blacklist'])){
					jsonRet(false,'BLACKLIST_LIMIT_EXCEEDED');
				}
				
				$chkAPI = $myconn->prepare("SELECT ID FROM ". db_table_pref ."blacklist WHERE OID=". $sr->Get('ID') ." AND email=?") or die(mysqli_error($myconn));
				$chkAPI->bind_param('s',$lmail);
				$chkAPI->execute();
				$chkAPI->store_result();
				if($chkAPI->num_rows==0){
					/* Add to Blacklist */
					$addBL = $myconn->prepare("INSERT INTO ". db_table_pref ."blacklist SET OID=". $sr->Get('ID') .",ipAddr='0.0.0.0',reasons=3,email=?") or die(mysqli_error($myconn));
					$addBL->bind_param('s',$lmail);
					$addBL->execute();
					$addBL->close();
					$chkAPI->close();
					/* Remove From List */
					$remMAIL = $myconn->prepare("DELETE FROM ". db_table_pref ."subscribers WHERE OID=". $sr->Get('ID') ." AND subscriber_mail=?") or die(mysqli_error($myconn));
					$remMAIL->bind_param('s',$lmail);
					$remMAIL->execute();
					$remMAIL->close();
					
					jsonRet(true,'EMAIL_ADDED_TO_BLACKLIST');
				}else{
					$chkAPI->close();
					jsonRet(false,'EMAIL_IN_BLACKLIST');				
				}
			}	

			# Move To
			else if($act=='moveto'){
				/* Check Group Owner */
				$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". $sr->Get('ID') ." AND ID=?") or die(mysqli_error($myconn));
				$chkGRP->bind_param('i',$lgrp);
				$chkGRP->execute();
				$chkGRP->store_result();
				if($chkGRP->num_rows==0){
					$chkGRP->close();
					jsonRet(false,'INVALID_GROUP');
				}else{
				
					/* Check E-Mail In Group */
					$cnkMAIL = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". $sr->Get('ID') ." AND GID=? AND subscriber_mail=?") or die(mysqli_error($myconn));
					$cnkMAIL->bind_param('is',$lgrp,$lmail);
					$cnkMAIL->execute();
					$cnkMAIL->store_result();
					if($cnkMAIL->num_rows==0){
						/* Move Now */
						$movMAIL = $myconn->prepare("UPDATE ". db_table_pref ."subscribers SET GID=? WHERE OID=". $sr->Get('ID') ." AND subscriber_mail=?") or die(mysqli_error($myconn));
						$movMAIL->bind_param('is',$lgrp,$lmail);
						$movMAIL->execute();
						$movMAIL->close();
						jsonRet(true,'EMAIL_MOVED');
					}else{
						$chkGRP->close();
						jsonRet(false,'EMAIL_ALREADY_EXISTS_IN_GROUP');
					}
				
				} $chkGRP->close();
			}
		
		# ACTIONS END *********************************************************************************************************
	
	}

}


$myconn->close();
ob_end_flush();
?>