<nav class="navbar navigation-menu" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php echo $lang['MENU']; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <div class="restaurant-menu-holder container">
      <ul class="restaurant-main-menu  pull-left">
		<li>
			<a href="<?php echo $CONF['installation_path']; ?>" title="<?php echo $lang['HOME']; ?>">
				<i class="fa fa-home"></i>
				<span class="nav-item"><?php echo $lang['HOME']; ?></span>
			</a>
		</li>

<!--		<li>
			<a href="<?php echo $CONF['installation_path']; ?>page/menus/" title="<?php echo $lang['MENUS']; ?>">
				<i class="fa fa-book"></i>
				<span class="nav-item"><?php echo $lang['MENUS']; ?></span>
			</a>
		</li>-->

		<li>
			<a href="<?php echo $CONF['installation_path']; ?>page/book-a-table/" title="<?php echo $lang['BookTables']; ?>">
				<i class="fa fa-th-large"></i>
				<span class="nav-item"><?php echo $lang['BookTables']; ?></span>
			</a>
		</li>

<!--		<li>
			<a href="<?php echo $CONF['installation_path']; ?>page/catering/" title="<?php echo $lang['Catering']; ?>">
				<i class="fa fa-cutlery"></i>
				<span class="nav-item"><?php echo $lang['Catering']; ?></span>
			</a>
		</li>-->

<!--		<li>
			<a href="<?php echo $CONF['installation_path']; ?>page/events/" title="<?php echo $lang['Events']; ?>">
				<i class="fa fa-bookmark"></i>
				<span class="nav-item"><?php echo $lang['Events']; ?></span>
			</a>
		</li>-->

<!--        <li>
            <a href="<?php echo $CONF['installation_path']; ?>page/gallery/" title="<?php echo $lang['Galleries']; ?>">
                <i class="fa fa-picture-o"></i>
                <span class="nav-item"><?php echo $lang['Galleries']; ?></span>
            </a>
        </li>-->

		<li>
			<a href="<?php echo $CONF['installation_path']; ?>page/about-us/" title="About us">
				<i class="fa fa-glass"></i>
				<span class="nav-item">About us</span>
			</a>
		</li>

		<li>
			<a href="<?php echo $CONF['installation_path']; ?>page/contact/" title="<?php echo $lang['Contact']; ?>">
				<i class="fa fa-map-marker"></i>
				<span class="nav-item"><?php echo $lang['Contact']; ?></span>
			</a>
		</li>
      </ul>

      </div>
    </div>
  </div>
</nav>
