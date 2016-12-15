<?php
if (!defined('EVENT_ESPRESSO_VERSION') ){
	exit('NO direct script access allowed');
}
/**
 * EE_Message_List_Table
 *
 * extends EE_Admin_List_Table class
 *
 * @package		Event Espresso
 * @subpackage	/includes/core/admin/messages
 * @author		Darren Ethier
 *
 */
class EE_Message_List_Table extends EE_Admin_List_Table {



	/**
	 * @return Messages_Admin_Page
	 */
	public function get_admin_page() {
		return $this->_admin_page;
	}



	protected function _setup_data() {
		$this->_data = $this->_get_messages( $this->_per_page, $this->_view );
		$this->_all_data_count = $this->_get_messages( $this->_per_page, $this->_view, true );
	}




	protected function _set_properties() {
		$this->_wp_list_args = array(
			'singular' => __( 'Message', 'event_espresso' ),
			'plural' => __( 'Messages', 'event_espresso' ),
			'ajax' => true,
			'screen' => $this->get_admin_page()->get_current_screen()->id
		);

		$this->_columns = array(
			'cb' => '<input type="checkbox" />',
			'to' => __( 'To', 'event_espresso' ),
			'from' => __( 'From', 'event_espresso' ),
			'messenger' => __( 'Messenger', 'event_espresso' ),
			'message_type' => __( 'Message Type', 'event_espresso' ),
			'context' => __( 'Context', 'event_espresso' ),
			'modified' => __( 'Modified', 'event_espresso' ),
			'action' => __( 'Actions', 'event_espresso' ),
			'msg_id' => __( 'ID', 'event_espresso' ),
		);

		$this->_sortable_columns = array(
			'modified' => array( 'MSG_modified' => true ),
			'message_type' => array( 'MSG_message_type' => false ),
			'messenger' => array( 'MSG_messenger' => false ),
			'to' => array( 'MSG_to' => false ),
			'from' => array( 'MSG_from' => false ),
			'context' => array( 'MSG_context' => false ),
			'msg_id' => array( 'MSG_ID', false ),
		);

		$this->_primary_column = 'to';

		$this->_hidden_columns = array(
			'msg_id'
		);
	}



	/**
	 * This simply sets up the row class for the table rows.
	 * Allows for easier overriding of child methods for setting up sorting.
	 * @param  object $item the current item
	 * @return string
	 */
	protected function _get_row_class( $item ) {
		$class = parent::_get_row_class( $item );
		//add status class
		$class .= ' ee-status-strip msg-status-' . $item->STS_ID();
		if ( $this->_has_checkbox_column ) {
			$class .= ' has-checkbox-column';
		}
		return $class;
	}



	/**
	 * _get_table_filters
	 * We use this to assemble and return any filters that are associated with this table that help further refine what get's shown in the table.
	 *
	 * @abstract
	 * @access protected
	 * @return string
	 * @throws \EE_Error
	 */
	protected function _get_table_filters() {
		$filters = array();
		EE_Registry::instance()->load_helper( 'Form_Fields' );
		//setup messengers for selects
		$m_values = $this->get_admin_page()->get_messengers_for_list_table();
		//lets do the same for message types
		$mt_values = $this->get_admin_page()->get_message_types_for_list_table();
		//and the same for contexts
		$contexts = $this->get_admin_page()->get_contexts_for_message_types_for_list_table();
		$i = 1;
		$labels = $c_values = array();
		foreach ( $contexts as $context => $label ) {
			//some message types may have the same label for a different context, so we're grouping these together so the end user
			//doesn't get confused.
			if ( isset( $labels[ $label ] ) ) {
				$c_values[ $labels[ $label ] ]['id'] .= ',' . $context;
				continue;
			}
			$c_values[ $i ]['id'] = $context;
			$c_values[ $i ]['text'] = $label;
			$labels[ $label ] = $i;
			$i++;
		}

		$msgr_default[0] = array(
			'id' => 'none_selected',
			'text' => __( 'All Messengers', 'event_espresso' )
		);

		$mt_default[0] = array(
			'id' => 'none_selected',
			'text' => __( 'All Message Types', 'event_espresso' )
		);

		$c_default[0] = array(
			'id' => 'none_selected',
			'text' => __( 'All Contexts', 'event_espresso' )
		);

		$msgr_filters = count( $m_values ) > 1
			? array_merge( $msgr_default, $m_values )
			: $m_values;
		$mt_filters = ! empty( $mt_values ) && count( $mt_values ) > 1
			? array_merge( $mt_default, $mt_values )
			: (array) $mt_values;
		$c_filters = ! empty( $c_values ) && count( $c_values ) > 1
			? array_merge( $c_default, $c_values )
			: (array) $c_values;

		if ( empty( $msgr_filters ) ) {
			$msgr_filters[0] = array(
				'id'   => 'none_selected',
				'text' => __( 'No Messengers active', 'event_espresso' )
			);
		}

		if ( empty( $mt_filters ) ) {
			$mt_filters[0] = array(
				'id'   => 'none_selected',
				'text' => __( 'No Message Types active', 'event_espresso' )
			);
		}

		if ( empty( $c_filters ) ) {
			$c_filters[0] = array(
				'id'   => 'none_selected',
				'text' => __( 'No Contexts (because no message types active)', 'event_espresso' )
			);
		}

		if ( count ( $msgr_filters ) > 1 ) {
			$filters[] = EEH_Form_Fields::select_input(
				'ee_messenger_filter_by',
				array_values( $msgr_filters ),
				isset( $this->_req_data['ee_messenger_filter_by'] )
					? sanitize_title( $this->_req_data['ee_messenger_filter_by'] )
					: ''
			);
		}
		if ( count( $mt_filters ) > 1 ) {
		$filters[] = EEH_Form_Fields::select_input(
				'ee_message_type_filter_by',
				array_values( $mt_filters ),
				isset( $this->_req_data['ee_message_type_filter_by'] ) ? sanitize_title(
					$this->_req_data['ee_message_type_filter_by']
				) : ''
			);
		}
		if ( count( $c_filters ) > 1 ) {
			$filters[] = EEH_Form_Fields::select_input(
				'ee_context_filter_by',
				array_values( $c_filters ),
				isset( $this->_req_data['ee_context_filter_by'] ) ? sanitize_text_field(
					$this->_req_data['ee_context_filter_by']
				) : ''
			);
		}
		return $filters;
	}



