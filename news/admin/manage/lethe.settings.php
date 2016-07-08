<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 01.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
if(!isset($pgnt)){die('You are not authorized to view this page!');}

/* Demo Check */
if(!isDemo('saveSets')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

/* Save Settings */
if(isset($_POST['saveSets'])){
	
	$letheSets = new lethe();
	$letheSets->letheSettings();
	$errText = $letheSets->errPrint;
	
}

echo($errText);
?>

<form name="genSets" id="genSets" action="" method="POST">
<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(letheglobal_general_settings);?></a></li>
    <li role="presentation"><a href="#helpers" aria-controls="helpers" role="tab" data-toggle="tab"><?php echo(settings_helpers);?></a></li>
    <li role="presentation"><a href="#save" aria-controls="save" role="tab" data-toggle="tab"><?php echo(letheglobal_save);?></a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
	<!-- GENERAL -->
    <div role="tabpanel" class="tab-pane fade in active" id="general">
		&nbsp;
			<div class="form-group">
				<label for="lethe_default_lang"><?php echo(sh('ZlPryzmM0A').settings_default_language);?></label>
				<select name="lethe_default_lang" id="lethe_default_lang" class="form-control autoWidth">
					<?php foreach($SLNG_LIST as $k=>$v){
						echo('<option value="'. $k .'"'. formSelector($k,lethe_default_lang,0) .'>'. showIn($v['sname'],'page') .'</option>');
					}?>
				</select>
			</div>
			<div class="form-group">
				<label for="lethe_default_timezone"><?php echo(sh('Y3lrxevM75').settings_default_timezone);?></label>
				<select name="lethe_default_timezone" id="lethe_default_timezone" class="form-control autoWidth">
					<?php 
					$tzones = timezone_list();
					foreach($tzones as $k=>$v){echo('<option value="'. $k .'"'. formSelector($k,lethe_default_timezone,0) .'>'. showIn($v,'page') .'</option>');}?>
				</select>
			</div>
			<div class="form-group">
				<label for="lethe_theme"><?php echo(sh('1vngRNdgmk').settings_default_theme);?></label>
				<select name="lethe_theme" id="lethe_theme" class="form-control autoWidth">
					<?php 
					foreach($LETHE_THEME_LIST as $k=>$v){echo('<option value="'. $k .'"'. formSelector(lethe_theme,$k,0) .'>'. $v .'</option>');}?>
				</select>
			</div>
			<div class="form-group">
				<label for="lethe_root_url"><?php echo(sh('pX6gY14gOl'));?>Lethe URL</label>
				<input type="url" value="<?php echo(showIn(lethe_root_url,'input'));?>" name="lethe_root_url" id="lethe_root_url" class="form-control autoWidth" size="50" placeholder="http://www.example.com/lethe/">
				<span class="help-block"><small><?php echo(settings_change_if_its_incorrect);?> e.g. http://www.example.com/lethe/</small></span>
			</div>
			<div class="form-group">
				<label for="lethe_admin_url"><?php echo(sh('GAYM2EmrXQ'));?>Lethe Admin URL</label>
				<input type="url" name="lethe_admin_url" id="lethe_admin_url" value="<?php echo(showIn(lethe_admin_url,'input'));?>" class="form-control autoWidth" size="50" placeholder="http://www.example.com/lethe/admin/">
				<span class="help-block"><small><?php echo(settings_change_if_its_incorrect);?> e.g. http://www.example.com/lethe/admin/</small></span>
			</div>
			<div class="form-group">
				<label for="lethe_save_tree_on"><?php echo(sh('maz8jKjgpO').settings_save_tree_on);?></label>
				<div>
				<input type="checkbox" name="lethe_save_tree_on" id="lethe_save_tree_on" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector(lethe_save_tree_on,1,1));?>>
				</div>
			</div>
			<div class="form-group">
				<label for="lethe_save_tree_text"><?php echo(sh('6mxg6vGg4n').settings_save_tree_text);?></label>
				<textarea name="lethe_save_tree_text" id="lethe_save_tree_text" class="form-control autoWidth"><?php echo(showIn(lethe_save_tree,'page'));?></textarea>
			</div>
			<div class="form-group">
				<label for="lethe_google_recaptcha_public"><?php echo(sh('KX1M7K1MmV').'Google reCaptcha '.settings_public_key);?></label>
				<input type="text" name="lethe_google_recaptcha_public" id="lethe_google_recaptcha_public" value="<?php echo(((DEMO_MODE) ? 'DEMO MODE':showIn(lethe_google_recaptcha_public,'input')));?>" class="form-control autoWidth" size="50">
			</div>
			<div class="form-group">
				<label for="lethe_google_recaptcha_private"><?php echo(sh('KX1M7K1MmV').'Google reCaptcha '.settings_private_key);?></label>
				<input type="text" name="lethe_google_recaptcha_private" id="lethe_google_recaptcha_private" value="<?php echo(((DEMO_MODE) ? 'DEMO MODE':showIn(lethe_google_recaptcha_private,'input')));?>" class="form-control autoWidth" size="50">
			</div>
			<div class="form-group">
				<label for="lethe_license_key"><?php echo(sh('VPGMkzEra7').settings_license_key);?></label>
				<input type="text" name="lethe_license_key" id="lethe_license_key" value="<?php echo(((DEMO_MODE) ? 'DEMO MODE':showIn(lethe_license_key,'input')));?>" class="form-control autoWidth" size="50">
			</div>
	</div>
	<!-- HELPERS -->
    <div role="tabpanel" class="tab-pane fade" id="helpers">
	&nbsp;
			<div class="form-group">
				<label for="lethe_debug_mode"><?php echo(sh('ZVKMZNoMLA').settings_debug_mode);?></label>
				<div>
				<input type="checkbox" name="lethe_debug_mode" id="lethe_debug_mode" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector(lethe_debug_mode,1,1));?>>
				</div>
			</div>
			<div class="form-group">
				<label for="lethe_system_notices"><?php echo(sh('2WzrLvx8m4').settings_system_notices);?></label>
				<div>
				<input type="checkbox" name="lethe_system_notices" id="lethe_system_notices" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector(lethe_system_notices,1,1));?>>
				</div>
			</div>
			<div class="form-group">
				<label for="lethe_sidera_helper"><?php echo(sh('PzWM4K4rqx'));?>Pointips</label>
				<div>
				<input type="checkbox" name="lethe_sidera_helper" id="lethe_sidera_helper" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector(lethe_sidera_helper,1,1));?>>
				</div>
			</div>
	</div>
    <div role="tabpanel" class="tab-pane fade" id="save">
	&nbsp;
	<div class="form-group">
		<button type="submit" name="saveSets" id="saveSets" class="btn btn-primary"><?php echo(letheglobal_save);?></button>
	</div>
	</div>
  </div>

</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		/* Change Theme */
		$("#lethe_theme").on('change',function(){
			var selTheme = $(this).val();
			  $(".getTheme").html('<link type="text/css" rel="stylesheet" href="bootstrap/dist/css/'+ selTheme +'_bootstrap.min.css"></link>');
		});
	});
</script>
