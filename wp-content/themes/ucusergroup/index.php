<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file
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
<?php if ( have_posts() ): ?>

	<div class="page-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<h2>News & Updates</h2>
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
				<ul class="news-list row">
			<?php while ( have_posts() ) : the_post(); ?>
					<li class="col-sm-4">
						<a href="<?php echo get_permalink($post->ID); ?>">
              <?php echo get_post_image($post) ?>
						</a>
						<div class="text-block">
							<h2><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h2>
							<p><?php echo $post->post_excerpt; ?></p>
						</div>
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
<h2>No posts to display</h2>
<?php endif; ?>
</div>
</div>
</div><!-- /content -->
</div><!--/middle-->
<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>