	protected function _add_view_counts() {
		foreach ( $this->_views as $view => $args ) {
			$this->_views[ $view ]['count'] = $this->_get_messages( $this->_per_page, $view, true, true );
		}
	}



	/**
	 * @param EE_Message $message
	 * @return string   checkbox
	 * @throws \EE_Error
	 */
	public function column_cb( $message ) {
		return sprintf( '<input type="checkbox" name="MSG_ID[%s]" value="1" />', $message->ID() );
	}



	/**
	 * @param EE_Message $message
	 * @return string
	 * @throws \EE_Error
	 */
	public function column_msg_id( EE_Message $message ) {
		return $message->ID();
	}



	/**
	 * @param EE_Message $message
	 * @return string    The recipient of the message
	 * @throws \EE_Error
	 */
	public function column_to( EE_Message $message ) {
		EE_Registry::instance()->load_helper( 'URL' );
		$actions = array();
		$actions['delete'] = '<a href="'
			. EEH_URL::add_query_args_and_nonce(
				array(
					'page' => 'espresso_messages',
					'action' => 'delete_ee_message',
					'MSG_ID' => $message->ID()
				),
				admin_url( 'admin.php' )
			)
			. '">' . __( 'Delete', 'event_espresso' ) . '</a>';
		return $message->to() . $this->row_actions( $actions );
	}


	/**
	 * @param EE_Message $message
	 * @return string   The sender of the message
	 */
	public function column_from( EE_Message $message ) {
		return $message->from();
	}


	/**
	 *
	 * @param EE_Message $message
	 * @return string  The messenger used to send the message.
	 */
	public function column_messenger( EE_Message $message ) {
		return ucwords( $message->messenger_label() );
	}


	/**
	 * @param EE_Message $message
	 * @return string  The message type used to generate the message.
	 */
	public function column_message_type( EE_Message $message ) {
		return ucwords( $message->message_type_label() );
	}


	/**
	 * @param EE_Message $message
	 * @return string  The context the message was generated for.
	 */
	public function column_context( EE_Message $message ) {
		return $message->context_label();
	}


	/**
	 * @param EE_Message $message
	 * @return string    The timestamp when this message was last modified.
	 */
	public function column_modified( EE_Message $message ) {
		return $message->modified();
	}


	/**
	 * @param EE_Message $message
	 * @return string   Actions that can be done on the current message.
	 */
	public function column_action( EE_Message $message ) {
		EE_Registry::instance()->load_helper( 'MSG_Template' );
		$action_links = array(
			'view' => EEH_MSG_Template::get_message_action_link( 'view', $message ),
			'error' => EEH_MSG_Template::get_message_action_link( 'error', $message ),
			'generate_now' => EEH_MSG_Template::get_message_action_link( 'generate_now', $message ),
			'send_now' => EEH_MSG_Template::get_message_action_link( 'send_now', $message ),
			'queue_for_resending' => EEH_MSG_Template::get_message_action_link( 'queue_for_resending', $message ),
			'view_transaction' => EEH_MSG_Template::get_message_action_link( 'view_transaction', $message ),
		);
		$content = '';
		switch ( $message->STS_ID() ) {
			case EEM_Message::status_sent :
				$content = $action_links['view'] . $action_links['queue_for_resending'] . $action_links['view_transaction'];
				break;
			case EEM_Message::status_resend :
				$content = $action_links['view'] . $action_links['send_now'] . $action_links['view_transaction'];
				break;
			case EEM_Message::status_retry :
				$content = $action_links['view'] . $action_links['send_now'] . $action_links['error'] . $action_links['view_transaction'];
				break;
			case EEM_Message::status_failed :
			case EEM_Message::status_debug_only :
				$content = $action_links['error'] . $action_links['view_transaction'];
				break;
			case EEM_Message::status_idle :
				$content = $action_links['view'] . $action_links['send_now'] . $action_links['view_transaction'];
				break;
			case EEM_Message::status_incomplete;
				$content = $action_links['generate_now'] . $action_links['view_transaction'];
				break;
		}
		return $content;
	}



