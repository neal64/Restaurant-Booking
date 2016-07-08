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
if($page_main=='templates'){
if(!permCheck($p)){
	echo(errMod(letheglobal_you_are_not_authorized_to_view_this_page,'danger'));
}else{

/* Requests */
if(!isset($_GET['ID']) || !is_numeric($_GET['ID'])){$ID=0;}else{$ID=intval($_GET['ID']);}

/* Mod Settings */
$mod_confs = $lethe_modules[recursive_array_search('lethe.templates',$lethe_modules)];
$pg_title = $mod_confs['title'];
$pg_nav_buts = '';
$errText = '';

/* Demo Check */
if(!isDemo('addTemplate,editTemplate')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

/* Source Limit */
$sourceLimit = calcSource(set_org_id,'templates');

/* Add Template */
if(isset($_POST['addTemplate'])){

	if(limitBlock($sourceLimit,set_org_max_template)){
		if(!isset($_POST['title']) || empty($_POST['title'])){$errText.='* '. templates_please_enter_a_template_name .'<br>';}
		if(!isset($_POST['details']) || empty($_POST['details'])){$errText.='* '. templates_please_enter_template_details .'<br>';}
		if(!isset($_POST['preview']) || !urlVal($_POST['preview'])){$_POST['preview']='';}
		
		if($errText==''){
		
			$addData = $myconn->prepare("INSERT INTO ". db_table_pref ."templates SET temp_name=?, temp_contents=?,temp_prev=?,temp_type='normal', OID=". set_org_id .", UID=". LETHE_AUTH_ID ."") or die(mysqli_error($myconn));
			$addData->bind_param('sss',$_POST['title'],$_POST['details'],$_POST['preview']);
			$addData->execute();
			$addData->close();
		
			
			$errText = errMod(letheglobal_recorded_successfully,'success');
			unset($_POST);
		}else{
			$errText = errMod($errText,'danger');
		}
	}else{$errText=errMod(letheglobal_limit_exceeded,'danger');}

}

/* Edit Template */
if(isset($_POST['editTemplate'])){

	$opTemp = $myconn->prepare("SELECT * FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
	$opTemp->bind_param('i',$ID);
	$opTemp->execute();
	$opTemp->store_result();
	if($opTemp->num_rows==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
	$sr = new Statement_Result($opTemp);
	$opTemp->fetch();
	$opTemp->close();
	
		/* Delete Template */
		if(isset($_POST['del']) && $_POST['del']=='YES'){
		
			$delData = $myconn->prepare("DELETE FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND temp_type='normal' AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
			$delData->bind_param('i',$ID);
			$delData->execute();
			$delData->close();
			header('Location: ?p=templates/list');
			die();
		}
	

		if(!isset($_POST['title']) || empty($_POST['title'])){$errText.='* '. templates_please_enter_a_template_name .'<br>';}
		if(!isset($_POST['details']) || empty($_POST['details'])){$errText.='* '. templates_please_enter_template_details .'<br>';}
		if(!isset($_POST['preview']) || !urlVal($_POST['preview'])){$_POST['preview']='';}
		
		if($errText==''){
		
			/* Update Templates */

			$addData = $myconn->prepare("UPDATE ". db_table_pref ."templates SET temp_name=?, temp_contents=?,temp_prev=? WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
			$addData->bind_param('sssi',$_POST['title'],$_POST['details'],$_POST['preview'],$ID);
			$addData->execute();
			$addData->close();
			
			$errText = errMod(letheglobal_updated_successfully,'success');
			unset($_POST);
		}else{
			$errText = errMod($errText,'danger');
		}
		
	}

}
?>

<?php if($page_sub=='list'){
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. templates_templates .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- Template List Start -->
<div class="row">
<?php 
$limit=12;
			 
$opCnt = $myconn->query("SELECT ID FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));

$pgGo = ((!isset($_GET["pgGo"]) || !is_numeric($_GET["pgGo"])) ? 1 : intval($_GET["pgGo"]));
$count		 = mysqli_num_rows($opCnt);
$total_page	 = ceil($count / $limit);
$dtStart	 = ($pgGo-1)*$limit;

$opTemp = $myconn->query("SELECT ID,OID,UID,temp_name,add_date,temp_prev,temp_type FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ." ORDER BY isSystem DESC, temp_name ASC LIMIT $dtStart,$limit") or die(mysqli_error($myconn));
if(mysqli_num_rows($opTemp)==0){echo('<div class="col-md-12">'. errMod(letheglobal_record_not_found,'danger') .'</div>');}
while($opTempRs = $opTemp->fetch_assoc()){
?>
  <div class="col-sm-5 col-md-3">
    <div class="thumbnail">
      <a href="?p=templates/edit&amp;ID=<?php echo($opTempRs['ID']);?>" class="tempPrevs effect6"><span><img src="<?php echo(($opTempRs['temp_prev']=='') ? 'holder.js/245x98/text:'.letheglobal_preview.'':showIn($opTempRs['temp_prev'],'input'));?>" alt=""></span></a>
      <div class="caption">
        <a href="?p=templates/edit&amp;ID=<?php echo($opTempRs['ID']);?>"><?php echo(showIn($opTempRs['temp_name'],'page'));?></a>
		<p><small><?php echo(setMyDate($opTempRs['add_date'],2));?></small></p>
		<p>
			<a href="?p=templates/edit&amp;ID=<?php echo($opTempRs['ID']);?>" class="tooltips text-warning" title="<?php echo(templates_edit_template);?>"><span class="glyphicon glyphicon-edit"></span></a>
			<a href="?p=newsletter/add&amp;TID=<?php echo($opTempRs['ID']);?>" class="tooltips text-danger" title="<?php echo(templates_use_for_newsletter);?>"><span class="glyphicon glyphicon-share"></span></a>
			<a href="act.xmlhttp.php?pos=temprev&amp;ID=<?php echo($opTempRs['ID']);?>" data-fancybox-type="iframe" class="fancybox tooltips text-success" title="<?php echo(letheglobal_preview);?>"><span class="glyphicon glyphicon-eye-open"></span></a>
			<?php if($opTempRs['temp_type']!='normal'){echo('<span class="glyphicon glyphicon-link text-info tooltips" title="'. $LETHE_TEMPLATE_TYPES[$opTempRs['temp_type']] .'"></span>');}?>
		</p>
      </div>
    </div>
  </div>
<?php } $opTemp->free();?>
</div>

<div class="row">
	<div class="col-md-12">
	<hr>
	<?php $pgVar='?p='. $p;include_once("inc/inc_pagination.php");?>
	</div>
</div>

<!-- Template List End -->
<?php }else if($page_sub=='add'){
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. letheglobal_add .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. sh('pRP9MnRKno') . letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_template) .'</div></div>
			   </div>');
?>
<!-- Template Add Start -->
<?php if(limitBlock($sourceLimit,set_org_max_template)){?>
<script>
	var customMCEchar='<?php echo(LOADED_LANG);?>';
	var miniPAN=true;
</script>
<script src="Scripts/tinymce/tinymce.min.js"></script>
<script src="Scripts/tinymce/tinymce.custom.js"></script>
<script src="Scripts/leUpload.js"></script>

	<form method="POST" action="">
	
		<div class="form-group">
			<label for="title"><?php echo(sh('Rl7xFBLxfz').letheglobal_title);?></label>
			<input type="text" class="form-control autoWidth" id="title" name="title" size="40" value="<?php echo((isseter('title')) ? showIn($_POST['title'],'input'):'');?>">
		</div>
		
		<div class="form-group">
			<label for="preview"><?php echo(sh('BIbU9iwML7').letheglobal_preview);?></label>
			<div class="input-group">
				<input type="url" class="form-control autoWidth" id="preview" name="preview" size="40" value="<?php echo((isseter('preview')) ? showIn($_POST['preview'],'input'):'');?>"> <span class="input-group-btn autoWidth"><button class="btn btn-default leupload_link" data-leupload-opener="fancybox" data-leupload-model="default" data-leupload-form="preview" data-leupload-platform="normal" type="button">leUpload</button></span>
			</div>
		</div>
		
		<div class="form-group">
			<label for="sc-lists"><?php echo(sh('44ql7ZGaYA').letheglobal_short_codes);?> <a href="javascript:;" class="sc-opener"><span class="glyphicon glyphicon-chevron-down"></span></a></label>
			<div id="sc-box" class="sHide">
				<div class="well"><?php echo(scList('details'));?></div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="details"><?php echo(sh('gOZYzxBDQL').letheglobal_details);?></label>
			<textarea class="mceEditor form-control" id="details" name="details"><?php echo((isseter('details')) ? $_POST['details']:'');?></textarea>
		</div>
			
		<div class="form-group">
			<button type="button" class="LethePreview btn btn-warning"><?php echo(letheglobal_preview);?></button> <button type="submit" class="btn btn-primary" name="addTemplate"><?php echo(letheglobal_save);?></button>
		</div>
	
	</form>
<?php }else{echo errMod(letheglobal_limit_exceeded,'danger');} # Limit Block?>

<!-- Template Add End -->
<?php }else if($page_sub=='edit'){
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. letheglobal_edit .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- Template Edit Start -->
<?php 
	$opTemp = $myconn->prepare("SELECT * FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND ID=? ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
	$opTemp->bind_param('i',$ID);
	$opTemp->execute();
	$opTemp->store_result();
	if($opTemp->num_rows==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
	$sr = new Statement_Result($opTemp);
	$opTemp->fetch();
	$opTemp->close();
?>
<script>
	var customMCEchar='<?php echo(LOADED_LANG);?>';
	var miniPAN=true;
</script>
<script src="Scripts/tinymce/tinymce.min.js"></script>
<script src="Scripts/tinymce/tinymce.custom.js"></script>
<script src="Scripts/leUpload.js"></script>

	<form method="POST" action="">
	
		<div class="form-group">
			<label for="title"><?php echo(sh('Rl7xFBLxfz').letheglobal_title);?></label>
			<input type="text" class="form-control autoWidth" id="title" name="title" size="40" value="<?php echo(showIn($sr->Get('temp_name'),'input'));?>">
		</div>
		
		<div class="form-group">
			<label for="preview"><?php echo(sh('BIbU9iwML7').letheglobal_preview);?></label>
			<div class="input-group">
				<input type="url" class="form-control autoWidth" id="preview" name="preview" size="40" value="<?php echo(showIn($sr->Get('temp_prev'),'input'));?>"> <span class="input-group-btn autoWidth"><button class="btn btn-default leupload_link" data-leupload-opener="fancybox" data-leupload-model="default" data-leupload-form="preview" data-leupload-platform="normal" type="button">leUpload</button></span>
			</div>
		</div>
		
		<div class="form-group">
			<label for="sc-lists"><?php echo(sh('44ql7ZGaYA').letheglobal_short_codes);?> <a href="javascript:;" class="sc-opener"><span class="glyphicon glyphicon-chevron-down"></span></a></label>
			<div id="sc-box" class="sHide">
				<div class="well"><?php echo(scList('details'));?></div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="details"><?php echo(sh('gOZYzxBDQL').letheglobal_details);?></label>
			<textarea class="mceEditor form-control" id="details" name="details"><?php echo($sr->Get('temp_contents'));?></textarea>
		</div>
		
		<?php if($sr->Get('temp_type')=='normal'){?>
		<div class="form-group">
			<label for="del"><?php echo(letheglobal_delete);?></label>
			<input type="checkbox" name="del" id="del" class="ionc" value="YES" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>">
		</div>
		<?php }?>
		
		<div class="form-group">
			<button type="button" class="LethePreview btn btn-warning"><?php echo(letheglobal_preview);?></button> <button type="submit" class="btn btn-primary" name="editTemplate"><?php echo(letheglobal_save);?></button>
		</div>
	
	</form>
<?php } # Record Check End?>
<!-- Template Edit End -->
<?php }else if($page_sub=='loader'){
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. letheglobal_loader .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
	?>
<!-- Template Loader Start -->
<div class="row" id="tempAPI">

</div>
<script type="text/javascript">
	$(document).ready(function(){
		loadTemplates(1,12,null);
	});
</script>
<!-- Template Loader End -->
<?php }else{
	echo('<h1>'. $pg_title .'</h1><hr>');
	foreach($mod_confs['contents'] as $k=>$v){
		echo('<div class="col-md-2 module-splash">
				<h4><span class="'. $v['icon'] .'"></span></h4>
				<div><a href="'. $v['page'] .'">'. $v['title'] .'</a></div>
			  </div>');
	}
}
?>

<?php 
} # Permission Check End
} # Module Load End
?>