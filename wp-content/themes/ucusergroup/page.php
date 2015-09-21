<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/starkers-utilities.php for info on get_template_parts()
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
        <div class="col-sm-9">
          <?php the_content(); ?>
          <?php comments_template( '', true ); ?>
        </div>

        <div class="sidebar-container col-sm-3">
          <?php get_sidebar(); ?>
        </div><!--/col-sm-4-->
      </div>
    </div><!-- /content -->
  <?php endwhile; ?>
</div><!--/middle-->


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
