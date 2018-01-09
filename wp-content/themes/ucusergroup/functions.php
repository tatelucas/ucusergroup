<?php

// Register Custom Navigation Walker
require_once('wp-bootstrap-navwalker.php');

register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'ucusergroup' ),
) );

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
      $ucUserGroupCitiesSelect  = '<select name="user_group" data-location-select id="ug_closest_location" class="ug_closest_location input">';
    } else {
      $ucUserGroupCitiesSelect  = '<select name="user_group" id="user_group" class="ug_closest_location input">';
    }
    $ucUserGroupCitiesSelect .= '<option value="">Select Local User Group</option>';
    foreach($ucUserGroupCities as $ucUserGroupCity){
      if($updateLocOnChange){
        $ucUserGroupCitiesSelect .= '<option id="'. $ucUserGroupCity->term_id .'" value="' . get_term_link($ucUserGroupCity->name, 'city') . '">' . $ucUserGroupCity->name . '</option>';
      } else {
        $ucUserGroupCitiesSelect .= '<option id="'. $ucUserGroupCity->term_id .'" value="'. $ucUserGroupCity->term_id . '">' . $ucUserGroupCity->name . '</option>';
      }
    }
    $ucUserGroupCitiesSelect .= '<option value="">
    None of the above
    </option>';
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


  //add cities to the admin menu
	function ug_cities_adjust_the_wp_menu() {
		add_menu_page(
			'Cities',
			'Cities',
			'add_users',
			'edit-tags.php?taxonomy=city',
			'',
			'div',
			6
		);
	}
	add_action( 'admin_menu', 'ug_cities_adjust_the_wp_menu', 999 );


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
    update_user_meta( $user_id, 'user_group', $_POST['user_group'] );
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

    wp_register_script('ucusergroup',  get_template_directory_uri() . '/js/jquery.main.js', false, '3');
  	wp_enqueue_script('ucusergroup');
  	wp_register_script('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', false, '3.3.5');
  	wp_enqueue_script('bootstrap');

	//wp_register_script('googlemaps', '//maps.googleapis.com/maps/api/js?' . $locale . '&key=' . GOOGLE_MAPS_V3_API_KEY . '&sensor=false', false, '3');
	wp_register_script('googlemaps', '//maps.googleapis.com/maps/api/js?sensor=false', false, '3');
	wp_register_script( 'ug_do_geolocate', get_template_directory_uri() . '/js/ug_geolocate.js' );
	wp_enqueue_script('googlemaps');
	wp_enqueue_script('ug_do_geolocate');
	wp_localize_script( 'ug_do_geolocate', 'ug_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}



	//set up ajax functions
	add_action( 'wp_enqueue_scripts', 'load_geo_scripts' );
    add_action( 'wp_ajax_uc_ajax_request_city', 'uc_ajax_request_city' );
	add_action( 'wp_ajax_nopriv_uc_ajax_request_city', 'uc_ajax_request_city' );
	add_action( 'wp_ajax_uc_ajax_closest_meetups', 'uc_ajax_closest_meetups' );
	add_action( 'wp_ajax_nopriv_uc_ajax_closest_meetups', 'uc_ajax_closest_meetups' );




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


	  //
	  // Event Espresso get upcoming closest events
	  //

    //for some reason, event espresso does not load here, so force it to load. - dwl
  include_once( EE_PUBLIC . 'template_tags.php' );


	function uc_ajax_closest_meetups() {
		//expects post $id of closest city, and (optional) $limit

		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {  //check to see if this is ajax

			if (isset($_POST["id"])) {
				$id = $_POST["id"];
				$limit = 3; //default limit
				if (isset($_POST["limit"]) && is_int($_POST["limit"])) {
					$limit = $_POST["limit"];
				}

            $attsNextThreeEvents = array(
              'title' => NULL,
              'limit' => $limit,
              'css_class' => NULL,
              'show_expired' => FALSE,
              'month' => NULL,
              'category_slug' => NULL,
			'tax_query' => array(
				array(
					'taxonomy' => 'city',
					'field'    => 'term_id',
					'terms'    => array( $id ),
				),
			),
              'order_by' => 'start_date',
              'sort' => 'ASC'
            );
            global $wp_query;
            //$wp_query = new EE_Event_List_Query( $attsNextThreeEvents );
			$wp_query = new EventEspresso\core\domain\services\wp_queries\EventListQuery( $attsNextThreeEvents );


            if (have_posts()) : while (have_posts()) : the_post();
              $userGroupNames = wp_get_post_terms($post->ID, 'ug-name');
              $userGroupName = $userGroupNames ? $userGroupNames[0] : '';
          ?>
            <div class="col-sm-4 col-xs-12 location-col">
              <a href="<?php the_permalink(); ?>">
                <time class="time-hold" datetime="<?php espresso_event_date('j', ' '); ?>"><span><?php espresso_event_date('j', ' '); ?></span><?php espresso_event_date('F', ' '); ?></time>
                <span class="name-hold">
                  <?php
                    if($userGroupName){
                      echo str_replace('--', ', ', $userGroupName->name);
                    } else {
                      ug_venue_location();
                    }
                  ?>
                </span>
                <span class="text-hold"><?php ug_venue_location(); ?></span>
              </a>
            </div>
          <?php
            endwhile;
			else:
			echo 'nothing';
            endif;
            wp_reset_query();
            wp_reset_postdata();


				//$return["json"] = json_encode($result);

				//print_r($return);
				//echo json_encode($return);

				exit;
			}	//end post

		} //end if ajax

		// Always die in functions echoing ajax content
	   wp_die();
	} //end


  // ----------------------------------------------------------
  // end geo-location
  // ----------------------------------------------------------


  // ------------------------------
  // custom fields for geo-location
  // ------------------------------

		// A callback function to add a custom field to our "cities" taxonomy
		function city_taxonomy_custom_fields($tag) {
		   // Check for existing taxonomy meta for the term you're editing
			$t_id = $tag->term_id; // Get the ID of the term you're editing
			$term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
		?>

			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="city_latitude"><?php _e('Latitude'); ?></label>
				</th>
				<td>
					<input type="text" name="term_meta[city_latitude]" id="term_meta[city_latitude]" size="25" style="width:60%;" value="<?php echo $term_meta['city_latitude'] ? $term_meta['city_latitude'] : ''; ?>"><br />
					<span class="description"><?php _e('Latitude'); ?></span>
				</td>
			</tr>

			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="city_longitude"><?php _e('Longitude'); ?></label>
				</th>
				<td>
					<input type="text" name="term_meta[city_longitude]" id="term_meta[city_longitude]" size="25" style="width:60%;" value="<?php echo $term_meta['city_longitude'] ? $term_meta['city_longitude'] : ''; ?>"><br />
					<span class="description"><?php _e('Longitude'); ?></span>
				</td>
			</tr>

		<?php
		}


		// A callback function to save our extra taxonomy field(s)
		function save_cities_taxonomy_custom_fields( $term_id ) {
			if ( isset( $_POST['term_meta'] ) ) {
				global $wpdb;
				$t_id = $term_id;
				$term_meta = get_option( "taxonomy_term_$t_id" );
				$cat_keys = array_keys( $_POST['term_meta'] );
					foreach ( $cat_keys as $key ){
					//if ( isset( $_POST['term_meta'][$key] ) ){
					//assume is set, so we can catch removals and blank submissions, ie deletes
						$term_meta[$key] = $_POST['term_meta'][$key];

							if ($key == "city_latitude") {

								$latitude = !empty($term_meta[$key]) ? $term_meta[$key] : "NULL";

								$latExists = "SELECT EXISTS(SELECT 1 FROM {$wpdb->prefix}terms_meta WHERE term_id=$t_id)";
								$latExists = $wpdb->get_results($latExists, ARRAY_N);

								//update DB

								if ($latExists[0][0] == 1) {
									$sql = "
										UPDATE {$wpdb->prefix}terms_meta
										SET term_latitude=$latitude
										WHERE term_id=$t_id
									";
								} else {
									$sql = "
										INSERT INTO {$wpdb->prefix}terms_meta
										(term_id, term_latitude)
										VALUES ($t_id, $latitude)
									";
								}

									// update the terms_meta fields so the lat-lng is db searchable
									$resultone = $wpdb->get_results($sql);
							}

							if ($key == "city_longitude") {

								$longitude = !empty($term_meta[$key]) ? $term_meta[$key] : "NULL";

								$lngExists = "SELECT EXISTS(SELECT 1 FROM {$wpdb->prefix}terms_meta WHERE term_id=$t_id)";
								$lngExists = $wpdb->get_results($lngExists, ARRAY_N);

								//update DB

								if ($lngExists[0][0] == 1) {
									$sql = "
										UPDATE {$wpdb->prefix}terms_meta
										SET term_longitude=$longitude
										WHERE term_id=$t_id
									";
								} else {
									$sql = "
										INSERT INTO {$wpdb->prefix}terms_meta
										(term_id, term_longitude)
										VALUES ($t_id, $longitude)
									";
								}

									// update the terms_meta fields so the lat-lng is db searchable
									$resulttwo = $wpdb->get_results($sql);
							}

					//}
				}
				//save the option array
				update_option( "taxonomy_term_$t_id", $term_meta );
			}
		}


	// Add the fields to the "cities" taxonomy, using our callback function
	add_action( 'city_edit_form_fields', 'city_taxonomy_custom_fields', 10, 2 );

	// Save the changes made on the "presenters" taxonomy, using our callback function
	add_action( 'edited_city', 'save_cities_taxonomy_custom_fields', 10, 2 );

  // ------------------------------
  // end custom fields for geo-location
  // ------------------------------





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

global $query;

add_action('parse_query', 'wpse32932_parse_query');
function wpse32932_parse_query( $wp ){
  if (is_archive()) {
    if($wp->query_vars['post_type'] == 'sponsor') {
      $wp->query_vars['posts_per_page'] = 30;
    }
  }
  /*
  var_dump($wp->query['post_type']);
    if( $wp->is_post_type_archive ):
        $wp->query_vars['posts_per_page'] = 30;
    endif;
    return $wp;
  */
}

function get_post_image($post) {
  $thumb = get_the_post_thumbnail($post->ID, 'homepage-blog-size');
  if ($thumb) {
  	return $thumb;
  } else {
  	return '<img src="/wp-content/uploads/2015/07/defaulthome.jpg" alt="' . $post->post_title . '" />';
  }
}




add_filter( 'wpe_heartbeat_allowed_pages', 'my_wpe_add_allowed_pages' );
function my_wpe_add_allowed_pages( $heartbeat_allowed_pages ) {
    $heartbeat_allowed_pages = array(
    	'index.php',
    	'admin.php',
    	'edit.php',
    	'post.php',
    	'post-new.php',
    );
    return $heartbeat_allowed_pages;
}



/* add javascript for timezones */
function ucusergroup_timezone_custom_scripts(){

    // Register and Enqueue a Script
    // get_stylesheet_directory_uri will look up child theme location
    wp_register_script( 'moment', get_stylesheet_directory_uri() . '/js/moment.js', array('jquery'));
    wp_enqueue_script( 'moment' );
	wp_register_script( 'moment-timezone-with-data-2010-2020', get_stylesheet_directory_uri() . '/js/moment-timezone-with-data-2010-2020.js', array('jquery'));
    wp_enqueue_script( 'moment-timezone-with-data-2010-2020' );
	wp_register_script( 'jstz', get_stylesheet_directory_uri() . '/js/jstz.min.js', array('jquery'));
    wp_enqueue_script( 'jstz' );
	wp_register_script( 'skypeTimezoneSet', get_stylesheet_directory_uri() . '/js/skypetimezoneset.js', array('jquery'));
    wp_enqueue_script( 'skypeTimezoneSet' );	
}
add_action('wp_enqueue_scripts', 'ucusergroup_timezone_custom_scripts');


   
//add_filter( 'Skype_custom_FHEE__espresso_list_of_event_dates__datetimes' , 'phptimezonedatefilter', 99);
function phptimezonedatefilter($data) {
	foreach ($data as $datum) {
		//datum should now be a EE datetime object
		//print_R($datum->Venue);
		//print_R($datum);
		//var_dump($datum);
		//[_model_relations:protected][Event][_model_relations:protected][Venue]
		//print_R($datum->_model_relations);
	}
	//print_R($data);
  return $data;
}


   
  
  
//change wordpress registration page  
add_filter( 'register_url', 'custom_register_url' );
function custom_register_url( $register_url )
{
    $register_url = "/create-account";
    return $register_url;
}



//add first and last name processing to registration
	//1. Fistname
	add_action( 'register_form', 'myplugin_register_form' ); 
	function myplugin_register_form() {

	$first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
	$last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
	$user_group = ( ! empty( $_POST['user_group'] ) ) ? trim( $_POST['user_group'] ) : '';

	?>
			<p>
				<label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?><br />
					<input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
			</p>
			<p>
				<label for="last_name"><?php _e( 'Last Name', 'mydomain' ) ?><br />
					<input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
			</p>		
			<p>
				<?php ug_city_select(false); ?>
			</p>			
	<?php
	}

	//2. Add validation. In this case, we make sure first_name is required.
	add_filter( 'registration_errors', 'myplugin_registration_errors', 10, 3 );
	function myplugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {

		if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
		$errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
		}

		if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
		$errors->add( 'last_name_error', __( '<strong>ERROR</strong>: You must include a last name.', 'mydomain' ) );
		}

		return $errors;
	}

	//3. Finally, save our extra registration user meta.
	add_action( 'user_register', 'myplugin_user_register' );
	function myplugin_user_register( $user_id ) {
		if ( ! empty( $_POST['first_name'] ) ) {
			update_user_meta( $user_id, 'first_name', trim( $_POST['first_name']  ));
		}

		if ( ! empty( $_POST['last_name'] ) ) {
			update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
		}
		
		if ( ! empty( $_POST['user_group'] ) ) {
			update_user_meta( $user_id, 'user_group', trim( $_POST['user_group'] ) );
		}		
	}
