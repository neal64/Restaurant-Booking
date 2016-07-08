<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 13.01.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
$errText = '';
if(!isset($pgnt) || !$pgnt){die();}

/* Demo Check */
if(!isDemo('editGroups,mergeGroups')){$errText = errMod(letheglobal_demo_mode_active,'danger');}
$sourceLimit = calcSource(set_org_id,'subscriber.groups');

/* Navigation */
$pg_nav_buts = '';

/* Edit Groups */
if(isset($_POST['editGroups'])){

	$succText = '';

	/* Add New */
	if(limitBlock($sourceLimit,set_org_max_subscriber_group)){
		if(isset($_POST['new_group']) && !empty($_POST['new_group'])){
			$addGrp = $myconn->prepare("INSERT INTO ". db_table_pref ."subscriber_groups SET OID=". set_org_id .",UID=". LETHE_AUTH_ID .",group_name=?,isUnsubscribe=0") or die(mysqli_error($myconn));
			$addGrp->bind_param('s',$_POST['new_group']);
			$addGrp->execute();
			$addGrp->close();
			$succText.='* '. subscribers_new_group_added_successfully .'<br>';
		}
	}
	
	/* Update */
	if(isset($_POST['group_datas'])){
	
		@set_time_limit(0);
		$dgrp = $myconn->prepare("DELETE FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND isUnsubscribe=0 AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
		$ugrp = $myconn->prepare("UPDATE ". db_table_pref ."subscriber_groups SET group_name=? WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
	
		$callLethe = new lethe();
		$callLethe->OID = set_org_id;
	
		foreach($_POST['group_datas'] as $k=>$v){
			/* Delete */
			if(isset($_POST['del_'.$v]) && $_POST['del_'.$v]=='YES'){
				/* Check System Groups */
				if(cntData("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=". intval($v) ." AND (isUnsubscribe=1 OR isUngroup=1)")==0){
					$dgrp->bind_param('i',$v);
					$dgrp->execute();
					
					# Remove Subscribers
					$opSubs = $myconn->query("SELECT * FROM ". db_table_pref ."subscribers WHERE GID=". intval($v) ."") or die(mysqli_error($myconn));
					while($opSubsRs = $opSubs->fetch_assoc()){
						$callLethe->removeSubscription($opSubsRs['subscriber_mail']);
					} $opSubs->free();
					
					# Remove Campaign Groups
					$myconn->query("DELETE FROM ". db_table_pref ."campaign_groups WHERE GID=". intval($v) ."") or die(mysqli_error($myconn));
					
					# Remove Group
					$myconn->query("DELETE FROM ". db_table_pref ."subscriber_groups WHERE ID=". intval($v) ."") or die(mysqli_error($myconn));
					
					# Remove Forms
					$opForms = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_forms WHERE form_group=". intval($v) ." AND isSystem=0") or die(mysqli_error($myconn));
					while($opFormsRs = $opForms->fetch_assoc()){
						$myconn->query("DELETE FROM ". db_table_pref ."subscribe_form_fields WHERE FID=". $opFormsRs['ID'] ."") or die(mysqli_error($myconn));
					} $opForms->free();
					$myconn->query("DELETE FROM ". db_table_pref ."subscribe_forms WHERE form_group=". intval($v) ." AND isSystem=0") or die(mysqli_error($myconn));
					
					
				}else{
					$succText.='* <strong>'. letheglobal_error .':</strong> '. subscribers_system_groups_could_not_be_deleted .'<br>';
				}
			}else{
			
			/* Update */
				$updVal = $_POST['grp_val_'.$v];
				if(strlen($updVal)<2){
					$succText.='* <strong>'. letheglobal_error .':</strong> '. subscribers_group_name_must_be_greater_than_2_character .'<br>';
				}else{
					$ugrp->bind_param('si',$updVal,$v);
					$ugrp->execute(); 
				}
			
			}
		}
		
		$succText.='* '. letheglobal_updated_successfully .'<br>';
		$errText = errMod($succText,'success');
		
		$ugrp->close();
		$dgrp->close();
	}
	

}

/* Merge */
if(isset($_POST['mergeGroups'])){

	if(!isset($_POST['merge_src']) || !is_array($_POST['merge_src'])){$errText = '* '. subscribers_please_choose_source_groups .'<br>';}
	if(!isset($_POST['merge_dest']) || !is_numeric($_POST['merge_dest'])){$errText = '* '. subscribers_please_choose_destination_group .'<br>';}
	

	if($errText==''){
		/* Create Query */
		$mergList = array();
		$remList = array();
		$destGrp = intval($_POST['merge_dest']);
		foreach($_POST['merge_src'] as $k=>$v){
			$mergList[] = 'GID='.$v;
			if($_POST['merge_dest']!=$v){
				$remList[] = 'ID='.$v;
			}
		}
		
		$mergList = implode(' OR ',$mergList);

		/* Merge Now */
		$myconn->query("UPDATE ". db_table_pref ."subscribers SET GID=". intval($destGrp) ." WHERE OID=". set_org_id ." AND (". $mergList .")") or die(mysqli_error($myconn));
		$succText = subscribers_groups_merged_successfully;
		
		/* Remove Older Groups */
		if(isset($_POST['remSrc']) && $_POST['remSrc']=='YES'){
			$remList = implode(' OR ',$remList);
			if($remList!=''){
				$myconn->query("DELETE FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND isUnsubscribe=0 AND isUngroup=0 AND (". $remList .")") or die(mysqli_error($myconn));
				$succText.='<br>'.subscribers_source_groups_removed;
			}
		}
		
		$errText = errMod($succText,'success');
	}else{
		$errText = errMod($errText,'danger');
	}

}
?>

<?php 
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. subscribers_groups .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>

	<div class="form-group">
		<?php 
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_subscriber_group) .'</div></div>
			   </div>');
		?>
	</div>
	
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-info">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          <?php echo(subscribers_merge_groups);?>
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
			<form method="POST" action=""> 
			<?php 
			/* Get Group Cache */
			$mrGrp = array();
			$opGrp = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND isUnsubscribe=0 AND isUngroup=0 ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ." ORDER BY group_name ASC") or die(mysqli_error($myconn));
			$grpCnt = mysqli_num_rows($opGrp);
			while($opGrpRs = $opGrp->fetch_assoc()){
				$mrGrp[$opGrpRs['ID']] = $opGrpRs['group_name'];
			} $opGrp->free();
			if($grpCnt>1){
			?>
			
				<div class="form-group">
					<label for="merge_dest"><?php echo(sh('Pw35uUlc5T').subscribers_destination);?></label>
					<select class="form-control autoWidth" name="merge_dest" id="merge_dest">
						<?php foreach($mrGrp as $k=>$v){echo('<option value="'. $k .'">'. showIn($v,'page') .' ('. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND GID=". intval($k) ."") .')</option>');}?>
					</select>
				</div>
				<div class="form-group">
					<label for="merge_dest"><?php echo(sh('2FltmsgExe').subscribers_sources);?></label>
					<select class="form-control autoWidth" name="merge_src[]" id="merge_src" multiple>
						<?php foreach($mrGrp as $k=>$v){echo('<option value="'. $k .'">'. showIn($v,'page') .' ('. cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND GID=". intval($k) ."") .')</option>');}?>
					</select>
				</div>
				<div class="form-group">
					<span><?php echo(sh('eZUhfGarpp'));?></span><label for="remSrc"><?php echo(subscribers_remove_sources_after_merging);?></label>
					<input type="checkbox" class="ionc" id="remSrc" name="remSrc" value="YES">
				</div>
				<div class="form-group">
					<button type="submit" name="mergeGroups" id="mergeGroups" class="btn btn-primary"><span class="glyphicon glyphicon-link"></span> <?php echo(subscribers_merge);?></button>
				</div>
			<?php } else{echo(errMod(subscribers_two_or_more_groups_required,'danger'));}?>

			</form>
      </div>
    </div>
  </div>
  
<form method="POST" action="">
<?php if(limitBlock($sourceLimit,set_org_max_subscriber_group)){?>
  <div class="panel panel-warning">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <?php echo(subscribers_add_new_group);?>
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
			<div class="row">
				<div class="col-md-12"><div class="form-group"><input type="text" value="" class="form-control" name="new_group" id="new_group" placeholder="<?php echo(subscribers_group_name);?>"></div></div>
			</div>
			<div class="form-group">
				<hr>
				<button name="editGroups" class="btn btn-primary" type="submit"><?php echo(letheglobal_save);?></button>
			</div>
      </div>
    </div>
  </div>
<?php }?>
  <div class="panel panel-success">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <?php echo(subscribers_groups);?> (<span class="total-cntr"></span>)
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">        

			<?php 
			$grpCntr = 0;
			$opGroup = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ." ORDER BY group_name ASC") or die(mysqli_error($myconn));
			while($opGroupRs = $opGroup->fetch_assoc()){
			$grpCount = cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE GID=". $opGroupRs['ID'] ."");
			$grpCntr = (int)($grpCntr + $grpCount);
			?>
			<div class="row">
				<div class="col-md-1"><div title="<?php echo(letheglobal_delete);?>" class="form-group tooltips"><label for="del_<?php echo($opGroupRs['ID']);?>"><span class="visible-xs"><?php echo(letheglobal_delete);?></span></label><input type="checkbox" name="del_<?php echo($opGroupRs['ID']);?>" id="del_<?php echo($opGroupRs['ID']);?>" value="YES" class="ionc"<?php echo(($opGroupRs['isUnsubscribe'] || $opGroupRs['isUngroup']) ? ' disabled':'');?>></div></div>
				<div class="col-md-1"><span class="label label-info"><?php echo($grpCount);?></span></div>
				<div class="col-md-10"><div class="form-group"><input type="text" value="<?php echo(showIn($opGroupRs['group_name'],'input'));?>" class="form-control input-sm" name="grp_val_<?php echo($opGroupRs['ID']);?>" id="grp_val_<?php echo($opGroupRs['ID']);?>"></div></div>
				<input type="hidden" name="group_datas[]" value="<?php echo($opGroupRs['ID']);?>">
				<hr class="visible-xs">
			</div>
			<?php } $opGroup->free();?>
			<div class="form-group">
				<hr>
				<button name="editGroups" class="btn btn-primary" type="submit"><?php echo(letheglobal_save);?></button>
			</div>
			<script src="Scripts/jquery.countTo.js"></script>
			<script>
				$(document).ready(function(){
					$('.total-cntr').countTo({
						from:0,
						to:<?php echo($grpCntr);?>,
						speed: 1000
					});
				});
			</script>
      </div>
    </div>
  </div>
</form>
</div>