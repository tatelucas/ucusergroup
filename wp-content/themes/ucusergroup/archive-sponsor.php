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
			<div class="col-sm-9">
        <!--<ul class="sponsor-list">-->
        <?php while ( have_posts() ) : the_post(); ?>
        <?php //query_posts($query_string . '&orderby=title&order=ASC'); ?>
        	<!--<li>
        		<a href="<?php the_field('sponsor_url', $sponsor->ID); ?>">
        			<?php echo get_the_post_thumbnail($sponsor->ID, 'full'); ?>
        		</a>
        	</li>-->
        
        
          <div class="sponsor-card col-sm-12">
            <div class="lib-panel">
              <div class="row box-shadow">
                <div class="col-sm-5 sponsor-card-img">
                  <a href="<?php the_field('sponsor_url', $sponsor->ID); ?>">
              			<?php echo get_the_post_thumbnail($sponsor->ID, 'full'); ?>
              		</a>
                </div>
                  <div class="col-sm-7">
                    <div class="lib-row lib-header">
                      <p><a href="<?php the_field('sponsor_url', $sponsor->ID); ?>"><?php the_title(); ?></a></p>
                      <div class="lib-header-seperator"></div>
                    </div>
                    <div class="lib-row lib-desc">
                      <?php the_content(); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
          <?php endwhile; ?>
          <!--</ul>-->
          <?php else: ?>
            <h2>No posts to display</h2>
          <?php endif; ?>
          </div>
                  <div class="sidebar-container col-sm-3">
          <?php get_sidebar(); ?>
        </div><!--/col-sm-4-->
        </div>
  </div><!-- /content -->
</div><!--/middle-->
<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>
