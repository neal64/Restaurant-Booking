<div class="container">
	<div role="alert" class="process-order alert alert-success alert-dismissible" style="margin-bottom:30px !important">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<?php
			echo $lang['paypal_processing_message'];

			//if user is loggedIn
			if (loggedIn()) {
				$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
				$query2 = mysqli_fetch_array($query);

				$user_id = $query2['user_id'];
				$order_user_nice_name = $query2['user_nice_name'];
				$order_user_email = $query2["user_email"];
				$order_user_phone = $query2["user_phone"];
			}

			//if user is not loggedIn
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

			$order_payment_method = "<div class=\'label label-success\'>PayPal</div>";
			$order_paypal_default = "<div class=\'label label-warning\'>Unpaid</div>";

			$stuff = array_combine($_POST['menu_item_name'], $_POST['plus_minus_qty']);
			foreach ($stuff as $ingredient => $quantity) {
			  $descriptions[] = $quantity."x - ".$ingredient;
			}

			$concatenate = implode(' <br /> --------------------- <br /> ', $descriptions);
			$success = implode('<br />', $descriptions);

			//insert order
			mysqli_query($con,"INSERT INTO orders (order_address,order_payment_method,order_paypal_default, order_catering_products, order_type, order_value,order_user_name, order_user_email, order_user_phone, order_comments) VALUES ('$order_address','$order_payment_method','$order_paypal_default','$concatenate','Catering', '$order_value','$order_user_nice_name', '$order_user_email', '$order_user_phone', '$order_comments');") or die(mysqli_error($con));
			$order_id = mysqli_insert_id($con);
			
			//insert into orderuser
			$query_orderuser = "INSERT INTO orderuser (order_id,user_id) VALUES ('$order_id','$user_id');";
			mysqli_query($con, $query_orderuser);
			foreach ($menu_item_id as $key => $value) {
				$query_menuorder = "INSERT INTO menuorder (menu_item_id,order_id) VALUES ('$value','$order_id');";
				mysqli_query($con, $query_menuorder);
			}
		?>


    </div>
</div>