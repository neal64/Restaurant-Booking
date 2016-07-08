<div class="modal fade" id="modal_<?php echo $query_userlist2['menu_item_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?php echo $query_userlist2['menu_item_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                	<?php echo $lang['cp_table_update'] . $query_userlist2['menu_item_name']; ?>
                	<?php echo ' (ID: ' .$query_userlist2['menu_item_id'] . ')'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="row">
					<!-- Food ID -->
					<input type="hidden" name="menu_item_id" value="<?php echo $query_userlist2['menu_item_id']; ?>" />
					<input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                	<!-- Food name -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['cp_food_name']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="menu_item_name" value="<?php echo $query_userlist2['menu_item_name']; ?>" />
                        </div>
                    </div> 
                	<!-- Food details -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Details']; ?></label>
                        <div class="row">
                        	<textarea class="form-control" name="menu_item_details"><?php echo $query_userlist2['menu_item_details']; ?></textarea>
                        </div>
                    </div> 
                	<!-- Food details -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['Price']; ?></label>
                        <div class="row">
							<input class="form-control" type="text" name="menu_item_price_per_slice" value="<?php echo $query_userlist2['menu_item_price_per_slice']; ?>" />
                        </div>
                    </div>   
                	<!-- Food thumbnail -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['change_image']; ?></label>
                        <div class="row">
							<div class="inputs_holder">
								<input class="form-control" type="file" name="menu_preview_image" value="<?php echo $query_userlist2['menu_preview_image']; ?>" />
								<div class="fake_input_holder">
						            <input type="button" value="<?php echo $lang['cp_food_picture']; ?>">
						        </div>
					        </div>
                        </div>
                    </div>  
                	<!-- Submit button -->
                    <div class="row">
                        <div class="row">
							<input type="submit" class="btn btn-success form-control" value="<?php echo $lang['update']; ?>" name="submit_edit_menu" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>