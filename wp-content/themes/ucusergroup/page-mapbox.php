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

<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.css' rel='stylesheet' />

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
var myIcon = L.icon({
      iconUrl: 'https://api.tiles.mapbox.com/v4/marker/pin-m-circle+0f68b2.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IlhHVkZmaW8ifQ.hAMX5hSW-QnTeRCMAy9A8Q',
      iconAnchor: [15, 35],
      popupAnchor: [0, -35]
    });

L.mapbox.accessToken = 'pk.eyJ1IjoidWN1c2VyZ3JvdXAiLCJhIjoiY2llendlNWNoMWVtcnNrbTNqYWhkZmtzOSJ9.FUTl0ZC0j6bErseY_7So6Q';
var map = L.mapbox.map('map', 'ucusergroup.ciezwe45e1emssrm02vw6snax', {
  center: [39.8282109,-98.5817593],
  zoom: 4,
  zoomControl: true,
  attributionControl: false,
  boxZoom: false,
  scrollWheelZoom: false,
  closePopupOnClick: false,
  doubleClickZoom: false,
  tap: false
});

<?php
foreach($ucUserGroupCities as $ucUserGroupCity){
  $term_meta = get_option( "taxonomy_term_$ucUserGroupCity->term_id" );

  if($term_meta) {
  ?>
  var popupHTML  = '<div class="marker-title"><strong><a href="<?php echo get_term_link($ucUserGroupCity->name, 'city'); ?>">';
      popupHTML += '<?php echo $ucUserGroupCity->name; ?>';
      popupHTML += '</a></strong></div>';

  L.marker(
    [<?php echo $term_meta['city_latitude'] ?>, <?php echo $term_meta['city_longitude'] ?>], {
      icon: myIcon,
    })
    .addTo(map).bindPopup(popupHTML);
  <?php
  }
}
?>





</script>

<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
