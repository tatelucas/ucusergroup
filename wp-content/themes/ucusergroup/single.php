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
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); global $post; ?>
		<div class="page-title">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="row">
							<div class="col-sm-10">
								<h2><?php the_title(); ?></h2>
								<div class="subtitle">
									<?php
									if($post->post_type == 'espresso_events') {
									?>
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <time class="time-hold" datetime="<?php espresso_event_date(); ?>"><span><?php espresso_event_date('l, F j, Y'); ?></span></time>
									<?php
									} else if ($post->post_type == 'post') {
									?>
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <time  datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?></time>
									<?php
									}
									?>


								</div><!--/subtitle-->
							</div><!--/col-sm-10-->
							<div class="col-sm-2 col-xs-12 btn-group">
								<?php

								if($post->post_type == 'espresso_events') {
									?>
									<a href="#" class="btn btn-outlined btn-header btn-join-event"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Sign Up</a>
									<?php
								} else if ($post->post_type == 'post') {
									comments_popup_link('Leave a Comment', '1 Comment', '% Comments', 'btn btn-outlined btn-header');
								}
								?>
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
					<?php
					if($post->post_type == 'espresso_events') {
					?>
					<div id="sidebar" role="complementary">
						<ul>
							<li class="widget ee_participant_widget">
								<h2 class="widgettitle">Who's Going?</h2>
								<?php echo do_shortcode('[ESPRESSO_EVENT_ATTENDEES show_gravatar=true]'); ?>
							</li>
						</ul>
					</div>
					<?php
					} else {
						get_sidebar();
					}
					?>
				</div><!--/col-sm-3-->
			</div><!--/row-->
	</div><!-- /content -->
	<?php endwhile; ?>
</div><!--/middle-->

<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