	/**
	 * Retrieve the EE_Message objects for the list table.
	 *
	 * @param int    $perpage The number of items per page
	 * @param string $view    The view items are being retrieved for
	 * @param bool   $count   Whether to just return a count or not.
	 * @param bool   $all     Disregard any paging info (no limit on data returned).
	 * @return int | EE_Message[]
	 * @throws \EE_Error
	 */
	protected function _get_messages( $perpage = 10, $view = 'all', $count = false, $all = false ) {

		$current_page = isset( $this->_req_data['paged'] ) && ! empty( $this->_req_data['paged'] )
			? $this->_req_data['paged']
			: 1;

		$per_page = isset( $this->_req_data['perpage'] ) && ! empty( $this->_req_data['perpage'] )
			? $this->_req_data['perpage']
			: $perpage;

		$offset = ( $current_page - 1 ) * $per_page;
		$limit = $all || $count ? null : array( $offset, $per_page );
		$query_params = array(
			'order_by' => empty( $this->_req_data[ 'orderby' ] ) ? 'MSG_modified' : $this->_req_data[ 'orderby' ],
			'order'    => empty( $this->_req_data[ 'order' ] ) ? 'DESC' : $this->_req_data[ 'order' ],
			'limit'    => $limit,
		);

		/**
		 * Any filters coming in from other routes?
		 */
		if ( isset( $this->_req_data['filterby'] ) ) {
			$query_params = array_merge( $query_params, EEM_Message::instance()->filter_by_query_params() );
			if ( ! $count ) {
				$query_params['group_by'] = 'MSG_ID';
			}
		}

		//view conditionals
		if ( $view !== 'all' && $count && $all ) {
			$query_params[0]['AND*view_conditional'] = array(
				'STS_ID' => strtoupper( $view ),
			);
		}

		if ( ! $all && ! empty( $this->_req_data['status'] ) && $this->_req_data['status'] !== 'all' ) {
			$query_params[0]['AND*view_conditional'] = array(
				'STS_ID' => strtoupper( $this->_req_data['status'] ),
			);
		}

		if ( ! $all && ! empty( $this->_req_data['s'] ) ) {
			$search_string = '%' . $this->_req_data['s'] . '%';
			$query_params[0]['OR'] = array(
				'MSG_to' => array( 'LIKE', $search_string ),
				'MSG_from' => array( 'LIKE', $search_string ),
				'MSG_subject' => array( 'LIKE', $search_string ),
				'MSG_content' => array( 'LIKE', $search_string ),
			);
		}

		//account for debug only status.  We don't show Messages with the EEM_Message::status_debug_only to clients when
		//the messages system is in debug mode.
		//Note: for backward compat with previous iterations, this is necessary because there may be EEM_Message::status_debug_only
		//messages in the database.
		if ( ! EEM_Message::debug() ) {
			$query_params[0]['AND*debug_only_conditional'] = array(
				'STS_ID' => array( '!=', EEM_Message::status_debug_only )
			);
		}

		//account for filters
		if (
			! $all
			&& isset( $this->_req_data['ee_messenger_filter_by'] )
			&& $this->_req_data['ee_messenger_filter_by'] !== 'none_selected'
		) {
			$query_params[0]['AND*messenger_filter'] = array(
				'MSG_messenger' => $this->_req_data['ee_messenger_filter_by'],
			);
		}
		if (
			! $all
			&& ! empty( $this->_req_data['ee_message_type_filter_by'] )
			&& $this->_req_data['ee_message_type_filter_by'] !== 'none_selected'
		) {
			$query_params[0]['AND*message_type_filter'] = array(
				'MSG_message_type' => $this->_req_data['ee_message_type_filter_by'],
			);
		}

		if (
			! $all
			&& ! empty( $this->_req_data['ee_context_filter_by'] )
			&& $this->_req_data['ee_context_filter_by'] !== 'none_selected'
		) {
			$query_params[0]['AND*context_filter'] = array(
				'MSG_context' => array( 'IN', explode( ',', $this->_req_data['ee_context_filter_by'] ) )
			);
		}

		return $count
			? EEM_Message::instance()->count( $query_params, null, true )
			: EEM_Message::instance()->get_all( $query_params );

	}
} //end EE_Message_List_Table class