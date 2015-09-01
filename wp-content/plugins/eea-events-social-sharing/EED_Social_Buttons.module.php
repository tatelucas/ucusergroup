<?php
/**
 * This file contains the main social_buttons class for adding social buttons to EE.
 *
 * @since %VER%
 * @package EE Social_Buttons
 * @subpackage main
 */

/**
 * Main class used for setting up all hooks etc used for adding social buttons to EE
 *
 * @since %VER%
 * @package EE Social_Buttons
 * @subpackage main
 * @author Seth Shoultes
 */
class EED_Social_Buttons {

	/**
	 * contains all hooks used for social_buttons
	 *
	 * @since %VER%
	 *
	 * @return void
	 */
	public static function set_hooks() {
		//Adds sharing above the registration details
		//add_action( 'AHEE__thank_you_page_overview_template__content', array( 'EED_Social_Buttons', 'thank_you_page_buttons'), 10, 1 );
		
		//Adds sharing below the registration details
		add_action( 'AHEE__thank_you_page_overview_template__bottom', array( 'EED_Social_Buttons', 'thank_you_page_buttons'), 10, 1 );
		
		//Enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', array( 'EED_Social_Buttons', 'enqueue_scripts' ));
	}
	
	public static function set_hooks_admin() {}


	/**
	  *    run - initial module setup
	  *
	  * @access    public
	  * @param  WP $WP
	  * @return    void
	  */
	 public function run( $WP ) {}


	/**
	 * 	enqueue_scripts - Load the scripts and css
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function enqueue_scripts() {
		//Check to see if the JS file exists in the '/uploads/espresso/' directory
		$js_path = is_readable( EVENT_ESPRESSO_UPLOAD_DIR . 'espresso_social_buttons' . DS . 'espresso_social_buttons.js' ) ? EVENT_ESPRESSO_UPLOAD_URL . 'espresso_social_buttons' . DS : EE_SOCIAL_BUTTONS_URL . 'assets' . DS;
		wp_register_script( 'espresso_social_buttons', $js_path . 'espresso_social_buttons.js', array(), EE_SOCIAL_BUTTONS_VERSION, TRUE );
		//Check to see if the CSS file exists in the '/uploads/espresso/' directory
		$css_path = is_readable( EVENT_ESPRESSO_UPLOAD_DIR . 'espresso_social_buttons' . DS . 'espresso_social_buttons.css' ) ? EVENT_ESPRESSO_UPLOAD_URL . 'espresso_social_buttons' . DS : EE_SOCIAL_BUTTONS_URL . 'assets' . DS;
		wp_register_style( 'espresso_social_buttons', $css_path . 'espresso_social_buttons.css', array(), EE_SOCIAL_BUTTONS_VERSION, 'screen' );
		wp_enqueue_style( 'espresso_social_buttons' );
	}



	/**
	 * @param EE_Transaction$transaction
	 * @return mixed
	 */
	public static function thank_you_page_buttons( $transaction ) {
		if ( $transaction instanceof EE_Transaction ) {
			// load the JS
			wp_enqueue_script( 'espresso_social_buttons' );
			//JS vars
			wp_localize_script(
				'espresso_social_buttons', 'ee_social_buttons',
				array(
					'facebook' => EE_Registry::instance()->CFG->organization->facebook,
				)
			);

			//Get the Twitter handle, else use EventEspresso as the default
			$co_twitter = EE_Registry::instance()->CFG->organization->twitter;
			$co_twitter = ! empty( $co_twitter ) ? str_replace( array( 'twitter.com/', 'http://', 'https://' ), "", $co_twitter ) : 'EventEspresso';

			$template_args = array(
				'heading' 	=> apply_filters( 'FHEE__EED_Social_Buttons__thank_you_page__heading', __( 'Support us on Social Media -- Spread the Word', 'event_espresso' )),
				'events' 		=> array()
			);
			$prev_event = '';
			foreach ( $transaction->registrations() as $registration ) {
				if ( $registration instanceof EE_Registration ) {
					$event_name = $registration->event_name();
					if ( $prev_event != $event_name ) {
						$prev_event = $registration->event_name();
						$template_args['events'][ sanitize_key( $event_name ) ] = array(
							'event_permalink' 		=> $transaction->primary_registration()->event()->get_permalink(),
							'organization_name' 	=> EE_Registry::instance()->CFG->organization->name,
							'event_name' 				=> $event_name,
							'co_twitter' 					=> $co_twitter,
							'tweet_message'			=> apply_filters(
								'FHEE__EED_Social_Buttons__thank_you_page__tweet_message',
								sprintf(
									__( 'I just registered for %1s at %2s', 'event_espresso'),
									$event_name,
									EE_Registry::instance()->CFG->organization->name
								),
								$registration,
								EE_Registry::instance()->CFG->organization
							)
						);
					}
				}
			}
			echo EEH_Template::locate_template( EE_SOCIAL_BUTTONS_TEMPLATES . 'espresso-social-buttons-template.template.php', $template_args );
		}
	}


}
