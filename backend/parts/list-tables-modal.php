<div class="modal fade" id="modal_<?php echo $table['table_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?php echo $table['table_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                	<?php echo $lang['cp_table_update'] . $table['table_details']; ?>
                	<?php echo ' (ID: ' .$table['table_id'] . ')'; ?>
                </h4>
            </div>
            <div class="modal-body">
	            <form class="row" method="POST">
	            	<!-- Table ID -->
					<input class="col-md-6" type="hidden" name="table_id" value="<?php echo $table['table_id']; ?>" />
                    <!-- Table number -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['table_number']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="table_details" value="<?php echo $table['table_details']; ?>" />
                        </div>
                    </div>
                    <!-- Table position -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['table_position']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="table_position" value="<?php echo $table['table_position']; ?>" />
                        </div>
                    </div>
                    <!-- Table No of places at table -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['table_number_of_places']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="table_number_of_places" value="<?php echo $table['table_number_of_places']; ?>" />
                        </div>
                    </div>
                    <!-- Table CSS(left - in %): -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['table_css_position_left']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="table_css_position_left" value="<?php echo $table['table_css_position_left']; ?>" />
                        </div>
                    </div>
                    <!-- Table CSS(top - in %): -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['table_css_position_top']; ?></label>
                        <div class="row">
                        	<input type="text" class="form-control" name="table_css_position_top" value="<?php echo $table['table_css_position_top']; ?>" />
                        </div>
                    </div>
                    <!-- Table Submit BTN -->
                    <div class="row margin_bottom_10">
                        <div class="row">
                        	<input type="submit" class="btn btn-success" value="<?php echo $lang['update']; ?>" name="submit_edit_table" />
                        </div>
                    </div>
	            </form>
            </div>
        </div>
    </div>
</div>