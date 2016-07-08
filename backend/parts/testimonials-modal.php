<div class="modal fade" id="modal_<?php echo $query_testimonial['testimonial_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?php echo $query_testimonial['testimonial_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <?php echo $lang['cp_table_update'] . $query_testimonial['testimonal_client_name']; ?>
                    <?php echo ' (ID: ' .$query_testimonial['testimonial_id'] . ')'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="row">
                    <!-- ID -->
                    <input type="hidden" name="update_testimonial_id" value="<?php echo $query_testimonial['testimonial_id']; ?>" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                    <!-- Name -->
                    <div class="row margin_bottom_10">
                        <label class="row">Client</label>
                        <div class="row">
                            <input type="text" class="form-control" name="update_testimonal_client_name" value="<?php echo $query_testimonial['testimonal_client_name']; ?>" />
                        </div>
                    </div> 
                    <!-- JOB -->
                    <div class="row margin_bottom_10">
                        <label class="row">Client job</label>
                        <div class="row">
                            <input type="text" class="form-control" name="update_testimonial_client_job" value="<?php echo $query_testimonial['testimonial_client_job']; ?>" />
                        </div>
                    </div> 
                    <!-- Works at -->
                    <div class="row margin_bottom_10">
                        <label class="row">Client works at</label>
                        <div class="row">
                            <input type="text" class="form-control" name="update_testimonial_works_at" value="<?php echo $query_testimonial['testimonial_works_at']; ?>" />
                        </div>
                    </div> 
                    <!-- Description -->
                    <div class="row margin_bottom_10">
                        <label class="row">Content</label>
                        <div class="row">
                            <textarea class="form-control" name="update_testimonial_content"><?php echo $query_testimonial['testimonial_content']; ?></textarea>
                        </div>
                    </div>
                    <!-- Thumbnail -->
                    <div class="row margin_bottom_10">
                        <label class="row"><?php echo $lang['change_image']; ?></label>
                        <div class="row">
                            <div class="inputs_holder">
                                <input class="form-control" type="file" name="update_testimonial_thumb" value="<?php echo $query_testimonial['testimonial_thumb']; ?>" />
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