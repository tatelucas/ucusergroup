<?php
/**
 * Content Template for the [ESPRESSO_EVENT_ATTENDEES] shortcode
 *
 * @package Event Espresso
 * @subpackage templates
 * @since 4.6.29
 * @author Darren Ethier
 *
 * Template Args that are available in this template
 * @type EE_Attendee $contact
 * @type bool       $show_gravatar  whether to show gravatar or not.
 */
if ( $show_gravatar ) {
	$gravatar = get_avatar( $contact->email(),
		(int) apply_filters( 'FHEE__loop-espresso_attendees-shortcode__template__avatar_size', 32 )
		);
} else {
	$gravatar = '';
}
?>

<?php
$group_size = $contact->get_most_recent_registration_for_event( $event->get('EVT_ID'))->get('REG_group_size');
// if group size is 1, ditch the quantity display
$group_size = $group_size > 1 ? '(' . $group_size . ')' : '';
?>

<?php do_action( 'AHEE__content-espresso_event_attendees__before', $contact, $show_gravatar ); ?>
<li><?php echo $gravatar . '&nbsp;' .  $contact->full_name() . ' ' . $group_size; ?></li>
<?php do_action( 'AHEE__content-espresso_event_attendees__after', $contact, $show_gravatar ); ?>