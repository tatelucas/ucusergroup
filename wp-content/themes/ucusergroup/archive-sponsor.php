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
<?php if ( have_posts() ): ?>

	<div class="page-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<h2>Sponsors</h2>
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
<ul class="sponsor-list">
<?php while ( have_posts() ) : the_post(); ?>
	<li>
		<a href="<?php the_field('sponsor_url', $sponsor->ID); ?>">
			<?php echo get_the_post_thumbnail($sponsor->ID, 'full'); ?>
		</a>
	</li>
<?php endwhile; ?>
</ul>
<?php else: ?>
<h2>No posts to display</h2>
<?php endif; ?>
</div>
</div>
</div><!-- /content -->
</div><!--/middle-->
<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>
