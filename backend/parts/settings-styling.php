<?php

//require configuration file
require_once('../../configuration.php');
//get languages
require_once('../../system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <?php require('../head.php'); ?>
    <title><?php echo $lang['CP_BASE_TITLE'] . $lang['styling_settings']; ?></title>
    <link rel="stylesheet" href="../skin/css/jquery-ui.css">
    <link rel="stylesheet" href="../skin/css/evol.colorpicker.min.css">

    <script src="../skin/js/jquery-ui.js"></script>
    <script src="../skin/js/evol.colorpicker.min.js" type="text/javascript"></script>
</head>

<body class="backend row index-edit-user-profile">
    <!-- ####### HEADER for logged in users ############################################################## -->
    <?php if (loggedIn()) { 

        $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") 
        or trigger_error("Query Failed: " . mysqli_error($con));
        $query2 = mysqli_fetch_array($query); 

        //Query infos from DB
        $informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
        $information = mysqli_fetch_array($informations);

        ?>
        
        <header class="row loggedin">
            <div class="">
                <div class="col-md-6">
                    <span class="label label-success pull-left">
                    <?php if ($query2['user_role'] == 'Administrator') { ?>
                        <?php echo $lang['role']; ?><strong>Administrator</strong>
                    <?php }else if ($query2['user_role'] == 'Client') { ?>
                        <?php echo $lang['role']; ?><strong>Client</strong>
                    <?php } ?>
                    </span>
                    <div class="pull-left">
                        <a title="<?php echo $lang['back_into_the_site']; ?>" href="<?php echo $CONF['installation_path']; ?>">
                            <?php echo $lang['back_into_the_site']; ?>
                        </a>
                    </div>
                </div>
                <div class="col-md-6"><p class="pull-right"><?php echo $lang['cp_login_hello'] . $query2['user_nice_name']; ?>! <a href="<?php echo $CONF['installation_path']; ?>backend/login.php?action=logout"><span class="label label-warning"><?php echo $lang['log_out']; ?></span></a></p></div>
            </div>
        </header>

        <div class="col-md-2 v2-sidebar-menu">
            <?php if ($query2['user_role'] == 'Administrator') { ?>
                <?php include('menu-administrators.php'); ?>
            <?php }else if ($query2['user_role'] == 'Client') {  ?>
                <?php include('menu-clients.php');  ?>
            <?php } ?>
        </div>

        <div class="col-md-10 v2-page-content">
            <div class="row">
                <div class="col-md-12">
                    <?php if(isset($_POST["phpr_styling"])) {
                            //Styling informations
                            $site_body_background = $_POST['site_body_background'];
                            $site_button_bg_normal = $_POST['site_button_bg_normal'];
                            $site_button_bg_hover = $_POST['site_button_bg_hover'];     
                            $site_button_txt_normal = $_POST['site_button_txt_normal'];
                            $site_button_txt_hover = $_POST['site_button_txt_hover'];
                            $header_color = $_POST['header_color'];
                            $header_top_bar_bg = $_POST['header_top_bar_bg'];
                            $header_middle_section_bg = $_POST['header_middle_section_bg'];
                            $header_nav_bg = $_POST['header_nav_bg'];
                            $header_nav_item_bg_hover = $_POST['header_nav_item_bg_hover'];
                            $header_nav_item_txt_hover = $_POST['header_nav_item_txt_hover'];
                            $header_nav_txt = $_POST['header_nav_txt'];
                            $footer_top_section_bg = $_POST['footer_top_section_bg'];
                            $footer_bottom_bar_bg = $_POST['footer_bottom_bar_bg'];
                            $footer_bottom_bar_txt = $_POST['footer_bottom_bar_txt'];
                            $footer_top_section_txt = $_POST['footer_top_section_txt'];
                            $site_main_font = $_POST['site_main_font'];

                            //Query update DB
                            $query_update = "UPDATE informations SET
                            site_body_background='$site_body_background',
                            site_button_bg_normal='$site_button_bg_normal',
                            site_button_bg_hover='$site_button_bg_hover',
                            site_button_txt_normal='$site_button_txt_normal',
                            site_button_txt_hover='$site_button_txt_hover',
                            header_top_bar_bg='$header_top_bar_bg',
                            header_nav_item_txt_hover='$header_nav_item_txt_hover',
                            header_nav_txt='$header_nav_txt',
                            header_color='$header_color',
                            header_middle_section_bg='$header_middle_section_bg',
                            header_nav_bg='$header_nav_bg',
                            header_nav_item_bg_hover='$header_nav_item_bg_hover',
                            footer_top_section_bg='$footer_top_section_bg',
                            footer_bottom_bar_txt='$footer_bottom_bar_txt',
                            footer_bottom_bar_bg='$footer_bottom_bar_bg',
                            footer_top_section_txt='$footer_top_section_txt',
                            site_main_font='$site_main_font'";
                            mysqli_query($con, $query_update);
                            #Success message ?>
                            <div class="container">
                                <div role="alert" class="alert alert-success">
                                  <?php echo $lang['cp_settings_success_message']; ?>
                                </div>
                            </div>
                        <?php } ?>



                    <h1><?php echo $lang['styling_settings']; ?></h1>

                    <form class="change_contact_infos col-md-7" method="POST">

                        <!-- BUTTONS -->
                        <h2>Buttons style</h2>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Button background(Normal)<br /> <span>Default: #00bc8c</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['site_button_bg_normal']; ?>" name="site_button_bg_normal" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Button background(Hover)<br /> <span>Default: #008966</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['site_button_bg_hover']; ?>" name="site_button_bg_hover" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Button text color(Normal)<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['site_button_txt_normal']; ?>" name="site_button_txt_normal" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Button text color(Hover)<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['site_button_txt_hover']; ?>" name="site_button_txt_hover" />
                            </div>
                        </div>

                        <!-- BODY -->
                        <h2>Body style</h2>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Background color<br /> <span>Default: #F3F0EB</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['site_body_background']; ?>" name="site_body_background" />
                            </div>
                        </div>

                        <!-- HEADER -->
                        <h2>Header style</h2>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Top bar text color<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_color']; ?>" name="header_color" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Top bar background<br /> <span>Default: #222222</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_top_bar_bg']; ?>" name="header_top_bar_bg" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Middle section background<br /> <span>Default: #343434</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_middle_section_bg']; ?>" name="header_middle_section_bg" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Navigation bar background<br /> <span>Default: #222222</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_nav_bg']; ?>" name="header_nav_bg" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Navigation item(Hover)<br /> <span>Default: #008966</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_nav_item_bg_hover']; ?>" name="header_nav_item_bg_hover" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Navigation text color<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_nav_txt']; ?>" name="header_nav_txt" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Navigation text color(Hover)<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['header_nav_item_txt_hover']; ?>" name="header_nav_item_txt_hover" />
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <h2>Footer style</h2>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Top section background<br /> <span>Default: #2D2D2D</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['footer_top_section_bg']; ?>" name="footer_top_section_bg" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Top section text color<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['footer_top_section_txt']; ?>" name="footer_top_section_txt" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Bottom bar background<br /> <span>Default: #444444</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['footer_bottom_bar_bg']; ?>" name="footer_bottom_bar_bg" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Bottom bar text color<br /> <span>Default: #ffffff</span></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control color_picker" value="<?php echo $information['footer_bottom_bar_txt']; ?>" name="footer_bottom_bar_txt" />
                            </div>
                        </div>

                        <!-- FONTS -->
                        <h2>Fonts</h2>
                        <div class="group_label_input settings">
                            <label class="col-md-5">Main font(Google fonts)</label>
                            <div class="col-md-7">
                                <select name="site_main_font" class="form-control">
                                    <option selected="selected"><?php echo $information['site_main_font']; ?></option>
                                    <option value="Aclonica">Aclonica</option>
                                    <option value="Allan">Allan</option>
                                    <option value="Annie+Use+Your+Telescope">Annie Use Your Telescope</option>
                                    <option value="Anonymous+Pro">Anonymous Pro</option>
                                    <option value="Allerta+Stencil">Allerta Stencil</option>
                                    <option value="Allerta">Allerta</option>
                                    <option value="Amaranth">Amaranth</option>
                                    <option value="Anton">Anton</option>
                                    <option value="Architects+Daughter">Architects Daughter</option>
                                    <option value="Arimo">Arimo</option>
                                    <option value="Artifika">Artifika</option>
                                    <option value="Arvo">Arvo</option>
                                    <option value="Asset">Asset</option>
                                    <option value="Astloch">Astloch</option>
                                    <option value="Bangers">Bangers</option>
                                    <option value="Bentham">Bentham</option>
                                    <option value="Bevan">Bevan</option>
                                    <option value="Bigshot+One">Bigshot One</option>
                                    <option value="Bowlby+One">Bowlby One</option>
                                    <option value="Bowlby+One+SC">Bowlby One SC</option>
                                    <option value="Brawler">Brawler </option>
                                    <option value="Buda:300">Buda</option>
                                    <option value="Cabin">Cabin</option>
                                    <option value="Calligraffitti">Calligraffitti</option>
                                    <option value="Candal">Candal</option>
                                    <option value="Cantarell">Cantarell</option>
                                    <option value="Cardo">Cardo</option>
                                    <option value="Carter One">Carter One</option>
                                    <option value="Caudex">Caudex</option>
                                    <option value="Cedarville+Cursive">Cedarville Cursive</option>
                                    <option value="Cherry+Cream+Soda">Cherry Cream Soda</option>
                                    <option value="Chewy">Chewy</option>
                                    <option value="Coda">Coda</option>
                                    <option value="Coming+Soon">Coming Soon</option>
                                    <option value="Copse">Copse</option>
                                    <option value="Corben:700">Corben</option>
                                    <option value="Cousine">Cousine</option>
                                    <option value="Covered+By+Your+Grace">Covered By Your Grace</option>
                                    <option value="Crafty+Girls">Crafty Girls</option>
                                    <option value="Crimson+Text">Crimson Text</option>
                                    <option value="Crushed">Crushed</option>
                                    <option value="Cuprum">Cuprum</option>
                                    <option value="Damion">Damion</option>
                                    <option value="Dancing+Script">Dancing Script</option>
                                    <option value="Dawning+of+a+New+Day">Dawning of a New Day</option>
                                    <option value="Didact+Gothic">Didact Gothic</option>
                                    <option value="Droid+Sans">Droid Sans</option>
                                    <option value="Droid+Sans+Mono">Droid Sans Mono</option>
                                    <option value="Droid+Serif">Droid Serif</option>
                                    <option value="EB+Garamond">EB Garamond</option>
                                    <option value="Expletus+Sans">Expletus Sans</option>
                                    <option value="Fontdiner+Swanky">Fontdiner Swanky</option>
                                    <option value="Forum">Forum</option>
                                    <option value="Francois+One">Francois One</option>
                                    <option value="Geo">Geo</option>
                                    <option value="Give+You+Glory">Give You Glory</option>
                                    <option value="Goblin+One">Goblin One</option>
                                    <option value="Goudy+Bookletter+1911">Goudy Bookletter 1911</option>
                                    <option value="Gravitas+One">Gravitas One</option>
                                    <option value="Gruppo">Gruppo</option>
                                    <option value="Hammersmith+One">Hammersmith One</option>
                                    <option value="Holtwood+One+SC">Holtwood One SC</option>
                                    <option value="Homemade+Apple">Homemade Apple</option>
                                    <option value="Inconsolata">Inconsolata</option>
                                    <option value="Indie+Flower">Indie Flower</option>
                                    <option value="IM+Fell+DW+Pica">IM Fell DW Pica</option>
                                    <option value="IM+Fell+DW+Pica+SC">IM Fell DW Pica SC</option>
                                    <option value="IM+Fell+Double+Pica">IM Fell Double Pica</option>
                                    <option value="IM+Fell+Double+Pica+SC">IM Fell Double Pica SC</option>
                                    <option value="IM+Fell+English">IM Fell English</option>
                                    <option value="IM+Fell+English+SC">IM Fell English SC</option>
                                    <option value="IM+Fell+French+Canon">IM Fell French Canon</option>
                                    <option value="IM+Fell+French+Canon+SC">IM Fell French Canon SC</option>
                                    <option value="IM+Fell+Great+Primer">IM Fell Great Primer</option>
                                    <option value="IM+Fell+Great+Primer+SC">IM Fell Great Primer SC</option>
                                    <option value="Irish+Grover">Irish Grover</option>
                                    <option value="Irish+Growler">Irish Growler</option>
                                    <option value="Istok+Web">Istok Web</option>
                                    <option value="Josefin+Sans">Josefin Sans Regular 400</option>
                                    <option value="Josefin+Slab">Josefin Slab Regular 400</option>
                                    <option value="Judson">Judson</option>
                                    <option value="Jura"> Jura Regular</option>
                                    <option value="Jura:500"> Jura 500</option>
                                    <option value="Jura:600"> Jura 600</option>
                                    <option value="Just+Another+Hand">Just Another Hand</option>
                                    <option value="Just+Me+Again+Down+Here">Just Me Again Down Here</option>
                                    <option value="Kameron">Kameron</option>
                                    <option value="Kenia">Kenia</option>
                                    <option value="Kranky">Kranky</option>
                                    <option value="Kreon">Kreon</option>
                                    <option value="Kristi">Kristi</option>
                                    <option value="La+Belle+Aurore">La Belle Aurore</option>
                                    <option value="Lato:100">Lato 100</option>
                                    <option value="Lato:100italic">Lato 100 (plus italic)</option>
                                    <option value="Lato:300">Lato Light 300</option>
                                    <option value="Lato">Lato</option>
                                    <option value="Lato:bold">Lato Bold 700</option>
                                    <option value="Lato:900">Lato 900</option>
                                    <option value="League+Script">League Script</option>
                                    <option value="Lekton"> Lekton </option>
                                    <option value="Limelight"> Limelight </option>
                                    <option value="Lobster">Lobster</option>
                                    <option value="Lobster Two">Lobster Two</option>
                                    <option value="Lora">Lora</option>
                                    <option value="Love+Ya+Like+A+Sister">Love Ya Like A Sister</option>
                                    <option value="Loved+by+the+King">Loved by the King</option>
                                    <option value="Luckiest+Guy">Luckiest Guy</option>
                                    <option value="Maiden+Orange">Maiden Orange</option>
                                    <option value="Mako">Mako</option>
                                    <option value="Maven+Pro"> Maven Pro</option>
                                    <option value="Maven+Pro:500"> Maven Pro 500</option>
                                    <option value="Maven+Pro:700"> Maven Pro 700</option>
                                    <option value="Maven+Pro:900"> Maven Pro 900</option>
                                    <option value="Meddon">Meddon</option>
                                    <option value="MedievalSharp">MedievalSharp</option>
                                    <option value="Megrim">Megrim</option>
                                    <option value="Merriweather">Merriweather</option>
                                    <option value="Metrophobic">Metrophobic</option>
                                    <option value="Michroma">Michroma</option>
                                    <option value="Miltonian Tattoo">Miltonian Tattoo</option>
                                    <option value="Miltonian">Miltonian</option>
                                    <option value="Modern Antiqua">Modern Antiqua</option>
                                    <option value="Monofett">Monofett</option>
                                    <option value="Molengo">Molengo</option>
                                    <option value="Mountains of Christmas">Mountains of Christmas</option>
                                    <option value="Muli:300">Muli Light</option>
                                    <option value="Muli">Muli Regular</option>
                                    <option value="Neucha">Neucha</option>
                                    <option value="Neuton">Neuton</option>
                                    <option value="News+Cycle">News Cycle</option>
                                    <option value="Nixie+One">Nixie One</option>
                                    <option value="Nobile">Nobile</option>
                                    <option value="Nova+Cut">Nova Cut</option>
                                    <option value="Nova+Flat">Nova Flat</option>
                                    <option value="Nova+Mono">Nova Mono</option>
                                    <option value="Nova+Oval">Nova Oval</option>
                                    <option value="Nova+Round">Nova Round</option>
                                    <option value="Nova+Script">Nova Script</option>
                                    <option value="Nova+Slim">Nova Slim</option>
                                    <option value="Nova+Square">Nova Square</option>
                                    <option value="Nunito:light"> Nunito Light</option>
                                    <option value="Nunito"> Nunito Regular</option>
                                    <option value="OFL+Sorts+Mill+Goudy+TT">OFL Sorts Mill Goudy TT</option>
                                    <option value="Old+Standard+TT">Old Standard TT</option>
                                    <option value="Open+Sans:300">Open Sans light</option>
                                    <option value="Open+Sans">Open Sans regular</option>
                                    <option value="Open+Sans:600">Open Sans 600</option>
                                    <option value="Open+Sans:800">Open Sans bold</option>
                                    <option value="Open+Sans+Condensed:300">Open Sans Condensed</option>
                                    <option value="Orbitron">Orbitron Regular (400)</option>
                                    <option value="Orbitron:500">Orbitron 500</option>
                                    <option value="Orbitron:700">Orbitron Regular (700)</option>
                                    <option value="Orbitron:900">Orbitron 900</option>
                                    <option value="Oswald">Oswald</option>
                                    <option value="Over+the+Rainbow">Over the Rainbow</option>
                                    <option value="Reenie+Beanie">Reenie Beanie</option>
                                    <option value="Pacifico">Pacifico</option>
                                    <option value="Patrick+Hand">Patrick Hand</option>
                                    <option value="Paytone+One">Paytone One</option>
                                    <option value="Permanent+Marker">Permanent Marker</option>
                                    <option value="Philosopher">Philosopher</option>
                                    <option value="Play">Play</option>
                                    <option value="Playfair+Display"> Playfair Display </option>
                                    <option value="Podkova"> Podkova </option>
                                    <option value="PT+Sans">PT Sans</option>
                                    <option value="PT+Sans+Narrow">PT Sans Narrow</option>
                                    <option value="PT+Sans+Narrow:regular,bold">PT Sans Narrow (plus bold)</option>
                                    <option value="PT+Serif">PT Serif</option>
                                    <option value="PT+Serif Caption">PT Serif Caption</option>
                                    <option value="Puritan">Puritan</option>
                                    <option value="Quattrocento">Quattrocento</option>
                                    <option value="Quattrocento+Sans">Quattrocento Sans</option>
                                    <option value="Radley">Radley</option>
                                    <option value="Raleway:100">Raleway</option>
                                    <option value="Redressed">Redressed</option>
                                    <option value="Rock+Salt">Rock Salt</option>
                                    <option value="Rokkitt">Rokkitt</option>
                                    <option value="Ruslan+Display">Ruslan Display</option>
                                    <option value="Schoolbell">Schoolbell</option>
                                    <option value="Shadows+Into+Light">Shadows Into Light</option>
                                    <option value="Shanti">Shanti</option>
                                    <option value="Sigmar+One">Sigmar One</option>
                                    <option value="Six+Caps">Six Caps</option>
                                    <option value="Slackey">Slackey</option>
                                    <option value="Smythe">Smythe</option>
                                    <option value="Sniglet:800">Sniglet</option>
                                    <option value="Special+Elite">Special Elite</option>
                                    <option value="Stardos+Stencil">Stardos Stencil</option>
                                    <option value="Sue+Ellen+Francisco">Sue Ellen Francisco</option>
                                    <option value="Sunshiney">Sunshiney</option>
                                    <option value="Swanky+and+Moo+Moo">Swanky and Moo Moo</option>
                                    <option value="Syncopate">Syncopate</option>
                                    <option value="Tangerine">Tangerine</option>
                                    <option value="Tenor+Sans"> Tenor Sans </option>
                                    <option value="Terminal+Dosis+Light">Terminal Dosis Light</option>
                                    <option value="The+Girl+Next+Door">The Girl Next Door</option>
                                    <option value="Tinos">Tinos</option>
                                    <option value="Ubuntu">Ubuntu</option>
                                    <option value="Ultra">Ultra</option>
                                    <option value="Unkempt">Unkempt</option>
                                    <option value="UnifrakturCook:bold">UnifrakturCook</option>
                                    <option value="UnifrakturMaguntia">UnifrakturMaguntia</option>
                                    <option value="Varela">Varela</option>
                                    <option value="Varela Round">Varela Round</option>
                                    <option value="Vibur">Vibur</option>
                                    <option value="Vollkorn">Vollkorn</option>
                                    <option value="VT323">VT323</option>
                                    <option value="Waiting+for+the+Sunrise">Waiting for the Sunrise</option>
                                    <option value="Wallpoet">Wallpoet</option>
                                    <option value="Walter+Turncoat">Walter Turncoat</option>
                                    <option value="Wire+One">Wire One</option>
                                    <option value="Yanone+Kaffeesatz">Yanone Kaffeesatz</option>
                                    <option value="Yanone+Kaffeesatz:300">Yanone Kaffeesatz:300</option>
                                    <option value="Yanone+Kaffeesatz:400">Yanone Kaffeesatz:400</option>
                                    <option value="Yanone+Kaffeesatz:700">Yanone Kaffeesatz:700</option>
                                    <option value="Yeseva+One">Yeseva One</option>
                                    <option value="Zeyada">Zeyada</option> 
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="submit" name="phpr_styling" class="btn btn-success" value="<?php echo $lang['cp_settings_save_changes']; ?>" />
                    </form>
                </div>
            </div>
        </div>

    <!-- ####### HEADER for logged in users ############################################################## -->
    <?php } else { ?>
        <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
    <?php } ?>

</body>
</html>