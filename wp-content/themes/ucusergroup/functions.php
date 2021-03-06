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
	wp_register_script('googlemaps', '//maps.googleapis.com/maps/api/js', false, '3');
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
}
add_action('wp_enqueue_scripts', 'ucusergroup_timezone_custom_scripts');



  function sfbug_custom_local_timezone_display() {
   ?>
   <script type="text/javascript">
	//customize time elements on the page to user's local time
		jQuery( "time" ).each(function( index ) {
			  var timex = jQuery( this ).text();

				//moment interprets the time, guesses the user's timezone, and reformats the date using the new timezone
				var format = 'dddd, MMMM D, YYYY h:mm a z'; //'YYYY/MM/DD HH:mm:ss ZZ';

				var timey = new moment(timex, format).format(format);				
				if (timey.indexOf('Invalid') <= -1) {		
				//this check is to make sure the date coming in is a full-format date, moment is picky
				  
					var timez = moment(timex).tz(moment.tz.guess()).format(format);

					if (timez.indexOf('Invalid') <= -1) {
						jQuery( this ).text(timez);
					}
					//else, do nothing and leave the date as originally displayed - there was a problem parsing it				  
				  
				}
		});


		jQuery( ".ee-event-datetimes-li" ).each(function( index ) {
			//this one customizes date in ul li array -- that is, dateblock on individual event page, and dates in sidebar events
			//each li date element has two child spans -- one with the date, and one with the time.  We need them both in order to change the timezones properly

			//first, get the date, or date range
			var dateblock = jQuery(this).find('.ee-event-datetimes-li-daterange').html();
			//dateblock will either be one date or a range.  Test for a hyphen to see if it is a range.
				if (dateblock.indexOf("-") > 0) {
					//split it at the hyphen to get the dates
					var oldStartDate = dateblock.split('-')[0].trim();
					var oldEndDate = dateblock.split('-')[1].trim();
				} else {
					//the timeblock has no hyphen.  It's just a single day event.
					var oldStartDate = dateblock.trim();
					var oldEndDate = oldStartDate;
				}

			//grab timeblock from the page
			var timeblock = jQuery(this).find('.ee-event-datetimes-li-timerange').html();
			//timeblock will either be one date/time or a range.  Test for a hyphen to see if it is a range.
			if (timeblock.indexOf("-") > 0) {
				//split it at the hyphen to get the times
				var oldStartTime = timeblock.split('-')[0].trim();
				var oldEndTime = timeblock.split('-')[1].trim();
				//var oldStartTime = oldStartTime.trim();

			} else {
				//the timeblock has no hyphen.  I really don't think this is supposed to happen with the timerange (it will with daterange)
				var oldStartTime = timeblock;
				var oldEndTime = oldStartTime;
			}

				var oldStartTime = oldStartDate.trim() + ' ' + oldStartTime.trim();
				var oldEndTime = oldEndDate + ' ' + oldEndTime;

				//for whatever reason, event espresso uses $nbsp; instead of space.  This messes up the date function, so they've got to be replaced.
				var oldStartTime = oldStartTime.replace(/&nbsp;/g, ' ');
				var oldEndTime = oldEndTime.replace(/&nbsp;/g, ' ');


				var pagetimeformat = 'MMMM D, YYYY h:mm a z';
				var timeformat = 'h:mm a z';
				var dateformat = 'MMMM D, YYYY';

				var startDateNew = moment(oldStartTime).tz(moment.tz.guess()).format(dateformat);
				var endDateNew = moment(oldEndTime).tz(moment.tz.guess()).format(dateformat);
				var startTimeNew = moment(oldStartTime).tz(moment.tz.guess()).format(timeformat);
				var endTimeNew = moment(oldEndTime).tz(moment.tz.guess()).format(timeformat);
				//alert(startTimeNew);


				if (startTimeNew.indexOf('Invalid') <= -1 && endTimeNew.indexOf('Invalid') <= -1) {
					jQuery(this).find('.ee-event-datetimes-li-timerange').text(startTimeNew + ' - ' + endTimeNew);
				}
				//else, do nothing and leave the date as originally displayed - there was a problem parsing it
				if (startTimeNew.indexOf('Invalid') <= -1 && endTimeNew.indexOf('Invalid') <= -1) {
					if (startDateNew != endDateNew) {
						//if this is a date range, show date range
						jQuery(this).find('.ee-event-datetimes-li-daterange').text(startDateNew + ' - ' + endDateNew);
					} else {
						//if single day, just show single day
						jQuery(this).find('.ee-event-datetimes-li-daterange').text(startDateNew);
					}
				}
				//else, do nothing and leave the date as originally displayed - there was a problem parsing it

		});


		jQuery(window).load(function ()
		{
			//if this is the calendar page, run the date fixes on the calendar
			if ( jQuery( "#espresso_calendar" ).length ) {
				//note: this code only partially works -- because the actual dates are not accessible in the calendar, moment guesses the timezones based on TODAY.
				// this means that the results will be incorrect if the date of the event is across a timechange that has not yet occurred.

				var i = setInterval(function ()
				{
					if (jQuery('.fc-event-inner').length)
					{
						clearInterval(i);
						// safe to execute your code here

						//time offset at server (Eastern Time)
						var qsite = moment().tz("America/New_York").format('Z');
						//timezone abbreviation of user
						var quser = moment().tz(moment.tz.guess()).format('z');

						jQuery( ".event-start-time" ).each(function( index ) {

							  var timea = jQuery( this ).text();
							  //add offset suffix so moment knows how to change it
							  var timea = timea + ' ' + qsite;

							  var enterformat = 'h:mm a ZZ';
							  var timeb = moment(timea, enterformat).format('h:mm a z');

							if (timeb.indexOf('Invalid') <= -1) {
							  jQuery( this ).text(timeb);
							}
							//else, do nothing, it could not parse the date correctly
						});

						jQuery( ".event-end-time" ).each(function( index ) {

							  var timea = jQuery( this ).text();
							  var timea = timea + ' ' + qsite;

							  var enterformat = 'h:mm a ZZ';

							  //moment interprets the time, and reformats the date using the new timezone
							  var timeb = moment(timea, enterformat).format('h:mm a z');

							if (timeb.indexOf('Invalid') <= -1) {
							  jQuery( this ).text(timeb + " " + quser);
							}
							//else, do nothing, it could not parse the date correctly
						});

					}
				}, 100);


			}

		});

   </script>
   <?php
   }
