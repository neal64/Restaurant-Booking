<?php
$today = date('Y-m-d');
//Query active events
$query_events = mysqli_query($con, "SELECT * FROM events WHERE event_date >='$today'") or trigger_error("Query Failed: " . mysqli_error($con));

?>

<div class="row bottom_area_margin">
	<div class="container">
		<!-- Page title -->
		<h1 class="clearfix"><?php echo $lang['AllActiveEvents']; ?></h1>
		<div id="phpr-timeline" class="phpr-container new-events">
		<?php while($query_event = mysqli_fetch_array($query_events)) { 
			$timestamp = $query_event['event_date'];
			$datetimearray = explode(" ", $timestamp);
			$date = $datetimearray[0];
			$time = $datetimearray[1];
			$reformatted_date = date('d-m-Y',strtotime($date));
			$reformatted_time = date('H:i',strtotime($time));
		?>
			<div class="phpr-timeline-block">
				<div class="phpr-timeline-img phpr-picture">
					<a title="<?php echo $query_event['event_thumbnail']; ?>" data-lightbox="gallery-popup" href="<?php echo $CONF['installation_path'] . $query_event['event_thumbnail']; ?>">
						<img src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $query_event['event_thumbnail'] . '&h=100&w=100&zc=1'; ?>" alt="<?php echo $query_event['event_name']; ?>" />
					</a>
				</div> <!-- phpr-timeline-img -->

				<div class="phpr-timeline-content">
					<h2><?php echo $query_event['event_name']; ?></h2>
					<p><?php echo $query_event['event_description']; ?></p>
					<span class="phpr-date"><?php echo $reformatted_date . " / " . $reformatted_time . " / " . substr($query_event['event_location'], 0, 22) .((strlen($query_event['event_location']) > 22) ? '...' : ''); ?></span>
				</div> <!-- phpr-timeline-content -->
			</div> <!-- phpr-timeline-block -->
		<?php } ?>
		</div>
	</div>
</div>