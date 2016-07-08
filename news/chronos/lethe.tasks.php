<?php
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 25.02.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lethe.php');
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lib/lethe.class.php');
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));

$errLogs = array();
$phase = rand(5000,10000);

# Load Organization Settings For First Time
# It's required for different timezone setting
$opCampSet = $myconn->query("SELECT 
									*
							   FROM 
										". db_table_pref ."campaigns
							  WHERE 
										ID = ". $ID ."") or die(mysqli_error($myconn));
if(mysqli_num_rows($opCampSet)>0){
	
	$opCampSetRs = $opCampSet->fetch_assoc();
	
	/* Load Organization */
	$orgSets = array(); # Main Settings Stored in This Array (set_x)
	$opOrg = new lethe();
	
	# If Organization Exists or allowed for daily limits
	# This function will call all organization settings for one phase
	# If Organization is exceeded daily limits, marked inactive or its not exists sending operation will abort on this section
	# Another limit controller in sending class for main submission accounts.
	if($opOrg->loadOrg($opCampSetRs['OID'])){
		# Settings Loaded
		$LETHE_ORG_SETS['set_org_name'] = $orgSets['set_org_name'];
	}else{
		$errLogs[] = "Error: Organization is Not Active or Daily Limit Exceeded!";
		/* Show Log */
		if(lethe_debug_mode){
			$errLogStr = '';
			foreach($errLogs as $k=>$v){
				$errLogStr.=$v.'<br>';
			}
			echo($errLogStr);
		}
		die();
	}
	/* Load Organization End */
	
}else{
	die("There No Campaign Found!");
} $opCampSet->free();

/* Memory Settings */
@set_time_limit(0);
@date_default_timezone_set($orgSets['set_org_timezone']); # Org Timezone
@ini_set('memory_limit','512M');
$errLogs[] = "Server: Timezone &gt; " . date_default_timezone_get();
$errLogs[] = "Server: Current Date is &gt; " . date("Y-m-d H:i:s A");
if(!ini_get('safe_mode')){
	$errLogs[] = "Error: PHP Safe Mode Active, set_time_limit May Not Work Properly";
}
$errLogs[] = "Server: Current Script Execute Time &gt; " . ini_get('max_execution_time');
$errLogs[] = "Server: Current Memory Limit &gt; "  . ini_get('memory_limit');;

/* Open Campaigns */
$opCamp = $myconn->query("SELECT 
									*
						   FROM 
									". db_table_pref ."campaigns
						  WHERE 
									ID = ". $ID ."
							AND
									(campaign_pos=0 OR campaign_pos=1)
							AND
									launch_date<='". date("Y-m-d H:i:s") ."'
							") or die(mysqli_error($myconn));

while($opCampRs = $opCamp->fetch_assoc()){
	# LOG **
	$errLogs[] = "Progress ($phase): Campaign Data Loaded - " . date("Y-m-d H:i:s A");
	# LOG **
	$errLogs[] = "Progress ($phase): Organization Data Loaded - " . date("Y-m-d H:i:s A");
		# NEWSLETTER ####################################################################################################################
		if($opCampRs['campaign_type']==0){
			# LOG **
			$errLogs[] = "Progress ($phase): Engine Settings Initialization - " . date("Y-m-d H:i:s A");
			
			# Mail Settings Init
			$opOrg->OID=$opCampRs['OID'];
			$opOrg->OSMID=$opCampRs['campaign_sender_account'];
			$opOrg->sub_from_title = showIn($opCampRs['campaign_sender_title'],'page');
			$opOrg->sub_reply_mail = showIn($opCampRs['campaign_reply_mail'],'page');
			$opOrg->sub_mail_attach = $opCampRs['attach'];
			$opOrg->orgSubInit(); # Load Submission Settings
			$opOrg->sub_mail_id = $opCampRs['campaign_key'];
			$setMailPerConn = $orgSets['set_send_per_conn'];
			$setMailPerConnCount = 0;
			
			# Static Short Code Replaces
			# LOG **
			$errLogs[] = "Progress ($phase): Static Data Rendering Started - " . date("Y-m-d H:i:s A");
			$replaced = $opOrg->shortReplaces(array(
													$opCampRs['subject'],
													$opCampRs['details'],
													$opCampRs['alt_details']
													));
													
			# Campaign Group Loader
			# LOG **
			$errLogs[] = "Progress ($phase): Campaign Groups Initialization - " . date("Y-m-d H:i:s A");
			$subGrps = array();
			$opCampGrp = $myconn->query("SELECT * FROM ". db_table_pref ."campaign_groups WHERE OID=". $opCampRs['OID'] ." AND CID=". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
			while($opCampGrpRs = $opCampGrp->fetch_assoc()){
				$subGrps[] = " S.GID=". $opCampGrpRs['GID'] ." ";
			} 
			if(mysqli_num_rows($opCampGrp)>0){
				$subGrps = " AND (". implode(" OR ",$subGrps) .") ";
				# LOG **
				$errLogs[] = "Progress ($phase): Campaign Groups Loaded - " . date("Y-m-d H:i:s A");
			}else{
				# LOG **
				$errLogs[] = "Error ($phase): Campaign Groups Corrupted - " . date("Y-m-d H:i:s A");
			}
			$opCampGrp->free();
			
			# Subscriber datas will collect on this section
				$listLoadCond = array();
				# Verify Mode Cond (If Verify Type Selected as "All" This Condition Will Escaped)
				if($orgSets['set_org_load_type']==1){ # Only Active Subscribers There No Verify Control (Single / Double Verified Will Include)
					$listLoadCond[] = ' AND (S.subscriber_active=1) ';
					# LOG **
					$errLogs[] = "Progress ($phase): Active Subscriber Selection - " . date("Y-m-d H:i:s A");
				}
				else if($orgSets['set_org_load_type']==2){ # Only Active and Single Verified Subscribers
					$listLoadCond[] = ' AND (S.subscriber_active=1 AND S.subscriber_verify=1) ';
					# LOG **
					$errLogs[] = "Progress ($phase): Active + Single Verified Subscriber Selection - " . date("Y-m-d H:i:s A");
				}else if($orgSets['set_org_load_type']==3){ # Only Active and Single + Double Verified Subscribers
					$listLoadCond[] = ' AND (S.subscriber_active=1 AND (S.subscriber_verify=1 OR S.subscriber_verify=2)) ';
					# LOG **
					$errLogs[] = "Progress ($phase): Active + Single Verified Subscriber Selection - " . date("Y-m-d H:i:s A");
				}else{
					# LOG **
					$errLogs[] = "Progress ($phase): Continue for Condition Set Without Active / Verify Controls - " . date("Y-m-d H:i:s A");
				}
				
				# Group Choicer
				$listLoadCond[] = $subGrps;
				
				# Load Type
				# If Random Load Option is Active
				# Thats will protect your repeated mails, if you cancel a campaign in progress
				if($orgSets['set_org_random_load']==1){
					//$listLoadCond[] = " ORDER BY RAND() ";
				}
				
				/* Maximum System Load */
				# LOG **
				$errLogs[] = "Progress ($phase): Maximum Data Loader Set by 5000 for One Phase - " . date("Y-m-d H:i:s A");
				$listLoadCond[] = " LIMIT 5000 ";
							
				/* Render Conds */
				# LOG **
				$errLogs[] = "Progress ($phase): Data Condution Settings End - " . date("Y-m-d H:i:s A");
				$listLoadCond = implode(' ',$listLoadCond);
				$sentData = array();
				
$subScriberStatement = "
						SELECT 
								S.* 
						FROM 
								". db_table_pref ."subscribers AS S
								LEFT JOIN ". db_table_pref ."tasks AS T ON (T.CID=". $opCampRs['ID'] ." AND S.subscriber_mail=T.subscriber_mail)
								LEFT JOIN ". db_table_pref ."unsubscribes AS U ON (U.CID=". $opCampRs['ID'] ." AND S.subscriber_mail=U.subscriber_mail)
						WHERE
								S.OID=". $orgSets['set_ID'] ."
								AND (T.subscriber_mail IS NULL) 
								AND (U.subscriber_mail IS NULL) 

						". $listLoadCond ."
									
						";
				# LOG **
				$errLogs[] = "Progress ($phase): Load Statement: <pre><code>" . $subScriberStatement . "</code></pre>";
					
				$opSubs = $myconn->query($subScriberStatement) or die(mysqli_error($myconn));
										
				# Update to Completed and Add Cron Remover
				if(mysqli_num_rows($opSubs)==0){
					# LOG **
					$errLogs[] = "Progress ($phase): There No Subscriber(s) Found, Task Complete or Error Occured - " . date("Y-m-d H:i:s A");
					$errLogs[] = "Progress ($phase): Cron Remover Active - " . date("Y-m-d H:i:s A");
					$errLogs[] = "Progress ($phase): Campaign Marked as Completed - " . date("Y-m-d H:i:s A");
					# Mark It Completed
					$myconn->query("UPDATE ". db_table_pref ."campaigns SET campaign_pos=3 WHERE OID=". $orgSets['set_ID'] ." AND ID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
					$myconn->query("UPDATE ". db_table_pref ."chronos SET pos=1 WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
					# Add Cron Remover
				}else{
					# If Position is Pending Turn it to In Progress
					if($opCampRs['campaign_pos']==0){
						$myconn->query("UPDATE ". db_table_pref ."campaigns SET campaign_pos=1 WHERE ID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
						# LOG **
						$errLogs[] = "Progress ($phase): New Campaign Started, Campaign Marked as In Progress - " . date("Y-m-d H:i:s A");
					}else{
						# LOG **
						$errLogs[] = "Progress ($phase): Task Handler Started - " . date("Y-m-d H:i:s A");
						$errLogs[] = "Progress ($phase): System Goes to Fetch Subscribers With Setting Condution - " . date("Y-m-d H:i:s A");
					}
				}
				
				# Add Sent Mails
				$addSents = $myconn->prepare("INSERT INTO ". db_table_pref ."tasks SET OID=". $orgSets['set_ID'] .",CID=". $opCampRs['ID'] .",subscriber_mail=?") or die(mysqli_error($myconn));
				
				# LOAD SUBSCRIBERS START ###########################################
				while($opSubsRs = $opSubs->fetch_assoc()){
															
					# User Specific SC Replaces Start ******************************************
					$ireplaced = array();
					foreach($replaced as $rk=>$rv){
						$rvVal = $rv;
						$frKeys = array(
											'#\{?(SUBSCRIBER_NAME)\}#'=>(($opSubsRs['subscriber_name']=='') ? '':$opSubsRs['subscriber_name']),
											'#\{?(SUBSCRIBER_MAIL)\}#'=>(($opSubsRs['subscriber_mail']=='') ? '':$opSubsRs['subscriber_mail']),
											'#\{?(SUBSCRIBER_WEB)\}#'=>(($opSubsRs['subscriber_web']=='') ? '':$opSubsRs['subscriber_web']),
											'#\{?(SUBSCRIBER_PHONE)\}#'=>(($opSubsRs['subscriber_phone']=='') ? '':$opSubsRs['subscriber_phone']),
											'#\{?(SUBSCRIBER_COMPANY)\}#'=>(($opSubsRs['subscriber_company']=='') ? '':$opSubsRs['subscriber_company']),
											'#\{?(NEWSLETTER_LINK\[(.*?)\])\}#'=>'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=web&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'">$2</a>',
											'#\{?(RSS_LINK\[(.*?)\])\}#'=>'<a href="'. $orgSets['set_org_rss_url'] .'">$2</a>',
											'#\{?(UNSUBSCRIBE_LINK\[(.*?)\])\}#'=>'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=unsubscribe&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'&amp;oid='. $orgSets['set_public_key'] .'">$2</a>',
											'#\{?(VERIFY_LINK\[(.*?)\])\}#'=>'', # Verify Link Cannot Be Use In Campaigns
										);
						$rvVal = preg_replace(array_keys($frKeys), $frKeys,$rvVal);
						
						# Track Link
						$rvVal = preg_replace_callback('#\{?(TRACK_LINK\[(.*?)\]\[(.*?)\])\}#',
														create_function(
															'$matches',
															'return \'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=track&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'&amp;redu=\'. letheURLEnc($matches[3]) .\'" target="_blank">\'. $matches[2] .\'</a>\';'
														)
														,$rvVal);
						
						$ireplaced[$rk] = $rvVal;
					}

					# User Specific SC Replaces End ***********************************************
					
					$rcSubject = $ireplaced[0];
					$rcBody = $ireplaced[1];
					$rcAltBody = $ireplaced[2];
					
					/* Add Open Tracker */
					$rcBody .= '<img src="'. lethe_root_url .'lethe.newsletter.php?pos=opntrck&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'" alt="" style="display:none;">';
															
					/* Design Receiver Data */

					$rcMail = showIn($opSubsRs['subscriber_mail'],'page');
					$rcName = showIn($opSubsRs['subscriber_name'],'page');
					$sentData[$rcMail] = array(
												'name'=>$rcName,
												'subject'=>$rcSubject,
												'body'=>$rcBody,
												'altbody'=>$rcAltBody,
												);
												
					# Save Sent Mails
					$addSents->bind_param('s',$rcMail);
					$addSents->execute();
					$setMailPerConnCount++;
					
												
					# Send Mails With Per Conn Limit Start ****
					if($setMailPerConnCount>=$setMailPerConn){
						$opOrg->sub_mail_receiver = $sentData;
						$opOrg->letheSender();
						$setMailPerConnCount=0; # Reset Conn Limit
						
						# LOG **
						$errLogs[] = "Progress ($phase): Rendered Data Send to Mail Engine - " . date("Y-m-d H:i:s A");
						$errLogs[] = "Progress ($phase): System Goes to Standby Mode - " . date("Y-m-d H:i:s A");
						
						# Go Standby
						sleep($orgSets['set_standby_time']);
					}
					# Send Mails With Per Conn Limit End ****
												
				} $opSubs->free();
				# LOAD SUBSCRIBERS END ###########################################
				
					# Send All Mails If Count Less Than Limit ****
						$opOrg->sub_mail_receiver = $sentData;
						$opOrg->letheSender();
						$setMailPerConnCount=0; # Reset Conn Limit
						# Go Standby
						sleep($orgSets['set_standby_time']);
					# Send All Mails If Count Less Than Limit ****
						
				
			/* Load Subscribers End */
			# LOG
			$errLogs[] = "Progress ($phase): Campaign Task Phase Finished!";
		}
		
		# AUTORESPONDER #################################################################################################################
		else if($opCampRs['campaign_type']==1){
			
			# LOG **
			$errLogs[] = "Progress ($phase): Engine Settings Initialization - " . date("Y-m-d H:i:s A");
			
			# Mail Settings Init
			$opOrg->OID=$opCampRs['OID'];
			$opOrg->OSMID=$opCampRs['campaign_sender_account'];
			$opOrg->sub_from_title = showIn($opCampRs['campaign_sender_title'],'page');
			$opOrg->sub_reply_mail = showIn($opCampRs['campaign_reply_mail'],'page');
			$opOrg->sub_mail_attach = $opCampRs['attach'];
			$opOrg->orgSubInit(); # Load Submission Settings
			$opOrg->sub_mail_id = $opCampRs['campaign_key'];
			$setMailPerConn = $orgSets['set_send_per_conn'];
			$setMailPerConnCount = 0;
			
			# Static Short Code Replaces
			# LOG **
			$errLogs[] = "Progress ($phase): Static Data Rendering Started - " . date("Y-m-d H:i:s A");
			$replaced = $opOrg->shortReplaces(array(
													$opCampRs['subject'],
													$opCampRs['details'],
													$opCampRs['alt_details']
													));
													
			# Campaign Group Loader
			# LOG **
			$errLogs[] = "Progress ($phase): Campaign Groups Initialization - " . date("Y-m-d H:i:s A");
			$subGrps = array();
			$opCampGrp = $myconn->query("SELECT * FROM ". db_table_pref ."campaign_groups WHERE OID=". $opCampRs['OID'] ." AND CID=". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
			while($opCampGrpRs = $opCampGrp->fetch_assoc()){
				$subGrps[] = " S.GID=". $opCampGrpRs['GID'] ." ";
			} 
			if(mysqli_num_rows($opCampGrp)>0){
				$subGrps = " AND (". implode(" OR ",$subGrps) .") ";
				# LOG **
				$errLogs[] = "Progress ($phase): Campaign Groups Loaded - " . date("Y-m-d H:i:s A");
			}else{
				# LOG **
				$errLogs[] = "Error ($phase): Campaign Groups Corrupted - " . date("Y-m-d H:i:s A");
			}
			$opCampGrp->free();
			
			# Subscriber datas will collect on this section
				$listLoadCond = array();
				
				# AR Condutions *******
				$opArData = $myconn->query("SELECT 
													* 	
											  FROM 
													". db_table_pref ."campaign_ar 
											 WHERE 
													OID=". $opCampRs['OID'] ." 
											   AND 
													CID=". $opCampRs['ID'] ."
												
											   AND
													ar_week_0=1 AND ar_week_1=1 AND ar_week_2=1 AND ar_week_3=1 AND ar_week_4=1 AND ar_week_5=1 AND ar_week_6=1
												") or die(mysqli_error($myconn));
				if(mysqli_num_rows($opArData)==0){
					# LOG **
					$errLogs[] = "Error ($phase): Autoresponder Settings Corrupted or Date Requirements Doesnt Meet - " . date("Y-m-d H:i:s A");
				}else{
					$opArDataRs = $opArData->fetch_assoc();
					# LOG **
					$errLogs[] = "Error ($phase): Autoresponder Settings Loaded - " . date("Y-m-d H:i:s A");
					
					# After Subscription
					if($opArDataRs['ar_type']==0){
						$date_prep = date("Y-m-d H:i:s");
						$listLoadCond[] = " AND (S.add_date > date_sub('". $date_prep ."', interval ". $opArDataRs['ar_time'] ." ". $opArDataRs['ar_time_type'] .")) ";
					# After Unsubscription
					}else if($opArDataRs['ar_type']==1){
						$date_prep = date("Y-m-d H:i:s");
					# Specific Date
					}else if($opArDataRs['ar_type']==2){
						$date_prep = date("Y-m-d H:i:s");
						
					# Special Date
					}else if($opArDataRs['ar_type']==3){
						$date_prep = date("Y-m-d H:i:s");
						# Remove Older Year Tasks
						$myconn->query("DELETE FROM ". db_table_pref ."tasks WHERE OID=". $opCampRs['OID'] ."  AND YEAR(add_date)<". date("Y") ."") or die(mysqli_error($myconn));
						$listLoadCond[] = " AND (S.subscriber_date BETWEEN '". $date_prep ."' - INTERVAL ". $opArDataRs['ar_time'] ." ". $opArDataRs['ar_time_type'] ." AND '". $date_prep ."' + INTERVAL ". $opArDataRs['ar_time'] ." ". $opArDataRs['ar_time_type'] .") ";
					} # Act End
				
				
				# Dont Use Group, Verify Cond On Unsubscriber Callbacks
				if($opArDataRs['ar_type']!=1){
					# Verify Mode Cond (If Verify Type Selected as "All" This Condition Will Escaped)
					if($orgSets['set_org_load_type']==1){ # Only Active Subscribers There No Verify Control (Single / Double Verified Will Include)
						$listLoadCond[] = ' AND (S.subscriber_active=1) ';
						# LOG **
						$errLogs[] = "Progress ($phase): Active Subscriber Selection - " . date("Y-m-d H:i:s A");
					}
					else if($orgSets['set_org_load_type']==2){ # Only Active and Single Verified Subscribers
						$listLoadCond[] = ' AND (S.subscriber_active=1 AND S.subscriber_verify=1) ';
						# LOG **
						$errLogs[] = "Progress ($phase): Active + Single Verified Subscriber Selection - " . date("Y-m-d H:i:s A");
					}else{
						# LOG **
						$errLogs[] = "Progress ($phase): Continue for Condition Set Without Active / Verify Controls - " . date("Y-m-d H:i:s A");
					}
					
					# Group Choicer
					$listLoadCond[] = $subGrps;
				}
				
				# Load Type
				# If Random Load Option is Active
				# Thats will protect your repeated mails, if you cancel a campaign in progress
				if($orgSets['set_org_random_load']==1){
					$listLoadCond[] = " ORDER BY RAND() ";
				}
				
				/* Maximum System Load */
				# LOG **
				$errLogs[] = "Progress ($phase): Maximum Data Loader Set by 5000 for One Phase - " . date("Y-m-d H:i:s A");
				$listLoadCond[] = " LIMIT 5000 ";
							
				/* Render Conds */
				# LOG **
				$errLogs[] = "Progress ($phase): Data Condution Settings End - " . date("Y-m-d H:i:s A");
				$listLoadCond = implode(' ',$listLoadCond);
				$sentData = array();
				
$subScriberStatement = "
						SELECT 
								S.* 
						FROM 
								". db_table_pref ."subscribers AS S
								LEFT JOIN ". db_table_pref ."tasks AS T ON (T.CID=". $opCampRs['ID'] ." AND S.subscriber_mail=T.subscriber_mail)
								". (($opArDataRs['ar_type']!=1) ? "LEFT JOIN ". db_table_pref ."unsubscribes AS U ON (U.CID=". $opCampRs['ID'] ." AND S.subscriber_mail=U.subscriber_mail)":",". db_table_pref ."unsubscribes AS U") ."
						WHERE
								S.OID=". $orgSets['set_ID'] ."
								AND (T.subscriber_mail IS NULL)
								". (($opArDataRs['ar_type']!=1) ? 'AND (U.subscriber_mail IS NULL)':" AND ((U.CID=". $opCampRs['ID'] .") AND (S.subscriber_mail=U.subscriber_mail) AND (U.add_date > date_sub('". $date_prep ."', interval ". $opArDataRs['ar_time'] ." ". $opArDataRs['ar_time_type'] .")))") ."
								

						". $listLoadCond ."
									
						";
				# LOG **
				$errLogs[] = "Progress ($phase): Load Statement: <pre><code>" . $subScriberStatement . "</code></pre>";
					
				$opSubs = $myconn->query($subScriberStatement) or die(mysqli_error($myconn));
										
				# Update to Completed and Add Cron Remover
				if(mysqli_num_rows($opSubs)==0){
					# LOG **
					$errLogs[] = "Progress ($phase): There No Subscriber(s) Found, Task Complete or Error Occured - " . date("Y-m-d H:i:s A");
					$errLogs[] = "Progress ($phase): Cron Remover Active - " . date("Y-m-d H:i:s A");
					$errLogs[] = "Progress ($phase): Campaign Marked as Completed - " . date("Y-m-d H:i:s A");
					
					# Mark it Completed after tasks end (for Specific Date, ar_end option will check on next phase)
						# ------- Settings will apply after all tasks done -----
						if($opArDataRs['ar_type']==2){
							# Reset AR If Finish Date Reach
							$date_prep_end = date("Y-m-d H:i:s",strtotime($opArDataRs['ar_end_date']));
							if($date_prep_end<=$date_prep){
							
								# Mark It Complete and Remove Cron If "End Campaign" Active
								if($opArDataRs['ar_end']==1){
									$myconn->query("UPDATE ". db_table_pref ."campaigns SET campaign_pos=3 WHERE OID=". $orgSets['set_ID'] ." AND ID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									$myconn->query("UPDATE ". db_table_pref ."chronos SET pos=1 WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
								}else{
									# Reset All Data and Update New Cron Date
									# New Launch Date
									$genDate = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . " +". $opArDataRs['ar_time'] ." ". $opArDataRs['ar_time_type'] .""));
									# New Finish Date
									$difference = dateDiff(strtotime($opCampRs['launch_date']),strtotime($opArDataRs['ar_end_date']));
									$genFinDate = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . " +". $difference .""));
									$myconn->query("UPDATE ". db_table_pref ."campaigns SET campaign_pos=0,launch_date='". $genDate ."' WHERE OID=". $orgSets['set_ID'] ." AND ID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									$myconn->query("UPDATE ". db_table_pref ."campaign_ar SET ar_end_date='". $genFinDate ."' WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									# Remove Old Cron
									$myconn->query("UPDATE ". db_table_pref ."chronos SET pos=1 WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									# Add New Cron
									$buildCron = new lethe();
									$buildCron->chronosMin = "*";
									$buildCron->chronosURL = "'".lethe_root_url.'chronos/lethe.tasks.php?ID='.$opCampRs['ID']."' > /dev/null 2>&1";
									$genComm = $buildCron->buildChronos();
									$addCron = $myconn->prepare("INSERT INTO ". db_table_pref ."chronos SET OID=". $orgSets['set_ID'] .", CID=". $opCampRs['ID'] .", pos=0, cron_command=?, launch_date=?");
									$addCron->bind_param('ss',$genComm,$genDate);
									$addCron->execute();
									$addCron->close();
									# Remove Datas
									$myconn->query("DELETE FROM ". db_table_pref ."tasks WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									$myconn->query("DELETE FROM ". db_table_pref ."reports WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									# Close Phase For New Settings
									die();
								}
							
							}else{
								# Campaign Continues
									# Reset All Data and Update New Cron Date
									# New Launch Date
									$genDate = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . " +". $opArDataRs['ar_time'] ." ". $opArDataRs['ar_time_type'] .""));
									# New Finish Date
									$difference = dateDiff(strtotime($opCampRs['launch_date']),strtotime($opArDataRs['ar_end_date']));
									$genFinDate = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . " +". $difference .""));
									$myconn->query("UPDATE ". db_table_pref ."campaigns SET campaign_pos=0,launch_date='". $genDate ."' WHERE OID=". $orgSets['set_ID'] ." AND ID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									$myconn->query("UPDATE ". db_table_pref ."campaign_ar SET ar_end_date='". $genFinDate ."' WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									# Remove Old Cron
									$myconn->query("UPDATE ". db_table_pref ."chronos SET pos=1 WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									# Add New Cron
									$buildCron = new lethe();
									$buildCron->chronosMin = "*";
									$buildCron->chronosURL = "'".lethe_root_url.'chronos/lethe.tasks.php?ID='.$opCampRs['ID']."' > /dev/null 2>&1";
									$genComm = $buildCron->buildChronos();
									$addCron = $myconn->prepare("INSERT INTO ". db_table_pref ."chronos SET OID=". $orgSets['set_ID'] .", CID=". $opCampRs['ID'] .", pos=0, cron_command=?, launch_date=?");
									$addCron->bind_param('ss',$genComm,$genDate);
									$addCron->execute();
									$addCron->close();
									# Remove Datas
									$myconn->query("DELETE FROM ". db_table_pref ."tasks WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									$myconn->query("DELETE FROM ". db_table_pref ."reports WHERE OID=". $orgSets['set_ID'] ." AND CID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
									# Close Phase For New Settings
									die();
							}
							# AR 2 Settings End
						}
						# ------- Settings will apply after all tasks done -----
				}else{
					# If Position is Pending Turn it to In Progress
					if($opCampRs['campaign_pos']==0){
						$myconn->query("UPDATE ". db_table_pref ."campaigns SET campaign_pos=1 WHERE ID = ". $opCampRs['ID'] ."") or die(mysqli_error($myconn));
						# LOG **
						$errLogs[] = "Progress ($phase): New Campaign Started, Campaign Marked as In Progress - " . date("Y-m-d H:i:s A");
					}else{
						# LOG **
						$errLogs[] = "Progress ($phase): Task Handler Started - " . date("Y-m-d H:i:s A");
						$errLogs[] = "Progress ($phase): System Goes to Fetch Subscribers With Setting Condution - " . date("Y-m-d H:i:s A");
					}
				}
				
				# Add Sent Mails
				$addSents = $myconn->prepare("INSERT INTO ". db_table_pref ."tasks SET OID=". $orgSets['set_ID'] .",CID=". $opCampRs['ID'] .",subscriber_mail=?") or die(mysqli_error($myconn));
				
				# LOAD SUBSCRIBERS START ###########################################
				$ireplaced = array();
				while($opSubsRs = $opSubs->fetch_assoc()){
															
					# User Specific SC Replaces Start ******************************************
					foreach($replaced as $rk=>$rv){
						$rvVal = $rv;
						$frKeys = array(
											'#\{?(SUBSCRIBER_NAME)\}#'=>(($opSubsRs['subscriber_name']=='') ? '':$opSubsRs['subscriber_name']),
											'#\{?(SUBSCRIBER_MAIL)\}#'=>(($opSubsRs['subscriber_mail']=='') ? '':$opSubsRs['subscriber_mail']),
											'#\{?(SUBSCRIBER_WEB)\}#'=>(($opSubsRs['subscriber_web']=='') ? '':$opSubsRs['subscriber_web']),
											'#\{?(SUBSCRIBER_PHONE)\}#'=>(($opSubsRs['subscriber_phone']=='') ? '':$opSubsRs['subscriber_phone']),
											'#\{?(SUBSCRIBER_COMPANY)\}#'=>(($opSubsRs['subscriber_company']=='') ? '':$opSubsRs['subscriber_company']),
											'#\{?(NEWSLETTER_LINK\[(.*?)\])\}#'=>'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=web&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'">$2</a>',
											'#\{?(RSS_LINK\[(.*?)\])\}#'=>'<a href="'. $orgSets['set_org_rss_url'] .'">$2</a>',
											'#\{?(UNSUBSCRIBE_LINK\[(.*?)\])\}#'=>'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=unsubscribe&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'&amp;oid='. $orgSets['set_public_key'] .'">$2</a>',
											'#\{?(VERIFY_LINK\[(.*?)\])\}#'=>'', # Verify Link Cannot Be Use In Campaigns
										);
						$rvVal = preg_replace(array_keys($frKeys), $frKeys,$rvVal);
						
						# Track Link
						$rvVal = preg_replace_callback('#\{?(TRACK_LINK\[(.*?)\]\[(.*?)\])\}#',
														create_function(
															'$matches',
															'return \'<a href="'. lethe_root_url .'lethe.newsletter.php?pos=track&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'&amp;redu=\'. letheURLEnc($matches[3]) .\'" target="_blank">\'. $matches[2] .\'</a>\';'
														)
														,$rvVal);
						
						$ireplaced[$rk] = $rvVal;
					}

					# User Specific SC Replaces End ***********************************************
					
					$rcSubject = $ireplaced[0];
					$rcBody = $ireplaced[1];
					$rcAltBody = $ireplaced[2];
					
					/* Add Open Tracker */
					$rcBody .= '<img src="'. lethe_root_url .'lethe.newsletter.php?pos=opntrck&amp;id='. $opCampRs['campaign_key'] .'&amp;sid='. $opSubsRs['subscriber_key'] .'" alt="" style="display:none;">';
															
					/* Design Receiver Data */

					$rcMail = showIn($opSubsRs['subscriber_mail'],'page');
					$rcName = showIn($opSubsRs['subscriber_name'],'page');
					$sentData[$rcMail] = array(
												'name'=>$rcName,
												'subject'=>$rcSubject,
												'body'=>$rcBody,
												'altbody'=>$rcAltBody,
												);
																
					# Save Sent Mails
					$addSents->bind_param('s',$rcMail);
					$addSents->execute();
					$setMailPerConnCount++;
					
												
					# Send Mails With Per Conn Limit Start ****
					if($setMailPerConnCount>=$setMailPerConn){
						$opOrg->sub_mail_receiver = $sentData;
						$opOrg->letheSender();
						$setMailPerConnCount=0; # Reset Conn Limit
						
						# LOG **
						$errLogs[] = "Progress ($phase): Rendered Data Send to Mail Engine - " . date("Y-m-d H:i:s A");
						$errLogs[] = "Progress ($phase): System Goes to Standby Mode - " . date("Y-m-d H:i:s A");
						
						# Go Standby
						sleep($orgSets['set_standby_time']);
					}
					# Send Mails With Per Conn Limit End ****
												
				} $opSubs->free();
				# LOAD SUBSCRIBERS END ###########################################
				
					# Send All Mails If Count Less Than Limit ****
						$opOrg->sub_mail_receiver = $sentData;
						$opOrg->letheSender();
						//print_r($sentData);
						$setMailPerConnCount=0; # Reset Conn Limit
						# Go Standby
						sleep($orgSets['set_standby_time']);
					# Send All Mails If Count Less Than Limit ****
						
				}
				# AR Condutions End *******
				
			/* Load Subscribers End */
			# LOG
			$errLogs[] = "Progress ($phase): Campaign Task Phase Finished!";
			
		} # Autoresponder End
		
}

/* Show Log */
if(lethe_debug_mode){
	$errLogStr = '';
	foreach($errLogs as $k=>$v){
		$errLogStr.=$v.'<br>';
	}
	echo($errLogStr);
}

/* Clear Cache */
$myconn->close();
ob_end_flush();
?>