//end first and last name


 
//Ultimate Member Customization

/* Add fields to account page */

if( is_plugin_active( 'ultimate-member/index.php' ) ) {
	add_action('um_after_account_general', 'showExtraFields', 100);
}

function showExtraFields()
{
	global $ultimatemember;
	$id = um_user('ID');

	$ucUserGroupCities = get_terms(
      array('city')
    );	
	
	$custom_fields = [
		"user_group" => "Your Skype User Group"
	];

	foreach ($custom_fields as $key => $value) {
		$fields[ $key ] = array(
				'title' => $value,
				'metakey' => $key,
				'type' => 'select',
				'label' => $value,
		);

		//$myvar = apply_filters('um_account_secure_fields', $fields, 'general' ); //old version of ultimate member
		$fields = apply_filters( 'um_account_secure_fields', $fields, $id );

		$field_value = get_user_meta(um_user('ID'), $key, true) ? : '';
		
		
		$ucUserGroupCitiesSelect  = '<select data-validate class="um-form-field valid" name="'.$key.'"
		id="'.$key.'" data-key="'.$key.'">';
		
		foreach($ucUserGroupCities as $ucUserGroupCity){
			$selected = '';
			if ($field_value == $ucUserGroupCity->term_id) {$selected = "selected";}
			$ucUserGroupCitiesSelect .= '<option '.$selected.' id="'. $ucUserGroupCity->term_id .'" value="'. $ucUserGroupCity->term_id . '">' . $ucUserGroupCity->name . '</option>';
		}
		
		$ucUserGroupCitiesSelect .= '</select>';

		$html = '<div class="um-field um-field-'.$key.' um-field-select" data-key="'.$key.'">
		<div class="um-field-label">
		<label for="'.$key.'">'.$value.'</label>
		<div class="um-clear"></div>
		</div>
		<div class="um-field-area">'
		. $ucUserGroupCitiesSelect . '
		
		</div>
		</div>';

		echo $html;
	}
}


