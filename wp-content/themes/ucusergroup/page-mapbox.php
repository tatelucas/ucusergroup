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

          <?php comments_template( '', true ); ?>
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
$ucUserGroupCities = get_terms(
  array('city')
);
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
