<?php
/**
 * The Template for displaying all single posts
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
            <div class="row">
							<div class="col-sm-10">
								<h2><?php the_title(); ?></h2>
								<div class="subtitle">
									<time  datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?></time>
								</div><!--/subtitle-->
							</div><!--/col-sm-10-->
							<div class="col-sm-2 btn-group">
								<?php comments_popup_link('Leave a Comment', '1 Comment', '% Comments', 'btn btn-outlined pull-right'); ?>
							</div><!--/col-sm-2-->
						</div><!--/row-->
          </div><!--/col-sm-12-->
        </div><!--/row-->
      </div><!--/container-fluid -->
    </div>

		<div class="content container-fluid">
      <div class="row">
        <div class="col-sm-9">

				<?php the_content(); ?>
				<?php comments_template( '', true ); ?>

			</div><!--/col-sm-9-->

				<div class="sidebar-container col-sm-3">
					<?php get_sidebar(); ?>
				</div><!--/col-sm-3-->
			</div><!--/row-->
	</div><!-- /content -->
	<?php endwhile; ?>
</div><!--/middle-->

<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
