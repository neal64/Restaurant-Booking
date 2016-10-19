<?php
if (isset($_REQUEST['rr'])) {

    $rate = $_REQUEST['rating'];
    $review = $_REQUEST['review'];
    $date = date('d-m-Y h:i:s A');
    $uid = $_REQUEST['uid'];

    $query = mysqli_query($con, "SELECT * FROM ratting WHERE uid = '" . $uid . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
    $no = mysqli_num_rows($query);
    if ($no == 1) {
        $qq = $query_insert = mysqli_query($con, "update ratting set `ratting`='$rate',`review` = '$review' , `date`= '$date' where `uid`= '$uid' ") or die("Query Failed: " . mysqli_error($con));
    } else {
        $query_insert = mysqli_query($con, "insert into ratting(`ratting`,`uid`,`review`,`date`) values('$rate','$uid','$review','$date')") or trigger_error("Query Failed: " . mysqli_error($con));
    }
    //$rr = mysqli_fetch_array($query_tables);
}
$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
$user = mysqli_fetch_array($query);
$user_id = $user['user_id'];
$query = mysqli_query($con, "SELECT * FROM ratting WHERE uid = '" . $user_id . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
$rate = mysqli_fetch_array($query);
$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);

if(isset($_POST['contact_us'])){
    $contact_name       = $_POST['contact_name']; // this is your Email address
    $contact_message    = $_POST['contact_message']; // this is the sender's Email address
    $contact_subject    = $_POST['contact_subject'];
    $contact_email      = $_POST['contact_email'];
    $date               = date('d-m-Y h:i:s A');
    
    $query_insert = mysqli_query($con, "insert into contact_us(`name`,`message`,`subject`,`email`,`date`) values('$contact_name','$contact_message','$contact_subject','$contact_email','$date')") or trigger_error("Query Failed: " . mysqli_error($con));
}

//Open hours
$contact_monday_hours = $information['contact_monday_hours'];
$contact_tuesday_hours = $information['contact_tuesday_hours'];
$contact_wednesday_hours = $information['contact_wednesday_hours'];
$contact_thursday_hours = $information['contact_thursday_hours'];
$contact_friday_hours = $information['contact_friday_hours'];
$contact_saturday_hours = $information['contact_saturday_hours'];
$contact_sunday_hours = $information['contact_sunday_hours'];
$wysiwyg_contact = $information['wysiwyg_contact'];
?>


<!-- IMPORT GOOGLE MAPS JS -->
<div class="row contact_infos high-padding">
    <div class="container">
        <h1 class="page-title text-center"><?php echo $lang['Contact_us_title']; ?></h1>
        <?php echo htmlspecialchars_decode($information['wysiwyg_contact']); ?>
        <div class="col-md-6">
            <div class="row">
                <h3><strong class=""><?php echo $lang['OpenBetween']; ?></strong></h3>
                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Monday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_monday_hours; ?></label>
                </div>

                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Tuesday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_tuesday_hours; ?></label>
                </div>

                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Wednesday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_wednesday_hours; ?></label>
                </div>

                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Thursday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_thursday_hours; ?></label>
                </div>

                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Friday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_friday_hours; ?></label>
                </div>

                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Saturday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_saturday_hours; ?></label>
                </div>

                <div class="row">
                    <label class="col-md-4"><?php echo $lang['Sunday']; ?></label>
                    <label class="col-md-6"><?php echo $contact_sunday_hours; ?></label>
                </div>
            </div>


            <div class="clearfix"></div>
            <div class="contact_left_top right pull-left row">
                <h3><strong class=""><?php echo $lang['Contact_us_form']; ?></strong></h3>
                <form method="POST" class="contact_us_now row">
                    <div class="group col-md-10">
                        <input required class="form-control" type="text" name="contact_name" placeholder="<?php echo $lang['Name_and_surname']; ?>" />
                    </div>

                    <div class="group col-md-10">
                        <input required class="form-control" type="text" name="contact_email" placeholder="<?php echo $lang['Email']; ?>" />
                    </div>

                    <div class="group col-md-10">
                        <input required class="form-control" type="text" name="contact_subject" placeholder="<?php echo $lang['Subject']; ?>" />
                    </div>

                    <div class="group col-md-10">
                        <textarea rows="4" class="form-control" name="contact_message" placeholder="<?php echo $lang['Message']; ?>"></textarea>
                    </div>

                    <div class="group col-md-10">
                        <input class="col-md-10 btn btn-success" type="submit" name="contact_us" value="<?php echo $lang['Send_message']; ?>" />
                    </div>
                </form>
                <span class="hidden-contact-message"><?php echo $lang['Message_was_send']; ?></span>

                <?php include('../../system/functions_mail.php'); ?>

            </div>
        </div>
        <div id="googleMap" class="col-md-6"></div>
        <?php if (loggedIn()) { ?>
            <div class="col-md-12" style="padding: 30px 0;">
                <div class="row">
                    <h3 class="page-title text-center">Ratting And Review</h3>
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
                        <form method="POST" class="row">
                            <div class="group col-md-6">
                                <div class="col-md-3">
                                    <label class="form-control" style="margin: 5px 0">Rate :</label>
                                </div>
                                <div class="col-md-9">
                                    <div id="_rate">
                                        <?php
                                        $starNumber = $rate['ratting'];
                                        ?>
                                        
                                        <fieldset class="rating" >
                                            <input class="stars" type="radio" id="star5" name="rating" value="5" <?php if ($starNumber == '5') { echo "checked"; } ?> />
                                            <label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                            <input class="stars" type="radio" id="star4half" name="rating" value="4.5" <?php if ($starNumber == '4.5') { echo "checked"; } ?> />
                                            <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
                                            <input class="stars" type="radio" id="star4" name="rating" value="4" <?php if ($starNumber == '4') { echo "checked"; } ?> />
                                            <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                            <input class="stars" type="radio" id="star3half" name="rating" value="3.5" <?php if ($starNumber == '3.5') { echo "checked"; } ?> />
                                            <label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                                            <input class="stars" type="radio" id="star3" name="rating" value="3" <?php if ($starNumber == '3') { echo "checked"; } ?> />
                                            <label class = "full" for="star3" title="Meh - 3 stars"></label>
                                            <input class="stars" type="radio" id="star2half" name="rating" value="2.5" <?php if ($starNumber == '2.5') { echo "checked"; } ?> />
                                            <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                                            <input class="stars" type="radio" id="star2" name="rating" value="2" <?php if ($starNumber == '2') { echo "checked"; } ?> />
                                            <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                            <input class="stars" type="radio" id="star1half" name="rating" value="1.5" <?php if ($starNumber == '1.5') { echo "checked"; } ?> />
                                            <label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                                            <input class="stars" type="radio" id="star1" name="rating" value="1" <?php if ($starNumber == '1') { echo "checked"; } ?> />
                                            <label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                                            <input class="stars" type="radio" id="starhalf" name="rating" value="0.5" <?php if ($starNumber == '0.5') { echo "checked"; } ?> />
                                            <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                                        </fieldset>
                                    </div>
                                    <input class="form-control" type="hidden" name="uid" value="<?php echo $user_id; ?>"  />
                                </div>
                            </div>
                            <div class="group col-md-6">
                                <div class="col-md-3 ">
                                    <label class="form-control" style="margin: 5px 0">Review :</label>
                                </div>
                                <div class="col-md-9">
                                    <input required class="form-control" type="text" name="review" placeholder="Review" value="<?php echo $rate['review']; ?>" style="margin: 5px 0"/>
                                </div>
                            </div>
                            <div class="group col-md-12">
                                <input class="col-sm-offset-4 col-md-4 btn btn-success" type="submit" name="rr" value="Submit Review" style="margin-top : 5px" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
<?php } ?>
    </div>
</div>


<script type="text/javascript">
    function initialize() {
        var myLatLng = new google.maps.LatLng(<?php echo $information['contact_latitude']; ?>,<?php echo $information['contact_longitude']; ?>)
        var mapProp = {
            center: myLatLng,
            zoom: 15,
            zoomControl: false,
            scaleControl: false,
            scrollwheel: false,
            navigationControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>	
