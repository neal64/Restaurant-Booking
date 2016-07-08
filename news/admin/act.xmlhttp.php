<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 05.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lethe.php');
if(!isLogged()){die('<script>window.location.href="'. lethe_admin_url .'pg.login.php";</script>');}
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_module_loader.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/org_set.php');
if(!isset($_GET['pos']) || empty($_GET['pos'])){$pos='';}else{$pos=trim($_GET['pos']);}
if(!isset($_GET['ID']) || !is_numeric($_GET['ID'])){$ID=0;}else{$ID=intval($_GET['ID']);}

/* Live Date */
if($pos=='getlivedate'){
	echo(date('d.m.Y H:i:s A'));
}

include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_auth.php');

/* Template Preview */
if($pos=='temprev'){
	$opTemp = $myconn->prepare("SELECT * FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
	$opTemp->bind_param('i',$ID);
	$opTemp->execute();
	$opTemp->store_result();
	if($opTemp->num_rows==0){echo(letheglobal_record_not_found);}
	$sr = new Statement_Result($opTemp);
	$opTemp->fetch();
	$opTemp->close();
	echo($sr->Get('temp_contents'));
}

/* Submission Account Details */
if($pos=='getSubInfos'){
	$subAccData = getSubmission($ID,0);
	$printData = '<div class="row">
		<div class="col-md-4">
			<p><strong>'. newsletter_daily_limit .':</strong></p>
			<p><strong>'. letheglobal_sending .':</strong></p>
			<p><strong>'. letheglobal_type .':</strong></p>
			<p><strong>'. newsletter_test_mail .':</strong></p>
		</div>
		<div class="col-md-8">
			<p>'. $subAccData['daily_sent'] .' / '. $subAccData['daily_limit'] .'</p>
			<p>'. $LETHE_MAIL_METHOD[$subAccData['send_method']] .'</p>
			<p>'. $LETHE_MAIL_TYPE[$subAccData['mail_type']] .'</p>
			<p>'. set_org_test_mail .'</p>
		</div>
	</div>';
	$printData.='
		<script>
			if($("#campaign_sender_title").val()==""){
				$("#campaign_sender_title").val("'. showIn(set_org_sender_title,'input') .'");
			}
			if($("#campaign_reply_mail").val()==""){
				$("#campaign_reply_mail").val("'. showIn(set_org_reply_mail,'input') .'");
			}
		</script>
	';
	echo($printData);
}

# End
if(isset($myconn)){$myconn->close();unset($myconn);ob_end_flush();}
?>