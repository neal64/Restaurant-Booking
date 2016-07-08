<?php

$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);


?>

<div class="row high-padding">
	<div class="container">
        <div class="col-md-12">
            <?php echo htmlspecialchars_decode( $information['wysiwyg_about'] ); ?>
        </div>
	</div>
</div>