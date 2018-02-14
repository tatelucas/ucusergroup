<?php
/*
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
				<div class="col-sm-10">
					<?php $tax = $wp_query->get_queried_object(); ?>
					<h2><?php echo $tax->name; ?> Users Group</h2>
				</div><!--/col-sm-10-->
				<div class="col-sm-2 col-xs-12 btn-group">
					<!--<a href="#" class="btn btn-outlined btn-header btn-join-event"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>&nbsp;&nbsp; View Calendar</a>-->
				</div><!--/col-sm-2-->
			</div><!--/row-->
		</div><!--/container-fluid -->
	</div>

	<div class="content container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<ul class="list-unstyled archive-list">
				<?php
				$attsNextThreeEvents = array(
					'title' => NULL,
					'limit' => 10,
					'css_class' => NULL,
					'show_expired' => FALSE,
					'month' => NULL,
					'category_slug' => NULL,
					'order_by' => 'start_date',
					'sort' => 'ASC',
					'tax_query' => array(
						array(
							'taxonomy' => 'city',
							'field'    => 'term_id',
							'terms'    => array( $tax->term_id ),
						)
				)
				);
				global $wp_query;
				
				//set timezone to blank here.  Fill in first query.  Then check to see if it's filled for future queries.  We're trying to avoid 20 or so hits to google from just 1 page.
				$Loc_timezone = '';
				
				//$wp_query = new EE_Event_List_Query( $attsNextThreeEvents );
				$wp_query = new EventEspresso\core\domain\services\wp_queries\EventListQuery( $attsNextThreeEvents );
				if ( have_posts() ): ?>
				<h1>Upcoming Events:</h1>
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="skypeevent-location-time">
						<article>
							<h2 class="event-h2"><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
							<?php
							if($post->post_type == 'espresso_events') {
							?>
							<div class="event-location" style="display: none;">
								<?php
									//Pull an array of venue objects for all venues assigned to the event
									global $post;
									if ( $post->EE_Event instanceof EE_Event ) {
									    $venues = $post->EE_Event->venues();

										//Shift the first element from the array and use as the venue object.
										$venue = !empty( $venues ) ? array_shift( $venues ) : NULL;
										
										//print_R($venue);

										//Echo venue name
										if( $venue instanceof EE_Venue ) {
										echo $venue->city() . ", ";
										echo $venue->state() . " ";
										echo $venue->zip();
										}
									}

									//get timezone from this event.  Assume it is the same for all other events at this venue, because it will be.
									if (empty($Loc_timezone)) {
										$Loc_timezone = skype_get_timezone_from_id($post->ID);
									}
								?>
							</div>
							<div class="event-time-cont">
								<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <time class="time-hold" datetime="<?php espresso_event_date(); ?>"><span><?php echo skype_timezone_format_timesingle($post->ID, $Loc_timezone); ?></span></time>
							</div>
							<?php
							} else {
							?>
							<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?> <?php the_time(); ?></time>
							<?php
							}
							?>
							<div class="row">
								<div class="col-sm-10">
									<?php the_excerpt(); ?>
								</div>
								<div class="col-sm-2">
									<a href="<?php esc_url( the_permalink() ); ?>" class="ticket-selector-submit-btn view-details-btn pull-right btn-block text-center">View Details</a>
								</div>
							</div>


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
				<h2>No upcoming events scheduled at this time.</h2>
				<?php endif; ?>
				</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<ul class="list-unstyled archive-list">
				<?php
				$attsPastEvents = array(
					'title' => NULL,
					'limit' => 10,
					'css_class' => NULL,
					'show_expired' => TRUE,
					'month' => NULL,
					'category_slug' => NULL,
					'order_by' => 'start_date',
					'sort' => 'DESC',
					'tax_query' => array(
						array(
							'taxonomy' => 'city',
							'field'    => 'term_id',
							'terms'    => array( $tax->term_id ),
						)
				)
				);
				global $wp_query;
				//$wp_query = new EE_Event_List_Query( $attsPastEvents );
				$wp_query = new EventEspresso\core\domain\services\wp_queries\EventListQuery( $attsPastEvents );
				if ( have_posts() ): ?>
				<h1>Past Events:</h1>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php 
						$datemunch = espresso_event_date('U','U', $post->ID, FALSE);
						$pieces = explode(" ", trim($datemunch));
						$datemunch = $pieces[0];

					if ($datemunch < date('U', time())) { ?>
					<li class="skypeevent-location-time">
						<article>
							<h2 class="event-h2"><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
							<?php
							if($post->post_type == 'espresso_events') {
							?>
							<div class="event-location" style="display: none;">
								<?php
									//Pull an array of venue objects for all venues assigned to the event
									global $post;
									if ( $post->EE_Event instanceof EE_Event ) {
									    $venues = $post->EE_Event->venues();

										//Shift the first element from the array and use as the venue object.
										$venue = !empty( $venues ) ? array_shift( $venues ) : NULL;
										
										//print_R($venue);

										//Echo venue name
										if( $venue instanceof EE_Venue ) {
										echo $venue->city() . ", ";
										echo $venue->state() . " ";
										echo $venue->zip();
										}
									}

									//get timezone from this event.  Assume it is the same for all other events at this venue, because it will be.
									if (empty($Loc_timezone)) {
										$Loc_timezone = skype_get_timezone_from_id($post->ID);
									}									
								?>
							</div>							
							<div class="event-time-cont">
								<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <time class="time-hold" datetime="<?php espresso_event_date(); ?>"><span><?php echo skype_timezone_format_timesingle($post->ID, $Loc_timezone); ?></span></time>
							</div>
							<?php
							} else {
							?>
							<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?> <?php the_time(); ?></time>
							<?php
							}
							?>
							<div class="row">
								<div class="col-sm-10">
									<?php the_excerpt(); ?>
								</div>
								<div class="col-sm-2">
									<a href="<?php esc_url( the_permalink() ); ?>" class="ticket-selector-submit-btn view-details-btn pull-right btn-block text-center">View Details</a>
								</div>
							</div>


						</article>
					</li>
					<?php } ?>
				<?php endwhile; ?>
				</ul>

				<div class="post-pagination">
					<?php
					// Previous/next page navigation.
					echo paginate_links();
					?>
				</div>

				<?php else: ?>
				
				<?php endif; ?>
				</div>

		</div>
		
	</div><!-- /content -->
</div><!--/middle-->
<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
