<header id="header" class="navbar-default">
  <div class="container-fluid">
    <div class="row">
      <div class="navbar-brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<? echo get_template_directory_uri() ?>/images/logobig.jpg" width="248" height="51" alt="Skype for Business Users Group"></a>
      </div>
      <div class="visible-xs phone-hold">
        <div class="pull-left img-hold">
          <a href="#"><img class="image-responsive" src="<? echo get_template_directory_uri() ?>/images/logo-2.jpg" height="71" width="93" alt="image description"></a>
        </div>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
	  
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

<?php
            wp_nav_menu( array(
                'menu'              => 'primary',
                'theme_location'    => 'primary',
                'depth'             => 2,
                'container'         => 'nav',
                'container_class'   => 'nav-bar',
                //'container_id'      => 'bs-example-navbar-collapse-1',
                'menu_class'        => '',  //nav navbar-nav
				'menu_id'			=> 'nav',
                'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                'walker'            => new WP_Bootstrap_Navwalker())
            );
        ?>	  	  

        <form action="<?php echo home_url( '/' ); ?>" class="search-form" role="search" method="get">
          <fieldset>
            <button type="submit" class="btn icon-search"></button>
            <input type="search" class="form-control" placeholder="Search" name="s">
          </fieldset>
        </form>
        <div class="btn-group">
          <?php if(is_user_logged_in()): ?>
		    <a href="/account/" class="btn btn-primary">Profile</a>
            <a href="<?php echo wp_logout_url(); ?>" class="btn btn-primary">Logout</a>
          <?php else: ?>
            <a href="<?php echo wp_registration_url(); ?>" class="btn btn-primary">Register</a>
            <a href="<?php echo wp_login_url(); ?>" class="btn btn-primary">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</header>
