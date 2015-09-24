<?php
/**
 * Template Name: Home Page
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

    <div class="banner">
      <div class="bg-stretch">
        <img src="<?php the_field('banner_image'); ?>" width="1170" height="505" alt="image description">
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-5">
            <h1><span><?php the_field('banner_text_top'); ?></span> <?php the_field('banner_text'); ?></h1>
            <a href="#upcoming-meetups" type="button" data-button-scroll class="btn btn-success hidden-xs"><i class="icon-search"></i>Find a Meetup near you</a>
          </div>
        </div>
      </div>
    </div>

    <div id="upcoming-meetups" class="location-section">
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
              <?php ug_city_select(); ?>
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
              $userGroupNames = wp_get_post_terms($post->ID, 'ug-name');
              $userGroupName = $userGroupNames ? $userGroupNames[0] : '';
          ?>
            <div class="col-sm-4 col-xs-12 location-col">
              <a href="<?php the_permalink(); ?>">
                <time class="time-hold" datetime="2015-07-23"><span><?php espresso_event_date('j', ' '); ?></span><?php espresso_event_date('F', ' '); ?></time>
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
                  'limit' => 19,
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
                $i = 1;
                if (have_posts()) : while (have_posts()) : the_post();
                  $userGroupLocations = wp_get_post_terms($post->ID, 'city');
                  $userGroupLocation = $userGroupLocations ? $userGroupLocations[0] : null;
                  if($i > 3):
              ?>
                <li><a href="<?php the_permalink(); ?>"><?php espresso_event_date('F j', ' '); ?> &ndash; <?php ug_venue_location(); ?></a></li>
              <?php
                  endif;
                  $i++;
                endwhile;
                endif;
                wp_reset_query();
                wp_reset_postdata();
              ?>
            </ul>
            <a href="<?php echo get_post_type_archive_link('espresso_events'); ?>" type="button" class="btn btn-success"><i class="icon-search"></i>View all meetups</a>

          </div>
        </div>
      </div>
    </div>

    <div class="info-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="home-about"><?php the_field('information_column_1'); ?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <a href="<?php echo get_permalink(21); ?>" type="button" class="btn btn-info"><i class="icon-circle-plus"></i>Find out more</a>
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
            <?php get_template_parts( array( 'parts/shared/registration-form' ) ); ?>
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
                  <?php $sponsors = get_posts(array('post_type' => 'sponsor', 'posts_per_page' => -1)); ?>
                  <?php foreach($sponsors as $i => $sponsor): ?>
                  <?php if($i % 8 == 0 && $i > 0): ?>
                </ul>
              </div>
              <div class="item">
                <ul class="sponsor-list">
                  <?php endif; ?>
                  <li>
                    <a href="<?php the_field('sponsor_url', $sponsor->ID); ?>">
                      <?php echo get_the_post_thumbnail($sponsor->ID, 'full'); ?>
                    </a>
                  </li>
                  <?php endforeach; ?>
                </ul>
              </div>

            </div>
            <ol class="carousel-indicators">
              <?php $sponsorsSlideCount = ceil(count($sponsors) / 8); ?>
              <?php for ($i=0; $i < $sponsorsSlideCount; $i++): ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
              <?php endfor; ?>
            </ol>
          </div>
        </div>
      </div>
    </div>


    <div class="news-section">
      <div class="container-fluid">
        <h1>Latest Users Group News</h1>
        <a href="<?php echo get_permalink(18); ?>" type="button" class="btn btn-success"><i class="icon-bookmark"></i>View all news</a>
        <ul class="news-list row">
          <?php
            $posts = get_posts(
              array(
                'posts_per_page' => 3
              )
            );
          ?>
          <?php foreach($posts as $post): ?>
          <li class="col-sm-4">
            <a href="<?php echo get_permalink($post->ID); ?>">
              <?php echo get_the_post_thumbnail($post->ID, 'homepage-blog-size') ?>
            </a>
            <div class="text-block">
              <h2><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h2>
              <p><?php echo $post->post_excerpt; ?></p>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
	
	
<script>
	//var x=document.getElementById("demo");
	var geocoder = new google.maps.Geocoder;
	  var infowindow = new google.maps.InfoWindow;
	  
	  
	jQuery(document).ready(function($) {
		getLocation();
	});	  
	  
	function getLocation()
	  {
	  if (navigator.geolocation)
		{
		navigator.geolocation.getCurrentPosition(showPosition,showError);
		}
	  else{//x.innerHTML="Geolocation is not supported by this browser.";
	  }
	  }

	  
	function getClosestCity (lat, lng) {
			// We'll pass this variable to the PHP function uc_ajax_request_city
		
		// This does the ajax request
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
		//alert (ajaxurl);
		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: ajaxurl,
			data: {
				'action':'uc_ajax_request_city',
				"lat": lat,
				"lng": lng
			},
			success:function(data) {
				// This outputs the result of the ajax request
				console.log(data);
				//alert("Form submitted successfully.\nReturned json: " + data["json"]);
				
				cityInfo = data["json"];
				obj = JSON.parse(cityInfo);
				//term_id, term_city
				
				if (obj.distance < 200) {
					var textToFind = obj.name;
					var dd = document.getElementById('ug_closest_location');
					for (var i = 0; i < dd.options.length; i++) {
						if (dd.options[i].text === textToFind) {
							dd.selectedIndex = i;
							dd.options[i].selected = true;
							//alert(dd.selectedIndex);
							break;
						}
					}
					
					
					//$("ug_closest_location").val(obj.name);
					alert ("The closest user group to you is " + obj.name);
				} else {
					//alert ("There are no user groups near you.  Perhaps you should start one!");
				}
				
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
		
	}

	function showPosition(position)
	  {
	  lat=position.coords.latitude;
	  lng=position.coords.longitude;
	  latlng=new google.maps.LatLng(lat, lng)
	  //mapholder=document.getElementById('mapholder')
	  //mapholder.style.height='250px';
	  //mapholder.style.width='100%';


			getClosestCity(lat, lng);
  
	  /*
	  //alert(lat);
	  $('#uclat').text(lat);
	  $('#uclng').text(lng);

	  var myOptions={
	  center:latlng,zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP,
	  mapTypeControl:false,
	  navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
	  };
	  var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
	  var marker=new google.maps.Marker({position:latlng,map:map,title:"You are here!"});
	  
	  codeLatLng(lat, lng);
	  */
	  }

	function showError(error)
	  {
		  /*
	  switch(error.code) 
		{
		case error.PERMISSION_DENIED:
		  x.innerHTML="User denied the request for Geolocation."
		  break;
		case error.POSITION_UNAVAILABLE:
		  x.innerHTML="Location information is unavailable."
		  break;
		case error.TIMEOUT:
		  x.innerHTML="The request to get user location timed out."
		  break;
		case error.UNKNOWN_ERROR:
		  x.innerHTML="An unknown error occurred."
		  break;
		}
		*/
	  }
	  
	  
	  function codeLatLng(lat, lng) {
		  /*

		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode({'location': latlng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
		  console.log(results)
			if (results[1]) {
			 //formatted address
			 //alert(results[0].formatted_address)
			 var fulladdy = results[0].formatted_address;
			 $('#uclocation').text(fulladdy);
			//find country name
				 for (var i=0; i<results[0].address_components.length; i++) {
				for (var b=0;b<results[0].address_components[i].types.length;b++) {

				//there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
					if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
						//this is the object you are looking for
						city= results[0].address_components[i];
						break;
					}
				}
			}
			//city data
			//alert(city.short_name + " " + city.long_name) //this is showing the state right now
			var cityaddy = city.short_name + " " + city.long_name;
			


			} else {
			  alert("No results found");
			}
		  } else {
			alert("Geocoder failed due to: " + status);
		  }
		});
		
		*/
	  } 
	  
</script>	


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>