//  add_action( 'wp_footer', 'sfbug_custom_local_timezone_display' );



/* PHP functions for formatting datetime for local event timezone */
	function skype_timezone_format_timerange ($EVT_ID = 0, $datetime) {
		global $wpdb;
			$thepost = $wpdb->get_row("select VNU_address, VNU_address2, VNU_city, STA_ID, VNU_zip from {$wpdb->prefix}esp_venue_meta where VNU_ID = (select VNU_ID from {$wpdb->prefix}esp_event_venue where EVT_ID =".$EVT_ID.")");
			$stateABBR = $wpdb->get_row("select STA_abbrev from {$wpdb->prefix}esp_state where STA_ID =". $thepost->STA_ID);
			
			$fullAddr = $thepost->VNU_address . " " . $thepost->VNU_address2 . " " . $thepost->VNU_city . ", " . $stateABBR->STA_abbrev . " " . $thepost->VNU_zip;
			
			$prepAddr = urlencode($fullAddr);
         	$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
       		$output= json_decode($geocode);
          	$latitude = $output->results[0]->geometry->location->lat;
        	$longitude = $output->results[0]->geometry->location->lng;
			$url = "https://maps.googleapis.com/maps/api/timezone/json?timestamp=1331161200&location=".$latitude .",".$longitude."&sensor=false";
			$json_timezone = file_get_contents($url);
			$output2= json_decode($json_timezone);
			
			$start_time = $datetime->start_time('H:i:s T');
			$start_date = $datetime->start_date('Y-m-d');
			
			$end_time = $datetime->end_time('H:i:s T');
			$end_date = $datetime->end_date('Y-m-d');				

			$rawstarttime = $start_date . " " . $start_time;
			$rawendtime = $end_date . " " . $end_time;
			
			$local_timezone_from = gmdate('Y-m-d H:i:s T', strtotime($rawstarttime));
			
			$from = new DateTime($local_timezone_from, new DateTimeZone($output2->timeZoneId));
			$from->setTimeZone(new DateTimeZone($output2->timeZoneId));

			$local_timezone_to = gmdate('Y-m-d H:i:s T', strtotime($rawendtime));
			$to = new DateTime($local_timezone_to, new DateTimeZone($output2->timeZoneId));
			$to->setTimeZone(new DateTimeZone($output2->timeZoneId));

		return $from->format('g:i a').' - '.$to->format('g:i a').' '.$from->format('T');		
	}
	
	function skype_get_timezone_from_id ($EVT_ID = 0) {
		global $wpdb;
			$thepost = $wpdb->get_row("select VNU_address, VNU_address2, VNU_city, STA_ID, VNU_zip from {$wpdb->prefix}esp_venue_meta where VNU_ID = (select VNU_ID from {$wpdb->prefix}esp_event_venue where EVT_ID =".$EVT_ID.")");
			$stateABBR = $wpdb->get_row("select STA_abbrev from {$wpdb->prefix}esp_state where STA_ID =". $thepost->STA_ID);
			
			$fullAddr = $thepost->VNU_address . " " . $thepost->VNU_address2 . " " . $thepost->VNU_city . ", " . $stateABBR->STA_abbrev . " " . $thepost->VNU_zip;
			
			$prepAddr = urlencode($fullAddr);
         	$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
       		$output= json_decode($geocode);
          	$latitude = $output->results[0]->geometry->location->lat;
        	$longitude = $output->results[0]->geometry->location->lng;
			$url = "https://maps.googleapis.com/maps/api/timezone/json?timestamp=1331161200&location=".$latitude .",".$longitude."&sensor=false";
			$json_timezone = file_get_contents($url);
			$output2= json_decode($json_timezone);
			
			return $output2->timeZoneId;
	}		
	
	function skype_timezone_format_timesingle ($EVT_ID = 0, $Loc_timezone = '') {
		global $wpdb;
		
			//if the timezone id is passed in, use it instead of running all the google queries again.
			if (!empty($Loc_timezone)) {
				$output2 = new stdClass();
				$output2->timeZoneId = $Loc_timezone;
			} else {		
				$thepost = $wpdb->get_row("select VNU_address, VNU_address2, VNU_city, STA_ID, VNU_zip from {$wpdb->prefix}esp_venue_meta where VNU_ID = (select VNU_ID from {$wpdb->prefix}esp_event_venue where EVT_ID =".$EVT_ID.")");
				$stateABBR = $wpdb->get_row("select STA_abbrev from {$wpdb->prefix}esp_state where STA_ID =". $thepost->STA_ID);
				
				$fullAddr = $thepost->VNU_address . " " . $thepost->VNU_address2 . " " . $thepost->VNU_city . ", " . $stateABBR->STA_abbrev . " " . $thepost->VNU_zip;
				
				$prepAddr = urlencode($fullAddr);
				$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
				$output= json_decode($geocode);
				$latitude = $output->results[0]->geometry->location->lat;
				$longitude = $output->results[0]->geometry->location->lng;
				$url = "https://maps.googleapis.com/maps/api/timezone/json?timestamp=1331161200&location=".$latitude .",".$longitude."&sensor=false";
				$json_timezone = file_get_contents($url);
				$output2= json_decode($json_timezone);
			}
			
			$datetime = espresso_event_date_obj($EVT_ID);	
			
			$start_time = $datetime->start_time('H:i:s T');
			$start_date = $datetime->start_date('Y-m-d');			

			$rawstarttime = $start_date . " " . $start_time;	
			
			$local_timezone_from = gmdate('Y-m-d H:i:s T', strtotime($rawstarttime));
			
			$from = new DateTime($local_timezone_from, new DateTimeZone($output2->timeZoneId));
			$from->setTimeZone(new DateTimeZone($output2->timeZoneId));			

		return $from->format('l, F j, Y g:i a T');
	}	
	
