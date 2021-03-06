<?php
/**
 * Template Name: Full Width
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

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


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
