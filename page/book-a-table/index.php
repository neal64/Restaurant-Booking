<?php
//require configuration file
require_once('../../configuration.php');
//get languages
require_once('../../system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <?php include ('../../head.php'); ?>
        <title><?php echo $lang['SITE_BASE_TITLE'] . $lang['BOOK_TABLES_PAGE_TITLE']; ?></title>
    </head>
    <body class="index-book-a-table">

        <?php include ('../../header.php'); ?>
        <?php include ('page-content.php'); ?>


        <style type="text/css">
<?php
$query_tables = mysqli_query($con, "SELECT * FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
while ($table = mysqli_fetch_array($query_tables)) {
    echo '.pin' . $table['table_id'] . ', .pulse' . $table['table_id'] . '{' . "\r\n";
    echo 'left: ' . $table['table_css_position_left'] . ';' . "\r\n";
    echo 'top: ' . $table['table_css_position_top'] . ';' . "\r\n";
    echo '}' . "\r\n";

    //Style pins
    echo '.pin' . $table['table_id'] . ':after' . '{' . "\r\n";
    echo 'content: "";';
    echo 'width: 14px;';
    echo 'height: 14px;';
    echo 'cursor: pointer;';
    echo 'margin: 8px 0 0 8px;';
    echo 'background: #e6e6e6;';
    echo 'position: absolute;';
    echo 'border-radius: 50%;';
    echo '}' . "\r\n";

    //Style pulses
    echo '.pulse' . $table['table_id'] . ':after' . '{' . "\r\n";
    echo 'content: "";';
    echo 'cursor: pointer;';
    echo 'border-radius: 50%;';
    echo 'height: 40px;';
    echo 'z-index: 2;';
    echo 'width: 40px;';
    echo 'position: absolute;';
    echo 'margin: -13px 0 0 -13px;';
    echo 'animation: pulsate 1s ease-out;';
    echo 'animation-iteration-count: infinite;';
    echo 'opacity: 0;';
    echo 'box-shadow: 0 0 1px 2px rgb(55, 90, 127);';
    echo '-o-box-shadow: 0 0 1px 2px rgb(55, 90, 127);';
    echo '-moz-box-shadow: 0 0 1px 2px rgb(55, 90, 127);';
    echo '-webkit-box-shadow: 0 0 1px 2px rgb(55, 90, 127);';
    echo 'animation-delay: 1.1s;';
    echo '}' . "\r\n";
}





//Image for ROOM #0
echo '.book-a-table.tables-holder.room_nr_0{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image0"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";
//Image for ROOM #1
echo '.book-a-table.tables-holder.room_nr_1{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";
//Image for ROOM #2
echo '.book-a-table.tables-holder.room_nr_2{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image2"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";
//Image for ROOM #3
echo '.book-a-table.tables-holder.room_nr_3{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image3"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";
//Image for ROOM #4
echo '.book-a-table.tables-holder.room_nr_4{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image4"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";

echo '.book-a-table.tables-holder.room_nr_5{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image5"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";

echo '.book-a-table.tables-holder.room_nr_6{' . "\r\n";
echo 'background:' . 'url(' . $CONF["restaurant_tables_image6"] . ') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0)' . "\r\n";
echo '}' . "\r\n";
?>
        </style>

        <?php require ('../../footer.php'); ?>

    </body>
</html>