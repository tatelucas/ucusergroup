<div id="espresso-social-buttons-dv">
	<h3 class="espresso-social-buttons-h3"><?php echo $heading; ?></h3>

	<?php foreach ( $events as $event_class => $event ) : ?>
	<div id="espresso-social-buttons-dv-<?php echo $event_class; ?>" class="espresso-social-buttons-dv">
		<?php if ( count( $events ) > 1 ) : ?>
			<h5 class="espresso-social-buttons-event-name-h5"><?php echo $event['event_name']; ?></h5>
		<?php endif; ?>
		<div id="espresso-social-buttons-dv-twitter" class="espresso-social-button-dv">
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $event['event_permalink']; ?>" data-text="<?php echo $event['tweet_message']; ?>"  data-via="<?php echo $event['co_twitter']; ?>" data-size="large"><?php echo __('Tweet', 'event_espresso'); ?></a>
		</div>
		<div id="espresso-social-buttons-dv-g-plusone" class="espresso-social-button-dv">
			<div class="g-plusone" data-href="<?php echo $event['event_permalink']; ?>"></div>
		</div>
		<div id="espresso-social-buttons-dv-facebook" class="espresso-social-button-dv">
			<div id="fb-root"></div>
			<div class="fb-like" data-href="<?php echo $event['event_permalink']; ?>" data-send="true" data-width="200" data-show-faces="true"></div>
		</div>
	</div>
	<?php endforeach; ?>
</div>