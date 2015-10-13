<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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


	<div class="page-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<?php if ( is_day() ) : ?>
						<h2>Archive: <?php echo  get_the_date( 'D M Y' ); ?></h2>
						<?php elseif ( is_month() ) : ?>
						<h2>Archive: <?php echo  get_the_date( 'M Y' ); ?></h2>
						<?php elseif ( is_year() ) : ?>
						<h2>Archive: <?php echo  get_the_date( 'Y' ); ?></h2>
						<?php else : ?>
						<h2>Archive</h2>
					<?php endif; ?>
				</div><!--/col-sm-12-->
			</div><!--/row-->
		</div><!--/container-fluid -->
	</div>

	<div class="content container-fluid">
		<div class="row">
			<div class="col-sm-9">
				<ul class="list-unstyled archive-list">
				<?php if ( have_posts() ): ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<li>
						<article>
							<h2><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
							<?php
							if($post->post_type == 'espresso_events') {
							?>
							<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <time class="time-hold" datetime="<?php espresso_event_date(); ?>"><span><?php espresso_event_date('l, F j, Y', NULL, $post->ID); ?></span></time>
							<?php
							} else {
							?>
							<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?> <?php the_time(); ?></time>
							<?php
							}
							?>
							<?php the_excerpt(); ?>
						</article>
					</li>
				<?php endwhile; ?>
				</ul>

				<div class="post-pagination">
					<?php
					// Previous/next page navigation.
					echo paginate_links();
					?>
				</div>

				<?php else: ?>
				<h2>No results found for '<?php echo get_search_query(); ?>'</h2>
				<?php endif; ?>
				</div>

				<div class="sidebar-container col-sm-3">
					<?php
						get_sidebar();
					?>
				</div><!--/col-sm-3-->

		</div>
	</div><!-- /content -->
</div><!--/middle-->
<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
