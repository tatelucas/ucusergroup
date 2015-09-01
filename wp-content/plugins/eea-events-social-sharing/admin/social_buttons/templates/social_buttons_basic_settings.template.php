<?php
/* @var $config EE_Social_Buttons_Config */
//var_dump( $social_buttons_config );
?>
<div class="padding">
	<h4>
		<?php _e('Social Buttons Settings', 'event_espresso'); ?>
	</h4>
	<table class="form-table">
		<tbody>
			<tr>
				<th><?php _e("Show Social Buttons in the event listings?", 'event_espresso');?></th>
				<td>
					<?php echo EEH_Form_Fields::select( __('Show Social Buttons in the event listings?', 'event_espresso'), $social_buttons_config->show_social_buttons_event_list, $yes_no_values, 'social_buttons[show_social_buttons_event_list]', 'show_social_buttons_event_list' ); ?><br/>
					<span class="description">
						<?php _e('Set to \'Yes\' to show the Social Buttons in your event listings.', 'event_espresso'); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th><?php _e("Show Social Buttons in the single event page?", 'event_espresso');?></th>
				<td>
					<?php echo EEH_Form_Fields::select( __('Show Social Buttons in the single event page?', 'event_espresso'), $social_buttons_config->show_social_buttons_single_event_page, $yes_no_values, 'social_buttons[show_social_buttons_single_event_page]', 'show_social_buttons_single_event_page' ); ?><br/>
					<span class="description">
						<?php _e('Set to \'Yes\' to show the Social Buttons in the single event page.', 'event_espresso'); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th><?php _e("Show Social Buttons in the registration confirmation/thank you page?", 'event_espresso');?></th>
				<td>
					<?php echo EEH_Form_Fields::select( __('Show Social Buttons in the registration confirmation/thank you page?', 'event_espresso'), $social_buttons_config->show_social_buttons_confirmation_page, $yes_no_values, 'social_buttons[show_social_buttons_confirmation_page]', 'show_social_buttons_confirmation_page' ); ?><br/>
					<span class="description">
						<?php _e('Set to \'Yes\' to show the Social Buttons in the registration confirmation/thank you page.', 'event_espresso'); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th><?php _e("Reset Social Buttons Settings?", 'event_espresso');?></th>
				<td>
					<?php echo EEH_Form_Fields::select( __('Reset Social Buttons Settings?', 'event_espresso'), 0, $yes_no_values, 'reset_social_buttons', 'reset_social_buttons' ); ?><br/>
					<span class="description">
						<?php _e('Set to \'Yes\' and then click \'Save\' to confirm reset all basic and advanced Event Espresso Social Buttons settings to their plugin defaults.', 'event_espresso'); ?>
					</span>
				</td>
			</tr>

		</tbody>
	</table>

</div>

<input type='hidden' name="return_action" value="<?php echo $return_action?>">

