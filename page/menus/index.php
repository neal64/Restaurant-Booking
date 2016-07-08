<?php
	//require configuration file
	require_once('../../configuration.php');
	//get languages
	require_once('../../system/languages.php');
?>

<!DOCTYPE html>
<html lang="en" class="no-js specialities-menu-book">
	<head>
		<?php include ('../../head.php'); ?>
		<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['MENUS_PAGE_TITLE']; ?></title>


		<link rel="stylesheet" type="text/css" href="<?php echo $CONF['installation_path']; ?>skin/css/specialities-menu-book.css" />
	</head>
	<body class="index-menus">


<?php 
$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);

?>
	<!-- Page header -->
	<header>
	<?php include('../../nav.php'); ?>
	</header>
	
	<?php require('../../style.php'); ?>

	<!-- Page content -->
	<div class="container">
		<div class="bb-custom-wrapper">
			<div id="bb-bookblock" class="bb-bookblock">
			<?php 
			$query2 = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
			while( $result2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) { 
				$category = $result2['menu_item_category'];
				$replace_menu_href_link = str_replace(" ","_",$category);
				$menu_href_link = strtolower($replace_menu_href_link);
				?>
			<section class="bb-item" id="<?php echo $menu_href_link; ?>">
				<div class="">
				<h1 class="text-center menu_title"><?php echo $result2['menu_item_category']; ?></h1>
                                <ul class="row overflow_scroll" style="overflow-x :hidden;">
					<?php 
					$query3 = mysqli_query($con, "SELECT * FROM menus WHERE menu_item_category = '".$result2['menu_item_category']."' ") or trigger_error("Query Failed: " . mysqli_error($con));
					while( $result3 = mysqli_fetch_array($query3, MYSQLI_ASSOC)) { ?>
                                    <li class="row single_menu_item">
						<div class="col-md-2">
							<a data-lightbox="menu-popup" href="<?php echo $CONF['installation_path'] . $result3['menu_preview_image']; ?>">
								<img class="pull-right" src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $result3['menu_preview_image'] . '&amp;h=95&amp;w=128&amp;zc=1'; ?>" alt="<?php echo $result3['menu_item_name']; ?>" />
							</a>
						</div>
						<div class="col-md-8">
							<div class="row menu_title">
								<span class="main_title"><?php echo $result3['menu_item_name']; ?></span>								
								<div class="clearfix"></div>
							</div>
							<div class="row menu_description">
								<?php echo $result3['menu_item_details']; ?>
							</div>
						</div>
						<div class="col-md-2">
							<span class="text-red"><?php echo $result3['menu_item_price_per_slice'] . $lang['Base_currency']; ?></span>
						</div>
					</li>
					<?php } ?>
				</ul>
				</div>
			</section>
			<?php } ?>
			</div>
			<nav>
				<a id="bb-nav-prev" href="#" class="btn-button"><i class="fa fa-arrow-left"></i><?php echo $lang['PreviousPage']; ?></a>
				<a id="bb-nav-next" href="#" class="btn-button"><?php echo $lang['NextPage']; ?><i class="fa fa-arrow-right"></i></a>
			</nav>
		</div>

	</div>



	<!-- SCRIPTS -->
	<script src="<?php echo $CONF['installation_path']; ?>skin/js/modernizr.custom.js"></script>
	<script src="<?php echo $CONF['installation_path']; ?>skin/js/jquerypp.custom.js"></script>
	<script src="<?php echo $CONF['installation_path']; ?>skin/js/jquery.bookblock.js"></script>
	<script>
	var Page = (function() {
		
		var config = {
				$bookBlock : $( '#bb-bookblock' ),
				$navNext : $( '#bb-nav-next' ),
				$navPrev : $( '#bb-nav-prev' )
			},
			init = function() {
				config.$bookBlock.bookblock( {
					speed : 1000,
					shadowSides : 0.8,
					shadowFlip : 0.4
				} );
				initEvents();
			},
			initEvents = function() {
				
				var $slides = config.$bookBlock.children();

				// add navigation events
				config.$navNext.on( 'click touchstart', function() {
					config.$bookBlock.bookblock( 'next' );
					return false;
				} );

				config.$navPrev.on( 'click touchstart', function() {
					config.$bookBlock.bookblock( 'prev' );
					return false;
				} );
				
				// add swipe events
				$slides.on( {
					'swipeleft' : function( event ) {
						config.$bookBlock.bookblock( 'next' );
						return false;
					},
					'swiperight' : function( event ) {
						config.$bookBlock.bookblock( 'prev' );
						return false;
					}
				} );

				// add keyboard events
				$( document ).keydown( function(e) {
					var keyCode = e.keyCode || e.which,
						arrow = {
							left : 37,
							up : 38,
							right : 39,
							down : 40
						};

					switch (keyCode) {
						case arrow.left:
							config.$bookBlock.bookblock( 'prev' );
							break;
						case arrow.right:
							config.$bookBlock.bookblock( 'next' );
							break;
					}
				} );
			};

			return { init : init };

	})();

	//init page
	Page.init();
	</script>
	</body>
</html>