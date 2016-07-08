<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- /.navbar-header -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a title="<?php echo $lang['HOMEPAGE_TITLE']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/">
                        <i class="fa fa-home fa-fw"></i>
                        <?php echo $lang['HOMEPAGE_TITLE']; ?>
                    </a>
                </li>
<!--                <li>
                    <a title="<?php echo $lang['cp_list_foods']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/list-menus.php">
                        <i class="fa fa-book fa-fw"></i>
                        <?php echo $lang['cp_list_foods']; ?>
                    </a>
                </li>-->
                <li>
                    <a title="<?php echo $lang['cp_tables']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/list-tables.php">
                        <i class="fa fa-th-large fa-fw"></i>
                        <?php echo $lang['cp_tables']; ?>
                    </a>
                </li>
                <li>
                    <a title="<?php echo $lang['orders']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/list-orders.php">
                        <i class="fa fa-shopping-cart fa-fw"></i>
                        <?php echo $lang['orders']; ?>
                    </a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-calendar fa-fw"></i> <?php echo $lang['Events']; ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/events-add.php">
                                <i class="fa fa-long-arrow-right fa-fw"></i>
                                <?php echo $lang['cp_event_adds']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/events-list.php">
                                <i class="fa fa-long-arrow-right fa-fw"></i>
                                <?php echo $lang['cp_event_list']; ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-quote-left fa-fw"></i> <?php echo $lang['testimonials']; ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/testimonials-add.php">
                                <i class="fa fa-long-arrow-right fa-fw"></i>
                                <?php echo $lang['testimonials_add']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/testimonials-list.php">
                                <i class="fa fa-long-arrow-right fa-fw"></i>
                                <?php echo $lang['testimonials_list']; ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a title="Rating & Review" href="<?php echo $CONF['installation_path']; ?>backend/parts/list-ratting.php">
                        <i class="fa fa-star fa-fw"></i>
                        Rating & Review
                    </a>
                </li>
                <li>
                    <a title="User Contact" href="<?php echo $CONF['installation_path']; ?>backend/parts/user-contact.php">
                        <i class="fa fa-star fa-fw"></i>
                        User Contact
                    </a>
                </li>
                <li>
                    <a title="<?php echo $lang['users']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/list-users.php">
                        <i class="fa fa-users fa-fw"></i>
                        <?php echo $lang['users']; ?>
                    </a>
                </li>
                <li>
                    <a title="<?php echo $lang['my_profile']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/user-profile.php">
                        <i class="fa fa-user fa-fw"></i>
                        <?php echo $lang['my_profile']; ?>
                    </a>
                </li>
                <li>
                    <a title="<?php echo $lang['HOMEPAGE_TITLE']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/about.php">
                        <i class="fa fa-heart fa-fw"></i>
                        <?php echo $lang['about_us']; ?>
                    </a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-cog fa-fw"></i> <?php echo $lang['cp_settings']; ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/settings-contact.php">
                                <i class="fa fa-map-marker fa-fw"></i>
                                <?php echo $lang['contact_infos']; ?>
                            </a>
                        </li>
<!--                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/settings-styling.php">
                                <i class="fa fa-adjust fa-fw"></i>
                                <?php echo $lang['styling_settings']; ?>
                            </a>
                        </li>-->
<!--                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/settings-languages.php">
                                <i class="fa fa-language fa-fw"></i>
                                <?php echo $lang['languages']; ?>
                            </a>
                        </li>-->
<!--                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/settings-social-media.php">
                                <i class="fa fa-share-alt fa-fw"></i>
                                <?php echo $lang['social_media']; ?>
                            </a>
                        </li>-->
                        <li>
                            <a href="<?php echo $CONF['installation_path']; ?>backend/parts/settings-payment.php">
                                <i class="fa fa-usd fa-fw"></i>
                                <?php echo $lang['payment_settings']; ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>