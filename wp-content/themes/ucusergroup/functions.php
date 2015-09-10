<?php

  // ----------------------------------------------------------
  // locations select
  // ----------------------------------------------------------

  function uc_city_select(){

    $ucUserGroupCities = get_terms(
      array('city')
    );

    $ucUserGroupCitiesSelect  = '<select data-location-select>';
    $ucUserGroupCitiesSelect .= '<option class="hidden">Select</option>';
    foreach($ucUserGroupCities as $ucUserGroupCity){
      $ucUserGroupCitiesSelect .= '<option value="' .  get_term_link($ucUserGroupCity->name, 'city') . '">' . $ucUserGroupCity->name. '</option>';
    }
    $ucUserGroupCitiesSelect .= '</select>';

    echo $ucUserGroupCitiesSelect;

  }

  function uc_location($VNU_ID = 0, $echo = TRUE ) {
    EE_Registry::instance()->load_helper( 'Venue_View' );
    $venue = EEH_Venue_View::get_venue( $VNU_ID );

    if($venue) {
      $venueCity = $venue->city();
      $venueState = $venue->state();
      $venueCountry = $venue->country() == 'US' ? 'USA' : $venue->country();
    }

    if ( $echo && $venue ) {
      if($venueCity)
        echo  $venueCity;
      if($venueCity && ($venueState || $venueCountry))
        echo ', ';
      if($venueState)
        echo $venueState;
      if($venueState && $venueCountry)
        echo', ';
      if($venueCountry)
        echo $venueCountry;
      return '';
    }
    if(isset($type))
      return EEH_Venue_View::venue_address( $type, $VNU_ID );
  }


  // ----------------------------------------------------------
  // custom taxonomies
  // ----------------------------------------------------------

  function cities_init() {
    register_taxonomy(
      'city',
      'espresso_events',
      array(
        'label' => __( 'User Group City' ),
        'rewrite' => array( 'slug' => 'city' ),
        'hierarchical' => true
      )
    );
  }
  add_action( 'init', 'cities_init' );

  function test_cat_init() {
    register_taxonomy(
      'tests',
      'post',
      array(
        'label' => __( 'Tests' ),
        'rewrite' => array( 'slug' => 'test' ),
        'hierarchical' => true
      )
    );
  }
  add_action( 'init', 'test_cat_init' );











// ----------------------------------------------------------
// starkers functions
// ----------------------------------------------------------

  require_once( 'external/starkers-utilities.php' );

  add_theme_support('post-thumbnails');
  add_theme_support('menus');

  add_action( 'wp_enqueue_scripts', 'script_enqueuer' );

  add_filter( 'body_class', 'add_slug_to_body_class' );

  function script_enqueuer() {}

  function starkers_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <?php if ( $comment->comment_approved == '1' ): ?>
    <li>
      <article id="comment-<?php comment_ID() ?>">
        <?php echo get_avatar( $comment ); ?>
        <h4><?php comment_author_link() ?></h4>
        <time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
        <?php comment_text() ?>
      </article>
    <?php endif;
  }

