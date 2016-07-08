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
include_once('mod.common.php');
include_once('mod.functions.php');
$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));
$pos2 = ((!isset($_GET['pos2']) || empty($_GET['pos2'])) ? '':trim($_GET['pos2']));
$preview = ((!isset($_GET['preview']) || empty($_GET['preview'])) ? false:true);
$loadForm = ((!isset($_GET['loadForm']) || empty($_GET['loadForm'])) ? false:true);
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));
$optModel = ((!isset($_GET['optModel']) || empty($_GET['optModel'])) ? '':trim($_GET['optModel']));

/* Demo Check */
if(DEMO_MODE){
	if($pos=='createdraft'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='addnewfield'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='savefieldorders'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='updateFields'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='savesettings0'){die(errMod(letheglobal_demo_mode_active,'danger'));}
}

/* Create Form Draft */
if($pos=='createdraft'){

	$sourceLimit = calcSource(set_org_id,'subscriber.forms');
	if(limitBlock($sourceLimit,set_org_max_subscribe_form)){ # Limit Control
	
		/* Form Errors */
		$formErrors = array();
		foreach($LETHE_SUBSCRIBE_ERRORS as $k=>$v){
			$formErrors[] = $v[1];
		}
		$formErrors = implode("[@]",$formErrors);

		if(isset($_GET['draftPos']) && is_numeric($_GET['draftPos']) && $_GET['draftPos']==0){ # Form Draft
			$addDraft = $myconn->query("INSERT INTO ". db_table_pref ."subscribe_forms SET
											OID=". set_org_id .",
											form_name='Draft Form',
											form_id='LetheForm_". substr(md5(time().rand().uniqid(true)),0,7) ."',
											form_type=0,
											isDraft=1,
											UID=". LETHE_AUTH_ID .",
											form_errors='". mysql_prep($formErrors) ."'
										") or die(mysqli_error($myconn));
			$formID = $myconn->insert_id;		
			$addStatic = $myconn->query("INSERT INTO ". db_table_pref ."subscribe_form_fields (OID,FID,field_label,field_name,field_type,field_required,field_placeholder,sorting,field_static,field_error,field_save) VALUES
										(". set_org_id .",". $formID .",'E-Mail','LetheForm_Mail','email',1,'E-Mail',0,1,'Invalid E-Mail Address','subscriber_mail'),
										(". set_org_id .",". $formID .",'Save','LetheForm_Save','submit',0,'',1,1,'','')
										") or die(mysqli_error($myconn));
			
			echo(errMod(letheglobal_recorded_successfully.' <a href="?p=subscribers/forms/add" class="alert-link">'. subscribers_click_to_continue .'</a>','success'));
		}
		else if(isset($_GET['draftPos']) && is_numeric($_GET['draftPos']) && $_GET['draftPos']==1){ # Link Draft
			
		}
		else if(isset($_GET['draftPos']) && is_numeric($_GET['draftPos']) && $_GET['draftPos']==2){ # Integration Draft
			
		}
	
	}else{
		echo(errMod(letheglobal_limit_exceeded,'danger'));
	}
}

/* Field Order Save */
if($pos=='savefieldorders'){
	if(isset($_POST['order'])){
		$orderlist = explode(',', $_POST['order']);
		$smt = $myconn->prepare("UPDATE ". db_table_pref ."subscribe_form_fields SET sorting=? WHERE OID=". set_org_id ." AND ID=?");
			foreach ($orderlist as $k=>$order) {
				$smt->bind_param('ii',$k,$order);
				$smt->execute();
			}
		$smt->close();
	}
}

/* Field Order Lister */
if($pos=='fieldorders'){
	$orderList = '
<script type="text/javascript">
$(function() {

$( "#sortable" ).sortable({
					placeholder: "ui-state-highlight",
					handle: ".sortable-button",
					axis: "y",
					containment: "#sortable-container", scroll: false,
					cursor: "move",
					opacity: 0.7
				});
$( "#sortable" ).disableSelection();

$(".sorting-save-button").bind("click",function() {
		var articleorder="";
		var succText = $(this).data("sorting-succ");
		var errText = $(this).data("sorting-err");
		var procText = $(this).data("sorting-proc");
		var chkPg = $(this).data("sorting-pg");
		var btn = $(this);
		btn.html(procText);
		
		
		$("#sortable li").each(function(i) {
			if (articleorder=="")
				articleorder = $(this).attr("data-article-id");
			else
				articleorder += "," + $(this).attr("data-article-id");
		});
		
		$.post(chkPg, { order: articleorder })
			.success(function(data) {
				btn.html(succText);
				btn.removeClass("btn-info");
				btn.addClass("btn-success");
				btn.prop("disabled",true);
				setTimeout(function () {
					btn.button("reset");
					btn.prop("disabled",false);
					btn.addClass("btn-info");
				}, 2000);
			})
			.error(function(data) { 
				btn.html(errText);		
				btn.removeClass("btn-info");
				btn.addClass("btn-danger");
			}); 
		
	});
});
</script>
	<ul id="sortable">';
	$opFields = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". set_org_id ." AND FID=". $ID ." ORDER BY sorting ASC") or die(mysqli_error($myconn));
	while($opFieldsRs = $opFields->fetch_assoc()){
		$orderList .= '<li data-article-id="'. $opFieldsRs['ID'] .'" class="ui-state-default">
						<div class="col-xs-1 col-md-1"><span class="glyphicon glyphicon-move sortable-button"></span></div>
						<div class="col-xs-5 col-md-11 sortable-list">
							<a href="javascript:;" class="fancybox-field-edit" data-field-ids="'. $opFieldsRs['ID'] .'">'. showIn($opFieldsRs['field_label'],'page') .'</a><br>
							<small><span class="text-muted">'. $LETHE_SUBSCRIBE_FIELD_TYPES[$opFieldsRs['field_type']] .'</span></small>
						</div>
					 </li>';
	}
	$orderList.='</ul>';
	$orderList.='<script>
						/* Fancybox Fielder */
						$(".fancybox-field-edit").click(function(){
							var clkFld = $(this).data("field-ids");
							$(this).fancybox({
							
								type: "ajax",
								href: "modules/lethe.subscribers/act.xmlhttp.php?pos=fieldeditor&ID=" + clkFld,
								autoSize: false,
								afterClose: function(){
									$(".intoAjax").html("");
								}
							
							});
						
						});
	</script>';
				 
	echo($orderList);
}

/* Field Editor */
if($pos=='fieldeditor'){
	$opField = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". set_org_id ." AND ID=". $ID ."") or die(mysqli_error($myconn));
	if(mysqli_num_rows($opField)==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
	$opFieldRs = $opField->fetch_assoc();

		$letheField = new lethe_forms();
		$letheField->FFID = $ID;
		$letheField->fieldSettings = $opFieldRs;
		$fieldModel = $letheField->fieldOptionEditor();
		
		echo($fieldModel);
		include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/ext/sidera.helper.ajax.php');
	} $opField->free();
}

/* Field Creator Form */
if($pos=='fieldcreator'){
	$fieldCreator = '<div id="fieldSelector" class="intoAjax">';
	$fieldCreator .= '
						<label for="fieldChooser">'.sh('7qNvAoFNKX'). subscribers_field_type .'</label>
						<select name="fieldChooser" id="fieldChooser" class="form-control autoWidth">
						<option value="0">'. letheglobal_choose .'</option>
						';
						foreach($LETHE_SUBSCRIBE_FIELD_TYPES as $k=>$v){
							$fieldCreator .= '<option value="'. $k .'"'. ((!fieldController($k,$ID)) ? ' disabled style="color:#ccc;"':'') .'>'. $v .'</option>';
						}
	$fieldCreator .= '
						</select>
						<hr>
						<div id="fieldOptions">

						</div>
					 ';
	$fieldCreator .= '</div>';
	
	$fieldCreator .=
	'<script>
		$("#fieldChooser").change(function(){
			if($(this).find(":selected").val()!="0"){
				getAjax("#fieldOptions","modules/lethe.subscribers/act.xmlhttp.php?pos=fieldoptionloader&optModel="+ $(this).find(":selected").val() +"&ID='. $ID .'","<span class=\"spin glyphicon glyphicon-refresh\"></span>");
			}
		});
	</script>';
	
	echo($fieldCreator);
	include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/ext/sidera.helper.ajax.php');
}

/* Field Updater */
if($pos=='updateFields'){
	$addField = new lethe_forms();
	$addField->OID = set_org_id;
	$addField->FFID = $ID;
	echo($addField->fieldUpdate());
}

/* Field Options Loader */
if($pos=='fieldoptionloader'){

	$letheField = new lethe_forms();
	$letheField->fieldType = $optModel;
	$letheField->FID = $ID;
	$fieldModel = $letheField->fieldModeller();

	$fieldOptions = '<div id="optFieldArea"><div class="form-group"><div class="fieldResult"></div>';
	$fieldOptions .= $fieldModel;
	$fieldOptions .= '</div>';
	$fieldOptions .= '
					<div class="form-group">
						<button type="button" class="btn btn-primary" id="addFieldOpt">'. subscribers_add_field .'</button>
					</div>
					</div>
					<script>
						$("#addFieldOpt").click(function(){
							$.ajax({
								url: "modules/lethe.subscribers/act.xmlhttp.php?pos=addnewfield&ID='. $ID .'",
								type: "POST",
								data: $("#fieldSelector input,#fieldSelector select").serialize(),
								success: function(data){
									$("#optFieldArea").html(data);
									getAjax("#sortable-container","modules/lethe.subscribers/act.xmlhttp.php?pos=fieldorders&ID='. $ID .'","<span class=\"spin glyphicon glyphicon-refresh\"></span>");
								},
								error: function(){
									$(".fieldResult").html("<div class=\"alert alert-danger\">'. subscribers_there_is_error_while_submit .'</div>");
								}
							});
						});
					</script>
					';
	echo($fieldOptions);
	include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/ext/sidera.helper.ajax.php');
}

/* Add New Field */
if($pos=='addnewfield'){
	$addField = new lethe_forms();
	$addField->OID = set_org_id;
	$addField->FID = $ID;
	echo($addField->fieldAdd());
}

/* Save Settings */
if($pos=='savesettings0'){

	$errText = '';
	
	/* Check System Form */
	$isSys = cntData("SELECT ID FROM ". db_table_pref ."subscribe_forms WHERE OID=".set_org_id ." AND ID=". $ID ." AND isSystem=1");
	
	
	/* Delete Form */
	if(isset($_POST['del']) && $_POST['del']=='YES' && $isSys==0){
		$myconn->query("DELETE FROM ". db_table_pref ."subscribe_forms WHERE OID=".set_org_id ." AND ID=". $ID ."") or die(mysqli_error($myconn));
		$myconn->query("DELETE FROM ". db_table_pref ."subscribe_form_fields WHERE OID=".set_org_id ." AND FID=". $ID ."") or die(mysqli_error($myconn));
		die('<script>location.href="?p=subscribers/forms/list";</script>');
	}
	
	if(!isset($_POST['form_type']) || !is_numeric($_POST['form_type'])){$_POST['form_type']=0;}
	if(!isset($_POST['form_name']) || empty($_POST['form_name'])){$errText .= '* '. subscribers_please_enter_a_form_name .'<br>';}
	if(!isset($_POST['success_url']) || empty($_POST['success_url'])){$_POST['success_url']=null;}
	if(!isset($_POST['success_url_text']) || empty($_POST['success_url_text'])){$_POST['success_url_text']=null;}
	if(!isset($_POST['success_text']) || empty($_POST['success_text'])){$_POST['success_text']=subscribers_your_e_mail_recorded_successfully;}
	if(!isset($_POST['redir_time']) || !is_numeric($_POST['redir_time'])){$_POST['redir_time']=0;}
	if(!isset($_POST['form_view']) || !is_numeric($_POST['form_view'])){$_POST['form_view']=0;}
	if(!isset($_POST['form_group']) || !is_numeric($_POST['form_group'])){$errText .= '* '. subscribers_please_choose_a_group .'<br>';}
	if(isset($_POST['include_jquery']) && $_POST['include_jquery']=="YES"){$_POST['include_jquery']=1;}else{$_POST['include_jquery']=0;}
	if(isset($_POST['include_jqueryui']) && $_POST['include_jqueryui']=="YES"){$_POST['include_jqueryui']=1;}else{$_POST['include_jqueryui']=0;}
	if(isset($_POST['publish_form']) && $_POST['publish_form']=="YES"){$noDraft=0;}else{$noDraft=1;}
	
	$formErrors = array();
	foreach($LETHE_SUBSCRIBE_ERRORS as $k=>$v){
		if(!isset($_POST['errors'.$k]) || empty($_POST['errors'.$k])){
			$errText .= '* '. subscribers_please_enter_error_output .': <strong>' . $v[0] .'</strong><br>';
		}else{
			$formErrors[] = $_POST['errors'.$k];
		}
	}
	
	if($errText==''){
		
		$formErrors = implode("[@]",$formErrors);
	
		$updateSet = $myconn->prepare("UPDATE ". db_table_pref ."subscribe_forms SET 
												form_name=?,
												form_type=?,
												form_success_url=?,
												form_success_url_text=?,
												form_success_text=?,
												form_success_redir=?,
												form_view=?,
												form_group=?,
												include_jquery=?,
												include_jqueryui=?,
												form_errors=?,
												isDraft=". $noDraft=0 ."
										WHERE
												OID=". set_org_id ."
										  AND
												ID=?
										") or die(mysqli_error($myconn));
										
		$updateSet->bind_param('sisssiiiiisi',
											$_POST['form_name'],
											$_POST['form_type'],
											$_POST['success_url'],
											$_POST['success_url_text'],
											$_POST['success_text'],
											$_POST['redir_time'],
											$_POST['form_view'],
											$_POST['form_group'],
											$_POST['include_jquery'],
											$_POST['include_jqueryui'],
											$formErrors,
											$ID
								);
		$updateSet->execute();
		$updateSet->close();
	
		$errText = (($noDraft) ? '<script>$(".pub-form").hide();</script>':'').errMod(letheglobal_recorded_successfully,'success');
	}else{
		$errText = errMod($errText,'danger');
	}
	echo($errText);

}

/* Generate Code */
if($pos=='generateCode'){

	$formCode = '';
	$letheForm = new letheForms();
	$letheForm->formID = $ID;
	$letheForm->OID = set_org_id;
	$letheForm->isPreviewForm = $preview;
	$formCode = $letheForm->buildForm();
	
	if($preview){
		echo('<div id="prevForm">'.$formCode.'</div>');
		echo('<script>$("#prevForm button").attr("disabled",true);$("#prevForm input[type=checkbox],#prevForm input[type=radio]").ionCheckRadio();</script>');
	}
	else if($loadForm){
		echo($formCode);
	}else{
		echo('<textarea class="form-control" onclick="this.select();" rows="5">'. showIn($formCode,'page') .'</textarea>');	
	}

}

/* API Actions */
if($pos=='apiactions'){

	$apiAct = '';
	
	/* Add Action */
	if($pos2=='add'){
		$apiAct.='
					<div class="form-group">
						<label for="api_group">'. sh('LLwa28jVb4').subscribers_groups .'</label>
						<select class="form-control autoWidth" id="api_group" name="api_group">';

							$opGroups = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND isUnsubscribe=0 ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
							while($opGroupsRs = $opGroups->fetch_assoc()){
								$apiAct.='<option value="'. $opGroupsRs['ID'] .'">'. showIn($opGroupsRs['group_name'],'page') .'</option>';
							} $opGroups->free();

		$apiAct.='
						</select>
					</div>
					<div class="form-group">
						<label for="api_mail"><span class="glyphicon glyphicon-ok text-success"></span> '. letheglobal_e_mail .'</label>
					</div>
					<div class="form-group">
						<label for="api_sname">'. subscribers_subscriber_name .'</label>
						<input type="checkbox" class="ionc" name="api_sname" id="api_sname" value="YES">
					</div>
					';
					
					#**
					$apiAct.='<script>
						$("#genAPILink").click(function(){
							var apiLink = "'. LETHE_API_URI .'";
							apiLink += "?act=add";
							apiLink += "&pkey='. set_org_public_key .'";
							apiLink += "&akey='. set_org_api_key .'";
							apiLink += "&lmail=[MAIL_VALUE]";
							apiLink += "&lgrp=" + $("#api_group").val();
							
							if($("#api_sname").is(":checked")){
								apiLink += "&lsname=[SUBSCRIBER_NAME_VALUE]";
							}
							
							$("#api_link").val(apiLink);
						});
					</script>';
					
	}
	/* Remove Action */
	else if($pos2=='remove'){
	
		$apiAct.='
						</select>
					</div>
					<div class="form-group">
						<label for="api_mail"><span class="glyphicon glyphicon-ok text-success"></span> '. letheglobal_e_mail .'</label>
					</div>
					';
					#**
					$apiAct.='<script>
						$("#genAPILink").click(function(){
							var apiLink = "'. LETHE_API_URI .'";
							apiLink += "?act=remove";
							apiLink += "&pkey='. set_org_public_key .'";
							apiLink += "&akey='. set_org_api_key .'";
							apiLink += "&lmail=[MAIL_VALUE]";
							
							$("#api_link").val(apiLink);
						});
					</script>';
	
	}
	/* Check Action */
	else if($pos2=='check'){
	
		$apiAct.='
						</select>
					</div>
					<div class="form-group">
						<label for="api_mail"><span class="glyphicon glyphicon-ok text-success"></span> '. letheglobal_e_mail .'</label>
					</div>
					';
					#**
					$apiAct.='<script>
						$("#genAPILink").click(function(){
							var apiLink = "'. LETHE_API_URI .'";
							apiLink += "?act=check";
							apiLink += "&pkey='. set_org_public_key .'";
							apiLink += "&akey='. set_org_api_key .'";
							apiLink += "&lmail=[MAIL_VALUE]";
							
							$("#api_link").val(apiLink);
						});
					</script>';
	
	}	
	/* Black List Action */
	else if($pos2=='toblacklist'){
	
		$apiAct.='
						</select>
					</div>
					<div class="form-group">
						<label for="api_mail"><span class="glyphicon glyphicon-ok text-success"></span> '. letheglobal_e_mail .'</label>
					</div>
					';
					#**
					$apiAct.='<script>
						$("#genAPILink").click(function(){
							var apiLink = "'. LETHE_API_URI .'";
							apiLink += "?act=toblacklist";
							apiLink += "&pkey='. set_org_public_key .'";
							apiLink += "&akey='. set_org_api_key .'";
							apiLink += "&lmail=[MAIL_VALUE]";
							
							$("#api_link").val(apiLink);
						});
					</script>';
	
	}	
	/* Move Action */
	else if($pos2=='moveto'){
	
		$apiAct.='
					<div class="form-group">
						<label for="api_group">'. sh('ocpnXgO15m').subscribers_destination_group .'</label>
						<select class="form-control autoWidth" id="api_group" name="api_group">';

							$opGroups = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND isUnsubscribe=0 ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
							while($opGroupsRs = $opGroups->fetch_assoc()){
								$apiAct.='<option value="'. $opGroupsRs['ID'] .'">'. showIn($opGroupsRs['group_name'],'page') .'</option>';
							} $opGroups->free();

		$apiAct.='
						</select>
					</div>
					<div class="form-group">
						<label for="api_mail"><span class="glyphicon glyphicon-ok text-success"></span> '. letheglobal_e_mail .'</label>
					</div>
					';
					
					#**
					$apiAct.='<script>
						$("#genAPILink").click(function(){
							var apiLink = "'. LETHE_API_URI .'";
							apiLink += "?act=moveto";
							apiLink += "&pkey='. set_org_public_key .'";
							apiLink += "&akey='. set_org_api_key .'";
							apiLink += "&lmail=[MAIL_VALUE]";
							apiLink += "&lgrp=" + $("#api_group").val();
							
							$("#api_link").val(apiLink);
						});
					</script>';
	
	}
	
	$apiAct.='<script>
	var sidera_helper_uri = "'. SIDERA_HELPER_URL .'";
	$(".shd-mh").bind("click",function(){
		var shd_key = $(this).data("shd-key");
		$.fancybox({
						 autoSize   : true,
						 type       : "iframe",
						 href       : sidera_helper_uri + shd_key
						 });
	});
	$(".ionc").ionCheckRadio();
	</script>';
	echo($apiAct);
}

/* Send E-Mail to Subscriber */
if($pos=='sbrsendmail'){

	$errText = '';
	$sbrMailForm='';
	$opSub = $myconn->prepare("SELECT ID,OID,subscriber_name,subscriber_mail,subscriber_verify,subscriber_verify_key,subscriber_verify_sent_interval,subscriber_key FROM ".db_table_pref."subscribers WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
	$opSub->bind_param('i',$ID);
	$opSub->execute();
	$opSub->store_result();
	if($opSub->num_rows==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
		$sr = new Statement_Result($opSub);
		$opSub->fetch();
		$opSub->close();

	if(!isDemo('sbrSentMailAct,resendact,genVerfyCode')){die(errMod(letheglobal_demo_mode_active,'danger'));}
	# Mail Actions Start
	if(isset($_POST['sbrSentMailAct'])){
		/* Org Limit Check */
		if(set_org_max_daily_limit!=0){
			if(set_org_daily_sent>=set_org_max_daily_limit){
				die(errMod(letheglobal_daily_limit_exceeded,'danger'));
			}
		}
		if(!isset($_POST['sbrSentMailSbj']) || empty($_POST['sbrSentMailSbj'])){$errText.='* '. subscribers_please_enter_a_subject .'<br>';}
		if(!isset($_POST['sbrSentMailBody']) || empty($_POST['sbrSentMailBody'])){$errText.='* '. subscribers_please_enter_a_message .'<br>';}
		
		$subAccs = explode(',',set_org_submission_account);
		if(count($subAccs)<1){$errText.='* '. subscribers_please_enter_a_subject .''. ((lethe_debug_mode) ? ' DEBUG: Invalid Submission Account!':'') .'<br>';}else{
			$OSMID = $subAccs[0];
		}
	
		if($errText==''){
		$sendMail = new lethe();
		$sendMail->OID=set_org_id;
		$sendMail->OSMID=$OSMID;
		$sendMail->sub_from_title = showIn(set_org_sender_title,'page');
		$sendMail->sub_reply_mail = showIn(set_org_reply_mail,'page');
		$sendMail->sub_test_mail = showIn(set_org_test_mail,'page');
		$sendMail->orgSubInit(); # Load Submission Settings
		$sendMail->sub_mail_id = md5($sr->Get('subscriber_mail'));
		
		/* Design Receiver Data */
		$rcMail = $sr->Get('subscriber_mail');
		$rcName = $sr->Get('subscriber_name');
		$rcSubject = trim($_POST['sbrSentMailSbj']);
		$rcBody = nl2br($_POST['sbrSentMailBody']);
		$rcAltBody = '';
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
				echo('<script>$("#sbr-SentMailFrm").html("");</script>');
				echo(errMod(subscribers_e_mail_sent_successfully,'success'));
			}else{
				echo(errMod(subscribers_error_occured_while_sending_e_mail,'danger'));
			}
		}else{
			echo(errMod($errText,'danger'));
		}
		
		die();
	}
	# Mail Actions End
	
	/* Re-Send Verification Start */
	if(isset($_POST['resendact'])){
		/* Org Limit Check */
		if(set_org_max_daily_limit!=0){
			if(set_org_daily_sent>=set_org_max_daily_limit){
				die(errMod(letheglobal_daily_limit_exceeded,'danger'));
			}
		}
		$intDate = date('Y-m-d H:i:s',strtotime($sr->Get('subscriber_verify_sent_interval')));
		if($intDate>date('Y-m-d H:i:s')){
			die(errMod(subscribers_you_can_send_new_mail_after_2_min,'danger'));
		}
		$lt = new lethe();
		$lt->OID=$sr->Get('OID');
		$lt->SUBID=$sr->Get('ID');
		if($lt->sendVerify()){
			echo(errMod(subscribers_verification_mail_successfully_sent_to_subscriber,'success'));
		}else{
			echo(errMod(subscribers_error_occured_while_sending_e_mail,'danger'));
		}
		unset($lt);
		die();
	}
	/* Re-Send Verification End */
	
	/* New Code Generation Start */
	if(isset($_POST['genVerfyCode'])){
	
		$genNew = encr(time().$sr->Get('subscriber_mail').$sr->Get('subscriber_verify_key').uniqid(true));
		
		$upCode = $myconn->prepare("UPDATE ". db_table_pref ."subscribers SET subscriber_verify_key=? WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		$upCode->bind_param('si',$genNew,$ID);
		if($upCode->execute()){
			echo('
			<script>
				$(".code1").html("'. $genNew .'");
				$(".code2").html("'. encr($genNew) .'");
			</script>
			');
		}
		$upCode->close();
	
	die();
	}
	/* New Code Generation End */
	
	$sbrMailForm .= '
	
	<div role="tabpanel">

	  <!-- Nav tabs -->
	  <ul class="nav nav-pills" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">'. letheglobal_e_mail .'</a></li>
		<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">'. subscribers_send_new_confirmation_email .'</a></li>
	  </ul>
	  <hr>
	  <!-- Tab panes -->
	  <div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="home">

			<div id="sbrMailSents"></div>
			<div class="container-fluid"><div class="row"><div class="col-md-12">
			<form name="sbr-SentMailFrm" id="sbr-SentMailFrm" action="" method="POST">
				<input type="hidden" name="sbrSentMailAct" value="YES">
				<div class="form-group">
					<label for="tofields">'. subscribers_receiver .'</label>
					<div class="help-block">&lt;'. showIn($sr->Get('subscriber_mail'),'page') .'&gt;</div>
				</div>
				<div class="form-group">
					<label for="sbrSentMailSbj">'. subscribers_subject .'</label>
					<input type="text" name="sbrSentMailSbj" id="sbrSentMailSbj" value="" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="sbrSentMailBody">'. subscribers_message .'</label>
					<textarea name="sbrSentMailBody" id="sbrSentMailBody" class="form-control" required></textarea>
				</div>
				<div class="form-group">
					<button type="submit" name="sbrSentMailButs" id="sbrSentMailButs" class="btn btn-warning">'. letheglobal_send .'</button>
				</div>
			</form>
			</div></div></div>
			<script>
				$("#sbr-SentMailFrm").on("submit",function(e){
					
					e.preventDefault();
					$("#sbrMailSents").html("<span class=\"spin glyphicon glyphicon-refresh\"></span>");
					$.ajax({
						url : "modules/lethe.subscribers/act.xmlhttp.php?pos=sbrsendmail&ID='. $ID .'",
						type: "POST",
						data : $("#sbr-SentMailFrm").serialize(),
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#sbrMailSents").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							$("#sbrMailSents").html("'. subscribers_e_mail_could_not_be_sent .'");
						}
					});
					
				});
			</script>

		
		</div>
		<div role="tabpanel" class="tab-pane fade" id="profile">
			<div id="sbrVerifySents"></div>
			<div id="sbrVerifyCodRes"></div>
			<div class="container-fluid"><div class="row"><div class="col-md-12">
			
				<form name="newVerify" id="newVerify" action="" method="POST">
					<input type="hidden" name="resendact" value="YES">
					<div class="form-group">
						<label>'. subscribers_verification_status .':</label>
						<span class="tooltiper" title="'. $LETHE_VERIFICATION_TYPE[$sr->Get('subscriber_verify')] .'">'. getBullets($sr->Get('subscriber_verify')) .'</span>
					</div>
					<div class="form-group">
						<label>'. subscribers_verification_codes .':</label><br>
						<p><strong>'. subscribers_single .':</strong> <code class="code1">'. $sr->Get('subscriber_verify_key') .'</code></p>
						<p><strong>'. subscribers_double .':</strong> <code class="code2">'. encr($sr->Get('subscriber_verify_key')) .'</code></p>
					</div>
					<div class="form-group">
						<label>'. subscribers_manual_verification_for_single .':</label>
						<a data-verif-code=".code1" href="javascript:;" class="manualVerify">'. letheglobal_click_here .'</a>
					</div>
					<div class="form-group">
						<label>'. subscribers_manual_verification_for_double .':</label>
						<a data-verif-code=".code2" href="javascript:;" class="manualVerify">'. letheglobal_click_here .'</a>
					</div>
					<div class="form-group">
						<button type="button" name="genNewCode" id="genNewCode" class="btn btn-warning">'.subscribers_generate_new_code .'</button> 
						<button type="submit" name="reSend" id="reSend" class="btn btn-success">'. subscribers_send_to_subscriber .'</button>
					</div>
				
				</form>
				
			<script>
				$(".tooltiper").tooltip();
				
				/* Open Manual Verification */
				$(".manualVerify").click(function(){
				
					var link = "'. lethe_root_url .'lethe.newsletter.php?pos=verification&oid='. set_org_public_key .'&sid='. $sr->Get('subscriber_key') .'&rt=";
					var vcode = $(this).data("verif-code");
					window.open(link+$(vcode).html());
				
				});
				
				/* Send Verification Mail */
				$("#newVerify").on("submit",function(e){
					
					e.preventDefault();
					$("#sbrVerifySents").html("<span class=\"spin glyphicon glyphicon-refresh\"></span>");
					$.ajax({
						url : "modules/lethe.subscribers/act.xmlhttp.php?pos=sbrsendmail&ID='. $ID .'",
						type: "POST",
						data : $("#newVerify").serialize(),
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#sbrVerifySents").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							$("#sbrVerifySents").html("'. subscribers_e_mail_could_not_be_sent .'");
						}
					});
					
				});
				
				/* Generate New Code */
				$("#genNewCode").click(function(){
				
					$.ajax({
						url : "modules/lethe.subscribers/act.xmlhttp.php?pos=sbrsendmail&ID='. $ID .'",
						type: "POST",
						data : {genVerfyCode:"YES"},
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#sbrVerifyCodRes").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							$("#sbrVerifyCodRes").html("'. subscribers_e_mail_could_not_be_sent .'");
						}
					});
				
				});
			</script>
			
			</div></div></div>
		</div>
	  </div>

	</div>	
	';
	echo($sbrMailForm);
	
	} 

}

/* Subscriber Details */
if($pos=='sbrfulldata'){
	
	$opSub = $myconn->prepare("SELECT 
										S.*,
										SG.ID AS SGID, SG.group_name
								 FROM 
										".db_table_pref."subscribers AS S,
										".db_table_pref."subscriber_groups AS SG
								WHERE 
										S.OID=". set_org_id ." 
								  AND 
										S.ID=?
								  AND
										(SG.ID = S.GID)
									") or die(mysqli_error($myconn));
	$opSub->bind_param('i',$ID);
	$opSub->execute();
	$opSub->store_result();
	if($opSub->num_rows==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
		$sr = new Statement_Result($opSub);
		$jsonBld = new lethe();
		$jsonBld->OID = set_org_id;
		$opSub->fetch();
		$buildJSON = $jsonBld->buildJSON($sr->Get('ID'));
	
		$dataCont = '
				<table class="footable">
				  <thead>
					<tr>
						<th colspan="2">'. $sr->Get('subscriber_mail') .'</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<td width="200"><strong>'. subscribers_groups .'</strong></td>
						<td>'. showIn($sr->Get('group_name'),'page') .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_name .'</strong></td>
						<td>'. (($sr->Get('subscriber_name')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':showIn($sr->Get('subscriber_name'),'page')) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_e_mail .'</strong></td>
						<td>'. (($sr->Get('subscriber_mail')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':showIn($sr->Get('subscriber_mail'),'page')) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_web .'</strong></td>
						<td>'. (($sr->Get('subscriber_web')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':showIn($sr->Get('subscriber_web'),'page')) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_date .'</strong></td>
						<td>'. (($sr->Get('subscriber_date')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':setMyDate($sr->Get('subscriber_date'),1)) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_phone .'</strong></td>
						<td>'. (($sr->Get('subscriber_phone')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':showIn($sr->Get('subscriber_phone'),'page')) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_company .'</strong></td>
						<td>'. (($sr->Get('subscriber_company')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':showIn($sr->Get('subscriber_company'),'page')) .'</td>
					</tr>
					<tr>
						<td><strong>'. subscribers_full_data .'</strong></td>
						<td><pre><code class="language-javascript">'. (($sr->Get('subscriber_full_data')=='') ? '<span class="text-danger glyphicon glyphicon-ban-circle"></span>':prettyPrint(showIn($buildJSON,'page'))) .'</code></pre></td>
					</tr>
					<tr>
						<td><strong>'. subscribers_status .'</strong></td>
						<td>'. getBullets($sr->Get('subscriber_active')) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_verification .'</strong></td>
						<td>'. getBullets($sr->Get('subscriber_verify')) .'</td>
					</tr>
					<tr>
						<td><strong>'. subscribers_subscriber_key .'</strong></td>
						<td><code>'. $sr->Get('subscriber_key') .'</code></td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_created .'</strong></td>
						<td>'. setMyDate($sr->Get('add_date'),2) .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_ip_address .'</strong></td>
						<td>'. $sr->Get('ip_addr') .'</td>
					</tr>
					<tr>
						<td><strong>'. subscribers_verification_key .'</strong></td>
						<td><code>'. $sr->Get('subscriber_verify_key') .'</code></td>
					</tr>
					<tr>
						<td><strong>'. subscribers_verification_key .' 2</strong></td>
						<td><code>'. encr($sr->Get('subscriber_verify_key')) .'</code></td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_country .'</strong></td>
						<td>'. showIn($sr->Get('local_country'),'page') .' <span class="flag flag-'. strtolower(showIn($sr->Get('local_country_code'),'page')) .'"></span></td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_country_code .'</strong></td>
						<td>'. showIn($sr->Get('local_country_code'),'page') .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_city .'</strong></td>
						<td>'. showIn($sr->Get('local_city'),'page') .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_region .'</strong></td>
						<td>'. showIn($sr->Get('local_region'),'page') .'</td>
					</tr>
					<tr>
						<td><strong>'. letheglobal_region_code .'</strong></td>
						<td>'. showIn($sr->Get('local_region_code'),'page') .'</td>
					</tr>
				  </tbody>
				</table>
				<script>
				Prism.highlightElement($(".language-javascript")[0]);
				</script>
		';
		
		echo('<div style="padding:5px">'. $dataCont .'</div><br>');
	}
	$opSub->close();
}

