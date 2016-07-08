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
if(!isDemo('editSubList')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

/* Navigation */
$pg_nav_buts = '';

/* Actions */
if(isset($_POST['editSubList'])){

	if(isset($_POST['sbr'])){
	
		if(isset($_POST['selOpt']) && $_POST['selOpt']!='action'){
		
			$movGrp = ((!isset($_POST['selOptGrp']) || !is_numeric($_POST['selOptGrp'])) ? 0:intval($_POST['selOptGrp']));
			$movStatus = false;
			$movePrep = $myconn->prepare("UPDATE ". db_table_pref ."subscribers SET GID=? WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
			$moveGrpOwnerPrep = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
			$moveGrpOwnerPrep->bind_param('i',$movGrp);
			$moveGrpOwnerPrep->execute();
			$moveGrpOwnerPrep->store_result();
			if($moveGrpOwnerPrep->num_rows>0){$movStatus = true;}
			$moveGrpOwnerPrep->close();
			
			$opRemCls = new lethe();
			$opRemCls->OID = set_org_id;
		
			foreach($_POST['sbr'] as $k=>$v){
				/* Delete */
				if($_POST['selOpt']=='delete'){
				
					$selSubMail = getSubscriber($v,0);
					$opRemCls->removeSubscription($selSubMail);
				
				}
				/* Move */
				else if($_POST['selOpt']=='move'){
					
					if($movStatus){
						$movePrep->bind_param('ii',$movGrp,$v);
						$movePrep->execute();
					}
				
				}
			}
			
			$movePrep->close();
		}	
	}

}
?>


<?php if($page_sub2=='list' || $page_sub2==''){
		echo('<h1>'. $pg_title .' 
		<span class="pull-right">
		<a href="javascript:;" class="toggler btn btn-warning" data-target=".search-panel" role="button"><span class="glyphicon glyphicon-search"></span></a>
		<span class="btn btn-default">'. letheglobal_record .': <span class="sbrCnt">0</span></span>
		</span>
		<span class="help-block text-primary">'. subscribers_subscribers .'</span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
			 
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
	
/* Search Datas */
$src_on = ((isset($_GET['src_on']) && $_GET['src_on']=='YES') ? 'YES':'NO');
$src_c = ((isset($_GET['src_c']) && !empty($_GET['src_c'])) ? trim($_GET['src_c']):'');
if($src_on=='YES'){
	$src_v = ((isset($_GET['src_v']) && !empty($_GET['src_v'])) ? trim($_GET['src_v']):'');
	$src_grp = ((isset($_GET['src_grp']) && is_numeric($_GET['src_grp'])) ? intval($_GET['src_grp']):0);
	$src_act = ((isset($_GET['src_act']) && is_numeric($_GET['src_act'])) ? intval($_GET['src_act']):999);
	$src_vrf = ((isset($_GET['src_vrf']) && is_numeric($_GET['src_vrf'])) ? intval($_GET['src_vrf']):999);
}else{
	$src_v = '';
	$src_grp = 0;
	$src_act = 999;
	$src_vrf = 999;
}

/* Search Queries */
$srcQry = array();

# Text
if($src_v!=''){
	$srcQry[] = "AND (UPPER(S.subscriber_name) LIKE UPPER('%". mysql_prep($src_v) ."%') OR UPPER(S.subscriber_mail) LIKE UPPER('%". mysql_prep($src_v) ."%'))";
}

# Group
if($src_grp!=0){
	$srcQry[] = "AND (S.GID=". $src_grp .")";
}

# Active
if($src_act!=999 || $src_act<2){
	$srcQry[] = "AND (S.subscriber_active=". $src_act .")";
}

# Verification
if($src_vrf!=999 || $src_vrf<3){
	$srcQry[] = "AND (S.subscriber_verify=". $src_vrf .")";
}

/* byCharacter */
if(!empty($src_c)){
	unset($srcQry);
	$srcQry = array();
	# Char
	if($src_c=='09'){
		$srcQry[] = "AND (UPPER(LEFT(S.subscriber_name,1)) REGEXP '^[0-9]')";
	}else if($src_c=='A2'){
		$srcQry[] = "AND (UPPER(LEFT(S.subscriber_name,1)) NOT REGEXP '^[0-9A-Za-z]')";
	}else{
		$srcQry[] = "AND (UPPER(LEFT(S.subscriber_name,1)) = UPPER('". mysql_prep($src_c) ."'))";
	}
	
}

/* Render Queries */
$rndQry = implode(' ',$srcQry);

/* Page Limit */
if(!isset($_GET['limit']) || !is_numeric($_GET['limit'])){
	(isMob() ? $limit = 10 : $limit = 25);
}else{
	$limit = ((intval($_GET['limit'])>200) ? 25:intval($_GET['limit']));
}
?>
<!-- Subscriber List Start -->
<div id="searchBox" class="well search-panel<?php echo((($src_on=='YES') ? '':' sHide'));?>">
	<h4><?php echo(letheglobal_search);?></h4><hr>
	<form name="srcForm" id="srcForm" action="" method="GET">
		<input type="hidden" name="src_on" value="YES">
		<input type="hidden" name="limit" value="<?php echo($limit);?>">
		<input type="hidden" name="p" value="subscribers/subscriber/list">
		<div class="row form-group">
			<div class="col-md-2"><?php echo(letheglobal_search);?></div>
			<div class="col-md-3">
				<input type="text" name="src_v" id="src_v" placeholder="" value="<?php echo(showIn($src_v,'input'));?>" class="form-control input-sm">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2"><?php echo(subscribers_groups);?></div>
			<div class="col-md-3">
				<select name="src_grp" id="src_grp" class="form-control input-sm">
					<option value="0"><?php echo(letheglobal_all);?></option>
					<?php
					foreach($listGrps as $k=>$v){
						echo('<option value="'. $v['ID'] .'"'. formSelector($src_grp,$v['ID'],0) .'>'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
					}
					?>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2"><?php echo(subscribers_subscriber_status);?></div>
			<div class="col-md-3">
				<select name="src_act" id="src_act" class="form-control autoWidth input-sm">
					<option value="999"><?php echo(letheglobal_all);?></option>
					<option value="1"<?php echo(formSelector($src_act,1,0));?>><?php echo(letheglobal_active);?></option>
					<option value="0"<?php echo(formSelector($src_act,0,0));?>><?php echo(letheglobal_inactive);?></option>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2"><?php echo(letheglobal_verification);?></div>
			<div class="col-md-3">
				<select name="src_vrf" id="src_vrf" class="form-control autoWidth input-sm">
					<option value="999"><?php echo(letheglobal_all);?></option>
					<?php foreach($LETHE_VERIFICATION_TYPE as $k=>$v){
						echo('<option value="'. $k .'"'. formSelector($src_vrf,$k,0) .'>'. $v .'</option>');
					}?>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2"><?php echo(subscribers_by_first_character);?></div>
			<div class="col-md-10">
				<?php 
					$chrs='';
					for( $i = 65; $i < 91; $i++){
							$chrs .= '<a href="?p=subscribers/subscriber/list&amp;src_c='. chr($i) .'&amp;src_on=YES" class="btn btn-xs '. (($src_c==chr($i)) ? 'btn-info':'btn-default') .'">'. chr($i) .'</a> ';
					}
					$chrs .= '<a href="?p=subscribers/subscriber/list&amp;src_c=09&amp;src_on=YES" class="btn btn-xs '. (($src_c=='09') ? 'btn-info':'btn-default') .'">0-9</a> ';
					$chrs .= '<a href="?p=subscribers/subscriber/list&amp;src_c=A2&amp;src_on=YES" class="btn btn-xs '. (($src_c=='A2') ? 'btn-info':'btn-default') .'">#</a> ';
					echo($chrs);
				?>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary"><?php echo(letheglobal_search);?></button>
		</div>
	</form>
</div>
<!-- LIST START -->
<?php 
$opSubs = $myconn->query("SELECT S.ID FROM ". db_table_pref ."subscribers AS S WHERE S.OID=". set_org_id ." ". $rndQry ."") or die(mysqli_error($myconn));

((!isset($_GET["pgGo"]) || !is_numeric($_GET["pgGo"])) ? $pgGo = 1 : $pgGo = intval($_GET["pgGo"]));
$count		 = mysqli_num_rows($opSubs);
$total_page	 = ceil($count / $limit);
$dtStart	 = ($pgGo-1)*$limit;

if($count==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{

$opRecs = $myconn->query("SELECT 
										S.*,
										SG.ID AS SGID,
										SG.group_name
							  FROM 
										". db_table_pref ."subscribers AS S,
										". db_table_pref ."subscriber_groups AS SG
							 WHERE 
										S.OID=". set_org_id ."
							   AND
										(SG.ID=S.GID)
										
										". $rndQry ."
										
							 LIMIT 
										$dtStart,$limit
										
										") or die(mysqli_error($myconn));
?>
<div class="pull-right">
<select class="input-sm form-control autoWidth" onchange="javascript:location.href = this.value;">
<?php 
$pgVarList='?p='. $p;
$pgVarList.='&amp;src_on='.$src_on;
$pgVarList.='&amp;src_v='.urlencode($src_v);
$pgVarList.='&amp;src_grp='.urlencode($src_grp);
$pgVarList.='&amp;src_act='.urlencode($src_act);
$pgVarList.='&amp;src_vrf='.urlencode($src_vrf);
$pgVarList.='&amp;src_c='.urlencode($src_c);
for($i=25;$i<=200;$i+=25){
	echo('<option value="'. $pgVarList .'&amp;limit='.intval($i).'"'. formSelector($limit,$i,0) .'>'. $i .'</option>');
}?>
</select><br>
</div>
<link rel="stylesheet" href="css/prism.css">
		<form method="POST" name="sbrForm" id="sbrForm" action="" class="clearfix">        
		<table class="footable table">
			<thead>
				<tr>
					<th data-sort-ignore="true"><div class="inlineIonc text-center"><label for="checkAll"></label><input type="checkbox" name="checkAll" id="checkAll" class="ionc"></div></th>
					<th data-hide="phone"><?php echo(letheglobal_name);?></th>
					<th><?php echo(letheglobal_e_mail);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_groups);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_active);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_verify);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_created);?></th>
					<th data-sort-ignore="true"><?php echo(subscribers_action);?></th>
				</tr>
			</thead>
			<tbody>
			<?php while($opRecsRs = $opRecs->fetch_assoc()){?>
				<tr>
					<td><div class="inlineIonc text-center"><label for="sbr<?php echo($opRecsRs['ID']);?>"></label><input type="checkbox" name="sbr[]" id="sbr<?php echo($opRecsRs['ID']);?>" class="ionc checkRow" value="<?php echo($opRecsRs['ID']);?>"></div></td>
					<td><?php echo(showIn((($opRecsRs['subscriber_name']=='') ? '{NO NAME}':$opRecsRs['subscriber_name']),'page'));?></td>
					<td><?php echo(showIn($opRecsRs['subscriber_mail'],'page'));?></td>
					<td><?php echo(showIn($opRecsRs['group_name'],'page'));?></td>
					<td data-value="<?php echo($opRecsRs['subscriber_active']);?>"><?php echo(getBullets($opRecsRs['subscriber_active']));?></td>
					<td data-value="<?php echo($opRecsRs['subscriber_verify']);?>"><span class="tooltips" title="<?php echo($LETHE_VERIFICATION_TYPE[$opRecsRs['subscriber_verify']]);?>"><?php echo(getBullets($opRecsRs['subscriber_verify']));?></span></td>
					<td><?php echo(setMyDate($opRecsRs['add_date'],2));?></td>
					<td>
						<a href="javascript:;" data-sbr-action-w="500" data-sbr-action-h="400" data-sbr-action="sbrsendmail" data-sbr-id="<?php echo($opRecsRs['ID']);?>" class="sbr-acts text-warning tooltips" title="<?php echo(subscribers_send_e_mail);?>"><span class="glyphicon glyphicon-envelope"></span></a> 
						<a href="javascript:;" data-sbr-action-w="700" data-sbr-action-h="400" data-sbr-action="sbrfulldata" data-sbr-id="<?php echo($opRecsRs['ID']);?>" class="sbr-acts text-danger tooltips" title="<?php echo(subscribers_full_details);?>"><span class="glyphicon glyphicon-eye-open"></span></a> 
						<a href="javascript:;" data-sbr-action-w="600" data-sbr-action-h="500" data-sbr-action="sbrstats" data-sbr-id="<?php echo($opRecsRs['ID']);?>" class="sbr-acts text-success tooltips" title="<?php echo(subscribers_stats);?>"><span class="glyphicon glyphicon-stats"></span></a> 
						<a href="javascript:;" data-sbr-action-w="500" data-sbr-action-h="400" data-sbr-action="sbredit" data-sbr-id="<?php echo($opRecsRs['ID']);?>" class="sbr-acts text-primary tooltips" title="<?php echo(subscribers_edit_subscriber);?>"><span class="glyphicon glyphicon-cog"></span></a> 
					</td>
				</tr>
			<?php } $opRecs->free();?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="8">
					<?php 
						$pgVar='?p='. $p;
						$pgVar.='&amp;src_on='.$src_on;
						$pgVar.='&amp;src_v='.urlencode($src_v);
						$pgVar.='&amp;src_grp='.urlencode($src_grp);
						$pgVar.='&amp;src_act='.urlencode($src_act);
						$pgVar.='&amp;src_vrf='.urlencode($src_vrf);
						$pgVar.='&amp;src_c='.urlencode($src_c);
						$pgVar.='&amp;limit='.intval($limit);
						include_once("inc/inc_pagination.php");?>
				</td>
			</tr>
			</tfoot>
		</table>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
			});
		</script>
		
		<div class="well">
			<div class="row">
				<div class="col-md-2 form-group">
					<select name="selOpt" id="selOpt" class="form-control input-sm">
						<option value="choose"><?php echo(subscribers_action);?></option>
						<option value="delete"><?php echo(letheglobal_delete);?></option>
						<option value="move"><?php echo(subscribers_move_to);?></option>
					</select>
				</div>
				<div class="mov-grps col-md-2 sHide form-group">
					<select name="selOptGrp" id="selOptGrp" class="form-control input-sm">
						<?php
						foreach($listGrps as $k=>$v){
							echo('<option value="'. $v['ID'] .'">'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
						}
						?>
					</select>
				</div>
				<div class="col-md-2 form-group"><button type="submit" name="editSubList" id="editSubList" class="btn btn-primary btn-sm"><?php echo(subscribers_selected_records);?> <span class="glyphicon glyphicon-wrench"></span></button></div>
				<div class="col-md-2 form-group"><button type="button" id="bulkActions" class="btn btn-warning btn-sm">Bulk Actions <span class="glyphicon glyphicon-tasks"></span></button></div>
			</div>
		</div>
		</form>
<?php }
$opSubs->close();
?>
<script src="Scripts/jquery.countTo.js"></script>
<script src="Scripts/prism.js"></script>
<script>
$(document).ready(function(){
	$(".sbrCnt").countTo({
		from: 0,
		to: <?php echo($count);?>
	});
	
	/* Action Changes */
	$("#selOpt").change(function(){
		if($(this).val()=='move'){
			$(".mov-grps").show();
		}else{
			$(".mov-grps").hide();
		}
	});
	
	
	/* Action Control */
	$("#sbrForm").on('submit',function(){
		if(!$('.checkRow:checked').length) {
					alert('<?php echo(subscribers_please_select_subscriber);?>!');
					return false;
				}
	
		if($("#selOpt").val()=='delete'){
			return confirm('<?php echo(subscribers_are_you_sure_to_delete_selected_subscribers.'\n'.subscribers_all_subscriber_data_can_be_deleted);?>');
		}else if($("#selOpt").val()=='move'){
			return confirm('<?php echo(subscribers_are_you_sure_to_move_selected_subscribers_to_target_group);?>');
		}else{
			alert('<?php echo(subscribers_please_choose_a_action);?>!');
			return false;
		}
	});
	
	/* Subscriber Settings */
	$(".sbr-acts").click(function(){
	
		var subid = $(this).data('sbr-id');
		var subact = $(this).data('sbr-action');
		var subact_ww = $(this).data('sbr-action-w');
		var subact_wh = $(this).data('sbr-action-h');
		$.fancybox({
		
			type: "ajax",
			href: "modules/lethe.subscribers/act.xmlhttp.php?pos="+ subact +"&ID="+subid,
			width: subact_ww,
			height: subact_wh,
			autoSize: false
		
		});
	});
	/* Bulk Actions */
	$("#bulkActions").click(function(){

		$.fancybox({
		
			type: "ajax",
			href: "modules/lethe.subscribers/act.xmlhttp.php?pos=bulkactions",
			width: 500,
			height: 500,
			autoSize: false
		
		});
	});
});
</script>
<!-- LIST END -->

<!-- Subscriber List End -->
<?php }else if($page_sub2=='add'){
		echo('<h1>'. $pg_title .'<span class="help-block text-primary">'. subscribers_add_subscriber .'</span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- Subscriber Add Start -->
	<div class="form-group">
		<label for="exampleInputEmail1"><?php echo(subscribers_subscribe_forms)?></label>
		<select class="form-control autoWidth" name="formSelector" id="formSelector">
			<?php $tempList = $myconn->query("SELECT ID,form_name,isSystem,isDraft,form_type,form_id FROM ". db_table_pref ."subscribe_forms WHERE OID=". set_org_id ." AND isDraft=0 AND form_type=0 ORDER BY isSystem=1 ASC,form_name ASC") or die(mysqli_error($myconn));
			echo('<option value="0">'. letheglobal_choose .'</option>');
			while($tempListRs = $tempList->fetch_assoc()){
				echo('<option value="'. $tempListRs['ID'] .'">'. showIn($tempListRs['form_name'],'page') .'</option>');
			} $tempList->free();
			?>
		</select><hr>
		
		<div class="row">
			<div class="col-md-3">
				<div id="formArea"></div>
			</div>
		</div>
		
		<script>
			$("#formSelector").change(function(){
				if($(this).val()!=0){
				
					$("#formArea").html('<span class="spin glyphicon glyphicon-refresh"></span>');
				
					$.ajax({
						url: "modules/lethe.subscribers/act.xmlhttp.php?pos=generateCode&loadForm=true&ID=" + $(this).val(),
						type: "POST",
						success: function(data){
							$("#formArea").html(data);
						},
						error: function(){
							$("#formArea").html("<div class=\"alert alert-danger\"><?php echo(subscribers_there_is_error_while_submit);?></div>");
						}
					});
				}
				
			});
		</script>
		
	</div>
<!-- Subscriber Add End -->
<?php }else if($page_sub2=='edit'){
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. subscribers_edit_subscriber .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- Subscriber Edit Start -->
Edit
<!-- Subscriber Edit End -->
<?php } # End Sub?>