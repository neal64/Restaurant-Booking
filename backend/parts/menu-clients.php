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
                    <a title="<?php echo $lang['HOMEPAGE_TITLE']; ?>" href="<?php echo $CONF['installation_path']; ?>">
                        <i class="fa fa-home fa-fw"></i>
                        <?php echo $lang['HOMEPAGE_TITLE']; ?>
                    </a>
                </li>
                <li>
                    <a title="<?php echo $lang['orders']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/list-orders.php">
                        <i class="fa fa-shopping-cart fa-fw"></i>
                        <?php echo $lang['orders']; ?>
                    </a>
                </li>
                <li>
                    <a title="<?php echo $lang['my_profile']; ?>" href="<?php echo $CONF['installation_path']; ?>backend/parts/user-profile.php">
                        <i class="fa fa-user fa-fw"></i>
                        <?php echo $lang['my_profile']; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>