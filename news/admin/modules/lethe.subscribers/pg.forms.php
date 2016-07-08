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

/* Request */
$fm = ((!isset($_GET['fm']) || !is_numeric($_GET['fm'])) ? 3:intval($_GET['fm']));

/* Navigation */
$pg_nav_buts = '<div class="nav-buts">
				<a href="?p=subscribers/forms/add" class="btn btn-success" role="button">'. letheglobal_add .'</a> 
				<a href="?p=subscribers/forms/list" class="btn btn-primary" role="button">'. letheglobal_list .'</a>
				</div>
				';


?>

<?php 
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. subscribers_subscribe_forms .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>

<?php if($page_sub2=='list' || $page_sub2==''){?>
<!-- Forms List Start -->

<?php $opForms = $myconn->query("SELECT * FROM ".db_table_pref ."subscribe_forms WHERE OID=". set_org_id ." AND isDraft=0 ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ." ORDER BY isSystem ASC, form_name ASC") or die(mysqli_error($myconn));?>

		<table class="footable table">
			<thead>
				<tr>
					<th><?php echo(subscribers_form_name);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_form_type);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_form_view);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_groups);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_form_fields);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_system);?></th>
					<th data-hide="phone,tablet"><?php echo(subscribers_subscription);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_created);?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if(mysqli_num_rows($opForms)==0){echo('<tr><td colspan="8">'. errMod(letheglobal_record_not_found,'danger') .'</td></tr>');}
			while($opFormsRs = $opForms->fetch_assoc()){?>
				<tr>
					<td><a href="?p=subscribers/forms/edit&amp;fm=<?php echo($opFormsRs['form_type']);?>&amp;ID=<?php echo($opFormsRs['ID']);?>"><?php echo(showIn($opFormsRs['form_name'],'page'));?></a></td>
					<td><?php echo($LETHE_SUBSCRIBE_FORM_TYPES[$opFormsRs['form_type']]);?></td>
					<td><?php echo($LETHE_SUBSCRIBE_FORM_VIEWS[$opFormsRs['form_view']]);?></td>
					<td><?php echo(showIn(getGroup($opFormsRs['form_group'],0),'page'));?></td>
					<td><?php echo(cntData("SELECT ID FROM ". db_table_pref ."subscribe_form_fields WHERE FID=". $opFormsRs['ID'] .""));?></td>
					<td><?php echo(getBullets($opFormsRs['isSystem']));?></td>
					<td><?php echo(getBullets((($opFormsRs['subscription_stop']) ? 0:1)));?></td>
					<td><?php echo(setMyDate($opFormsRs['add_date'],2));?></td>
				</tr>
			<?php } $opForms->free();?>
			</tbody>
		</table>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
			});
		</script>

<!-- Forms List End -->
<?php }else if($page_sub2=='add' || $page_sub2=='edit'){

		if($page_sub2=='add'){
			$sourceLimit = calcSource(set_org_id,'subscriber.forms');
			$opDraft = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_forms WHERE OID=". set_org_id ." AND isDraft=1 AND isSystem<>1 AND form_type=0 AND UID=". LETHE_AUTH_ID ."") or die(mysqli_error($myconn));
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_subscribe_form) .'</div></div>
			   </div>');
		}else{
			$opDraft = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_forms WHERE OID=". set_org_id ." AND ID=". $ID ." AND form_type=0 ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
		}
