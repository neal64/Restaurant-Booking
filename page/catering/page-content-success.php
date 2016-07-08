<div class="container">
	<div role="alert" class="alert alert-success alert-dismissible" style="margin-bottom:30px">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <?php 
        echo $lang['paypal_success_message'];
        //var_dump($_POST); 
        $order_user_email = $_POST['custom'];
        $order_paypal_default = '<div class=\"label label-success\">Paid via Paypal</div>';
        // Here the confirmation of the payment is sent into the dashboard.
		$query_update_order = "UPDATE orders SET order_paypal_default='$order_paypal_default' WHERE order_user_email='$order_user_email' ORDER BY order_id DESC LIMIT 1";
		mysqli_query($con, $query_update_order);
        ?>
    </div>
</div>