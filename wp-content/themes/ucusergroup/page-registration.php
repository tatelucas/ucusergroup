<?php
/**
 * Template Name: Registration
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

<div class="middle">
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <div class="page-title">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 register-page">
            <h2><?php the_title(); ?></h2>
            <?php
            $subtitle = get_field( 'subtitle' );
            if ($subtitle) {
              echo "<p class='subtitle'>
              $subtitle
              </p>";
            }
            ?>
            <?php the_content(); ?>
            <?php get_template_parts( array( 'parts/shared/registration-form' ) ); ?>
          </div><!--/col-sm-12-->
        </div><!--/row-->
      </div><!--/container-fluid -->
    </div>

  <?php endwhile; ?>
</div><!--/middle-->


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
