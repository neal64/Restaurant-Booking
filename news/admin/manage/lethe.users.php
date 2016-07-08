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
if(!isDemo('addUser,editUser')){$errText = errMod(letheglobal_demo_mode_active,'danger');}
$ID = ((!isset($_GET['ID']) || intval($_GET['ID'])==0) ? 0:intval($_GET['ID']));
/* Actions */
if(isset($_POST['addUser'])){ # New User

	$myLethe = new lethe();
	$myLethe->OID = 0;
	$myLethe->isMaster = 1;
	$myLethe->auth_mode = 2;
	$myLethe->addUser();
	$errText = $myLethe->errPrint;

}

if(isset($_POST['editUser'])){ # Edit User

	$myLethe = new lethe();
	$myLethe->OID = 0;
	$myLethe->UID = $ID;
	$myLethe->isMaster = 1;
	$myLethe->auth_mode = 2;
	$myLethe->editUser();
	$errText = $myLethe->errPrint;

}
?>
		<?php if($page_sub2=='add'){
				echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. settings_add_user .'</span></span></h1><hr>'.
					  $pg_nav_buts.
					  $errText
					 );
		?>
			<!-- ADD USER START -->
			<form name="addNewUser" method="POST" action="">
				<div class="form-group">
					<label for="usr_name"><?php echo(sh('IiIMsL5qIW').letheglobal_name);?></label>
					<input type="text" name="usr_name" id="usr_name" value="<?php echo(((isset($_POST['usr_name'])) ? showIn($_POST['usr_name'],'input'):''))?>" class="form-control autoWidth">
				</div>
				<div class="form-group">
					<label for="usr_mail"><?php echo(sh('v21Akj0TAh').letheglobal_e_mail);?></label>
					<input type="email" name="usr_mail" id="usr_mail" value="<?php echo(((isset($_POST['usr_mail'])) ? showIn($_POST['usr_mail'],'input'):''))?>" class="form-control autoWidth">
				</div>
				<div class="form-group">
					<label for="usr_pass"><?php echo(sh('XLjyd6v62s').letheglobal_password);?></label>
					<input type="password" name="usr_pass" id="usr_pass" value="" class="form-control autoWidth" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="usr_pass2"><?php echo(sh('9fSmVUpiv3').letheglobal_type_it_again);?></label>
					<input type="password" name="usr_pass2" id="usr_pass2" value="" class="form-control autoWidth" autocomplete="off">
				</div>
				<div class="form-group">
					<button type="submit" name="addUser" class="btn btn-success"><?php echo(letheglobal_save);?></button>
				</div>
			</form>
			<!-- ADD USER END -->
		<?php }else if($page_sub2=='edit'){
				echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. settings_edit_user .'</span></span></h1><hr>'.$pg_nav_buts);
				
				$opUser = $myconn->query("SELECT * FROM ". db_table_pref ."users WHERE ID=". $ID ."") or die(mysqli_error($myconn));
				if(mysqli_num_rows($opUser)==0){echo(errMod(letheglobal_record_not_found.'!','danger'));}else{
				$opUserRs = $opUser->fetch_assoc();
		?>
			<!-- EDIT USER START -->
			<form name="editCurrUser" method="POST" action="">
				<div class="form-group">
					<label for="usr_name"><?php echo(sh('IiIMsL5qIW').letheglobal_name);?></label>
					<input type="text" name="usr_name" id="usr_name" value="<?php echo(showIn($opUserRs['real_name'],'input'))?>" class="form-control autoWidth">
				</div>
				<div class="form-group">
					<label for="usr_mail"><?php echo(sh('v21Akj0TAh').letheglobal_e_mail);?></label>
					<input type="email" name="usr_mail" id="usr_mail" value="<?php echo(showIn($opUserRs['mail'],'input'))?>" class="form-control autoWidth">
				</div>
				<div class="form-group">
					<label for="usr_pass"><?php echo(sh('XLjyd6v62s').letheglobal_password);?></label>
					<input type="password" name="usr_pass" id="usr_pass" value="" class="form-control autoWidth" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="usr_pass2"><?php echo(sh('9fSmVUpiv3').letheglobal_type_it_again);?></label>
					<input type="password" name="usr_pass2" id="usr_pass2" value="" class="form-control autoWidth" autocomplete="off">
				</div>
				<?php if(!$opUserRs['isPrimary']){?>
				<div class="form-group">
					<span><?php echo(sh('8Ldfvb0tGm'));?></span><label for="active"><?php echo(letheglobal_active);?></label>
					<input type="checkbox" name="active" id="active" value="YES" class="ionc"<?php echo(formSelector($opUserRs['isActive'],1,1));?>>
				</div>
				<div class="form-group">
					<label for="del"><?php echo(letheglobal_delete);?></label>
					<input type="checkbox" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>" name="del" id="del" value="YES" class="ionc">
				</div>
				<?php }?>
				<div class="form-group">
					<button type="submit" name="editUser" class="btn btn-success"><?php echo(letheglobal_save);?></button>
				</div>
			</form>
			<!-- EDIT USER END -->
			<?php } $opUser->free();?>
		<?php }else{
				echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. settings_users .'</span></span></h1><hr>'.$pg_nav_buts);
		?>
		<!-- USER LIST START -->
		<table class="footable table">
			<thead>
				<tr>
					<th><?php echo(settings_user);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_created);?></th>
					<th data-hide="phone"><?php echo(letheglobal_last_login);?></th>
					<th data-hide="phone,tablet"><?php echo(letheglobal_e_mail);?></th>
					<th><?php echo(letheglobal_active);?></th>
					<th data-hide="phone"><?php echo(letheglobal_primary);?></th>
				</tr>
			</thead>
			<tbody>
			<?php $opUsers = $myconn->query("SELECT * FROM ". db_table_pref ."users WHERE auth_mode=2 ORDER BY isPrimary DESC,real_name ASC") or die(mysqli_error($myconn));
			while($opUsersRs = $opUsers->fetch_assoc()){
			?>
				<tr>
					<td><a href="?p=settings/users/edit&amp;ID=<?php echo($opUsersRs['ID']);?>"><?php echo(showIn($opUsersRs['real_name'],'page'));?></a></th>
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
		<!-- USER LIST END -->

		<?php } # Child Sub End?>