/* end PHP functions for local event timezone */
  
  
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

if( is_plugin_active( 'ultimate-member/ultimate-member.php' ) ) {
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


if( is_plugin_active( 'ultimate-member/ultimate-member.php' ) ) {
	// Disable UM registration protection, allow default registration functionality
	remove_action( 'login_form_register', 'um_form_register_redirect', 10 );
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


//add hook from EE to display total registrations

add_action( 'AHEE__loop-espresso_event_attendees__before', 'my_event_registration_count', 5, 2 );
//add_action( 'AHEE_event_details_after_the_content', 'my_event_registration_count' );
function my_event_registration_count( $contacts, $post ) {
  //print_R($post);
  //$event_obj = $post->EE_Event;
  $event_obj = $post;
  $html = '';
  if ( $event_obj instanceof EE_Event ){
    $reg_count = EEM_Event::instance()->count_related(
      $event_obj, 
      'Registration',
      array( 
        array(
          'STS_ID' => array(
            'NOT_IN',
            array(
              EEM_Registration::status_id_cancelled,
			  EEM_Registration::status_id_incomplete,
			  EEM_Registration::status_id_declined
            )
          )
        )
      )
    );	
    $html .= '<p><span class="event_total_attendees"><strong>';
    $html .= $reg_count;
    //$html .= ' of ';
    //$html .= $event_obj->total_available_spaces();
    $html .= ' attendees total </strong></span>';
	$html .= '&nbsp;&nbsp;<span class="toggle_gravitars"><sup>&#9776;</sup></span>';
	$html .= '</p>';
	
	$html .= '
	<script>
	jQuery( ".toggle_gravitars" ).click(function() {
    jQuery( ".ee_participant_widget .event-attendees-list img" ).toggle();
	});
	</script>
	';
  }
   
  echo $html;
}