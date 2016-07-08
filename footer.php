<?php

$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);

//Social media
$social_fb = $information['social_fb'];
$social_tw = $information['social_tw'];
$social_gplus = $information['social_gplus'];
$social_dribbble = $information['social_dribbble'];
$social_stumbleupon = $information['social_stumbleupon'];
$social_linkedin = $information['social_linkedin'];
$social_pin = $information['social_pin'];
$social_tumblr = $information['social_tumblr'];
$social_instagram = $information['social_instagram'];
$social_vimeo = $information['social_vimeo'];
$social_flickr = $information['social_flickr'];
$social_digg = $information['social_digg'];
$social_youtube = $information['social_youtube'];
//Contact Open Hours
$contact_monday_hours = $information['contact_monday_hours'];
$contact_tuesday_hours = $information['contact_tuesday_hours'];
$contact_wednesday_hours = $information['contact_wednesday_hours'];
$contact_thursday_hours = $information['contact_thursday_hours'];
$contact_friday_hours = $information['contact_friday_hours'];
$contact_saturday_hours = $information['contact_saturday_hours'];
$contact_sunday_hours = $information['contact_sunday_hours'];
?>
<script type="text/javascript">
	function initialize() {
		var myLatLng = new google.maps.LatLng(<?php echo $information['contact_latitude']; ?>,<?php echo $information['contact_longitude']; ?> )
		var mapProp = {
		center:myLatLng,
		zoom:17,
		zoomControl: false,
		scaleControl: false,
		scrollwheel: false,
		navigationControl: false,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	};

	var map=new google.maps.Map(document.getElementById("googleMapFooter"),mapProp);

	var marker = new google.maps.Marker({
		position: myLatLng,
		map: map
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<footer>
	<div class="row footer_top">
		<div class="container">
			<div class="col-md-3">
				<a title="Restaurant Logo" href="<?php echo $CONF['installation_path']; ?>"><img alt="logo" src="<?php echo $CONF['logo_url']; ?>"></a>
				<div class="clearfix"></div>
				<strong><?php echo $lang['Address'] . ":"; ?></strong>
				<div class="clearfix"></div>
				<span><?php echo $information['contact_address']; ?></span>

				<div class="cleafix"></div>

				<ul class="social_links">
					<?php if (!empty($social_fb)) { ?> 
					<li>
						<a href="<?php echo $social_fb; ?>" title="Facebook">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/facebook.png" alt="Facebook">
						</a>
					</li>
					<?php } if (!empty($social_tw)) { ?> 
					<li>
						<a href="<?php echo $social_tw; ?>" title="Twitter">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/twitter.png" alt="Twitter">
						</a>
					</li>
					<?php } if (!empty($social_gplus)) { ?> 
					<li>
						<a href="<?php echo $social_gplus; ?>" title="Google+">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/google-plus.png" alt="Google+">
						</a>
					</li>
					<?php } if (!empty($social_dribbble)) { ?> 
					<li>
						<a href="<?php echo $social_dribbble; ?>" title="Dribbble">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/dribbble.png" alt="Dribbble">
						</a>
					</li>
					<?php } if (!empty($social_stumbleupon)) { ?> 
					<li>
						<a href="<?php echo $social_stumbleupon; ?>" title="Stumbleupon">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/stumbleupon.png" alt="Stumbleupon">
						</a>
					</li>
					<?php } if (!empty($social_linkedin)) { ?> 
					<li>
						<a href="<?php echo $social_linkedin; ?>" title="LinkedIn">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/linkedin.png" alt="LinkedIn">
						</a>
					</li>
					<?php } if (!empty($social_pin)) { ?> 
					<li>
						<a href="<?php echo $social_pin; ?>" title="Pinterest">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/pinterest.png" alt="Pinterest">
						</a>
					</li>
					<?php } if (!empty($social_tumblr)) { ?> 
					<li>
						<a href="<?php echo $social_tumblr; ?>" title="Tumblr">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/tumblr.png" alt="Tumblr">
						</a>
					</li>
					<?php } if (!empty($social_instagram)) { ?> 
					<li>
						<a href="<?php echo $social_instagram; ?>" title="Instagram">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/instagram.png" alt="Instagram">
						</a>
					</li>
					<?php } if (!empty($social_vimeo)) { ?> 
					<li>
						<a href="<?php echo $social_vimeo; ?>" title="Vimeo">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/vimeo.png" alt="Vimeo">
						</a>
					</li>
					<?php } if (!empty($social_flickr)) { ?> 
					<li>
						<a href="<?php echo $social_flickr; ?>" title="Flickr">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/flickr.png" alt="Flickr">
						</a>
					</li>
					<?php } if (!empty($social_digg)) { ?> 
					<li>
						<a href="<?php echo $social_digg; ?>" title="Digg">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/digg.png" alt="Digg">
						</a>
					</li>
					<?php } if (!empty($social_youtube)) { ?> 
					<li>
						<a href="<?php echo $social_youtube; ?>" title="YouTube">
							<img src="<?php echo $CONF['installation_path']; ?>skin/images/social_media/youtube.png" alt="YouTube">
						</a>
					</li>
					<?php } ?> 
				</ul>				
			</div>
			<div class="col-md-3 contact_area">
				<h3><strong class=""><?php echo $lang['ContactUsNow']; ?></strong></h3>
				<span class="title"><?php echo $lang['PhoneOrders'] . ":"; ?></span>
				<span class="content"><?php echo $information['contact_phone_number']; ?></span>
				<span class="title"><?php echo $lang['EmailOrders'] . ":"; ?></span>
				<span class="content"><?php echo $information['contact_email']; ?></span>
			</div>
			
			<div class="col-md-3">
				<h3><strong class=""><?php echo $lang['LocationOnTheMap']; ?></strong></h3>
				<div id="googleMapFooter"></div>
			</div>	

			<div class="col-md-3">
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
		</div>
	</div>
	<div class="row footer_bottom">
		<div class="container">
			<span class="text-left"><?php echo $lang['Copyright']; ?></span>
		</div>
	</div>
</footer>


<!--
 Go to www.addthis.com/dashboard to customize your tools 
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51ac9aed237b0b2a" async="async"></script>-->