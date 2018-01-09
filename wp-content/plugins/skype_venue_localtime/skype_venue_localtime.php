<?php
/*
Plugin Name: Skype EE Custom Local Timezone Shortcode - Site Specific Plugin
Description: Shortcode to display events in event local timezone.
*/
/* Begin Adding Functions Below This Line; Do not include an opening PHP tag as this sample code already includes one! */
function register_new_tony_shortcodes( $shortcodes, EE_Shortcodes $lib ) {
   
    //Add a shortcode to be used with the EE Datetimes within messages
    if ( $lib instanceof EE_Datetime_Shortcodes ) {
        //Add your shortcode to the add as the key, the value should be a description of the shortcode.
        $shortcodes['[CUSTOM_DATETIME_SHORTCODE]'] = _('This is datetime Custom Shortcode!');
    }
    //Add a shortcode to be used with the EE Event List within messages
    if ( $lib instanceof EE_Event_Shortcodes ) {
        //Add your shortcode to the add as the key, the value should be a description of the shortcode.
        $shortcodes['[CUSTOM_EVENT_SHORTCODE]'] = _('This is Custom EVENT Shortcode!');
    }
 
    //Return the shortcodes.
    return $shortcodes;
}
add_filter( 'FHEE__EE_Shortcodes__shortcodes', 'register_new_tony_shortcodes', 10, 2 );
function register_new_tony_shortcodes_parser( $parsed, $shortcode, $data, $extra_data, EE_Shortcodes $lib ) {
     global $wpdb;
    //Check for the datetime shortcodes as that's were we added the custom shortcode above
    //also check that $data is the expected object (in this case an EE_Datetime)
	 $event = $data instanceof EE_Event ? $data : null;
    if ( $lib instanceof EE_Datetime_Shortcodes  && $data instanceof EE_Datetime ) {
        //Then check for our specific shortcode
        if ( $shortcode == '[CUSTOM_DATETIME_SHORTCODE]' ) {
              $event = $data;
            //Do whatever you need to do here and return the value for that specific datetime.
            //return $data->get_i18n_datetime('DTT_EVT_start'); 
			//$start_dte = $data->get_i18n_datetime('DTT_EVT_start');
			$thepost = $wpdb->get_row("select VNU_address from {$wpdb->prefix}esp_venue_meta where VNU_ID = (select VNU_ID from {$wpdb->prefix}esp_event_venue where EVT_ID =".$data->get('EVT_ID').")");
			$prepAddr = urlencode($thepost->VNU_address);
         	$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyDk_5niAyDbIGm5Fo7wdnOxP3IVtyIl03k');
       		$output= json_decode($geocode);
          	$latitude = $output->results[0]->geometry->location->lat;
        	$longitude = $output->results[0]->geometry->location->lng;
			$url = "https://maps.googleapis.com/maps/api/timezone/json?timestamp=1331161200&location=".$latitude .",".$longitude."&sensor=false";
			$json_timezone = file_get_contents($url);
			$output2= json_decode($json_timezone);
			//$date = new DateTime($data->get_i18n_datetime('DTT_EVT_start'));
			//$date->setTimeZone(new DateTimeZone($output2->timeZoneId));
			
			//$local_timezone_from = $date->format('Y-m-d H:i:s T');			
			
			$rawstarttime = $data->get( 'DTT_EVT_start' );
			
			//print_R( gmdate('Y-m-d H:i:s T', strtotime($rawstarttime)) );
			$local_timezone_from = gmdate('Y-m-d H:i:s T', strtotime($rawstarttime));
			
			$from = new DateTime($local_timezone_from, new DateTimeZone($output2->timeZoneId));
			$from->setTimeZone(new DateTimeZone($output2->timeZoneId));

			//$date = new DateTime($data->get_i18n_datetime('DTT_EVT_end'));
			$rawendtime = $data->get( 'DTT_EVT_end' );
			//$local_timezone_to = $date->format('Y-m-d H:i:s T');
			$local_timezone_to = gmdate('Y-m-d H:i:s T', strtotime($rawendtime));
			$to = new DateTime($local_timezone_to, new DateTimeZone($output2->timeZoneId));
			$to->setTimeZone(new DateTimeZone($output2->timeZoneId));
			
		return $from->format('F j, Y g:i a').' - '.$to->format('F j, Y g:i a').' '.$from->format('T');
        }
    }
 
    if ( $lib instanceof EE_Event_Shortcodes ) {
    //Then check for our specific shortcode
        if ( $shortcode == '[CUSTOM_EVENT_SHORTCODE]' ) {
         
            //First check we have an event object.
            $event = $data instanceof EE_Event ? $data : null;
 
            //if no event, then let's see if there is a reg_obj.  If there IS, then we'll try and grab the event from the reg_obj instead.
            if ( empty( $event ) ) {
                $aee = $data instanceof EE_Messages_Addressee ? $data : NULL;
                $aee = $extra_data instanceof EE_Messages_Addressee ? $extra_data : $aee;
 
                $event = $aee instanceof EE_Messages_Addressee && $aee->reg_obj instanceof EE_Registration ? $aee->reg_obj->event() : NULL;
            }
             
            //Check we do now actually have the event object.
            if ( !empty( $event ) ) {
                //Do whatever you need to do using the event object, which is now $event and return.
                return $event->ID();
            }
        }
    }
 
    //If not within the correct section, or parsing the correct shortcode,
    //Return the currently parsed content.
    return $parsed;
}
add_filter( 'FHEE__EE_Shortcodes__parser_after', 'register_new_tony_shortcodes_parser', 10, 5 );