//for social login -- upon user login, check to see if they have a usergroup set.  If not, redirect to account page, and show notice
function skype_location_login_check( $user_login, $user ) {
	$usergroup = get_user_meta($user->ID, 'user_group', true) ? : '';
	if (!isset($usergroup) || empty($usergroup) ) {
		wp_redirect("/account?skypelocation=1");
		exit;	
	}
}
add_action('wp_login', 'skype_location_login_check', 99, 2);



// Unregister the Bootstrap css file enqueued from the Memphis Document Library
// as it clashes with the theme bootstrap because it is loaded too late.
// This css file is manually added in ucusergroup/parts/shared/html-header.php
// just before the theme bootstrap file
function dequeue_memphis_doc_library_bootstrap() {
	if ( is_plugin_active( 'memphis-documents-library/memphis-documents.php' ) ) {
		wp_dequeue_style( 'bootstrap.min.css' );
		wp_deregister_style('bootstrap.min.css');
		wp_dequeue_style( 'bootstrap.site.min.css' );
		wp_deregister_style('bootstrap.site.min.css');
		wp_dequeue_style( 'memphis-bootstrap.min.css' );
		wp_deregister_style('memphis-bootstrap.min.css');
	}
}
add_action( 'wp_enqueue_scripts', 'dequeue_memphis_doc_library_bootstrap', 9999);


//Making jQuery to load from Google Library
function replace_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', false, '3.2.1');
		wp_enqueue_script('jquery');
	}
}
//add_action('init', 'replace_jquery');