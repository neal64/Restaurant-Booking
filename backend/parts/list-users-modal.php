<div class="modal fade" id="modal_<?php echo $query_userlist2['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?php echo $query_userlist2['user_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <?php echo $lang['cp_users_edit'] . $query_userlist2['user_nice_name']; ?>
                    <?php echo ' (ID: ' .$query_userlist2['user_id'] . ')'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <form method="POST" class="row">
                    <!-- User ID -->
                    <input class="col-md-6" type="hidden" name="user_id" value="<?php echo $query_userlist2['user_id']; ?>" />
                    <!-- User name -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Name_and_surname']; ?></label>
                        <div class="row">
                            <input type="text" class="form-control" name="user_nice_name" value="<?php echo $query_userlist2['user_nice_name']; ?>" />
                        </div>
                    </div> 
                    <!-- User email -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Email']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="user_email" value="<?php echo $query_userlist2['user_email']; ?>" />
                        </div>
                    </div> 
                    <!-- User phone -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Phone_number']; ?></label>
                        <div class="row">
                            <input class="form-control" type="text" name="user_phone" value="<?php echo $query_userlist2['user_phone']; ?>" />
                        </div>
                    </div>   
                    <!-- Submit button -->
                    <div class="row">
                        <div class="row">
                            <input type="submit" class="btn btn-success form-control" value="<?php echo $lang['update']; ?>" name="submit_edit_user" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>