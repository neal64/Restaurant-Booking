<h1><?php echo $lang['LatestPicturesInGalleries']; ?></h1>
<div class="clearfix"></div>
<div class="gallery-slider">
<?php
	$query2 = mysqli_query($con, "SELECT * FROM menus LIMIT 0 , 10") or trigger_error("Query Failed: " . mysqli_error($con));
	while( $result2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {
?>
    <div class="item single_gallery_item relative">
        <img src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $result2['menu_preview_image'] . '&amp;h=410&amp;w=560&amp;zc=1'; ?>" alt="<?php echo $lang['gallery_item']; ?>">
        <div class="single_gallery_item_title"><?php echo $result2['menu_item_name']; ?></div>
    </div>
<?php } ?>
</div>