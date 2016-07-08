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
if(!isDemo('editCodes')){$errText = errMod(letheglobal_demo_mode_active,'danger');}
$sourceLimit = calcSource(set_org_id,'shortcode');

$pg_nav_buts = '';

if(isset($_POST['editCodes'])){

	/* Add New */
	if(limitBlock($sourceLimit,set_org_max_shortcode)){
		if(isset($_POST['new_code']) && !empty($_POST['new_code'])){
			if(isset($_POST['new_code_val']) && !empty($_POST['new_code_val'])){
				if(array_key_exists(strtoupper($_POST['new_code']),$LETHE_SYSTEM_SHORTCODES)){$errText.='* '. organizations_short_code_has_defined_as_a_system_code .'<br>';}
				if(cntData("SELECT ID FROM ". db_table_pref ."short_codes WHERE OID=". set_org_id ." AND code_key='". mysql_prep($_POST['new_code']) ."'")!=0){$errText.='* '. organizations_short_code_already_exists .'<br>';}
				
				if($errText==''){
					$addCode = $myconn->prepare("INSERT INTO ". db_table_pref ."short_codes SET OID=". set_org_id .", code_key=?, code_val=?") or die(mysqli_error($myconn));
					$addCode->bind_param('ss',$_POST['new_code'],$_POST['new_code_val']);
					$addCode->execute();
					$addCode->close();
				}
			}
		}
	}
	
	/* Update */
	if(isset($_POST['code_datas'])){
	
		$upCode = $myconn->prepare("UPDATE ". db_table_pref ."short_codes SET code_val=? WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		foreach($_POST['code_datas'] as $k=>$v){
			$codeID = intval($v);
			$codeKey = ((isset($_POST['code_key_'.$codeID])) ? '{'. showIn($_POST['code_key_'.$codeID],'page') .'}':'');
			if(!isset($_POST['code_val_'.$codeID]) || empty($_POST['code_val_'.$codeID])){$errText.='* '. organizations_please_enter_code_value .' '. $codeKey .'<br>';$codeVal='';}else{$codeVal=$_POST['code_val_'.$codeID];}
			
			/* Update Data */
			if(!empty($codeVal)){
				$upCode->bind_param('si',$codeVal,$codeID);
				$upCode->execute();
			}
			
			/* Delete Data */
			if(isset($_POST['del_'.$codeID]) && $_POST['del_'.$codeID]=='YES'){
				$myconn->query("DELETE FROM ". db_table_pref ."short_codes WHERE OID=". set_org_id ." AND ID=". $codeID ." AND isSystem=0") or die(mysqli_error($myconn));
			}
		}
		$upCode->close();
	
	}
	
	if($errText==''){
		$errText = errMod(letheglobal_updated_successfully,'success');
	}else{
		$errText = errMod($errText,'danger');
	}

}
?>

<?php 
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. organizations_short_codes .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>

<form method="POST" action="">
	
	<div class="form-group">
		<?php 
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_shortcode) .'</div></div>
			   </div>');
		?>
	</div>

	<h3><?php echo(organizations_system_codes);?><small> <a href="javascript:;" data-target="#systemCodes" class="toggler"><span class="glyphicon glyphicon-chevron-down"></span></a></small></h3>
	<hr>
	<div id="systemCodes" class="sHide">
	<?php foreach($LETHE_SYSTEM_SHORTCODES as $k=>$v){?>
	<div class="row">
		<div class="col-md-4"><div class="form-group"><span class="label label-danger">{<?php echo($k);?>}</span></div></div>
		<div class="col-md-8"><?php echo((($k=='LETHE_SAVE_TREE') ? lethe_save_tree:$v));?></div>
	</div>
	<?php }?>
	</div>
	
	<h3><?php echo(organizations_custom_codes);?></h3>
	<hr>
	<?php $opCodes = $myconn->query("SELECT * FROM ". db_table_pref ."short_codes WHERE OID=". set_org_id ." ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ." ORDER BY isSystem DESC,code_key ASC") or die(mysqli_error($myconn));
	while($opCodesRs = $opCodes->fetch_assoc()){
	?>
	<div class="row">
		<div class="col-md-1"><div class="form-group"><label for="del_<?php echo($opCodesRs['ID']);?>"><span class="visible-xs"><?php echo(letheglobal_delete);?></span></label><input type="checkbox" name="del_<?php echo($opCodesRs['ID']);?>" id="del_<?php echo($opCodesRs['ID']);?>" value="YES" class="ionc"></div></div>
		<div class="col-md-4"><div class="form-group"><span class="label label-<?php echo((($opCodesRs['isSystem']==0) ? 'primary':'warning'));?>">{<?php echo(showIn($opCodesRs['code_key'],'page'));?>}</span></div></div>
		<div class="col-md-7"><div class="form-group"><input type="text" value="<?php echo(showIn($opCodesRs['code_val'],'input'));?>" class="form-control" name="code_val_<?php echo($opCodesRs['ID']);?>" id="code_val_<?php echo($opCodesRs['ID']);?>"></div></div>
		<input type="hidden" name="code_datas[]" value="<?php echo($opCodesRs['ID']);?>">
		<input type="hidden" name="code_key_<?php echo($opCodesRs['ID']);?>" value="<?php echo($opCodesRs['code_key']);?>">
		<hr class="visible-xs">
	</div>
	<?php } $opCodes->free();?>
	
	<?php if(limitBlock($sourceLimit,set_org_max_shortcode)){?>
	<h3><?php echo(organizations_add_new_code);?></h3>
	<hr>
	<div class="row">
		<div class="col-md-4"><div class="form-group"><input type="text" onblur="shortCodeMaker(this.id);" value="" class="form-control" name="new_code" id="new_code" placeholder="<?php echo(organizations_new_code);?>"></div></div>
		<div class="col-md-8"><div class="form-group"><input type="text" value="" class="form-control" name="new_code_val" id="new_code_val" placeholder="<?php echo(organizations_new_code_value);?>"></div></div>
	</div>
	<?php }?>
	
	<div class="form-group">
		<hr>
		<button name="editCodes" class="btn btn-primary" type="submit"><?php echo(letheglobal_save);?></button>
	</div>
	
</form>