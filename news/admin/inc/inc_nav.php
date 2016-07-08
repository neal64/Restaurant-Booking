		<nav class="navbar navbar-default" role="navigation">
		  <div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand letheLogoMini" href="?p=dashboard">Lethe</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
			  <ul class="nav navbar-nav">
				<?php 
					$letheNav = '';
					foreach($lethe_modules as $k=>$v){
						$lp = str_replace('?p=','',$v['page']);
						if(permCheck($lp)){
						$letheNav.='<li class="dropdown">
								<a href="'. $v['page'] .'" class="dropdown-toggle" data-toggle="dropdown"><span class="'. $v['icon'] .'"></span> '. $v['title'] .' <span class="caret"></span></a>
								  <ul class="dropdown-menu" role="menu">'.PHP_EOL;
								  foreach($v['contents'] as $ck=>$cv){
									$lp = str_replace('?p=','',$cv['page']);
									if(permCheck($lp)){
										$letheNav.='<li><a href="'. $cv['page'] .'"><span class="'. $cv['icon'] .'"></span> '. $cv['title'] .'</a></li>'.PHP_EOL;
									}
								  }
						$letheNav.='
								  </ul>
							</li>';
						}
					} echo($letheNav);
				?>
			  </ul>
			<ul class="nav navbar-nav navbar-right">
				<li></li>
				<?php if(LETHE_AUTH_MODE==2){?>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></a>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="?p=settings/users"><span class="glyphicon glyphicon-user"></span> <?php echo(letheglobal_administrators);?></a></li>
					<li><a href="?p=settings/general"><span class="glyphicon glyphicon-wrench"></span> <?php echo(letheglobal_general_settings);?></a></li>
					<li><a href="?p=settings/submission"><span class="glyphicon glyphicon-send"></span> <?php echo(letheglobal_submission_accounts);?></a></li>
				  </ul>
				</li>
				<?php }?>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="flag flag-<?php echo(LOADED_LANG);?>"></span> <span class="caret"></span></a>
				  <ul class="dropdown-menu lethe-langs" role="menu">
					<?php 
					foreach($SLNG_LIST as $k=>$v){
						echo('<li><a href="pg.lang.php?l='. $k .'&amp;p='. $p .'"><span class="flag flag-'. $k .'"></span> '. showIn($v['sname'],'page') .'</a></li>');
					}?>
				  </ul>
				</li>
				<li>
				  <a href="pg.login.php?loginPos=logout"><span class="glyphicon glyphicon-off"></span></a>
				</li>
			</ul>
			  
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>