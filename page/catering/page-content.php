<script type="text/javascript">
//ADD ITEMS TO CART
jQuery(document).ready(function() {
	jQuery( ".add-to-cart" ).click(function() {
		jQuery(this).parent().find(".move_menu_item_inputs").clone().appendTo( ".cart_items_goes_up" );
		jQuery(this).parent().find(".p-update-user-informations").delay(300).fadeIn( "slow" );
		jQuery(this).parent().find(".p-update-user-informations").delay(500).fadeOut("slow");
		jQuery(this).delay(550).fadeOut("slow");
		jQuery(this).parent().find(".add-to-cart-done").delay(600).fadeIn("slow");
	});
});
</script>
<script>
new WOW().init();
</script>


<div class="container">

<?php
if (loggedIn()) {
	$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
	$query2 = mysqli_fetch_array($query);

	$user_id = $query2['user_id'];
	$order_user_nice_name = $query2['user_nice_name'];
	$order_user_email = $query2["user_email"];
	$order_user_phone = $query2["user_phone"];
}

if(isset($_POST["submit_order"])) {

	if (!loggedIn()) {
		$order_user_nice_name = $_POST['order_user_nice_name'];
		$order_user_email = $_POST["order_user_email"];
		$order_user_phone = $_POST["order_user_phone"];
	}

	$order_address = $_POST["order_address"];
	$order_comments = $_POST["order_comments"];
	$order_value = $_POST["order_value"];
	$menu_item_id = $_POST["menu_item_id"];
	$order_type = 'Catering';
	$order_catering_products = $_POST['order_catering_products'];
	$productsx = $_POST['plus_minus_qty'] . "x - " . $_POST['menu_item_name'];

	$order_payment_method = "<div class=\'label label-success\'>On delivery</div>";
	$order_paypal_default = "<div class=\'label label-success\'>On delivery</div>";

	$stuff = array_combine($_POST['menu_item_name'], $_POST['plus_minus_qty']);
	foreach ($stuff as $ingredient => $quantity) {
	    $descriptions[] = $quantity."x - ".$ingredient;
	}

	$concatenate = implode(' <br /> --------------------- <br /> ', $descriptions);
	$success = implode('<br />', $descriptions);
	//echo $concatenate;

	//insert order
	mysqli_query($con,"INSERT INTO orders (order_address,order_payment_method,order_paypal_default,order_catering_products, order_type, order_value,order_user_name, order_user_email, order_user_phone, order_comments) VALUES ('$order_address','$order_payment_method','$order_paypal_default','$concatenate','Catering', '$order_value','$order_user_nice_name', '$order_user_email', '$order_user_phone', '$order_comments');") or die(mysqli_error($con));
	$order_id = mysqli_insert_id($con); ?>


	<div role="alert" class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<strong>Well done!</strong> Your order has been registered.
		<br /><br /><strong>Your purchased product/s:</strong> <br /><?php echo $concatenate; ?>

		<br /><br /><strong>Order value:</strong> <?php echo $order_value . $lang['Base_currency']; ?>
    </div>

	<?php

	include('../../system/functions_mail.php');

	//insert into orderuser
	$query_orderuser = "INSERT INTO orderuser (order_id,user_id) VALUES ('$order_id','$user_id');";
	mysqli_query($con, $query_orderuser);

	foreach ($menu_item_id as $key => $value) {
		$query_menuorder = "INSERT INTO menuorder (menu_item_id,order_id) VALUES ('$value','$order_id');";
		mysqli_query($con, $query_menuorder);
	}

} ?>



<?php
	$query = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));

?>
<div class="col-md-4 sidebar_manus fixed_sidebar">
	<h1><?php echo $lang['Select_a_category']; ?></h1>
	<ul class="list_big_categories">
	<?php 
		while( $result = mysqli_fetch_array($query, MYSQLI_ASSOC)) { 
		$category = $result['menu_item_category'];
		$replace_menu_href_link = str_replace(" ","_",$category);
		$menu_href_link = strtolower($replace_menu_href_link);
		?>
		<li class="wow bounceInUp">
			<a href="#<?php echo $menu_href_link; ?>" class="<?php echo $result['menu_item_category']; ?>" title="<?php echo $result['menu_item_category']; ?>">
				<i class="fa fa-caret-right"></i><?php echo $result['menu_item_category']; ?>
			</a>
		</li>
	<?php } ?>
	</ul>
