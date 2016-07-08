<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 18.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
$errText = '';
if(!isset($pgnt) || !$pgnt){die();}

/* Demo Check */
if(!isDemo('addOrganization,editOrganization')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

if(!isset($_GET['ID']) || empty($_GET['ID'])){$ID='';}else{$ID=$_GET['ID'];}
if(LETHE_AUTH_MODE!=2){$ID=LETHE_AUTH_ORG_PRIVATE;}
if($page_sub2=='add'){
	if(!PRO_MODE){header('Location: ?p=organizations/organization/edit');}
}

/* Add */
if(isset($_POST['addOrganization'])){
	$lethe = new lethe();
	$lethe->addOrganization();
	$errText = $lethe->errPrint;
}

/* Edit */
if(isset($_POST['editOrganization'])){
	$lethe = new lethe();
	$lethe->OID = ((LETHE_AUTH_MODE!=2) ? set_org_id:$ID);
	$lethe->editOrganization();
	$errText = $lethe->errPrint;
}

$pg_nav_buts = '<div class="nav-buts">
				<a href="?p=organizations/organization" class="btn btn-primary">'. letheglobal_list .'</a>
				</div>
				';
?>

<?php if($page_sub2==''){ #List
/* Super Admin Check */
if(LETHE_AUTH_MODE!=2){header('Location: ?p=organizations/organization/edit');}

		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. $pg_title .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- Organization List Start -->
		<table class="footable table">
			<thead>
				<tr>
					<th><?php echo(organizations_organization);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_created);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_tag);?></th>
					<th><?php echo(letheglobal_active);?></th>
					<th data-hide="phone"><?php echo(letheglobal_primary);?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$opOrgz = $myconn->query("SELECT * FROM ". db_table_pref ."organizations ORDER BY isPrimary DESC,orgName ASC") or die(mysqli_error($myconn));
			if(mysqli_num_rows($opOrgz)==0){echo('<tr><td colspan="6">'. errMod(letheglobal_record_not_found.'!','danger') .'</td></tr>');}
			while($opOrgzRs = $opOrgz->fetch_assoc()){
			?>
				<tr>
					<td><a href="?p=organizations/organization/edit&amp;ID=<?php echo($opOrgzRs['ID']);?>"><?php echo(showIn($opOrgzRs['orgName'],'page'));?></a></th>
					<td><?php echo(setMyDate($opOrgzRs['addDate'],2));?></td>
					<td><?php echo(showIn($opOrgzRs['orgTag'],'page'));?></td>
					<td data-value="<?php echo($opOrgzRs['isActive']);?>"><?php echo(getBullets((int)$opOrgzRs['isActive']));?></td>
					<td data-value="<?php echo($opOrgzRs['isPrimary']);?>"><?php echo(getBullets((int)$opOrgzRs['isPrimary']));?></td>
				</tr>
			<?php } $opOrgz->free();?>
			</tbody>
		</table>
			
		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
			});
		</script>
