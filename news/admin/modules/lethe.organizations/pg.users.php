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
if(!isDemo('addUser,editUser')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

if(LETHE_AUTH_MODE!=2){$OID=LETHE_AUTH_ORG_ID;}else{$OID=set_org_id;}

/* Permission List Loads */
foreach($lethe_modules as $k=>$v){
	$pg = str_replace('?p=','',$v['page']);
	$LETHE_PERMISSIONS_LIST[$pg] = $v['title'];
	foreach($v['contents'] as $a=>$b){
		$pg = str_replace('?p=','',$b['page']);
		$LETHE_PERMISSIONS_LIST[$pg] = showIn($v['title'],'input') . ' &gt; ' . showIn($b['title'],'input');
	}
}
ksort($LETHE_PERMISSIONS_LIST);

if(isset($_POST['addUser'])){ # New User

	$myLethe = new lethe();
	$myLethe->OID = set_org_id;
	$myLethe->isMaster = 0;
	$myLethe->addUser();
	$errText = $myLethe->errPrint;

}

if(isset($_POST['editUser'])){ # Edit User

	$myLethe = new lethe();
	$myLethe->OID = set_org_id;
	$myLethe->UID = $ID;
	$myLethe->isMaster = 0;
	$myLethe->editUser();
	$errText = $myLethe->errPrint;

}

$pg_nav_buts = '<div class="nav-buts">
				<a href="?p=organizations/users/add" class="btn btn-success">'. letheglobal_add .'</a>
				<a href="?p=organizations/users" class="btn btn-primary">'. letheglobal_list .'</a>
				</div>
				';
?>

<?php if($page_sub2==''){ #List
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. organizations_users .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
?>
<!-- User List Start -->
		<table class="footable table">
			<thead>
				<tr>
					<th><?php echo(organizations_users);?></th>
					<th data-hide="phone,tablet"><?php echo(organizations_organization);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_created);?></th>
					<th data-hide="phone"><?php echo(letheglobal_last_login);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_e_mail);?></th>
					<th><?php echo(letheglobal_active);?></th>
					<th data-hide="phone"><?php echo(letheglobal_primary);?></th>
				</tr>
			</thead>
			<tbody>
			<?php $opUsers = $myconn->query("SELECT * FROM ". db_table_pref ."users WHERE auth_mode<>2 AND OID=". set_org_id ." ORDER BY isPrimary DESC,real_name ASC") or die(mysqli_error($myconn));
			while($opUsersRs = $opUsers->fetch_assoc()){
			?>
				<tr>
					<td><a href="?p=organizations/users/edit&amp;ID=<?php echo($opUsersRs['ID']);?>"><?php echo(showIn($opUsersRs['real_name'],'page'));?></a></th>
					<td><?php echo(showIn(set_org_name,'page'));?></td>
					<td><?php echo(setMyDate($opUsersRs['add_date'],2));?></td>
					<td><?php echo(setMydate($opUsersRs['last_login'],2));?></td>
					<td><?php echo(showIn($opUsersRs['mail'],'page'));?></td>
					<td data-value="<?php echo($opUsersRs['isActive']);?>"><?php echo(getBullets($opUsersRs['isActive']));?></td>
					<td data-value="<?php echo($opUsersRs['isPrimary']);?>"><?php echo(getBullets($opUsersRs['isPrimary']));?></td>
				</tr>
			<?php } $opUsers->free();?>
			</tbody>
		</table>
			
		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
			});
		</script>
