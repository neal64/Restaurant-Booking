<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 15.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once(dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'lethe.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_auth.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_module_loader.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/org_set.php');

/* Module Functions */
$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));

/* Demo Check */
if(DEMO_MODE){
	if($pos=='createdraft'){die(errMod(letheglobal_demo_mode_active,'danger'));}
}

/* Show Template List */
if($pos=='templist'){
	$tempData = '';
	$opTemps = $myconn->query("SELECT ID,OID,temp_name,temp_type,UID,temp_prev FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND temp_type='normal' ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ." ORDER BY temp_name ASC") or die(mysqli_error($myconn));
	while($opTempRs = $opTemps->fetch_assoc()){
		$tempData.='<div class="thumbnail"><a href="javascript:;" data-temp-id="'. $opTempRs['ID'] .'" class="tempPrevs effect6"><span><img src="'. (($opTempRs['temp_prev']=='') ? 'images/temp/tempHolder.png':showIn($opTempRs['temp_prev'],'input')) .'" alt=""></span></a></div>';
	} $opTemps->free();
	$tempData.='
	<script>
		$(".tempPrevs").click(function(){
			var tempID = $(this).data("temp-id");
			var myField = tinyMCE.get("details");
			$.ajax({
				url : "modules/lethe.newsletter/act.xmlhttp.php?pos=loadtemp&ID=" + tempID,
				type: "POST",
				contentType: "application/x-www-form-urlencoded",
				success: function(data, textStatus, jqXHR)
				{
					tinyMCE.activeEditor.setContent(data);
					myField.focus();
					$.fancybox.close();
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					tinyMCE.activeEditor.setContent("'. newsletter_template_could_not_be_loaded .'!");
					myField.focus();
					$.fancybox.close();
				}
			});
		});
	</script>
	';
	echo($tempData);
}

/* Load Template */
if($pos=='loadtemp'){
	$opTemps = $myconn->prepare("SELECT * FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
	$opTemps->bind_param('i',$ID);
	$opTemps->execute();
	$opTemps->store_result();
	if($opTemps->num_rows==0){
		echo(letheglobal_record_not_found);
	}else{
		$sr = new Statement_Result($opTemps);
		$opTemps->fetch();
		echo($sr->Get('temp_contents'));
	}
	$opTemps->close();
}

/* Send Test */
if($pos=='sendtest'){
	
	if(DEMO_MODE){die(errMod(letheglobal_demo_mode_active,'danger'));}
	
	$errText = '';
	if(!isset($_POST['subject']) || empty($_POST['subject'])){$errText.='* '. newsletter_please_enter_a_subject .'<br>';}
	if(!isset($_POST['details']) || empty($_POST['details'])){$errText.='* '. newsletter_please_enter_details .'<br>';}
	if(!isset($_POST['alt_details']) || empty($_POST['alt_details'])){$_POST['alt_details']=null;}
	if(!isset($_POST['attach']) || empty($_POST['attach'])){$_POST['attach']=null;}
	if(!isset($_POST['campaign_sender_title']) || empty($_POST['campaign_sender_title'])){$errText.='* '. letheglobal_please_enter_a_sender_title .'<br>';}
	if(!isset($_POST['campaign_reply_mail']) || !mailVal($_POST['campaign_reply_mail'])){$errText.='* '. letheglobal_please_enter_a_reply_mail .'<br>';}
	
	if($errText==''){
		/* Org Limit Check */
		if(set_org_max_daily_limit!=0){
			if(set_org_daily_sent>=set_org_max_daily_limit){
				die(errMod(letheglobal_daily_limit_exceeded,'danger'));
			}
		}
		
			# Control Submission Account
			$subAccList = explode(',',set_org_submission_account);
			$OSMID = 0;
			if(isset($_POST['subAcc']) && is_numeric($_POST['subAcc'])){
				if(in_array(intval($_POST['subAcc']),$subAccList)){
					$OSMID = intval($_POST['subAcc']);
				}else{
					$OSMID=$subAccList;
				}
			}

			# Start
				$sendMail = new lethe();
				$sendMail->OID=set_org_id;
				$sendMail->OSMID=$OSMID;
				$sendMail->sub_from_title = showIn($_POST['campaign_sender_title'],'page');
				$sendMail->sub_reply_mail = showIn($_POST['campaign_reply_mail'],'page');
				$sendMail->sub_test_mail = showIn(set_org_test_mail,'page');
				$sendMail->sub_mail_attach = $_POST['attach'];
				$sendMail->orgSubInit(); # Load Submission Settings
				$sendMail->sub_mail_id = md5(set_org_test_mail);
				
				/* Short Code Replace */
				$replaced = $sendMail->shortReplaces(array(
														$_POST['subject'],
														$_POST['details'],
														$_POST['alt_details']
														)
												);
				$_POST['subject'] = $replaced[0];
				$_POST['details'] = $replaced[1];
				$_POST['alt_details'] = $replaced[2];
				
				/* Design Receiver Data */
				$rcMail = showIn(set_org_test_mail,'page');
				$rcName = showIn($_POST['campaign_sender_title'],'page');
				$rcSubject = showIn($_POST['subject'],'page');
				$rcBody = $_POST['details'];
				$rcAltBody = $_POST['alt_details'];
				$recData = array($rcMail=>array(
												'name'=>$rcName,
												'subject'=>$rcSubject,
												'body'=>$rcBody,
												'altbody'=>$rcAltBody,
												)						
								);
				$sendMail->sub_mail_receiver = $recData;
				$sendMail->letheSender();
					if($sendMail->sendPos){
						echo(errMod(letheglobal_e_mail_sent_successfully. '('. set_org_test_mail .')','success'));
						if($sendMail->sub_isDebug){
							echo(errMod('<strong>DEBUG:</strong>'.$sendMail->sendingErrors,'info'));
						}
					}else{
						$subErrors = subscribers_error_occured_while_sending_e_mail.'<br>';
						$subErrors .= $sendMail->sendingErrors;
						echo(errMod($subErrors,'danger'));
					}
			# End
			
	}else{
		echo(errMod($errText,'danger'));
	}
	
}

/* Efficiency */
if($pos=='efficiency'){
	
# If you see unset stats check GET value to array conversation
# $campEff = "". $totalSub .",". $sentCnt .",". $unSub .",". $opRecsRs['BOUNCE'] .",". $opRecsRs['OPENS'] .",". $opRecsRs['CLICKS'] ."";
$effData = ((!isset($_GET['effData']) || empty($_GET['effData'])) ? '0,0,0,0,0,0':trim($_GET['effData']));
$effData = explode(",",$effData);
$effData = ((count($effData)!=6) ? array(0,0,0,0,0,0):$effData);
	
$totalSub = $effData[0]; # Total Subscriber
$sent = $effData[1]; # Sent
$unsent = $totalSub-$sent; # Unsent

$totalUnsub = $effData[2]; # Total Unsubscriber
$bounces = $effData[3]; # Bounces
$opens = $effData[4]; # Opens
$nonopens = $totalSub-$opens; # Non-Opens
$clicks = $effData[5]; # Clicks (Thats will not affect to score)

/* Percs */
$sentPerc = percentage($sent, $totalSub, 2); # Percentage for Current Sent
$unsentPerc = percentage($unsent, $totalSub, 2); # Percentage for Current Quoue
$clicksPerc = percentage($clicks, $totalSub, 2);  # Percentage for Clicks
$opensPerc = percentage($opens, $totalSub, 2); # Percentage for Opens
$totalUnsubPerc = percentage($totalUnsub, $totalSub, 2); # Percentage for Unsubscription
$bouncesPerc = percentage($bounces, $totalSub, 2);  # Percentage for Bounces

/* Get Score */
$score = ((($sent-($totalUnsub+$bounces+$nonopens))*100));
$score = (($sent!=0) ? ($score/$sent):$score);
$score = (($score<0) ? 0:$score);

/* Calc Clicks */
$clikDatas = array();
$clikDataMon = array();
for($i=1;$i<=12;$i++){
	$mm = date("m",strtotime("2015-".$i));
	$clikDataMon[] = '"'.date("m-y",strtotime('01-'.$i.'-'.date("Y").'')).'"';
	$clikDatas[] = cntData("SELECT ID FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND CID=". $ID ." AND MONTH(add_date)='". $mm ."' AND YEAR(add_date)='". date("Y") ."'");
}

	echo('
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5">
				<div class="row">
					<div class="col-md-6">
						<h4>'. letheglobal_submission .'</h4><hr>
						<canvas id="myChart" width="125" height="125"></canvas>
					</div>
					<div class="col-md-6">
						<h4>'. letheglobal_deliveries .'</h4><hr>
						<canvas id="myChart2" width="125" height="125"></canvas>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h4>'. letheglobal_clicks .'</h4><hr>
						<canvas id="myChart3" width="310" height="125"></canvas>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="row">
					<div class="col-md-12"><h1>'. letheglobal_score .' <span class="pull-right score-count"><span class="countScore" data-from="0" data-to="'. $score .'">0</span>% <span class="glyphicon glyphicon-thumbs-up"></span></span></h1><hr></div>
					<div class="col-md-4"><div class="alert alert-custom-2">'. letheglobal_sent .'<br><span class="count" data-from="0" data-to="'. $sentPerc .'">0</span>%</div></div>
					<div class="col-md-4"><div class="alert alert-custom-3">'. letheglobal_unsent .'<br><span class="count" data-from="0" data-to="'. $unsentPerc .'">0</span>%</div></div>
					<div class="col-md-4"><div class="alert alert-custom-5">'. letheglobal_clicks .'<br><span class="count" data-from="0" data-to="'. $clicksPerc .'">0</span>%</div></div>
					<div class="col-md-4"><div class="alert alert-custom-7">'. letheglobal_opens .'<br><span class="count" data-from="0" data-to="'. $opensPerc .'">0</span>%</div></div>
					<div class="col-md-4"><div class="alert alert-custom-4">'. letheglobal_unsubscribe .'<br><span class="count" data-from="0" data-to="'. $totalUnsubPerc .'">0</span>%</div></div>
					<div class="col-md-4"><div class="alert alert-custom-6">'. letheglobal_bounces .'<br><span class="count" data-from="0" data-to="'. $bouncesPerc .'">0</span>%</div></div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<h5>'. letheglobal_losses .'</h5><hr>
						<h3 class="text-danger"><span class="count" data-from="0" data-to="'. ($totalUnsubPerc) .'">0</span>% <span class="glyphicon glyphicon-thumbs-down"></span></h3>
					</div>
					<div class="col-md-4">
						<h5>'. letheglobal_list_quality .'</h5><hr>
						<h3 class="text-success"><span class="count" data-from="0" data-to="'. (100-$bouncesPerc) .'">0</span>% <span class="glyphicon glyphicon-thumbs-up"></span></h3>
					</div>
					<div class="col-md-4">
						<h5>'. letheglobal_campaign_quality .'</h5><hr>
						<h3 class="text-success"><span class="count" data-from="0" data-to="'. (percentage(($clicksPerc+$opensPerc)/2,100,0)) .'">0</span>% <span class="glyphicon glyphicon-thumbs-up"></span></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		
		
var data = [
    {
        value: '. $sent .',
        color: "#57464e",
        highlight: "#7b646f",
        label: "'. letheglobal_sent .'"
    },
    {
        value: '. $unsent .',
        color: "#5d6163",
        highlight: "#777e82",
        label: "'. letheglobal_unsent .'"
    }
];

var data2 = [
    {
        value: '. $totalUnsub .',
        color: "#cc5544",
        highlight: "#d87061",
        label: "'. letheglobal_unsubscribe .'"
    },
    {
        value: '. $clicks .',
        color: "#ecb77a",
        highlight: "#f4cfa4",
        label: "'. letheglobal_clicks .'"
    },
    {
        value: '. $opens .',
        color: "#dfbd3b",
        highlight: "#ecce5a",
        label: "'. letheglobal_opens .'"
    },
    {
        value: '. $bounces .',
        color: "#d8e6af",
        highlight: "#f4ffd2",
        label: "'. letheglobal_bounces .'"
    }
];
var data3 = {
    labels: ['. implode(",",$clikDataMon) .'],
    datasets: [
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: ['. implode(",",$clikDatas) .']
        }
    ]
};

var options3 = {
	datasetStrokeWidth : 1,
}

		var ctx = document.getElementById("myChart").getContext("2d");
		var myNewChart = new Chart(ctx).Pie(data);
		
		var ctx2 = document.getElementById("myChart2").getContext("2d");
		var myNewChart2 = new Chart(ctx2).Pie(data2);
		
		var ctx3 = document.getElementById("myChart3").getContext("2d");
		var myNewChart3 = new Chart(ctx3).Line(data3,options3);
		
		$(".countScore").countTo({
			onUpdate: function (value) {
				$(".score-count").css("color",getGreenToRed(parseInt(value)));
				if(value<50){
					$(".score-count .glyphicon").removeClass("glyphicon-thumbs-up");
					$(".score-count .glyphicon").addClass("glyphicon-thumbs-down");
				}else{
					$(".score-count .glyphicon").removeClass("glyphicon-thumbs-down");
					$(".score-count .glyphicon").addClass("glyphicon-thumbs-up");
				}
			}
		});
		$(".count").countTo({
			formatter: function (value, options) {
				return value.toFixed(2);
			},
		});
	</script>
	');
}

/* Report Extra Info */
if($pos=='extInfo'){
	# Clicked URLs etc
	$extraInfo = '<h4 class="text-primary">'. newsletter_extra_info .'</h4><hr>';
	
	$opData = $myconn->prepare("SELECT * FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
	$opData->bind_param('i',$ID);
	$opData->execute();
	$opData->store_result();
	if($opData->num_rows==0){
		$extraInfo.=errMod(letheglobal_record_not_found,'danger');
	}else{
		
		$extST = new Statement_Result($opData);
		$opData->fetch();
		$extInfos = $extST->Get('extra_info');
		
		$extraInfo.=letheglobal_e_mail.': '. showIn($extST->Get('email'),'page') .'<br>';
		$extraInfo.=letheglobal_date.': '. setMyDate($extST->Get('add_date'),2) .'<br>';
		$extraInfo.=letheglobal_clicks.' / '. letheglobal_opens .' '. newsletter_hit .': '. showIn($extST->Get('hit_cnt'),'page') .'<br>';
		$extraInfo.=newsletter_extra_info.': <hr>';
		if(!empty($extInfos)){
			$extraInfo.='<pre>'. showIn($extST->Get('extra_info'),'page') .'</pre>';
		}
		
	} $opData->close();
	
	echo($extraInfo);
}

?>


<?php 
$myconn->close();
ob_end_flush();
?>