<!-- Organization List End -->
<?php }else if($page_sub2=='add'){ #Add
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. letheglobal_add .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- Organization Add Start -->
<!-- ONLY PRO VERSION -->
<!-- Organization Add End -->
<?php }else if($page_sub2=='edit'){ #Edit?>
<!-- Organization Edit Start -->
<?php 
if(!PRO_MODE){
	$ID = set_org_id;
}else{
	$ID = ((LETHE_AUTH_MODE!=2) ? set_org_id:intval($ID));
}
$opOrg = $myconn->prepare("SELECT * FROM ". db_table_pref ."organizations WHERE ID=?") or die(mysqli_error($myconn));
			$opOrg->bind_param('s',$ID);
			$opOrg->execute();
			$opOrg->store_result();
			if($opOrg->num_rows==0){
				echo errMod('* '. letheglobal_record_not_found .'','danger');
			}else{
				$sr = new Statement_Result($opOrg);
				$opOrg->fetch();
				
		echo('<h1>'. showIn($sr->Get('orgName')) .'<span class="help-block"><span class="text-primary">'. letheglobal_edit .'</span></span></h1><hr>'.
			  $errText
			 );
?>
<?php //if(!isset($lethe->isSuccess) || $lethe->isSuccess==0){?>
<form action="" method="POST">
	<div role="tabpanel">

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(organizations_general);?></a></li>
		<li role="presentation" style="display:none;"><a href="#limits" aria-controls="limits" role="tab" data-toggle="tab"><?php echo(organizations_limits);?></a></li>
		<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><?php echo(organizations_settings);?></a></li>
		<li role="presentation"><a href="#save" aria-controls="save" role="tab" data-toggle="tab"><?php echo(letheglobal_save);?></a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<!-- GENERAL -->
		<div role="tabpanel" class="tab-pane fade in active" id="general">
			&nbsp;
			<div class="form-group">
				<label for="org_name"><?php echo(sh('G4e9iXSAzy').organizations_organization_name);?></label>
				<input type="text" class="form-control autoWidth" id="org_name" name="org_name" size="40" value="<?php echo(showIn($sr->Get('orgName'),'input'));?>">
			</div>
			<div class="form-group">
				<label for="org_private_key"><?php echo(sh('BPVgomvMpO').organizations_private_key);?></label>
				<input onclick="this.select();" type="text" class="form-control autoWidth" id="org_private_key" name="org_private_key" size="40" value="<?php echo(showIn($sr->Get('private_key'),'input'));?>" readonly>
			</div>
			<div class="form-group">
				<label for="org_public_key"><?php echo(sh('G4Pr97e8yp').organizations_public_key);?></label>
				<input onclick="this.select();" type="text" class="form-control autoWidth" id="org_public_key" name="org_public_key" size="40" value="<?php echo(showIn($sr->Get('public_key'),'input'));?>" readonly>
			</div>
			<div class="form-group">
				<label for="org_api_key"><?php echo(sh('5bBgbbOgEa').organizations_api_key);?></label>
				<input onclick="this.select();" type="text" class="form-control autoWidth" id="org_api_key" name="org_api_key" size="40" value="<?php echo(showIn($sr->Get('api_key'),'input'));?>" readonly>
			</div>
			<div class="form-group">
				<label for="org_rss_url"><?php echo(sh('bByrWnKg9L'));?>RSS</label>
				<input onclick="this.select();" type="text" class="form-control autoWidth" id="org_rss_url" name="org_rss_url" size="40" value="<?php echo(showIn($sr->Get('rss_url'),'input'));?>">
				<span class="help-block txxs"><strong>Default:</strong> <?php echo(lethe_root_url.'lethe.newsletter.php?pos=rss&amp;oid='.$sr->Get('public_key'));?></span>
			</div>
		
		</div>
		<!-- LIMITS -->
		<div role="tabpanel" class="tab-pane fade" id="limits">
			&nbsp;
			<div class="form-group">
				<label for="org_max_disk_quota"><?php echo(sh('sOMxavMns9').organizations_maximum_disk_quota);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<select id="org_max_disk_quota" name="org_max_disk_quota" class="form-control autoWidth">
					<?php 
					foreach($LETHE_ORG_DISK_QUOTA_LIST as $k=>$v){
						echo('<option value="'. $v .'"'. formSelector($v,set_org_max_disk_quota,0) .'>'. (($v==0) ? letheglobal_unlimited:formatBytes($v,0,0)) .'</option>');
					}
					?>
				</select>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(0,0) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_user"><?php echo(sh('Ui5lTJHQkK').organizations_maximum_user);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_user" name="org_max_user" value="<?php echo((defined('set_org_max_user')) ? showIn(set_org_max_user,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'users'),set_org_max_user) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_newsletter"><?php echo(sh('71oZLhC3cV').organizations_maximum_newsletter);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_newsletter" name="org_max_newsletter" value="<?php echo((defined('set_org_max_newsletter')) ? showIn(set_org_max_newsletter,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'newsletters'),set_org_max_newsletter) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_autoresponder"><?php echo(sh('jLXNE56gUg').organizations_maximum_autoresponder);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_autoresponder" name="org_max_autoresponder" value="<?php echo((defined('set_org_max_autoresponder')) ? showIn(set_org_max_autoresponder,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'autoresponder'),set_org_max_autoresponder) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_subscriber"><?php echo(sh('LfGB6T1JMr').organizations_maximum_subscriber);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_subscriber" name="org_max_subscriber" value="<?php echo((defined('set_org_max_subscriber')) ? showIn(set_org_max_subscriber,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'subscribers'),set_org_max_subscriber) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_subscriber_group"><?php echo(sh('xyyyyqvwF2').organizations_maximum_subscriber_group);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_subscriber_group" name="org_max_subscriber_group" value="<?php echo((defined('set_org_max_subscriber_group')) ? showIn(set_org_max_subscriber_group,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'subscriber.groups'),set_org_max_subscriber_group) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_subscribe_form"><?php echo(sh('aIhmrEqZ7D').organizations_maximum_subscribe_form);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_subscribe_form" name="org_max_subscribe_form" value="<?php echo((defined('set_org_max_subscribe_form')) ? showIn(set_org_max_subscribe_form,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'subscriber.forms'),set_org_max_subscribe_form) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_blacklist"><?php echo(sh('CcQajclBzO').organizations_maximum_blacklist);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_blacklist" name="org_max_blacklist" value="<?php echo((defined('set_org_max_blacklist')) ? showIn(set_org_max_blacklist,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'subscriber.blacklist'),set_org_max_blacklist) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_template"><?php echo(sh('ow9Oc0forZ').organizations_maximum_template);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_template" name="org_max_template" value="<?php echo((defined('set_org_max_template')) ? showIn(set_org_max_template,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'templates'),set_org_max_template) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_shortcode"><?php echo(sh('RFsCUOaRjk').organizations_maximum_short_code);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_shortcode" name="org_max_shortcode" value="<?php echo((defined('set_org_max_shortcode')) ? showIn(set_org_max_shortcode,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits(calcSource(set_org_id,'shortcode'),set_org_max_shortcode) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_max_daily_limit"><?php echo(sh('3Zb0MmV4bv').organizations_daily_send_limit);?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_max_daily_limit" name="org_max_daily_limit" value="<?php echo((defined('set_org_max_daily_limit')) ? showIn(set_org_max_daily_limit,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3">'. getMyLimits($sr->Get('daily_sent'),set_org_max_daily_limit) .'</div></div>');
				}?>
			</div>
			<div class="form-group">
				<label for="org_standby_organization"><?php echo(sh('ftJoFAPhU6').organizations_standby_between_organizations.' ('. letheglobal_minute .')');?></label>
				<?php if(LETHE_AUTH_MODE==2 && PRO_MODE){?>
				<input type="number" onkeydown="validateNumber(event);" class="form-control autoWidth" id="org_standby_organization" name="org_standby_organization" value="<?php echo((defined('set_org_standby_organization')) ? showIn(set_org_standby_organization,'input'):'');?>" size="5">
				<span class="help-block">0 = <?php echo(letheglobal_unlimited);?></span>
				<?php }else{
					echo('<div class="row"><div class="col-md-3"><span class="label label-warning">'. set_org_standby_organization.' '. letheglobal_minute .'</span></div></div>');
				}?>
			</div>
		</div>
		<!-- SETTINGS -->
		<div role="tabpanel" class="tab-pane fade" id="settings">
			&nbsp;
			<div class="form-group">
				<label for="org_submission_account"><?php echo(sh('ID2c7YKKMH').organizations_submission_account);?></label>
				<?php if(LETHE_AUTH_MODE==2){?>
				<select name="org_submission_account[]" id="org_submission_account" class="form-control autoWidth" multiple>
					<?php 
					$currAccs = set_org_submission_account;
					$currAccs = explode(",",$currAccs);
					$opSubAcc = $myconn->query("SELECT ID,isActive,acc_title FROM ". db_table_pref ."submission_accounts WHERE isActive=1 ORDER BY acc_title ASC") or die(mysqli_error($myconn));
					while($opSubAccRs = $opSubAcc->fetch_assoc()){
						echo('<option value="'. $opSubAccRs['ID'] .'"'. ((defined('set_org_submission_account')) ? ((in_array($opSubAccRs['ID'],$currAccs)) ? ' selected':''):'') .'>'. showIn($opSubAccRs['acc_title'],'input') .'</option>');
					} $opSubAcc->free();
					?>
				</select>
				<?php }else{
					echo('<div class="row"><div class="col-md-3"><span class="label label-warning">'. set_org_submission_account .'</span></div></div>');
				}?>
			</div>
			
			<div class="form-group">
				<label for="org_sender_title"><?php echo(sh('uWlPzwExES').organizations_sender_title);?></label>
				<input type="text" class="form-control autoWidth" id="org_sender_title" name="org_sender_title" value="<?php echo((defined('set_org_sender_title')) ? showIn(set_org_sender_title,'input'):'');?>">
			</div>
			
			<div class="form-group">
				<label for="org_reply_mail"><?php echo(sh('zIo5YkkltJ').organizations_reply_e_mail);?></label>
				<input type="email" class="form-control autoWidth" id="org_reply_mail" name="org_reply_mail" value="<?php echo((defined('set_org_reply_mail')) ? showIn(set_org_reply_mail,'input'):'');?>">
			</div>
			
			<div class="form-group">
				<label for="org_test_mail"><?php echo(sh('bcWtR8fOlU').organizations_test_e_mail);?></label>
				<input type="email" class="form-control autoWidth" id="org_test_mail" name="org_test_mail" value="<?php echo((defined('set_org_test_mail')) ? showIn(set_org_test_mail,'input'):'');?>">
			</div>
			
			<div class="form-group">
				<label for="org_timezone"><?php echo(sh('WqUDsK9a6d').organizations_timezone);?></label>
				<select name="org_timezone" id="org_timezone" class="form-control autoWidth">
					<?php 
					$tzones = timezone_list();
					foreach($tzones as $k=>$v){echo('<option value="'. $k .'"'. ((defined('set_org_timezone')) ? formSelector(set_org_timezone,$k,0):'') .'>'. $v .'</option>');}?>
				</select>
			</div>
			
			<div class="form-group">
				<label for="org_after_unsubscribe"><?php echo(sh('9AD1ki4Cyo').organizations_after_unsubscribe);?></label>
				<select name="org_after_unsubscribe" id="org_after_unsubscribe" class="form-control autoWidth">
					<?php 
					foreach($LETHE_AFTER_UNSUBSCRIBE as $k=>$v){
						echo('<option value="'. $k .'"'. ((defined('set_org_after_unsubscribe')) ? formSelector(set_org_after_unsubscribe,$k,0):'') .'>'. $v .'</option>');
					}
					?>
				</select>
			</div>
			
			<div class="form-group">
				<label for="org_verification"><?php echo(sh('lTvpd5ypqz').organizations_verification);?></label>
				<select name="org_verification" id="org_verification" class="form-control autoWidth">
					<?php 
					foreach($LETHE_VERIFICATION_TYPE as $k=>$v){
						echo('<option value="'. $k .'"'. ((defined('set_org_verification')) ? formSelector(set_org_verification,$k,0):'') .'>'. $v .'</option>');
					}
					?>
				</select>
			</div>
			
			<div class="form-group">
				<label for="org_random_load"><?php echo(sh('NnedVTtSjA').organizations_random_loader);?></label>
				<div>
				<input type="checkbox" name="org_random_load" id="org_random_load" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((defined('set_org_random_load') && set_org_random_load==1) ? ' checked':''));?>>
				</div>
			</div>
			
			<div class="form-group">
				<label for="org_load_type"><?php echo(sh('07NRNro5bL').organizations_load);?></label>
				<select name="org_load_type" id="org_load_type" class="form-control autoWidth">
					<?php 
					foreach($LETHE_LOAD_TYPES as $k=>$v){
						echo('<option value="'. $k .'"'. ((defined('set_org_load_type')) ? formSelector(set_org_load_type,$k,0):'') .'>'. $v .'</option>');
					}
					?>
				</select>
			</div>
		
		</div>
		<!-- SAVE -->
		<div role="tabpanel" class="tab-pane fade" id="save">
			&nbsp;
			<?php if(!$sr->Get('isPrimary')){?>
			<div class="form-group">
				<span><?php echo(sh('za7rBvQrZy'));?></span><label for="del"><?php echo(letheglobal_delete);?></label>
				<input type="checkbox" name="del" id="del" value="YES" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>" class="ionc">
			</div>
			<?php }?>
			<div class="form-group">
				<button name="editOrganization" id="editOrganization" class="btn btn-primary" type="submit"><?php echo(letheglobal_save);?></button>
			</div>
		</div>
	  </div>

	</div>
</form>
<?php //} # Update Success?>
<?php } # Org End?>
<!-- Organization Edit End -->
<?php } #Page Subs End?>