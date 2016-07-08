<div class="modal fade" id="modal_<?php echo $query_event['event_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?php echo $query_event['event_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <?php echo $lang['cp_table_update'] . $query_event['event_name']; ?>
                    <?php echo ' (ID: ' .$query_event['event_id'] . ')'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="row">
                    <!-- Event ID -->
                    <input type="hidden" name="update_event_id" value="<?php echo $query_event['event_id']; ?>" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                    <!-- Event name -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['cp_event_name']; ?></label>
                        <div class="row">
                            <input type="text" class="form-control" name="update_event_name" value="<?php echo $query_event['event_name']; ?>" />
                        </div>
                    </div> 
                    <!-- Event location -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['cp_event_location']; ?></label>
                        <div class="row">
                            <input class="form-control" type="text" name="update_event_location" value="<?php echo $query_event['event_location']; ?>" />
                        </div>
                    </div> 
                    <!-- Event date -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['cp_event_date']; ?></label>
                        <div class="row">
                            <input class="form-control" type="text" name="update_event_date" value="<?php echo $query_event['event_date']; ?>" />
                        </div>
                    </div> 
                    <!-- Event description -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['cp_event_description']; ?></label>
                        <div class="row">
                            <textarea class="form-control" name="update_event_description"><?php echo $query_event['event_description']; ?></textarea>
                        </div>
                    </div>
                    <!-- Food thumbnail -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['change_image']; ?></label>
                        <div class="row">
                            <div class="inputs_holder">
                                <input class="form-control" type="file" name="update_event_thumbnail" value="<?php echo $query_event['event_thumbnail']; ?>" />
                                <div class="fake_input_holder">
                                    <input type="button" value="<?php echo $lang['cp_food_picture']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>  
                    <!-- Submit button -->
                    <div class="row">
                        <div class="row">
                            <input type="submit" class="btn btn-success form-control" value="<?php echo $lang['update']; ?>" name="update_submit_event" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>