<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                	<?php echo $lang['cp_user_update']; ?>
                </h4>
            </div>
            <div class="modal-body">
            	<form class="row" method="POST">
                	<!-- User name -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Name_and_surname']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="user_nice_name" value="<?php echo $query2['user_nice_name']; ?>" />
                        </div>
                    </div> 
                	<!-- User name -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Email']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="user_email" value="<?php echo $query2['user_email']; ?>" />
                        </div>
                    </div>  
                	<!-- User order address -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['cp_order_address']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="user_delivery_address" value="<?php echo $query2['user_delivery_address']; ?>" />
                        </div>
                    </div>   
                	<!-- User phone -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Phone_number']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="user_phone" value="<?php echo $query2['user_phone']; ?>" />
                        </div>
                    </div>    
                	<!-- User phone -->
                    <div class="row margin_bottom_10">
                        <div class="row">
							<input type="submit" class="btn btn-success form-control" value="<?php echo $lang['update']; ?>" name="submit_edit_profile" />
                        </div>
                    </div> 
            	</form>
            </div>
        </div>
    </div>
</div>