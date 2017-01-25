<?php
/**
 * Template Name: Mapbox
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>


<script src='https://api.mapbox.com/mapbox-gl-js/v0.31.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v0.31.0/mapbox-gl.css' rel='stylesheet' />

<style>
#marker {
    background-image: url('/wp-content/themes/ucusergroup/images/pin-m-circle.png');
    background-size: cover;
    width: 30px;
    height: 70px;
    cursor: pointer;
}

.mapboxgl-popup {
    max-width: 200px;
}
</style>



<div class="middle">
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <?php
    $ucUserGroupCities = get_terms(array('city'), array(
      'hide_empty' => false,
    ));
    ?>

    <div class="page-title">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <h2><?php the_title(); ?></h2>
            <?php
            $subtitle = get_field( 'subtitle' );
            if ($subtitle) {
              echo "<p class='subtitle'>
              $subtitle
              </p>";
            }
            ?>
          </div><!--/col-sm-12-->
        </div><!--/row-->
      </div><!--/container-fluid -->
    </div>

    <div class="map-container">
      <div id="map"></div>
    </div><!--/map-container-->

    <div class="content container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <?php the_content(); ?>

          <div class="row browse-meetups">
          <?php
          if ($ucUserGroupCities) {
            foreach($ucUserGroupCities as $ucUserGroupCity) {
            ?>
              <div class="col-sm-3">
                <div class="browse-meetup-city same-height-left">
                  <h4><a href="<?php echo get_term_link($ucUserGroupCity->name, 'city'); ?>"><?php echo $ucUserGroupCity->name; ?></a></h4>

                  <?php
                  $attsNextThreeEvents = array(
                    'title' => NULL,
                    'limit' => 3,
                    'css_class' => NULL,
                    'show_expired' => FALSE,
                    'month' => NULL,
                    'category_slug' => NULL,
                    'order_by' => 'start_date',
                    'sort' => 'ASC',
                    'tax_query' => array(
              				array(
              					'taxonomy' => 'city',
              					'field'    => 'term_id',
              					'terms'    => array( $ucUserGroupCity->term_id ),
              				)
                  )
                  );
                  global $wp_query;
                  $wp_query = new EE_Event_List_Query( $attsNextThreeEvents );
                  if (have_posts()) : while (have_posts()) : the_post();
                      echo '<p><a href="' . get_the_permalink() . '"><span>' . get_the_title() . '</span></a></p>';
                    endwhile;
                  else:
                    echo '<p>No upcoming meetups</p>';
                  endif;
                  wp_reset_query();
                  wp_reset_postdata();
                  ?>

                </div>
              </div>
            <?php
            }
          }
          ?>
        </div><!--/row-->
        </div>
      </div>
    </div><!-- /content -->
  <?php endwhile; ?>
</div><!--/middle-->




<script>
mapboxgl.accessToken = 'pk.eyJ1IjoidWN1c2VyZ3JvdXAiLCJhIjoiY2llendlNWNoMWVtcnNrbTNqYWhkZmtzOSJ9.FUTl0ZC0j6bErseY_7So6Q';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/outdoors-v9',
  center: [-98.5817593,39.8282109],
  zoom: 3,
  attributionControl: false,
  boxZoom: false,
  scrollZoom: false,
  doubleClickZoom: true
});
	
	
<?php
foreach($ucUserGroupCities as $ucUserGroupCity){
  $term_meta = get_option( "taxonomy_term_$ucUserGroupCity->term_id" );

  if($term_meta) {
  ?>  
		// create the popup
		var popup = new mapboxgl.Popup({offset:[0, -35]})
			.setHTML('<div class="marker-title"><strong><a href="<?php echo get_term_link($ucUserGroupCity->name, 'city'); ?>"><?php echo $ucUserGroupCity->name; ?></a></strong></div>');

		// create DOM element for the marker
		var el = document.createElement('div');
		el.id = 'marker';

		// create the marker
		new mapboxgl.Marker(el, {offset:[-15, -35]})
			.setLngLat([<?php echo $term_meta['city_longitude'] ?>, <?php echo $term_meta['city_latitude'] ?>])
			.setPopup(popup) // sets a popup on this marker
			.addTo(map);

  <?php
  }
}
?>	
	
</script>


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
