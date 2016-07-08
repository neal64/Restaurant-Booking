<div class="modal fade" id="modal_<?php echo $query_userlist2['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?php echo $query_userlist2['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <?php echo "Edit User Ratting : " . $query_userlist2['user_nice_name']; ?>
                    <?php echo ' (ID: ' . $query_userlist2['id'] . ')'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <form method="POST" class="row">
                    <!-- User ID -->
                    <input class="col-md-6" type="hidden" name="id" value="<?php echo $query_userlist2['id']; ?>" />
                    <!-- User name -->
                    <div class="row margin_bottom_10">
                        <label class="row">Ratting</label>
                        <div class="row">
                            <style>
                                .rating { 
                                    border: none;
                                    float: left;
                                }
                                .rating > input { display: none; } 
                                .rating > label:before { 
                                    margin: 5px;
                                    font-size: 1.7em;
                                    font-family: FontAwesome;
                                    display: inline-block;
                                    content: "\f005";
                                }
                                .rating > .half:before { 
                                    content: "\f089";
                                    position: absolute;
                                }
                                .rating > label { 
                                    color: #DDD; 
                                    float: right; 
                                }
                                .rating > input:checked ~ label, 
                                .rating:not(:checked) > label:hover,  
                                .rating:not(:checked) > label:hover ~ label { color: #c79c60;  }

                                .rating > input:checked + label:hover, 
                                .rating > input:checked ~ label:hover,
                                .rating > label:hover ~ input:checked ~ label, 
                                .rating > input:checked ~ label:hover ~ label { color: #c79c60;  }    

                            </style>
                            
                            <fieldset class="rating" >
                                <input class="stars" type="radio" id="star5" name="rating" value="5" <?php if ($starNumber == '5') {
                                echo "checked";
                            } ?> />
                                <label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                <input class="stars" type="radio" id="star4half" name="rating" value="4.5" <?php if ($starNumber == '4.5') {
                                echo "checked";
                            } ?> />
                                <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
                                <input class="stars" type="radio" id="star4" name="rating" value="4" <?php if ($starNumber == '4') {
                                echo "checked";
                            } ?> />
                                <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                <input class="stars" type="radio" id="star3half" name="rating" value="3.5" <?php if ($starNumber == '3.5') {
                                echo "checked";
                            } ?> />
                                <label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                                <input class="stars" type="radio" id="star3" name="rating" value="3" <?php if ($starNumber == '3') {
                                echo "checked";
                            } ?> />
                                <label class = "full" for="star3" title="Meh - 3 stars"></label>
                                <input class="stars" type="radio" id="star2half" name="rating" value="2.5" <?php if ($starNumber == '2.5') {
                                echo "checked";
                            } ?> />
                                <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                                <input class="stars" type="radio" id="star2" name="rating" value="2" <?php if ($starNumber == '2') {
                                echo "checked";
                            } ?> />
                                <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                <input class="stars" type="radio" id="star1half" name="rating" value="1.5" <?php if ($starNumber == '1.5') {
                                echo "checked";
                            } ?> />
                                <label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                                <input class="stars" type="radio" id="star1" name="rating" value="1" <?php if ($starNumber == '1') {
                                echo "checked";
                            } ?> />
                                <label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                                <input class="stars" type="radio" id="starhalf" name="rating" value="0.5" <?php if ($starNumber == '0.5') {
                                echo "checked";
                            } ?> />
                                <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                            </fieldset>
                        </div>
                    </div> 
                    <!-- User email -->
                    <div class="row margin_bottom_10">
                        <label class="row">Review</label>
                        <div class="row">
                            <input type="text" class="form-control" name="review" value="<?php echo $query_userlist2['review']; ?>" />
                        </div>
                    </div>   
                    <!-- Submit button -->
                    <div class="row">
                        <div class="row">
                            <input type="submit" class="btn btn-success form-control" value="<?php echo $lang['update']; ?>" name="submit_edit_ratting" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>