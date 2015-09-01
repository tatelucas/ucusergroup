<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
* Event Espresso
*
* Event Registration and Management Plugin for WordPress
*
* @ package 		Event Espresso
* @ author			Seth Shoultes
* @ copyright 	(c) 2008-2011 Event Espresso  All Rights Reserved.
* @ license 		{@link http://eventespresso.com/support/terms-conditions/}   * see Plugin Licensing *
* @ link 				{@link http://www.eventespresso.com}
* @ since		 	$VID:$
*
* ------------------------------------------------------------------------
*
* Social_Buttons_Admin_Page_Init class
*
* This is the init for the Social_Buttons Addon Admin Pages.  See EE_Admin_Page_Init for method inline docs.
*
* @package			Event Espresso (social_buttons addon)
* @subpackage		admin/Social_Buttons_Admin_Page_Init.core.php
* @author				Darren Ethier
*
* ------------------------------------------------------------------------
*/
class Social_Buttons_Admin_Page_Init extends EE_Admin_Page_Init  {

	/**
	 * 	constructor
	 *
	 * @access public
	 * @return \Social_Buttons_Admin_Page_Init
	 */
	public function __construct() {

		do_action( 'AHEE_log', __FILE__, __FUNCTION__, '' );

		define( 'SOCIAL_BUTTONS_PG_SLUG', 'espresso_social_buttons' );
		define( 'SOCIAL_BUTTONS_LABEL', __( 'Social Buttons', 'event_espresso' ));
		define( 'EE_SOCIAL_BUTTONS_ADMIN_URL', admin_url( 'admin.php?page=' . SOCIAL_BUTTONS_PG_SLUG ));
		define( 'EE_SOCIAL_BUTTONS_ADMIN_ASSETS_PATH', EE_SOCIAL_BUTTONS_ADMIN . 'assets' . DS );
		define( 'EE_SOCIAL_BUTTONS_ADMIN_ASSETS_URL', EE_SOCIAL_BUTTONS_URL . 'admin' . DS . 'social_buttons' . DS . 'assets' . DS );
		define( 'EE_SOCIAL_BUTTONS_ADMIN_TEMPLATE_PATH', EE_SOCIAL_BUTTONS_ADMIN . 'templates' . DS );
		define( 'EE_SOCIAL_BUTTONS_ADMIN_TEMPLATE_URL', EE_SOCIAL_BUTTONS_URL . 'admin' . DS . 'social_buttons' . DS . 'templates' . DS );

		parent::__construct();
		$this->_folder_path = EE_SOCIAL_BUTTONS_ADMIN;

	}





	protected function _set_init_properties() {
		$this->label = SOCIAL_BUTTONS_LABEL;
	}



	/**
	*		_set_menu_map
	*
	*		@access 		protected
	*		@return 		void
	*/
	protected function _set_menu_map() {
		$this->_menu_map = new EE_Admin_Page_Sub_Menu( array(
			'menu_group' => 'addons',
			'menu_order' => 25,
			'show_on_menu' => TRUE,
			'parent_slug' => 'espresso_events',
			'menu_slug' => SOCIAL_BUTTONS_PG_SLUG,
			'menu_label' => SOCIAL_BUTTONS_LABEL,
			'capability' => 'administrator',
			'admin_init_page' => $this
		));
	}



}
// End of file Social_Buttons_Admin_Page_Init.core.php
// Location: /wp-content/plugins/espresso-new-addon/admin/social_buttons/Social_Buttons_Admin_Page_Init.core.php