/* Subscriber Stats */
if($pos=='sbrstats'){
	
	$subReport = '';
	$opSbr = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
	$opSbr->bind_param('i',$ID);
	$opSbr->execute();
	$opSbr->store_result();
	if($opSbr->num_rows==0){
		$subReport = errMod(letheglobal_record_not_found,'danger');
	}else{
		$srb = new Statement_Result($opSbr);
		$opSbr->fetch();
		$subReport .= '<h3 class="text-primary">'. showIn($srb->Get('subscriber_mail'),'page') .'<span class="txxs help-block"><strong>'. letheglobal_date .':</strong> '. setMyDate($srb->Get('add_date'),2) .'</span></h3><hr>';
		$subReport .= '
			<table class="footable table table-striped">
				<thead>
					<tr>
						<th>'. letheglobal_campaign .'</th>
						<th>'. letheglobal_type .'</th>
						<th data-hide="phone">'. letheglobal_date .'</th>
						<th data-hide="phone">'. newsletter_hit .'</th>
					</tr>
				</thead>
				<tbody>';
		$opRep = $myconn->query("SELECT 
											R.*,
											C.subject,C.ID AS CaID
								   FROM 
											". db_table_pref ."reports AS R,
											". db_table_pref ."campaigns AS C
								  WHERE 
											email='". mysql_prep($srb->Get('subscriber_mail')) ."'
									AND
											(C.ID=R.CID)
									
									") or die(mysqli_error($myconn));
		while($opRepRs = $opRep->fetch_assoc()){
			$subReport .= '
					<tr>
						<td>'. showIn($opRepRs['subject'],'page') .'</td>
						<td>';
						if($opRepRs['pos']==2){
							$subReport .= '<span class="label label-danger">'. letheglobal_bounces .'</span>';
						}else if($opRepRs['pos']==1){
							$subReport .= '<span class="label label-warning">'. letheglobal_opens .'</span>';
						}else{
							$subReport .= '<span class="label label-success">'. letheglobal_clicks .'</span>';
						}
			$subReport .= '
						</td>
						<td>'. setMyDate($opRepRs['add_date'],2) .'</td>
						<td>'. (int)$opRepRs['hit_cnt'] .'</td>
					</tr>
			';
		} $opRep->free();
		$subReport .= '
				</tbody>
			</table>
			<script>$(".footable").footable();</script>
		';
	}
	$opSbr->close();
	echo($subReport);
	
}

/* Subscriber Edit */
if($pos=='sbredit'){

	$opSub = $myconn->prepare("SELECT 
										S.*,
										SG.ID AS SGID, SG.group_name
								 FROM 
										".db_table_pref."subscribers AS S,
										".db_table_pref."subscriber_groups AS SG
								WHERE 
										S.OID=". set_org_id ." 
								  AND 
										S.ID=?
								  AND
										(SG.ID = S.GID)
									") or die(mysqli_error($myconn));
	$opSub->bind_param('i',$ID);
	$opSub->execute();
	$opSub->store_result();
	if($opSub->num_rows==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
		$sr = new Statement_Result($opSub);
		$opSub->fetch();
		
	/* Update Subscriber */
	if(!isDemo('uptSubscriber')){die(errMod(letheglobal_demo_mode_active,'danger'));}
	$errText = '';
	if(isset($_POST['uptSubscriber'])){
		if(!isset($_POST['group']) || !is_numeric($_POST['group'])){$errText.='* '. subscribers_please_choose_a_group .'<br>';}
		if(!isset($_POST['subscriber_name']) || empty($_POST['subscriber_name'])){$_POST['subscriber_name']=NULL;}
		if(!isset($_POST['subscriber_mail']) || !mailVal($_POST['subscriber_mail'])){$errText.='* '. letheglobal_invalid_e_mail_address .'<br>';}else{
			if(cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND subscriber_mail='". mysql_prep($sr->Get('subscriber_mail')) ."' AND ID<>". $sr->Get('ID') ."")!=0){
				$errText.='* '. letheglobal_e_mail_already_exists .'<br>';
			}
		}
		if(!isset($_POST['subscriber_web']) || empty($_POST['subscriber_web'])){$_POST['subscriber_web']=NULL;}
		if(!isset($_POST['subscriber_date']) || empty($_POST['subscriber_date'])){$_POST['subscriber_date']=NULL;}else{
				$_POST['subscriber_date'] = str_replace('/','-',$_POST['subscriber_date']);
				$_POST['subscriber_date']=date('Y-m-d H:i:s',strtotime($_POST['subscriber_date']));
			}
		if(!isset($_POST['subscriber_phone']) || empty($_POST['subscriber_phone'])){$_POST['subscriber_phone']=NULL;}
		if(!isset($_POST['subscriber_company']) || empty($_POST['subscriber_company'])){$_POST['subscriber_company']=NULL;}
		if(!isset($_POST['subscriber_active']) || empty($_POST['subscriber_active'])){$_POST['subscriber_active']=0;}else{$_POST['subscriber_active']=1;}
		if(!isset($_POST['subscriber_verify']) || !is_numeric($_POST['subscriber_verify'])){$_POST['subscriber_verify']=$sr->Get('subscriber_verify');}
		
		if($errText==''){
			
/* 			Disabled JSON Update
			$regenFull = array();
			$regenFullJson = '';
			
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Group','content'=>$_POST['group']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Name','content'=>$_POST['subscriber_name']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'E-Mail','content'=>$_POST['subscriber_mail']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Web','content'=>$_POST['subscriber_web']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Date','content'=>$_POST['subscriber_date']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Phone','content'=>$_POST['subscriber_phone']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Company','content'=>$_POST['subscriber_company']);
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Country','content'=>$sr->Get('local_country'));
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'CountryCode','content'=>$sr->Get('local_country_code'));
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'City','content'=>$sr->Get('local_city'));
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'Region','content'=>$sr->Get('local_region'));
			$regenFull[$_POST['subscriber_mail']][] = array('label'=>'RegionCode','content'=>$sr->Get('local_region_code'));
			$regenFullJson = json_encode($regenFull); */
			
			$upDater = $myconn->prepare("UPDATE 
												 ". db_table_pref ."subscribers 
											SET
												 GID=?,
												 subscriber_name=?,
												 subscriber_mail=?,
												 subscriber_web=?,
												 subscriber_date=?,
												 subscriber_phone=?,
												 subscriber_company=?,
												 subscriber_active=?,
												 subscriber_verify=?
										  WHERE
												 OID=". set_org_id ."
											AND
												ID=?
											") or die(mysqli_error($myconn));
			$upDater->bind_param('issssssiii',
												$_POST['group'],
												$_POST['subscriber_name'],
												$_POST['subscriber_mail'],
												$_POST['subscriber_web'],
												$_POST['subscriber_date'],
												$_POST['subscriber_phone'],
												$_POST['subscriber_company'],
												$_POST['subscriber_active'],
												$_POST['subscriber_verify'],
												$ID
								);
			$upDater->execute();
			$upDater->close();
			die(errMod(letheglobal_updated_successfully,'success'));
		}else{
			die(errMod($errText,'danger'));
		}
	}
		
	/* Load Groups for All Sections */
	$listGrps = array();
	$opGroups = $myconn->query("SELECT 
										SG.*,
										(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE GID=SG.ID) AS sbr_cnt
								  FROM 
										". db_table_pref ."subscriber_groups AS SG
								 WHERE 
										OID=". set_org_id ." 

										". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
							  ORDER BY
										group_name
								   ASC
								") or die(mysqli_error($myconn));
		while($opGroupsRs = $opGroups->fetch_assoc()){
			$listGrps[] = $opGroupsRs;
		} $opGroups->free();
		
		$editForm = '
		<div id="updateResult" tabindex="-1"></div>
		<form name="subUpdater" id="subUpdater" action="" method="POST">
		<input type="hidden" name="uptSubscriber" id="uptSubscriber" value="YES">
		
			<div class="form-group">
				<label for="group">'. subscribers_groups .'</label>
				<select class="form-control autoWidth" id="group" name="group">';
				foreach($listGrps as $k=>$v){
					
					$editForm .= '<option value="'. $v['ID'] .'"'. formSelector($sr->Get('GID'),$v['ID'],0) .'>'. showIn($v['group_name'],'page') .'</option>';
				}
		$editForm .= '
				</select>
			</div>
			<div class="form-group">
				<label for="subscriber_name">'. letheglobal_name .'</label>
				<input type="text" class="form-control" id="subscriber_name" name="subscriber_name" value="'. showIn($sr->Get('subscriber_name'),'input') .'">
			</div>
			<div class="form-group">
				<label for="subscriber_mail">'. letheglobal_e_mail .'</label>
				<input type="email" class="form-control" id="subscriber_mail" name="subscriber_mail" value="'. showIn($sr->Get('subscriber_mail'),'input') .'">
			</div>
			<div class="form-group">
				<label for="subscriber_web">'. letheglobal_web .'</label>
				<input type="text" class="form-control" id="subscriber_web" name="subscriber_web" value="'. showIn($sr->Get('subscriber_web'),'input') .'">
			</div>
			<div class="form-group">
				<label for="subscriber_date">'. letheglobal_date .'</label>
				<input type="text" class="form-control" id="subscriber_date" name="subscriber_date" value="'. (($sr->Get('subscriber_date')!='') ? setMyDate($sr->Get('subscriber_date'),1):'') .'">
			</div>
			<div class="form-group">
				<label for="subscriber_phone">'. letheglobal_phone .'</label>
				<input type="phone" class="form-control" id="subscriber_phone" name="subscriber_phone" value="'. showIn($sr->Get('subscriber_phone'),'input') .'">
			</div>
			<div class="form-group">
				<label for="subscriber_company">'. letheglobal_company .'</label>
				<input type="text" class="form-control" id="subscriber_company" name="subscriber_company" value="'. showIn($sr->Get('subscriber_company'),'input') .'">
			</div>
			<div class="form-group">
				<label for="subscriber_verify">'. letheglobal_verification .'</label>
				<select class="form-control autoWidth" id="subscriber_verify" name="subscriber_verify">';
				foreach($LETHE_VERIFICATION_TYPE as $k=>$v){
					
					$editForm .= '<option value="'. $k .'"'. formSelector($sr->Get('subscriber_verify'),$k,0) .'>'. $v .'</option>';
				}
		$editForm .= '
				</select>
			</div>
			<div class="form-group">
				<label for="subscriber_active">'. letheglobal_active .'</label>
				<input type="checkbox" class="ionc" id="subscriber_active" name="subscriber_active" value="YES"'. formSelector($sr->Get('subscriber_active'),1,1) .'>
			</div>
			<div class="form-group">
				<button type="submit" name="editSubscriber" id="editSubscriber" class="btn btn-success">'. letheglobal_save .'</button>
			</div>
		
		</form>
		
		<script>
		$(".ionc").ionCheckRadio();
				/* Call Updater */
				$("#subUpdater").on("submit",function(e){
					
					e.preventDefault();
					$("#updateResult").html("<span class=\"spin glyphicon glyphicon-refresh\"></span>");
					$.ajax({
						url : "modules/lethe.subscribers/act.xmlhttp.php?pos=sbredit&ID='. $ID .'",
						type: "POST",
						data : $("#subUpdater").serialize(),
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#updateResult").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							$("#updateResult").html("'. subscribers_e_mail_could_not_be_sent .'");
						}
					});
					$("#updateResult").focus();
					
				});
		</script>
		';
		
		echo($editForm);
		
	}
	
	$opSub->close();

}

/* Bulk Actions */
if($pos=='bulkactions'){
	
	if(!isDemo('RunAction')){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if(isset($_POST['RunAction'])){
		$errText = '';
		if(!isset($_POST['groups'])){$errText.='* '. subscribers_please_choose_a_group .'<br>';}
		if(!isset($_POST['actions']) || empty($_POST['actions'])){$errText.='* '. subscribers_please_choose_a_action .'<br>';}
		
		if($errText==''){
			$acts = trim($_POST['actions']);
			$grps = array();
			foreach($_POST['groups'] as $k=>$v){
				$grps[] = " (GID=".$v.") ";
			}
			$grps = implode(' OR ',$grps);
			$grps = " OID=". set_org_id ." AND (" . $grps.")";
			
			/* Actions */
			# Mark as Active
			if($acts=='toactive'){
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_active=1 WHERE ". $grps ."");
				die(errMod(''. letheglobal_updated .': <strong>'. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE ". $grps ."") .'</strong> '. letheglobal_record .'','success'));
			}
			# Mark as Inactive
			else if($acts=='toinactive'){
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_active=0 WHERE ". $grps ."");
				die(errMod(''. letheglobal_updated .': <strong>'. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE ". $grps ."") .'</strong> '. letheglobal_record .'','success'));
			}
			# Mark as Unverified
			else if($acts=='tounverified'){
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_verify=0 WHERE ". $grps ."");
				die(errMod(''. letheglobal_updated .': <strong>'. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE ". $grps ."") .'</strong> '. letheglobal_record .'','success'));
			}
			# Mark as Single Verified
			else if($acts=='tosingleverified'){
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_verify=1 WHERE ". $grps ."");
				die(errMod(''. letheglobal_updated .': <strong>'. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE ". $grps ."") .'</strong> '. letheglobal_record .'','success'));
			}
			# Mark as Double Verified
			else if($acts=='todoubleverified'){
				$myconn->query("UPDATE ". db_table_pref ."subscribers SET subscriber_verify=2 WHERE ". $grps ."");
				die(errMod(''. letheglobal_updated .': <strong>'. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE ". $grps ."") .'</strong> '. letheglobal_record .'','success'));
			}
			
		}else{
			die(errMod($errText,'danger'));
		}
		die();
	}
	
	/* Load Groups for All Sections */
	$listGrps = array();
	$opGroups = $myconn->query("SELECT 
										SG.*,
										(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE GID=SG.ID) AS sbr_cnt
								  FROM 
										". db_table_pref ."subscriber_groups AS SG
								 WHERE 
										OID=". set_org_id ." 

										". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
							  ORDER BY
										group_name
								   ASC
								") or die(mysqli_error($myconn));
		while($opGroupsRs = $opGroups->fetch_assoc()){
			$listGrps[] = $opGroupsRs;
		} $opGroups->free();
	
	$bulks = '
	<h4 class="text-primary">'. subscribers_bulk_actions .'</h4><hr>
	<div id="updateResult"></div>
	<form name="actBulk" id="actBulk" action="" method="POST">
	<input type="hidden" name="RunAction" value="YES">
		<div class="form-group">
			<label for="groups">'. subscribers_groups .'</label>
			<select name="groups[]" id="groups" class="form-control autoWidth" multiple>';
			foreach($listGrps as $k=>$v){
				$bulks.='<option value="'. $v['ID'] .'">'. showIn($v['group_name'],'page') .'</option>';
			}
	$bulks.='
			</select>
		</div>
		<div class="form-group">
			<label for="actions">'. subscribers_action .'</label>
			<select name="actions" id="actions" class="form-control autoWidth">
				<option value="">'. letheglobal_choose .'</option>
				<option value="toactive">'. subscribers_mark_as_active .'</option>
				<option value="toinactive">'. subscribers_mark_as_inactive .'</option>
				<option value="tounverified">'. subscribers_mark_as_unverified .'</option>
				<option value="tosingleverified">'. subscribers_mark_as_single_verified .'</option>
				<option value="todoubleverified">'. subscribers_mark_as_double_verified .'</option>
			</select>
		</div>
		<div class="form-group">
			<button class="btn btn-success" name="applyAction" id="applyAction">'. letheglobal_apply .'</button>
		</div>
	</form>
	<script>
				/* Call Updater */
				$("#actBulk").on("submit",function(e){
					
					e.preventDefault();
					$("#updateResult").html("<span class=\"spin glyphicon glyphicon-refresh\"></span>");
					$.ajax({
						url : "modules/lethe.subscribers/act.xmlhttp.php?pos=bulkactions",
						type: "POST",
						data : $("#actBulk").serialize(),
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#updateResult").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							$("#updateResult").html("'. letheglobal_error_occured .'!");
						}
					});
					$("#updateResult").focus();
					
				});
	</script>
	';
	
	echo($bulks);
}
?>


<?php 
$myconn->close();
ob_end_flush();
?>