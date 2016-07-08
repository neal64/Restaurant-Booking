<?php
//Query distinct menu_item_category
$query_distinct_categories = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));

?>

<div class="container restaurant_gallery high-padding">
	<!-- Page title -->
	<h1 class="page-title"><?php echo $lang['Contact_page_title']; ?></h1>
	<!-- Page content -->
	<div class="content_menus_with_details">
	<?php
	while( $results_distinct_categories = mysqli_fetch_array($query_distinct_categories, MYSQLI_ASSOC)) {
		$single_category = $results_distinct_categories['menu_item_category'];
		$replace_menu_href_link = str_replace(" ","_",$single_category);
		$menu_href_link = strtolower($replace_menu_href_link);

		//Query all menus and count them
		$query_menus_details = mysqli_query($con, "SELECT * FROM menus WHERE menu_item_category = '".$results_distinct_categories['menu_item_category']."' ") or trigger_error("Query Failed: " . mysqli_error($con));
		$num_rows = mysqli_num_rows($query_menus_details);
	?>

		<div class="clearfix"></div>
			<h3 class="food_category"><?php echo $results_distinct_categories['menu_item_category']; ?></h3>
			
			<?php while( $results_menus_details = mysqli_fetch_array($query_menus_details, MYSQLI_ASSOC)) { ?>
			<div class="col-md-4 single_gallery_item">
				<div class="row">
					<img alt="<?php echo $results_menus_details['menu_item_name']; ?>" src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $results_menus_details['menu_preview_image'] . '&amp;h=330&amp;w=560&amp;zc=1'; ?>" />
					<a title="<?php echo $results_menus_details['menu_item_name']; ?>" class="gallery-single-image" data-lightbox="gallery-popup" href="<?php echo $CONF['installation_path'] . $results_menus_details['menu_preview_image']; ?>">
						<span><?php echo $results_menus_details['menu_item_name']; ?></span>
						<div class="icon text-center">
							<i class="red fa fa-expand"></i>
						</div>
					</a>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	</div>
	<!-- Back to top button -->
	<a id="back_to_top"><?php echo $lang['Back_to_top']; ?></a>
</div>