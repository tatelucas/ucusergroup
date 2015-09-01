<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * Event Espresso
 *
 * Event Registration and Ticketing Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			    Event Espresso
 * @ copyright		(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 * @ version		 	$VID:$
 *
 * ------------------------------------------------------------------------
 */
 /**
 *
 * Class EE_Social_Buttons_Config
 *
 * Description
 *
 * @package         Event Espresso
 * @subpackage    core
 * @author				Brent Christensen
 * @since		 	   $VID:$
 *
 */

class EE_Social_Buttons_Config extends EE_Config_Base {

	public $show_social_buttons_event_list;
	public $show_social_buttons_single_event_page;
	public $show_social_buttons_confirmation_page;

	/**
	 *    class constructor
	 *
	 * @access    public
	 * @return \EE_Organization_Config
	 */
	public function __construct() {
		$this->show_social_buttons_event_list = 1;
		$this->show_social_buttons_single_event_page = 1;
		$this->show_social_buttons_confirmation_page = 1;
		
	}


}



// End of file EE_Social_Buttons_Config.php
// Location: /wp-content/plugins/espresso-new-addon/EE_Social_Buttons_Config.php