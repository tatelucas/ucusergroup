<?php
/**
 * Template Name: Home Page
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

    <div class="banner">
      <div class="bg-stretch">
        <img src="<? echo get_template_directory_uri() ?>/images/img-1.jpg" width="1170" height="505" alt="image description">
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-5">
            <h1><span>Welcome to Skype for Business Users Group</span>The definitive meetup for UC Professionals</h1>
            <button type="button" class="btn btn-success hidden-xs"><i class="icon-search"></i>Find a Meetup near you</button>
          </div>
        </div>
      </div>
    </div>

    <div class="location-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
            <div class="visible-xs form-hold">
              <form action="#" class="search-form" role="search">
                <fieldset>
                  <button type="submit" class="btn icon-search"></button>
                  <input type="search" class="form-control" placeholder="Find a Meetup">
                </fieldset>
              </form>
              <div class="btn-group">
                <button type="button" class="btn btn-primary">Register</button>
              </div>
            </div>
            <h1>Upcoming Meetups Near You</h1>
            <div class="select-form">
              <span class="text-hold">location<i class="icon-mapmarker"></i></span>
              <?php uc_city_select(); ?>
            </div>

            <?php the_field('upcoming_meetups_text'); ?>

          </div>
        </div>

        <div class="gray-box">
          <?php
            $attsNextThreeEvents = array(
              'title' => NULL,
              'limit' => 3,
              'css_class' => NULL,
              'show_expired' => FALSE,
              'month' => NULL,
              'category_slug' => NULL,
              'order_by' => 'start_date',
              'sort' => 'ASC'
            );
            global $wp_query;
            $wp_query = new EE_Event_List_Query( $attsNextThreeEvents );
            if (have_posts()) : while (have_posts()) : the_post();
          ?>
            <div class="col-sm-4 col-xs-12 location-col">
              <a href="<?php the_permalink(); ?>">
                <time class="time-hold" datetime="2015-07-23"><span><?php espresso_event_date('j', ' '); ?></span><?php espresso_event_date('F', ' '); ?></time>
                <span class="name-hold"><?php the_title(); ?></span>
                <span class="text-hold"><?php uc_location(); ?></span>
              </a>
            </div>
          <?php
            endwhile;
            endif;
            wp_reset_query();
            wp_reset_postdata();
          ?>
        </div>

        <div class="row">
          <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0">
            <h2>Other meetups upcoming soon</h2>
            <ul class="location-list">
              <?php
                $attsUpcomingEvents = array(
                  'title' => NULL,
                  'limit' => 16,
                  'css_class' => NULL,
                  'show_expired' => FALSE,
                  'month' => NULL,
                  'category_slug' => NULL,
                  'order_by' => 'start_date',
                  'sort' => 'ASC',
                  'offset' => 3
                );
                global $wp_query;
                $wp_query = new EE_Event_List_Query( $attsUpcomingEvents );
                if (have_posts()) : while (have_posts()) : the_post();
              ?>
                <li><a href="<?php the_permalink(); ?>"><?php espresso_event_date('F j', ' '); ?> &ndash; <?php uc_location(); ?></a></li>
              <?php
                endwhile;
                endif;
                wp_reset_query();
                wp_reset_postdata();
              ?>
            </ul>
            <button type="button" class="btn btn-success"><i class="icon-search"></i>View all meetups</button>
          </div>
        </div>
      </div>
    </div>

    <div class="info-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <?php the_field('information_column_1'); ?>
          </div>
          <div class="col-sm-6">
            <?php the_field('information_column_2'); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <button type="button" class="btn btn-info"><i class="icon-circle-plus"></i>Find out more</button>
          </div>
        </div>
      </div>
      <div class="picture-hold">
        <img src="<? echo get_template_directory_uri() ?>/images/img-2.png" height="394" width="704" alt="image description">
      </div>
    </div>


    <div class="subscribe-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <?php the_field('join_skype_for_business_text'); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <form action="#" class="subscribe-form">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" type="text" placeholder="Name">
                </div>
                <div class="form-group">
                  <input class="form-control" type="email" placeholder="Email">
                </div>
                <div class="form-group">
                  <select>
                    <option class="hidden">Select State</option>
                    <option>Chicago</option>
                    <option>Texas</option>
                    <option>California</option>
                    <option>Washington</option>
                    <option>Montana</option>
                  </select>
                </div>
                <button type="button" class="btn btn-success"><i class="icon-circle-check"></i>Join Today</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="sponsor-section">
      <div class="container-fluid">
        <div class="col-sm-12">
          <h1>Our National Sponsors</h1>
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div class="item active">
                <ul class="sponsor-list">
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-1.jpg" height="83" width="193" alt="actiance"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-2.jpg" height="83" width="175" alt="aruba networks"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-3.jpg" height="83" width="301" alt="audio codes"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-4.jpg" height="83" width="194" alt="talk computer"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-5.jpg" height="83" width="250" alt="enghouse interactive"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-6.jpg" height="82" width="108" alt="grandstream"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-7.jpg" height="83" width="47" alt="il"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-8.jpg" height="83" width="306" alt="plantronics"></a></li>
                </ul>
              </div>
              <div class="item">
                <ul class="sponsor-list">
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-1.jpg" height="83" width="193" alt="actiance"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-2.jpg" height="83" width="175" alt="aruba networks"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-3.jpg" height="83" width="301" alt="audio codes"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-4.jpg" height="83" width="194" alt="talk computer"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-5.jpg" height="83" width="250" alt="enghouse interactive"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-6.jpg" height="82" width="108" alt="grandstream"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-7.jpg" height="83" width="47" alt="il"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-8.jpg" height="83" width="306" alt="plantronics"></a></li>
                </ul>
              </div>
              <div class="item">
                <ul class="sponsor-list">
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-1.jpg" height="83" width="193" alt="actiance"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-2.jpg" height="83" width="175" alt="aruba networks"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-3.jpg" height="83" width="301" alt="audio codes"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-4.jpg" height="83" width="194" alt="talk computer"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-5.jpg" height="83" width="250" alt="enghouse interactive"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-6.jpg" height="82" width="108" alt="grandstream"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-7.jpg" height="83" width="47" alt="il"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-8.jpg" height="83" width="306" alt="plantronics"></a></li>
                </ul>
              </div>
            </div>
            <ol class="carousel-indicators">
              <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
              <li data-target="#carousel-example-generic" data-slide-to="1"></li>
              <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            </ol>
          </div>
        </div>
      </div>
    </div>


    <div class="news-section">
      <div class="container-fluid">
        <h1>Latest Users Group News</h1>
        <button type="button" class="btn btn-success"><i class="icon-bookmark"></i>View all news</button>
        <ul class="news-list row">
          <li class="col-sm-4">
            <a href="#"><img src="<? echo get_template_directory_uri() ?>/images/img-3.jpg" height="230" width="358" alt="image description"></a>
            <div class="text-block">
              <h2><a href="#">Winter 2015 Events and Ignite Conference Trip Giveaway!</a></h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....</p>
            </div>
          </li>
          <li class="col-sm-4">
            <a href="#"><img src="<? echo get_template_directory_uri() ?>/images/img-4.jpg" alt="image description" width="358" height="230"></a>
            <div class="text-block">
              <h2><a href="#">Welcome Sennheiser as a National Sponsor!</a></h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....</p>
            </div>
          </li>
          <li class="col-sm-4">
            <img src="<? echo get_template_directory_uri() ?>/images/img-5.jpg" height="230" width="358" alt="image description">
            <div class="text-block">
              <h2><a href="#">Welcome ComputerTalk as a National Sponsor!</a></h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....</p>
            </div>
          </li>
        </ul>
      </div>
    </div>


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>