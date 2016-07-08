<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 11.03.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lethe.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'lib/lethe.class.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'lib/BHM/lethe.rules.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'lib/BHM/lethe.boxConnector.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'lib/BHM/PlancakeEmailParser.php');

# Submission Account
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));
$errRes = array();
$maxPost = 1000;

$opAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE ID=". $ID ."") or die(mysqli_error($myconn));
if(mysqli_num_rows($opAcc)!=0){
	
	
	$opAccRs = $opAcc->fetch_assoc();
	$conn_security = array('/novalidate-cert','/ssl','/tls');
	
	# Load Bounce Actions
	$bounceActs = json_decode($opAccRs['bounce_actions'],true);
		
	# Connection
	if($opAccRs['bounce_acc']==0){
		$inst=pop3_login($opAccRs['pop3_host'],$opAccRs['pop3_port'],$opAccRs['pop3_user'],$opAccRs['pop3_pass'],$folder='INBOX',$conn_security[$opAccRs['pop3_secure']]);		
	}else{
		$inst=pop3_login($opAccRs['imap_host'],$opAccRs['imap_port'],$opAccRs['imap_user'],$opAccRs['imap_pass'],$folder='INBOX',$conn_security[$opAccRs['imap_secure']]);
	}
	
	if(!$inst){
		$errRes[] = '* Cannot Connect to Mailbox!';
	}else{
		$cTotal = @imap_num_msg($inst);
		$list=@pop3_list($inst);
		$stat=@pop3_stat($inst);
		$errRes[] = '* Mailbox Connection OK!';
		$errRes[] = '* Total Record: ' . $cTotal;
		$bounceApp = new lethe();
		
		if(!isset($stat['Unread']) || $stat['Unread']<=0 || !isset($stat) || !isset($list)){
			$stat['Unread'] = 0;
			$errRes[] = '* Mailbox Empty or There No Unread Mail Found!';
		}
		

		# Fetch
		if($stat['Unread']>0){
			foreach($list as $row){
				$msgHead = imap_fetchheader($inst, $row['msgno'],FT_UID);
				$msgBody = imap_fetchbody($inst, $row['msgno'],FT_UID);
				$emailParser = new PlancakeEmailParser($msgHead);
				
				# Check Encoding
				$chkEnc = $emailParser->getHeader('Content-Transfer-Encoding');
				if(!empty($chkEnc)){
						$msgBody = bodyDecoding($msgBody,$emailParser->getHeader('Content-Transfer-Encoding'));
				}
				
				# Get Lethe Campaign ID
				if(preg_match('/^X-Lethe-ID:(.*)/im',$msgBody,$matches)){
					$letheID = $matches[1];
				}else{$letheID = '';}
				
				# Get Lethe Receiver
				if(preg_match('/^X-Lethe-Receiver:(.*)/im',$msgBody,$matches)){
					$letheReceiver = $matches[1];
				}else{$letheReceiver = '';}
				
				$mailSubject = $emailParser->getSubject();
				
				# Check Mail for Sender is Lethe
				if(empty($letheID)){
					$errRes[] = ('Mail Not Sent From Lethe (Subject: '. $mailSubject .')');
				}else{
					$errRes[] = ('Lethe Mail Found');
					
					# Check Bounce Rule
					$bounceReturn = bmhBodyRules($mailSubject.$msgBody);
					$bounceRuleAct = @$bounceActs[$bounceReturn['rule_cat']];
					
						# Use Rule For Mail
						$bounceApp->bounceKey = trim($letheID);
						$bounceApp->reportIP = "0.0.0.0";
						$bounceApp->reportMail = trim($letheReceiver);
						$bounceApp->reportBounceType = $bounceReturn['rule_cat'];
						$bounceApp->bounceAction = $bounceRuleAct;
						$errRes[] = 'Camp Key: ' . $letheID;
						$errRes[] = 'Receiver: ' . $letheReceiver;
						
						if($bounceApp->bounceHandle()){
							# Remove Msg
							@imap_delete($inst, $row['msgno']);
							$errRes[] = 'action was applied -> Rule: ' . @$LETHE_BOUNCE_ACTIONS[$bounceRuleAct];
						}else{
							$errRes[] = 'action was not applied';
						}
					
				}
				$errRes[] = '<hr>';
				
			}
			@imap_expunge($inst);
		}
		
	}
	
}else{
	$errRes[] = '* Undefined Submission Account!';
}

if(lethe_debug_mode){
	echo(implode('<br>',$errRes));
	//print_r($errRes);
}

$opAcc->free();
$myconn->close();
?>