</div>
<div class="col-md-8 content_menus_with_details pull-right">
	<h1><?php echo $lang['List_of_specialities']; ?></h1>

	<?php 
	$query2 = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
	while( $result2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) { 
		$category = $result2['menu_item_category'];
		$replace_menu_href_link = str_replace(" ","_",$category);
		$menu_href_link = strtolower($replace_menu_href_link);
		?>
	<section class="section_menus wow bounceInUp" id="<?php echo $menu_href_link; ?>">
		<?php $query3 = mysqli_query($con, "SELECT * FROM menus WHERE menu_item_category = '".$result2['menu_item_category']."' ") or trigger_error("Query Failed: " . mysqli_error($con)); 
		$num_rows = mysqli_num_rows($query3);
		?>
		<h2 class="catering_menu_title"><i class="fa fa-arrow-right"></i><?php echo $result2['menu_item_category'] . '( '. $num_rows . ' )'; ?></h2>

		<ul class="row">
			<?php while( $result3 = mysqli_fetch_array($query3, MYSQLI_ASSOC)) { ?>
			<li class="row">
				<div class="col-md-3 product_thumbnail">
					<a data-lightbox="menu-popup" href="<?php echo $CONF['installation_path'] . $result3['menu_preview_image']; ?>">
						<img class="pull-right" src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $result3['menu_preview_image'] . '&h=380&w=600&zc=1'; ?>" alt="<?php echo $result3['menu_item_name']; ?>" />
					</a>
				</div>
				<div class="col-md-9 product_body">
					<div class="row menu_title">
						<h2><?php echo $result3['menu_item_name']; ?></h2>
						<div class="clearfix"></div>
						<strong><?php echo $lang['Price'] . ":"; ?></strong>
						<?php echo $result3['menu_item_price_per_slice'] . $lang['Base_currency']; ?>
					</div>
					<div class="row menu_description">
						<strong><?php echo $lang['Details'] . ":"; ?></strong>
						<?php echo $result3['menu_item_details']; ?>
					</div>
					<div class="move_menu_item_inputs">
						<input class="single_menu_item" type="hidden" name="menu_item_id[]" value="<?php echo $result3['menu_item_id']; ?>" />
						<input disabled="disabled" class="single_menu_item menu_item_price_per_slice" type="hidden" name="menu_item_price_per_slice[]" value="<?php echo $result3['menu_item_price_per_slice']; ?>" />
						<input class="single_menu_item item_name clearfix col-md-8 form-control" readonly="readonly"  type="text" name="menu_item_name[]" value="<?php echo $result3['menu_item_name']; ?>" />
						<input class="single_menu_item temporary_price_foreach_product" type="hidden" value="" />
						<button aria-hidden="true" class="form-control remove-product-from-cart single_menu_item" type="button">×</button>

						<div class="single_menu_item col-md-2">
						    <input type="text" value="1" class="pull-left row plus-minus-qty form-control" name="plus_minus_qty[]" />
						</div>
					</div>
					<a class="add-to-cart btn-cart"><?php echo $lang['Add_to_cart']; ?> <span class="p-update-user-informations"><i class="fa fa-check"></i></span></a>
					<a class="add-to-cart-done btn-cart-done"><?php echo $lang['Added']; ?> <i class="fa fa-check"></i></a>

				</div>
			</li>
			<?php } ?>
		</ul>
	</section>
	<?php } ?>
</div>

<a id="back_to_top"><?php echo $lang['Back_to_top']; ?></a>
</div>


<script type="text/javascript">
	// SMOOTH SCROLL
	jQuery(function() {
	  jQuery('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = jQuery(this.hash);
	      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        jQuery('html,body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    }
	  });
	});


	// APPEAR BACK TO TOP AFTER 1000px SCROLL DOWN
	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > 200) {
			jQuery('#back_to_top').fadeIn(200);
		} else {
			jQuery('#back_to_top').fadeOut(200);
		}
	});
	// Animate the scroll to top
	jQuery('#back_to_top').click(function(event) {
		event.preventDefault();
		jQuery('html, body').animate({scrollTop: 0}, 300);
	})

</script>

