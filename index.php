<?php


//require configuration file
require_once('configuration.php');
//get languages
require_once('system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php include ('head.php'); ?>
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['HOMEPAGE_TITLE']; ?></title>
</head>
<body>

<?php
	//get current directory
	$currentDirectory = dirname(__FILE__);
	//get header
	include ($currentDirectory . '/header.php');
	//get slider
	include ($currentDirectory . '/slider.php'); 
	//Query testimonials
	$query_testimonials = mysqli_query($con, "SELECT * FROM testimonials") or trigger_error("Query Failed: " . mysqli_error($con));
?>

<!-- 1ST SECTION ################################################## --> 
<div class="restaurant-full-width block-menu block-book-a-table block-catering row">
	<div class="container">
		<div class="row">
			<div class="col-md-4"><?php include ($currentDirectory . '/page/book-a-table/block-book-a-table.php'); ?></div>
			<div class="col-md-4"><?php include ($currentDirectory . '/page/book-a-table/block-book-a-table.php'); ?></div>
			<div class="col-md-4"><?php include ($currentDirectory . '/page/book-a-table/block-book-a-table.php'); ?></div>
		</div>
	</div>
</div>

<div class="restaurant-full-width block-events row">
	<div class="container block-events">
		<?php include ($currentDirectory . '/page/events/block-events.php'); ?>
	</div>
</div>

<!-- 2ND SECTION ################################################## --> 
<div class="row section-testimonials high-padding">
	<div class="container">
		<div class="testimonials-slider text-center owl-carousel owl-theme">
			<?php while($testimonial = mysqli_fetch_array($query_testimonials)) { ?>
			<div class="item">
				<div class="testimonial-author">
					<img src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $testimonial['testimonial_thumb'] . '&h=100&w=100&zc=1'; ?>" alt="<?php echo $testimonial['testimonal_client_name']; ?>" />
					<ul class="testimonial-author-info">
						<li class="name text-left"><?php echo $testimonial['testimonal_client_name']; ?></li>
						<li class="position text-left"><?php echo $testimonial['testimonial_client_job'] . ', ' . $testimonial['testimonial_works_at']; ?></li>
					</ul>
				</div>
				<p><?php echo $testimonial['testimonial_content']; ?></p>
			</div>
			<?php } ?>
		</div>
	</div>
</div>



<!-- 3RD SECTION ################################################## -->
<div class="restaurant-full-width block-gallery row">
	<div class="container block-gallery">
		<?php include ($currentDirectory . '/page/gallery/block-gallery.php'); ?>
	</div>
</div><!-- END: Block Book a Table -->

<!-- FOOTER ################################################## -->
<?php include ($currentDirectory . '/footer.php'); ?>
</body>
</html>