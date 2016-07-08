
<style>
<?php

//B U T T O N S
//Button - background,color - normal state
echo '.btn-success {' . "\r\n";
echo 'background: ' . $information['site_button_bg_normal'] . ' !important;' . "\r\n";
echo 'color: ' . $information['site_button_txt_normal'] . ' !important;' . "\r\n";
echo '}' . "\r\n\r\n";
//Button - background,color - hover state
echo '.btn-success:hover, .btn-success:focus, .btn-success:active, .btn-success.active, .open > .dropdown-toggle.btn-success {' . "\r\n";
echo 'background: ' . $information['site_button_bg_hover'] . ' !important;' . "\r\n";
echo 'color: ' . $information['site_button_txt_hover'] . ' !important;' . "\r\n";
echo '}' . "\r\n\r\n";


//H E A D E R
//Header style: Top bar background
echo '.header-holder-top.first {' . "\r\n";
echo 'background: ' . $information['header_top_bar_bg'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Header style: Middle section background
echo 'header .restaurant-bg {' . "\r\n";
echo 'background: ' . $information['header_middle_section_bg'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Header style: Navigation bar background
echo 'nav.navigation-menu,nav.navigation-menu {' . "\r\n";
echo 'background: ' . $information['header_nav_bg'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Header style: Navigation item(Hover state)
echo 'nav .restaurant-main-menu a:hover, nav.navigation-menu a:hover {' . "\r\n";
echo 'background: ' . $information['header_nav_item_bg_hover'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Header style: Text color
echo 'header {' . "\r\n";
echo 'color: ' . $information['header_color'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Header nav style: Text color - normal
echo 'nav .restaurant-main-menu a {' . "\r\n";
echo 'color: ' . $information['header_nav_txt'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Header nav style: Text color - hover
echo 'nav .restaurant-main-menu a:hover, nav.navigation-menu a:hover {' . "\r\n";
echo 'color: ' . $information['header_nav_item_txt_hover'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";


// F O O T E R
//Footer style: Top section background and color
echo '.footer_top {' . "\r\n";
echo 'background: ' . $information['footer_top_section_bg'] . ';' . "\r\n";
echo 'color: ' . $information['footer_top_section_txt'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Footer style: Bottom bar background
echo 'footer {' . "\r\n";
echo 'background: ' . $information['footer_bottom_bar_bg'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";
//Footer style: Bottom color
echo '.footer_bottom {' . "\r\n";
echo 'color: ' . $information['footer_bottom_bar_txt'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";


// B O D Y
//Body background
echo 'body {' . "\r\n";
echo 'background: ' . $information['site_body_background'] . ';' . "\r\n";
echo '}' . "\r\n\r\n";


// F O N T S
echo "<link href='http://fonts.googleapis.com/css?family='" .$information['site_main_font']. "' rel='stylesheet' type='text/css'>"
?>
</style>