?>
<!-- Forms Add Start -->
<?php if($page_sub2=='edit' || $page_sub2=='add'){?>
	<div role="tabpanel">

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<?php echo('
			'. (($fm==0 || $page_sub2!='edit') ? '<li role="presentation" class="active"><a href="#s-forms" aria-controls="s-forms" role="tab" data-toggle="tab">'.subscribers_form.'</a></li>':'') .'
			'. (($fm==1 || $page_sub2!='edit') ? '<li role="presentation"><a href="#s-link" aria-controls="s-link" role="tab" data-toggle="tab">API</a></li>':'') .'
		');?>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<?php if($fm==0 || $page_sub2!='edit'){?>
		<div role="tabpanel" class="tab-pane active" id="s-forms">
			<!-- Forms -->
			&nbsp;
			<?php 
			if(mysqli_num_rows($opDraft)==0){
				if($page_sub2=='add'){
					echo('<div class="draftMod0">'.errMod(''. subscribers_the_draft_could_not_be_found .'! <a href="javascript:;" data-draft-mod="0" class="draftMaker alert-link">'. subscribers_create_new_form .'</a>','danger').'</div>');
				}else{
					echo('<div class="draftMod0">'.errMod(letheglobal_record_not_found,'danger').'</div>');
				}
			}else{
			$opDraftRs = $opDraft->fetch_assoc();
			?>
				<div class="row">
					<form method="POST" action="">
						<div class="col-md-6">
							<div class="col-md-12" id="formSettings">
								<h3><?php echo(' <small>'.sh('Xf3ZhRCtof').'</small>'.subscribers_settings);?> <small> <a href="javascript:;" data-target=".settings-box" class="toggler"><span class="glyphicon glyphicon-chevron-up"></span></a></small></h3><hr>
								<div class="settings-box">
								<div class="form-group">
									<label for="form_name"><?php echo(sh('S4RYJVdlRm').subscribers_form_name);?></label>
									<input type="text" value="<?php echo(showIn($opDraftRs['form_name'],'input'));?>" class="form-control" id="form_name" name="form_name">
								</div>
								<div class="form-group">
									<label for="success_text"><?php echo(sh('UCq0iWVuo5').subscribers_success_text);?></label>
									<input type="text" value="<?php echo(showIn($opDraftRs['form_success_text'],'input'));?>" class="form-control" id="success_text" name="success_text">
								</div>
								<div class="form-group">
									<label for="success_url"><?php echo(sh('4OAMBtYOJx').subscribers_success_url);?></label>
									<input type="url" value="<?php echo(showIn($opDraftRs['form_success_url'],'input'));?>" class="form-control" id="success_url" name="success_url" placeholder="http://">
								</div>
								<div class="form-group">
									<label for="success_url_text"><?php echo(sh('ghtA4HT1C8').subscribers_success_url_text);?></label>
									<input type="text" value="<?php echo(showIn($opDraftRs['form_success_url_text'],'input'));?>" class="form-control" id="success_url_text" name="success_url_text">
								</div>
								<div class="form-group">
									<label for="redir_time"><?php echo(sh('J1bxwfNe4J').subscribers_redirection_time);?></label>
									<select class="form-control autoWidth" name="redir_time" id="redir_time">
										<?php for($i=0;$i<=10;$i++){echo('<option value="'. $i .'"'. formSelector($i,$opDraftRs['form_success_redir'],0) .'>'. $i .' ('. letheglobal_seconds .')</option>');}?>
									</select>
								</div>
								<div class="form-group">
									<label for="subscrib_err"><?php echo(sh('LA0QLgFnQc').subscribers_form_errors);?></label> <small> <a href="javascript:;" data-target="#subscrib_err" class="toggler"><span class="glyphicon glyphicon-chevron-down"></span></a></small>
									<div id="subscrib_err" class="well sHide">
										<?php 
										$formErrors = explode("[@]",$opDraftRs['form_errors']);
										$f = 0;
										foreach($LETHE_SUBSCRIBE_ERRORS as $k=>$v){?>
										<div class="form-group">
											<label for="errors<?php echo($k);?>"><?php echo($v[0]);?></label>
											<input type="text" class="form-control" name="errors<?php echo($k);?>" id="errors<?php echo($k);?>" value="<?php echo(showIn($formErrors[$f],'input'));?>">
										</div>
										<?php $f++;}?>
									</div>
								</div>
								<div class="form-group">
									<label for="form_view"><?php echo(sh('vLKlp3IGwU').subscribers_form_view);?></label>
									<select class="form-control autoWidth" name="form_view" id="form_view">
										<?php foreach($LETHE_SUBSCRIBE_FORM_VIEWS as $k=>$v){
											echo('<option value="'. $k .'"'. formSelector($opDraftRs['form_view'],$k,0) .'>'. $v .'</option>');
										}?>
									</select>
								</div>
								<div class="form-group">
									<label for="form_group"><?php echo(sh('LLwa28jVb4').subscribers_groups);?></label>
									<select class="form-control autoWidth" id="form_group" name="form_group">
										<?php $opGroups = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND isUnsubscribe=0 ". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."") or die(mysqli_error($myconn));
										while($opGroupsRs = $opGroups->fetch_assoc()){
											echo('<option value="'. $opGroupsRs['ID'] .'"'. formSelector($opDraftRs['form_group'],$opGroupsRs['ID'],0) .'>'. showIn($opGroupsRs['group_name'],'page') .'</option>');
										} $opGroups->free();
										?>
									</select>
								</div>
								<div class="form-group">
									<span><?php echo(sh('0vwnCyrPJN'));?></span><label for="include_jquery"><?php echo(subscribers_include_jquery);?></label>
									<input type="checkbox" class="ionc" name="include_jquery" id="include_jquery" value="YES"<?php echo(formSelector($opDraftRs['include_jquery'],1,2));?>>
								</div>
								<div class="form-group">
									<span><?php echo(sh('dFfb5Mm2BY'));?></span><label for="include_jqueryui"><?php echo(subscribers_include_jquery_ui);?></label>
									<input type="checkbox" class="ionc" name="include_jqueryui" id="include_jqueryui" value="YES"<?php echo(formSelector($opDraftRs['include_jqueryui'],1,2));?>>
								</div>
								<div class="form-group pub-form <?php if($opDraftRs['isDraft']==0){echo('sHide');}?>">
									<span><?php echo(sh('FZOOxkgf3j'));?></span><label for="publish_form"><?php echo(subscribers_publish_form);?></label>
									<input type="checkbox" class="ionc" name="publish_form" id="publish_form" value="YES"<?php echo(formSelector($opDraftRs['isDraft'],0,2));?>>
								</div>	
								<div class="form-group">
									<span><?php echo(sh('wLqHKcpIoy'));?></span><label for="stop_form"><?php echo(subscribers_disable_subscription);?></label>
									<input type="checkbox" class="ionc" name="stop_form" id="stop_form" value="YES"<?php echo(formSelector($opDraftRs['subscription_stop'],1,2));?>>
								</div>	
								<?php if($opDraftRs['isDraft']==0 && $opDraftRs['isSystem']!=1){?>
								<div class="form-group">
									<span><?php echo(sh('YfZ5D0jvh9'));?></span><label for="del"><?php echo(letheglobal_delete);?></label>
									<input type="checkbox" class="ionc" name="del" id="del" value="YES" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>">
								</div>
								<?php }?>
								<div class="form-group">
									<div id="formSetting0"></div>
									<button type="button" class="btn btn-danger" name="saveFormSettings0" id="saveFormSettings0"><?php echo(subscribers_save_settings);?></button>
								</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-12">
								<h3><?php echo(' <small>'.sh('9ymNZp7Wgq').'</small>'.subscribers_form_fields);?><small> <a href="javascript:;" data-target=".addfield-box" class="toggler"><span class="glyphicon glyphicon-chevron-up"></span></a></small></h3><hr>
								<div class="addfield-box">
									<div class="form-group">
										<button type="button" class="btn btn-warning fancybox-field"><?php echo(subscribers_add_field);?></button>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<h3><?php echo(' <small>'.sh('mIVBSD29Z1').'</small>'.subscribers_placement);?><small> <a href="javascript:;" data-target=".placement-box" class="toggler"><span class="glyphicon glyphicon-chevron-up"></span></a></small></h3><hr>
								<div class="placement-box">
									<div class="form-group">
									<div id="sortable-container">
									
									</div>
									</div>
									<div class="form-group">
										<button type="button" class="btn btn-info sorting-save-button" name="saveFormOrder" data-sorting-pg="modules/lethe.subscribers/act.xmlhttp.php?pos=savefieldorders&ID=<?php echo($opDraftRs['ID']);?>" data-sorting-succ="<?php echo(letheglobal_saved);?>" data-sorting-err="<?php echo(letheglobal_error_occured);?>" data-sorting-proc="<?php echo(letheglobal_updating);?>"><?php echo(subscribers_save_placement);?></button>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<h3><?php echo(subscribers_embed_code.' <small>'.sh('OcDzKawmRC').'</small>');?></h3><hr>
								<div class="form-group" id="LetheFormCode">
								
								</div>
								<div class="form-group">
									<button type="button" class="btn btn-warning fancybox" data-fancybox-type="ajax" data-fancybox-href="modules/lethe.subscribers/act.xmlhttp.php?pos=generateCode&ID=<?php echo($opDraftRs['ID']);?>&preview=true" name="previewForm" id="previewForm"><?php echo(letheglobal_preview);?></button> <button type="button" class="btn btn-success" name="generateForm" id="generateForm"><?php echo(subscribers_generate_code);?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<script>			
					$(document).ready(function(){
						/* Fancybox Fielder */
						$(".fancybox-field").fancybox({
						
							type: "ajax",
							href: "modules/lethe.subscribers/act.xmlhttp.php?pos=fieldcreator&ID=<?php echo($opDraftRs['ID']);?>",
							autoSize: false
						
						});
					
						/* Save Settings */
						$("#saveFormSettings0").click(function(){
							var but = $(this);
							$(this).attr('disabled',true);
							$(this).append(' <span class="spin glyphicon glyphicon-refresh"></span>');
							$.ajax({
							url: 'modules/lethe.subscribers/act.xmlhttp.php?pos=savesettings0&ID=<?php echo($opDraftRs['ID']);?>',
							type: 'POST',
							data: $("#formSettings input, #formSettings select").serialize(),
							success: function(data){
									but.attr('disabled',false);
									but.find("span").remove("span");
									$("#formSetting0").html(data);
								}
							});
						});
						
						/* Generate Code */
						$("#generateForm").click(function(){
							$.ajax({
							url: 'modules/lethe.subscribers/act.xmlhttp.php?pos=generateCode&ID=<?php echo($opDraftRs['ID']);?>',
							type: 'POST',
							success: function(data){
									$("#LetheFormCode").html(data);
								}
							});
						});
										
						getAjax('#sortable-container','modules/lethe.subscribers/act.xmlhttp.php?pos=fieldorders&ID=<?php echo($opDraftRs['ID']);?>',"<span class=\"spin glyphicon glyphicon-refresh\"></span>");
					});
				</script>
			<?php } $opDraft->free();?>
		</div>
		<?php } if($fm==1 || $page_sub2!='edit'){?>
		<div role="tabpanel" class="tab-pane" id="s-link">
			<!-- API -->
			&nbsp;
			<form name="apiBuilder" id="apiBuilder" action="" method="POST">
				<div class="row">
					<div class="col-md-4">
						<h4><?php echo(subscribers_api_action);?></h4><hr>
						<div class="form-group">
							<label for="api_action"><?php echo(sh('xffNuWe8TF').subscribers_api_action);?></label>
							<select class="form-control autoWidth" id="api_action" name="api_action">
								<option value=""><?php echo(letheglobal_choose);?></option>
								<option value="add"><?php echo(letheglobal_add);?></option>
								<option value="remove"><?php echo(letheglobal_remove);?></option>
								<option value="check"><?php echo(letheglobal_check);?></option>
								<option value="toblacklist"><?php echo(subscribers_add_to_blacklist);?></option>
								<option value="moveto"><?php echo(subscribers_move_to);?></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<h4><?php echo(subscribers_api_data);?></h4><hr>
						<div id="api_act_gen">

						</div>
					</div>
					<div class="col-md-4">
						<h4><?php echo(subscribers_api_handler);?></h4><hr>
						<div class="form-group">
							<label for="api_link"><?php echo(sh('CJ6zfITODb').subscribers_api_link);?></label>
							<textarea class="form-control" name="api_link" id="api_link" onclick="this.select();" rows="7" readonly></textarea>
						</div>
						<div class="form-group">
							<button type="button" name="genAPILink" id="genAPILink" class="btn btn-warning"><span class="glyphicon glyphicon-refresh"></span></button>
						</div>
					</div>
				</div>
			</form>
			<script>
				$(document).ready(function(){
					$("#api_action").change(function(){
						if($(this).val()!=''){
							$("#api_link").val('');
							getAjax('#api_act_gen','modules/lethe.subscribers/act.xmlhttp.php?pos=apiactions&pos2='+ $(this).val() +'','<span class="spin glyphicon glyphicon-refresh">');
						}
					});
					
					
				});
			</script>
		</div>
		<?php }?>
	  </div>

	</div>
	
<?php if($fm!=0 && $page_sub2=='edit'){
	echo(errMod('Invalid Request!','danger'));
}else{
?>
<script>
	$(document).ready(function(){
		
		/* Draft Maker */
		$(".draftMaker").click(function(){
			var draftMod = $(this).data('draft-mod');
			$.ajax({
			url: 'modules/lethe.subscribers/act.xmlhttp.php?pos=createdraft&draftPos='+draftMod,
			success: function(data){
					$(".draftMod"+draftMod).html(data);
				}
			});
			
		/* Page Loads */

		});
		
	});
</script>
<?php }}?>
<!-- Forms Add End -->
<?php } # Subs End?>