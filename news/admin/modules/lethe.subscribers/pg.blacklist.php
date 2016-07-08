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
if(!isDemo('editBlacklist,addRecord')){$errText = errMod(letheglobal_demo_mode_active,'danger');}
$sourceLimit = calcSource(set_org_id,'subscriber.blacklist');

/* Navigation */
$pg_nav_buts = '';

/* Add Record */
if(isset($_POST['addRecord'])){

	if(limitBlock($sourceLimit,set_org_max_blacklist)){

		if(!isset($_POST['new_rec_mail']) || !mailVal($_POST['new_rec_mail'])){$errText.='* '. letheglobal_invalid_e_mail_address .'<br>';}else{
			if(cntData("SELECT ID FROM ". db_table_pref ."blacklist WHERE email='". mysql_prep($_POST['new_rec_mail']) ."'")!=0){
				$errText.='* '. letheglobal_e_mail_already_exists .'<br>';
			}
		}
		if(!isset($_POST['new_rec_ip']) || empty($_POST['new_rec_ip'])){$_POST['new_rec_ip']='0.0.0.0';}
		if(!isset($_POST['new_rec_reason']) || !is_numeric($_POST['new_rec_reason'])){$errText.='* '. subscribers_please_choose_a_reason .'<br>';}
		
		if($errText==''){
		
			$blist = new lethe();
			$blist->OID = set_org_id;
			$blist->addBlacklist();
			
			/* Init Limits */
			$sourceLimit = calcSource(set_org_id,'subscriber.blacklist');
		
			$errText = errMod(letheglobal_recorded_successfully,'success');
		}else{
			$errText = errMod($errText,'danger');
		}
		
	}else{$errText=errMod(letheglobal_limit_exceeded,'danger');}

}

/* Edit Blacklist */
if(isset($_POST['editBlacklist'])){

	if(isset($_POST['del'])){
		
		$delRec = $myconn->prepare("DELETE FROM ". db_table_pref ."blacklist WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		
		foreach($_POST['del'] as $k=>$v){
			$v = ((!is_numeric($v)) ? null:$v);
			$delRec->bind_param('i',$v);
			$delRec->execute();
		}
		
		$delRec->close();
			/* Init Limits */
			$sourceLimit = calcSource(set_org_id,'subscriber.blacklist');
			
			$errText = errMod(letheglobal_updated_successfully,'success');
	}

}
?>

<?php 
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. subscribers_blacklist .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>

	<div class="form-group">
		<?php 
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_blacklist) .'</div></div>
			   </div>');
		?>
	</div>
	
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php if(limitBlock($sourceLimit,set_org_max_blacklist)){?>
  <div class="panel panel-info">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          <?php echo(subscribers_add_new_record);?>
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
			<form method="POST" action=""> 
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="new_rec_mail"><?php echo(sh('GtDVXeVUi0').letheglobal_e_mail);?></label>
							<input type="email" name="new_rec_mail" id="new_rec_mail" value="" class="form-control">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="new_rec_ip"><?php echo(sh('tCwJQ2Q7aB').letheglobal_ip_address);?></label>
							<input type="ip" name="new_rec_ip" id="new_rec_ip" value="" class="form-control" placeholder="0.0.0.0" pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="new_rec_reason"><?php echo(sh('VWf7IQKkSM').subscribers_reason);?></label>
							<select name="new_rec_reason" id="new_rec_reason" class="form-control">
								<?php foreach($LETHE_BLACKLIST_REASON as $k=>$v){
									echo('<option value="'. $k .'">'. $v .'</option>');
								}?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="addRecord">&nbsp;</label>
							<div><button type="submit" name="addRecord" id="addRecord" class="btn btn-primary"><?php echo(letheglobal_add);?></button></div>
						</div>
					</div>
				</div>
			</form>
      </div>
    </div>
  </div>
<?php }?>
  <div class="panel panel-warning">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <?php echo('List');?>
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
		<?php 
			(isMob() ? $limit = 10 : $limit = 20);
			((!isset($_GET["pgGo"]) || !is_numeric($_GET["pgGo"])) ? $pgGo = 1 : $pgGo = intval($_GET["pgGo"]));
			 $count		 = mysqli_num_rows($myconn->query("SELECT ID FROM ". db_table_pref ."blacklist WHERE OID=". set_org_id .""));
			 $total_page	 = ceil($count / $limit);
			 $dtStart	 = ($pgGo-1)*$limit;
			$opRecs = $myconn->query("SELECT * FROM ". db_table_pref ."blacklist WHERE OID=". set_org_id ." ORDER BY email asc LIMIT $dtStart,$limit") or die(mysqli_error($myconn));
		if($count==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{?>
		<form method="POST" action="">        
		<table class="footable table">
			<thead>
				<tr>
					<th data-sort-ignore="true"><div class="inlineIonc text-center"><label for="checkAll"></label><input type="checkbox" name="checkAll" id="checkAll" class="ionc"></div></th>
					<th><?php echo(letheglobal_e_mail);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_ip_address);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_reason);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_date);?></th>
				</tr>
			</thead>
			<tbody>
			<?php while($opRecsRs = $opRecs->fetch_assoc()){?>
				<tr>
					<td><div class="inlineIonc text-center"><label for="del<?php echo($opRecsRs['ID']);?>"></label><input type="checkbox" name="del[]" id="del<?php echo($opRecsRs['ID']);?>" class="ionc checkRow" value="<?php echo($opRecsRs['ID']);?>"></div></td>
					<td><?php echo(showIn($opRecsRs['email'],'page'));?></td>
					<td><?php echo(showIn($opRecsRs['ipAddr'],'page'));?></td>
					<td><?php echo($LETHE_BLACKLIST_REASON[$opRecsRs['reasons']]);?></td>
					<td><?php echo(setMyDate($opRecsRs['add_date'],2));?></td>
				</tr>
			<?php }?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="5">
					<?php $pgVar='?p='. $p;include_once("inc/inc_pagination.php");?>
				</td>
			</tr>
			</tfoot>
		</table>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
			});
		</script>
		<button type="submit" name="editBlacklist" id="editBlacklist" class="btn btn-primary"><?php echo(letheglobal_save);?></button>
		</form>
		<?php } $opRecs->free();?>
		
      </div>
    </div>
  </div>
</div>