<!-- User List End -->
<?php }else if($page_sub2=='add'){ #Add
		$sourceLimit = calcSource(set_org_id,'users');
		if(!limitBlock($sourceLimit,set_org_max_user)){$lethe->isSuccess=1;}
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. organizations_add_user .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
		echo('<div class="row">
				<div class="col-md-3"><div class="form-group"><label>'. sh('pRP9MnRKno') . letheglobal_limits .'</label><span class="clearfix"></span>'. getMyLimits($sourceLimit,set_org_max_user) .'</div></div>
			   </div>');
?>
<!-- User Add Start -->
<?php if(!isset($lethe->isSuccess) || $lethe->isSuccess==0){?>
<form action="" method="POST" onsubmit="listbox_selectall('perm-sel-list', true)">
	<div role="tabpanel">

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(organizations_general);?></a></li>
		<li role="presentation"><a href="#limits" aria-controls="limits" role="tab" data-toggle="tab"><?php echo(organizations_limits);?></a></li>
		<li role="presentation"><a href="#save" aria-controls="save" role="tab" data-toggle="tab"><?php echo(letheglobal_save);?></a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<!-- GENERAL -->
		<div role="tabpanel" class="tab-pane fade in active" id="general">
			&nbsp;
			<div class="form-group">
				<label for="usr_name"><?php echo(sh('cR6FGFiiTW').organizations_name);?></label>
				<input type="text" class="form-control autoWidth" id="usr_name" name="usr_name" size="40" value="<?php echo((isseter('usr_name')) ? showIn($_POST['usr_name'],'input'):'');?>">
			</div>
			<div class="form-group">
				<label for="usr_mail"><?php echo(sh('Ekp38ddLLh').organizations_e_mail);?></label>
				<input type="email" class="form-control autoWidth" id="usr_mail" name="usr_mail" size="40" value="<?php echo((isseter('usr_mail')) ? showIn($_POST['usr_mail'],'input'):'');?>">
			</div>
			<div class="form-group">
				<label for="usr_pass"><?php echo(sh('r4Xr11XyDs').organizations_password);?></label>
				<input type="password" class="form-control autoWidth" id="usr_pass" name="usr_pass" size="20" value="" autocomplete="OFF">
			</div>
			<div class="form-group">
				<label for="usr_pass2"><?php echo(sh('TOGetPSb63').organizations_re_type);?></label>
				<input type="password" class="form-control autoWidth" id="usr_pass2" name="usr_pass2" size="20" value="" autocomplete="OFF">
			</div>
			<div class="form-group">
				<label for="user_auth_mode"><?php echo(sh('5hzSlnNDyE').organizations_management_type);?></label>
				<select class="form-control autoWidth" name="user_auth_mode" id="user_auth_mode">
					<?php foreach($LETHE_MANAGEMENT_TYPE as $k=>$v){
						if($k!=2){
							echo('<option value="'. $k .'"'. ((isseter('user_auth_mode',1,0)) ? formSelector((int)$_POST['user_auth_mode'],$k,0):'') .'>'. $v .'</option>');
						}
					}?>
				</select>
			</div>
		
		</div>
		<!-- LIMITS -->
		<div role="tabpanel" class="tab-pane fade" id="limits">
			&nbsp;
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label class="text-danger" for="perm-all-list"><?php echo(sh('KLR4WZCTia').organizations_pages);?></label><hr>
						<select name="perm-all-list" id="perm-all-list" class="form-control" multiple>
							<?php foreach($LETHE_PERMISSIONS_LIST as $k=>$v){
								echo('<option value="'. $k .'">'. $v .'</option>');
							}?>
						</select>
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
						<label for="add-to-list"><?php echo(letheglobal_move);?></label><hr>
						<button onclick="listbox_moveacross('perm-all-list', 'perm-sel-list');" type="button" class="btn btn-default" id="add-to-list"><span class="glyphicon glyphicon-chevron-right"></span></button>
						<button onclick="listbox_moveacross('perm-sel-list','perm-all-list');" type="button" class="btn btn-default" id="del-from-list"><span class="glyphicon glyphicon-chevron-left"></span></button>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="text-success" for="perm-sel-list"><?php echo(sh('i1iGeBHqqP').organizations_allowed_pages);?></label><hr>
						<select name="perm-sel-list[]" id="perm-sel-list" class="form-control" multiple>
						
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- SAVE -->
		<div role="tabpanel" class="tab-pane fade" id="save">
			&nbsp;
			<div class="form-group">
				<button name="addUser" id="addUser" class="btn btn-primary" type="submit"><?php echo(letheglobal_save);?></button>
			</div>
		</div>
	  </div>

	</div>
</form>
<?php } #Success End?>
<!-- Organization Add End -->
<?php }else if($page_sub2=='edit'){ #Edit?>
<!-- Organization Edit Start -->
<?php $opOrg = $myconn->prepare("SELECT * FROM ". db_table_pref ."users WHERE ID=? AND OID=". set_org_id ."") or die(mysqli_error($myconn));
			$opOrg->bind_param('s',$ID);
			$opOrg->execute();
			$opOrg->store_result();
			if($opOrg->num_rows==0){
				echo errMod('* '. letheglobal_record_not_found .'','danger');
			}else{
				$sr = new Statement_Result($opOrg);
				$opOrg->fetch();
				
		echo('<h1>'. organizations_organization .'<span class="help-block"><span class="text-primary">'. organizations_edit_user .'</span></span></h1><hr>'.
			  $errText
			 );
?>
<?php if(!isset($lethe->isSuccess) || $lethe->isSuccess==0){
$permPages = array();
$opPerList = $myconn->query("SELECT * FROM ". db_table_pref ."user_permissions WHERE UID=". $sr->Get('ID') ."") or die(mysqli_error($myconn));
while($opPerListRs = $opPerList->fetch_assoc()){
	$permPages[$opPerListRs['perm']] = '';
}$opPerList->free();
?>
<form action="" method="POST"  onsubmit="listbox_selectall('perm-sel-list', true);listbox_selectall('perm-all-list', true)">
	<div role="tabpanel">

	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(organizations_general);?></a></li>
		<li role="presentation"><a href="#limits" aria-controls="limits" role="tab" data-toggle="tab"><?php echo(organizations_limits);?></a></li>
		<li role="presentation"><a href="#save" aria-controls="save" role="tab" data-toggle="tab"><?php echo(letheglobal_save);?></a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<!-- GENERAL -->
		<div role="tabpanel" class="tab-pane fade in active" id="general">
			&nbsp;
			<div class="form-group">
				<label for="usr_name"><?php echo(sh('cR6FGFiiTW').organizations_name);?></label>
				<input type="text" class="form-control autoWidth" id="usr_name" name="usr_name" size="40" value="<?php echo(showIn($sr->Get('real_name'),'input'));?>">
			</div>
			<div class="form-group">
				<label for="usr_mail"><?php echo(sh('Ekp38ddLLh').organizations_e_mail);?></label>
				<input type="email" class="form-control autoWidth" id="usr_mail" name="usr_mail" size="40" value="<?php echo(showIn($sr->Get('mail'),'input'));?>">
			</div>
			<div class="form-group">
				<label for="usr_pass"><?php echo(sh('r4Xr11XyDs').organizations_password);?></label>
				<input type="password" class="form-control autoWidth" id="usr_pass" name="usr_pass" size="20" value="" autocomplete="OFF">
			</div>
			<div class="form-group">
				<label for="usr_pass2"><?php echo(sh('TOGetPSb63').organizations_re_type);?></label>
				<input type="password" class="form-control autoWidth" id="usr_pass2" name="usr_pass2" size="20" value="" autocomplete="OFF">
			</div>
			<div class="form-group">
				<label for="user_auth_mode"><?php echo(sh('5hzSlnNDyE').organizations_management_type);?></label>
				<select class="form-control autoWidth" name="user_auth_mode" id="user_auth_mode">
					<?php foreach($LETHE_MANAGEMENT_TYPE as $k=>$v){
						if($k!=2){
							echo('<option value="'. $k .'"'. formSelector($sr->Get('auth_mode'),$k,0) .'>'. $v .'</option>');
						}
					}?>
				</select>
			</div>
		
		</div>
		<!-- LIMITS -->
		<div role="tabpanel" class="tab-pane fade" id="limits">
			&nbsp;
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label class="text-danger" for="perm-all-list"><?php echo(sh('KLR4WZCTia').organizations_pages);?></label><hr>
						<select name="perm-all-list[]" id="perm-all-list" class="form-control" multiple>
							<?php foreach($LETHE_PERMISSIONS_LIST as $k=>$v){
								echo(((!array_key_exists($k,$permPages)) ? '<option value="'. $k .'">'. $v .'</option>':''));
							}?>
						</select>
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
						<label for="add-to-list"><?php echo(letheglobal_move);?></label><hr>
						<button onclick="listbox_moveacross('perm-all-list', 'perm-sel-list');" type="button" class="btn btn-default" id="add-to-list"><span class="glyphicon glyphicon-chevron-right"></span></button>
						<button onclick="listbox_moveacross('perm-sel-list','perm-all-list');" type="button" class="btn btn-default" id="del-from-list"><span class="glyphicon glyphicon-chevron-left"></span></button>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="text-success" for="perm-sel-list"><?php echo(sh('i1iGeBHqqP').organizations_allowed_pages);?></label><hr>
						<select name="perm-sel-list[]" id="perm-sel-list" class="form-control" multiple>
							<?php foreach($LETHE_PERMISSIONS_LIST as $k=>$v){
								echo(((array_key_exists($k,$permPages)) ? '<option value="'. $k .'">'. $v .'</option>':''));
							}?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- SAVE -->
		<div role="tabpanel" class="tab-pane fade" id="save">
			&nbsp;
			<div class="form-group">
				<span><?php echo(sh('wf9sOx76al'));?></span><label for="active"><?php echo(letheglobal_active);?></label>
				<input type="checkbox" name="active" id="active" value="YES" class="ionc"<?php echo(formSelector($sr->Get('isActive'),1,1));?>>
			</div>
			<div class="form-group">
				<span><?php echo(sh('YysgHL1jSw'));?></span><label for="user_spec_view"><?php echo(organizations_user_can_see_only_their_own_records);?></label>
				<input type="checkbox" name="user_spec_view" id="user_spec_view" value="YES" class="ionc"<?php echo(formSelector($sr->Get('user_spec_view'),1,1));?>>
			</div>
			<div class="form-group">
				<label for="del"><?php echo(letheglobal_delete);?></label>
				<input type="checkbox" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>" name="del" id="del" value="YES" class="ionc">
			</div>
			<div class="form-group">
				<button name="editUser" id="editUser" class="btn btn-primary" type="submit"><?php echo(letheglobal_save);?></button>
			</div>
		</div>
	  </div>

	</div>
</form>
<?php } #Success End?>
<?php } # Org End?>
<!-- Organization Edit End -->
<?php } #Page Subs End?>