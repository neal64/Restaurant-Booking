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
if(!isDemo('exportRun,importRun')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

/* Navigation */
$pg_nav_buts = '';

/* Exporting */
if(isset($_POST['exportRun'])){

	$resultData='';
	$dest = set_org_resource.'/expimp/';
	
	if(!isset($_POST['exp_groups'])){$resultData.='* '. subscribers_please_choose_a_group .'<br>';}else{
		/* Check Group Owner */
		$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		foreach($_POST['exp_groups'] as $k=>$v){
			$chkGRP->bind_param('i',$v);
			$chkGRP->execute();
			$chkGRP->store_result();
			if($chkGRP->num_rows==0){$resultData.='* '. subscribers_invalid_group .'<br>';}
		}
		$chkGRP->close();
	}
	if(!isset($_POST['exp_model']) || empty($_POST['exp_model']) || !array_key_exists($_POST['exp_model'],$LETHE_IMP_EXP_MODELS)){$resultData.='* '. subscribers_please_choose_a_model .'<br>';}
	if(!isset($_POST['exp_sep']) || empty($_POST['exp_sep']) || !array_key_exists($_POST['exp_sep'],$LETHE_IMP_EXP_SEPARATORS)){$resultData.='* '. subscribers_please_choose_a_separator .'<br>';}
	if(!isset($_POST['exp_markas']) || !is_numeric($_POST['exp_markas'])){$_POST['exp_markas']=0;}
	if(!isset($_POST['exp_markverif']) || !is_numeric($_POST['exp_markverif'])){$_POST['exp_markverif']=0;}
	
	/* Create File */
	if($resultData==''){
		$expF = 'lethe.export.'.uniqid().'.txt';
		if(!touch($dest.$expF)){$resultData.='* '. subscribers_export_file_could_not_be_created .'!<br>';}
	}
	
	if($resultData==''){
	
		$progText = '<span id="expStat"></span>
		<script>
			$(document).ready(function(){
				$("#import_prog").html("<div class=well>- '. subscribers_exports_began .'<br>- <span class=text-danger>'. subscribers_processing_dont_close_window .'</span></div>");
				$("#import_prog .well").append("<br><span class=\"spin glyphicon glyphicon-refresh\"></span>");
				$.ajax({
					url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=export",
					type: "POST",
					data : {exp_groups:"'. implode(',',$_POST['exp_groups']) .'",
							exp_model:"'. $_POST['exp_model'] .'",
							exp_sep:"'. $_POST['exp_sep'] .'",
							exp_markas:"'. $_POST['exp_markas'] .'",
							exp_markverif:"'. $_POST['exp_markverif'] .'",
							expF:"'. $expF .'"
							},
					contentType: "application/x-www-form-urlencoded",
					success: function(data, textStatus, jqXHR)
					{
						$("#import_prog .well").html(data);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$("#import_prog .well").html("'. letheglobal_error_occured .'");
					}
				});
			});
		</script>
		';
		$errText = $progText;
	
	}else{
		$errText = errMod($resultData,'danger');
	}

}

/* Import Custom */
if(isset($_POST['importRun'])){
	
	@set_time_limit(0);
	
	$resultData='';
	$dest = set_org_resource.'/expimp/';
	
	if(!isset($_POST['imp_groups']) || !is_numeric($_POST['imp_groups'])){$resultData.='* '. subscribers_please_choose_a_group .'<br>';}else{
		/* Check Group Owner */
		$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		$chkGRP->bind_param('i',$_POST['imp_groups']);
		$chkGRP->execute();
		$chkGRP->store_result();
		if($chkGRP->num_rows==0){$resultData.='* '. subscribers_invalid_group .'<br>';}
		$chkGRP->close();
	}
	if(!isset($_POST['imp_model']) || empty($_POST['imp_model']) || !array_key_exists($_POST['imp_model'],$LETHE_IMP_EXP_MODELS)){$resultData.='* '. subscribers_please_choose_a_model .'<br>';}
	if(!isset($_POST['imp_sep']) || empty($_POST['imp_sep']) || !array_key_exists($_POST['imp_sep'],$LETHE_IMP_EXP_SEPARATORS)){$resultData.='* '. subscribers_please_choose_a_separator .'<br>';}
	if(!isset($_POST['markas']) || $_POST['markas']!='YES'){$_POST['markas']=0;}else{$_POST['markas']=1;}
	if(!isset($_POST['markverif']) || !is_numeric($_POST['markverif'])){$_POST['markverif']=0;}
	if(!isset($_FILES['imp_file']) || $_FILES['imp_file']['error']!=0){
			$resultData.='* '. subscribers_please_choose_a_file .'<br>';
			if(lethe_debug_mode){
				$resultData.='* File Error: '. $_FILES['imp_file']['error'] .'<br>';
			}
	}

	if($resultData==''){
		/* Start Upload */
			$file_name = 'lethe.import.'.uniqid();
			include_once(LETHE_ADMIN.'/classes/class.upload.php');
			$handle = new upload($_FILES['imp_file']);
				
			if ($handle->uploaded) {
				$handle->file_new_name_body   = $file_name;
				$handle->file_safe_name = true;
				$handle->file_overwrite = false;
				$handle->file_auto_rename = true;
				$handle->allowed = $LETHE_EXP_IMP_MIMES; //*
				$handle->file_max_size = $LETHE_MAX_IMPORT_FILE_SIZE;
										
						
				//** Processing
				$handle->process($dest);
				if ($handle->processed) { # Uploaded
				
						if($_POST['imp_model']=='model4'){
							if(replaceImportContent($handle->file_dst_name,'model4',$_POST['imp_sep'])){
								$_POST['imp_model'] = 'model1';
							}
						}
						else if($_POST['imp_model']=='model5'){
							if(replaceImportContent($handle->file_dst_name,'model5',$_POST['imp_sep'])){
								$_POST['imp_model'] = 'model1';
							}
						}
				
						/* Parsing Start */
						$succText = '<span class="impRes"></span><script>
							$(document).ready(function(){
								$("#import_prog").html("<div class=well>- '. subscribers_file_uploaded .'<br><span class=text-danger>'. subscribers_processing_dont_close_window .'</span><br></div>");
									$("#import_prog .well").append("<br><span class=\"spin glyphicon glyphicon-refresh\"></span>");
									$.ajax({
										url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=import1",
										type: "POST",
										data : {impF:"'. $handle->file_dst_name .'",
												exp_groups:"'. intval($_POST['imp_groups']) .'",
												exp_model:"'. $_POST['imp_model'] .'",
												exp_sep:"'. $_POST['imp_sep'] .'",
												adv_csv:"'. ((isset($_POST['adv_csv']) && $_POST['adv_csv']=='YES') ? 'YES':'') .'",
												csvCond:\''. ((isset($_POST['csvCond']) && $_POST['csvCond']!='') ? $_POST['csvCond']:'') .'\',
												markas:"'. $_POST['markas'] .'",
												markverif:"'. $_POST['markverif'] .'"
												},
										contentType: "application/x-www-form-urlencoded",
										success: function(data, textStatus, jqXHR)
										{
											$(".impRes").html(data)
										},
										error: function (jqXHR, textStatus, errorThrown)
										{
											$("#import_prog .well").html("'. letheglobal_error_occured .'");
										}
									});
							});
						</script>';
						$errText = $succText;			
						/* Parsing End */
				
						$handle->clean();
					}
				else{ # Uploading Error
						$errText = errMod($handle->error,'danger');
					}
				# Uploading Finished
		
			}else{
				$errText = errMod('* '. subscribers_could_not_upload_file .'!','danger');
			}
		/* End Upload */
	}else{
		$errText = errMod($resultData,'danger');
	}
}
?>

<?php 
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. subscribers_export.' / '.subscribers_import .'</span></span></h1><hr>'.
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
							   AND 
									isUnsubscribe=0 
									". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
						  ORDER BY
									group_name
							   ASC
							") or die(mysqli_error($myconn));
	while($opGroupsRs = $opGroups->fetch_assoc()){
		$listGrps[] = $opGroupsRs;
	} $opGroups->free();
?>

<div id="import_prog"></div>
<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#exp" aria-controls="exp" role="tab" data-toggle="tab"><?php echo(subscribers_export);?></a></li>
    <li role="presentation"><a href="#imp_file" aria-controls="imp_file" role="tab" data-toggle="tab"><?php echo(subscribers_import_file);?></a></li>
    <li role="presentation"><a href="#imp_party" aria-controls="imp_party" role="tab" data-toggle="tab"><?php echo(subscribers_import_from_third_party);?></a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="exp">
	<!-- EXPORT START -->
	<div class="row">
		<div class="container">
			&nbsp;
			<form name="exp_form" id="exp_form" action="" method="POST">
			
				<div class="form-group">
					<label for="exp_groups"><?php echo(sh('QwYbQ4Idxx').subscribers_groups);?></label>
					<select name="exp_groups[]" id="exp_groups" class="form-control autoWidth" size="5" multiple>
						<?php
						foreach($listGrps as $k=>$v){
							echo('<option value="'. $v['ID'] .'">'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="exp_model"><?php echo(sh('3GHPISDGq8').subscribers_export_model);?></label>
					<select name="exp_model" id="exp_model" class="form-control autoWidth">
						<?php foreach($LETHE_IMP_EXP_MODELS as $k=>$v){
							echo('<option value="'. $k .'">'. showIn($v,'page') .'</option>');
						}?>
					</select>
				</div>
				<div class="form-group">
					<label for="exp_sep"><?php echo(sh('fa0DAq7tiJ').subscribers_separator);?></label>
					<select name="exp_sep" id="exp_sep" class="form-control autoWidth">
						<?php foreach($LETHE_IMP_EXP_SEPARATORS as $k=>$v){
							echo('<option value="'. $k .'">'. showIn($v,'page') .'</option>');
						}?>
					</select>
				</div>
				<div class="form-group">
					<label for="exp_markas"><?php echo(sh('zo3qJXWHpY').subscribers_subscriber_status);?></label>
					<select name="exp_markas" id="exp_markas" class="form-control autoWidth">
						<option value="0"><?php echo(letheglobal_all);?></option>
						<option value="1"><?php echo(letheglobal_active);?></option>
						<option value="2"><?php echo(letheglobal_inactive);?></option>
					</select>
				</div>
				<div class="form-group">
					<label for="exp_markverif"><?php echo(sh('c7meBvQuhj').subscribers_verification_status);?></label>
					<select name="exp_markverif" id="exp_markverif" class="form-control autoWidth">
						<option value="0"><?php echo(letheglobal_all);?></option>
						<option value="1"><?php echo(letheglobal_single_verified);?></option>
						<option value="2"><?php echo(letheglobal_double_verified);?></option>
					</select>
				</div>
				<div class="form-group">
					<button name="exportRun" id="exportRun" class="btn btn-primary"><?php echo(subscribers_export);?></button>
				</div>
			
			</form>
		</div>
	</div>
	<!-- EXPORT END -->
	</div>
    <div role="tabpanel" class="tab-pane" id="imp_file">
	<!-- IMPORT START -->
	<div class="row">
		<div class="container-fluid">
			&nbsp;
			<form name="imp_form" id="imp_form" action="" method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<label for="imp_groups"><?php echo(sh('Htj13E2WaU').subscribers_groups);?></label>
					<select name="imp_groups" id="imp_groups" class="form-control autoWidth">
						<?php
						foreach($listGrps as $k=>$v){
							echo('<option value="'. $v['ID'] .'">'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
						}
						?>
					</select>
				</div>
				<div class="form-group impModF">
					<label for="imp_model"><?php echo(sh('uDRovbx9Sf').subscribers_import_model);?></label>
					<select name="imp_model" id="imp_model" class="form-control autoWidth">
						<?php foreach($LETHE_IMP_EXP_MODELS as $k=>$v){
							echo('<option value="'. $k .'">'. showIn($v,'page') .'</option>');
						}?>
					</select>
				</div>
				<div class="form-group impSepF">
					<label for="imp_sep"><?php echo(sh('WXAcnzrmKq').subscribers_separator);?></label>
					<select name="imp_sep" id="imp_sep" class="form-control autoWidth">
						<?php foreach($LETHE_IMP_EXP_SEPARATORS as $k=>$v){
							echo('<option value="'. $k .'">'. showIn($v,'page') .'</option>');
						}?>
					</select>
				</div>
				<div class="form-group">
					<label for="markas"><?php echo(sh('8qtQgNmPD1').subscribers_as_marked);?></label>
					<div>
					<input type="checkbox" name="markas" id="markas" data-on-label="<?php echo(letheglobal_active);?>" data-off-label="<?php echo(letheglobal_inactive);?>" value="YES" class="letheSwitch" checked>
					</div>
				</div>
				<div class="form-group">
					<label for="markverif"><?php echo(sh('lAcEYlh25C').subscribers_verification);?></label>
					<select name="markverif" id="markverif" class="form-control autoWidth">
						<option value="0"><?php echo(letheglobal_not_verified);?></option>
						<option value="1"><?php echo(letheglobal_single_verified);?></option>
						<option value="2"><?php echo(letheglobal_double_verified);?></option>
					</select>
				</div>
				<div class="form-group">
					<label for="adv_csv"><?php echo(subscribers_advanced_csv);?></label>
					<input type="checkbox" name="adv_csv" id="adv_csv" value="YES" class="ionc">
				</div>
				<div class="form-group sHide csvActive">
					<button id="csvAnalyser" type="button" class="btn btn-warning btn-sm"><?php echo(subscribers_csv_analyser);?></button>
					<input type="hidden" name="csvCond" id="csvCond" value="">
				</div>
				<div class="form-group">
					<label for="imp_file"><?php echo(sh('NUL2R9bexN').letheglobal_file);?></label>
					<input type="file" name="imp_file" id="imp_file" class="filestyle autoWidth" required>
					<span class="helper-block text-muted"><strong><?php echo(letheglobal_only);?>:</strong> csv, txt</span>
				</div>
				<div class="form-group">
					<button type="submit" name="importRun" id="importRun" class="btn btn-primary"><?php echo(subscribers_import);?></button>
				</div>
			
			</form>
			<script>
				$(document).ready(function(){
					/* Analyser Toggle */
					$('#adv_csv').change(function() {
						$(".csvActive").slideToggle();
						$(".impModF").slideToggle();
						$(".impSepF").slideToggle();
					});
					
					/* Analyser Form */
					$("#csvAnalyser").click(function(){
						$.fancybox({
						
							type: "ajax",
							href: "modules/lethe.subscribers/exip.xmlhttp.php?pos=csvAnalyser",
							width: 900,
							height: 700,
							autoSize: false
						
						});
					});
				});
			</script>
		</div>
	</div>
	<!-- IMPORT END -->
	</div>
    <div role="tabpanel" class="tab-pane" id="imp_party">
	<!-- 3TH Part Start -->
	<div class="row">
		<div class="container-fluid">
			&nbsp;
			<form name="prty_form" id="prty_form" action="" method="POST">
				<div class="form-group">
					<label for="db_groups"><?php echo(sh('Htj13E2WaU').subscribers_groups);?></label>
					<select name="db_groups" id="db_groups" class="form-control autoWidth">
						<?php
						foreach($listGrps as $k=>$v){
							echo('<option value="'. $v['ID'] .'">'. showIn($v['group_name'],'page') .' ('. $v['sbr_cnt'] .')</option>');
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="db_markas"><?php echo(sh('8qtQgNmPD1').subscribers_as_marked);?></label>
					<div>
					<input type="checkbox" name="db_markas" id="db_markas" data-on-label="<?php echo(letheglobal_active);?>" data-off-label="<?php echo(letheglobal_inactive);?>" value="YES" class="letheSwitch" checked>
					</div>
				</div>
				<div class="form-group">
					<label for="db_markverif"><?php echo(sh('lAcEYlh25C').subscribers_verification);?></label>
					<select name="db_markverif" id="db_markverif" class="form-control autoWidth">
						<option value="0"><?php echo(letheglobal_not_verified);?></option>
						<option value="1"><?php echo(letheglobal_single_verified);?></option>
						<option value="2"><?php echo(letheglobal_double_verified);?></option>
					</select>
				</div>
			
				
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-success">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							  <?php echo(subscribers_database_connection);?>
							</a>
						  </h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">

								<div class="form-group">
									<label for="db_host"><?php echo(sh('ERVS4JLXZu').subscribers_database_host);?></label>
									<input type="text" class="form-control autoWidth" id="db_host" name="db_host">
								</div>
								<div class="form-group">
									<label for="db_name"><?php echo(sh('qlEFwhGAS5').subscribers_database_name);?></label>
									<input type="text" class="form-control autoWidth" id="db_name" name="db_name">
								</div>
								<div class="form-group">
									<label for="db_user"><?php echo(sh('T4x3p5AnKG').subscribers_database_username);?></label>
									<input type="text" class="form-control autoWidth" id="db_user" name="db_user">
								</div>
								<div class="form-group">
									<label for="db_pass"><?php echo(sh('5OfrhT5QCR').subscribers_database_password);?></label>
									<input type="password" class="form-control autoWidth" id="db_pass" name="db_pass" autocomplete="off">
								</div>
								<div class="form-group">
									<div id="connRes"></div>
								</div>
								<div class="form-group">
									<button type="button" name="test_DB" id="test_DB" class="btn btn-primary"><?php echo(subscribers_test_connection);?> <span></span></button>
								</div>
						  
						  </div>
						</div>
					  </div>
					  <div class="panel panel-warning">
						<div class="panel-heading" role="tab" id="headingTwo">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							  <?php echo(subscribers_table_selection);?>
							</a>
						  </h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						  <div class="panel-body">

								<div class="form-group">
									<label for="db_platform"><?php echo(sh('hiOfZr034c').subscribers_platform);?></label>
									<select name="db_platform" id="db_platform" class="form-control autoWidth">
										<?php foreach($LETHE_IMPORT_PART_SOFTWARES as $k=>$v){
											echo('<option value="'. $k .'">'. $v['name'] .'</option>');
										}?>
									</select>
								</div>
								<div class="form-group">
									<label for="db_pref"><?php echo(sh('K27TSEufwn').subscribers_table_prefix);?></label>
									<input type="text" class="form-control autoWidth" id="db_pref" name="db_pref" placeholder="wp_">
								</div>
								<div class="form-group">
									<div id="tblRes"></div>
								</div>
								<div class="form-group">
									<button type="button" name="test_TBL" id="test_TBL" class="btn btn-primary"><?php echo(subscribers_check_tables);?><span></span></button>
								</div>
						  
						  </div>
						</div>
					  </div>
					  <div class="panel panel-danger">
						<div class="panel-heading" role="tab" id="headingThree">
						  <h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
							  <?php echo(subscribers_import);?>
							</a>
						  </h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
						  <div class="panel-body">
								<div id="db_imp_res"></div>
						  </div>
						</div>
					  </div>
					</div>
				
				<script>
					$(document).ready(function(){
						/* Connection Test */
						$("#test_DB").click(function(){
							$("#test_DB span").addClass('spin glyphicon glyphicon-refresh');
							$.ajax({
								url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=dbcheck",
								type: "POST",
								data : $("#prty_form").serialize(),
								contentType: "application/x-www-form-urlencoded",
								success: function(data, textStatus, jqXHR)
								{
									$("#connRes").html(data);
									$("#test_DB span").removeClass('spin glyphicon glyphicon-refresh');
								},
								error: function (jqXHR, textStatus, errorThrown)
								{
									$("#connRes").html("<?php echo(letheglobal_error_occured);?>");
								}
							});
						});
						/* Table Test */
						$("#test_TBL").click(function(){
							$("#test_TBL span").addClass('spin glyphicon glyphicon-refresh');
							$.ajax({
								url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=tblcheck",
								type: "POST",
								data : $("#prty_form").serialize(),
								contentType: "application/x-www-form-urlencoded",
								success: function(data, textStatus, jqXHR)
								{
									$("#tblRes").html(data);
									$("#test_TBL span").removeClass('spin glyphicon glyphicon-refresh');
								},
								error: function (jqXHR, textStatus, errorThrown)
								{
									$("#connRes").html("<?php echo(letheglobal_error_occured);?>");
								}
							});
						});
					});
				</script>
			</form>
		</div>
	</div>
	<!-- 3TH Part End -->
	
	</div>
  </div>

</div>