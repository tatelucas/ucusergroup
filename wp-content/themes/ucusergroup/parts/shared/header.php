<header id="header" class="navbar-default">
  <div class="container-fluid">
    <div class="row">
      <div class="navbar-brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<? echo get_template_directory_uri() ?>/images/logo.jpg" width="248" height="51" alt="Skype for Business Users Group"></a>
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

        <?php $mainNavItems = wp_get_nav_menu_items('Main Nav'); ?>
        <nav class="nav-bar">
          <ul id="nav">
            <?php foreach($mainNavItems as $mainNavItem): ?>
            <li><a href="#"><span class="style-color"><?php echo $mainNavItem->title; ?></span><?php echo $mainNavItem->description; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>
        <form action="<?php echo home_url( '/' ); ?>" class="search-form" role="search" method="get">
          <fieldset>
            <button type="submit" class="btn icon-search"></button>
            <input type="search" class="form-control" placeholder="Find a Meetup" name="s">
          </fieldset>
        </form>
        <div class="btn-group">
          <?php if(is_user_logged_in()): ?>
            <a href="<?php echo wp_logout_url(); ?>" class="btn btn-primary">Logout</a>
          <?php else: ?>
            <a href="<?php echo get_permalink(103); ?>" class="btn btn-primary">Register</a>
            <a href="<?php echo get_permalink(105); ?>" class="btn btn-primary">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</header>
