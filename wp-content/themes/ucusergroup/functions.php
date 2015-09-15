<?php

// ----------------------------------------------------------
// sidebars
// ----------------------------------------------------------

  if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>',
    ));
  }

  // ----------------------------------------------------------
  // user group city select
  // ----------------------------------------------------------

  function ug_city_select(){

    $ucUserGroupCities = get_terms(
      array('city')
    );

    $ucUserGroupCitiesSelect  = '<select data-location-select>';
    $ucUserGroupCitiesSelect .= '<option class="hidden">Select</option>';
    foreach($ucUserGroupCities as $ucUserGroupCity){
      $ucUserGroupCitiesSelect .= '<option value="' .  get_term_link($ucUserGroupCity->name, 'city') . '">' . str_replace("User Group", "", $ucUserGroupCity->name) . '</option>';
    }
    $ucUserGroupCitiesSelect .= '</select>';

    echo $ucUserGroupCitiesSelect;

  }

  // ----------------------------------------------------------
  // venue location
  // ----------------------------------------------------------

  function ug_venue_location($VNU_ID = 0, $echo = TRUE ) {
    EE_Registry::instance()->load_helper( 'Venue_View' );
    $venue = EEH_Venue_View::get_venue( $VNU_ID );

    if($venue) {
      $venueCity = $venue->city();
      $venueState = $venue->state();
    }

    if ( $echo && $venue ) {
      if($venueCity)
        echo  $venueCity;
      if($venueCity && $venueState)
        echo ', ';
      if($venueState)
        echo $venueState;
      return '';
    }
    if(isset($type))
      return EEH_Venue_View::venue_address( $type, $VNU_ID );
  }

  // ----------------------------------------------------------
  // image sizes
  // ----------------------------------------------------------

  add_image_size( 'homepage-blog-size', 358, 230, true ); // 220 pixels wide by 180 pixels tall, hard crop mode


  // ----------------------------------------------------------
  // custom taxonomies
  // ----------------------------------------------------------

  function cities_init() {
    register_taxonomy(
      'city',
      'espresso_events',
      array(
        'label' => __( 'User Group' ),
        'rewrite' => array( 'slug' => 'user-group' ),
        'hierarchical' => true
      )
    );
  }
  add_action( 'init', 'cities_init' );

  // function user_state_init() {
  //   register_taxonomy(
  //     'user_state',
  //     'user',
  //     array(
  //       'label' => __( 'User State' ),
  //       'rewrite' => array( 'slug' => 'user-state' )
  //     )
  //   );
  // }
  // add_action( 'init', 'user_state_init' );

  // ----------------------------------------------------------
  // user state on register
  // ----------------------------------------------------------

  add_action('user_register', 'register_extra_fields');
  function register_extra_fields ( $user_id, $password = "", $meta = array() ){
    update_user_meta( $user_id, 'user_state', $_POST['user_state'] );
  }


  // ----------------------------------------------------------
  // custom post types
  // ----------------------------------------------------------

  add_action( 'init', 'create_sponsor_posttype' );
  function create_sponsor_posttype() {
    register_post_type( 'sponsor',
      array(
        'labels' => array(
          'name' => __( 'Sponsors' ),
          'singular_name' => __( 'Sponsor' ),
          'menu_name' => __('Sponsors'),
          'add_new_item'       => __( 'Add New Sponsor' ),
          'new_item'           => __( 'New Sponsor' ),
          'edit_item'          => __( 'Edit Sponsor' ),
          'view_item'          => __( 'View Sponsor' ),
          'all_items'          => __( 'All Sponsors' )
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'sponsors'),
        'supports' => array('title', 'thumbnail')
      )
    );
  }

  // ----------------------------------------------------------
  // hide admin bar if user is not admin
  // ----------------------------------------------------------

  add_action('after_setup_theme', 'remove_admin_bar');

  function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
  }


  // ==========================================================
  // ==========================================================


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
