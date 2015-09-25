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

  function ug_city_select($updateLocOnChange = true){

    $ucUserGroupCities = get_terms(
      array('city')
    );

    if($updateLocOnChange){
      $ucUserGroupCitiesSelect  = '<select data-location-select id="ug_closest_location" class="ug_closest_location">';
    } else {
      $ucUserGroupCitiesSelect  = '<select id="ug_closest_location" class="ug_closest_location">';
    }
    $ucUserGroupCitiesSelect .= '<option class="hidden">Select</option>';
    foreach($ucUserGroupCities as $ucUserGroupCity){
      if($updateLocOnChange){
        $ucUserGroupCitiesSelect .= '<option id="'. $ucUserGroupCity->term_id .'" value="' . get_term_link($ucUserGroupCity->name, 'city') . '">' . $ucUserGroupCity->name . '</option>';
      } else {
        $ucUserGroupCitiesSelect .= '<option id="'. $ucUserGroupCity->term_id .' value="' . $ucUserGroupCity->name . '">' . $ucUserGroupCity->name . '</option>';
      }
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
        'label' => __( 'User Group Location' ),
        'rewrite' => array( 'slug' => 'user-group-location' ),
        'hierarchical' => true
      )
    );
  }
  add_action( 'init', 'cities_init' );

  function ug_name_init() {
    register_taxonomy(
      'ug-name',
      'espresso_events',
      array(
        'label' => __( 'User Group Name' ),
        'rewrite' => array( 'slug' => 'user-group-name' ),
        'hierarchical' => true
      )
    );
  }
  add_action( 'init', 'ug_name_init' );


  // filter for tags (as a taxonomy) with comma
//  replace '--' with ', ' in the output - allow tags with comma this way

if(!is_admin()){ // make sure the filters are only called in the frontend

  $custom_taxonomy_type = 'city'; // here goes your taxonomy type

  function comma_taxonomy_filter($tag_arr){
    global $custom_taxonomy_type;
    $tag_arr_new = $tag_arr;
    if($tag_arr->taxonomy == $custom_taxonomy_type && strpos($tag_arr->name, '--')){
      $tag_arr_new->name = str_replace('--',', ',$tag_arr->name);
    }
    return $tag_arr_new;
  }
  add_filter('get_'.$custom_taxonomy_type, comma_taxonomy_filter);

  function comma_taxonomies_filter($tags_arr){
    $tags_arr_new = array();
    foreach($tags_arr as $tag_arr){
      $tags_arr_new[] = comma_taxonomy_filter($tag_arr);
    }
    return $tags_arr_new;
  }
  add_filter('get_the_taxonomies',  comma_taxonomies_filter);
  add_filter('get_terms',       comma_taxonomies_filter);
  add_filter('get_the_terms',     comma_taxonomies_filter);
}

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



  // ----------------------------------------------------------
  // geo-location
  // ----------------------------------------------------------


  function load_geo_scripts() {
	  wp_localize_script( 'ajax-script', 'ajax_object.ajaxurl', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	//wp_register_script('googlemaps', 'http://maps.googleapis.com/maps/api/js?' . $locale . '&key=' . GOOGLE_MAPS_V3_API_KEY . '&sensor=false', false, '3');
	wp_register_script('googlemaps', 'http://maps.googleapis.com/maps/api/js?sensor=false', false, '3');
	wp_register_script( 'ug_do_geolocate', get_template_directory_uri() . '/js/ug_geolocate.js' );
	wp_enqueue_script('googlemaps');
	wp_enqueue_script('ug_do_geolocate');
	wp_localize_script( 'ug_do_geolocate', 'ug_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	add_action( 'wp_enqueue_scripts', 'load_geo_scripts' );
    add_action( 'wp_ajax_uc_ajax_request_city', 'uc_ajax_request_city' );
	add_action( 'wp_ajax_nopriv_uc_ajax_request_city', 'uc_ajax_request_city' );




	function uc_ajax_request_city() {

		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {  //check to see if this is ajax

			if (isset($_POST["lat"]) && !empty($_POST["lat"])) { //Checks if action value exists
				/*$action = $_POST["action"];
				switch($action) { //Switch case for value of action
				case "test": test_function(); break;
				}
				*/

				$lat = "";
				$lng = "";

				$lat = $_POST["lat"];
				$lng = $_POST["lng"];


				global $wpdb;

				$latitude = $lat;
				$longitude = $lng;

				// Build the spherical geometry SQL string
				$earthRadius = '3959'; // In miles

				$sql = "
					SELECT
						ROUND(
							$earthRadius * ACOS(
								SIN( $latitude*PI()/180 ) * SIN( term_latitude*PI()/180 )
								+ COS( $latitude*PI()/180 ) * COS( term_latitude*PI()/180 )  *  COS( (term_longitude*PI()/180) - ($longitude*PI()/180) )   )
						, 1)
						AS distance,
						{$wpdb->prefix}terms_meta.term_id,
						term_latitude,
						term_longitude,
						term_city,
						term_state,
						name
					FROM {$wpdb->prefix}terms_meta

					INNER JOIN {$wpdb->prefix}terms
					ON {$wpdb->prefix}terms_meta.term_id = {$wpdb->prefix}terms.term_id

					WHERE {$wpdb->prefix}terms_meta.term_latitude is not NULL AND {$wpdb->prefix}terms_meta.term_longitude is not NULL

					ORDER BY
						distance ASC
					LIMIT 1";

				// Search the database for the nearest agents
				$result = $wpdb->get_results($sql);

				$result = $result[0];

				$return["json"] = json_encode($result);

				//print_r($return);
				echo json_encode($return);

				//exit;

			}	//end post

		} //end if ajax

		// Always die in functions echoing ajax content
	   wp_die();
	} //end



  // ----------------------------------------------------------
  // end geo-location
  // ----------------------------------------------------------


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


  function ee_default_ticket_selector_one_all_events() {
   ?>
   <script type="text/javascript">
   jQuery(document).ready(function () {
   jQuery("select.ticket-selector-tbl-qty-slct option[value='0']").remove();
  });
   </script>
   <?php
   }
  add_action( 'wp_footer', 'ee_default_ticket_selector_one_all_events' );
