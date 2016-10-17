<?php
/*
 * Plugin Name: Event Espresso Cancellation Link
 * Description: Create shortcode for event cancel link
 * Plugin URI: 
 * Author: David Lorimer, Memona Usama
 * Author URI:
 * Version: 1.0
 * License: GPL2
 */
 
function cancel_registration(){
	
	if(isset($_REQUEST['txn_reg_status_change'])){
		global $wpdb;
		
		$wpdb->update( 
			  $wpdb->prefix . 'esp_registration', 
			  array( 
				  'STS_ID' => 'RCN',	// string
			  ), 
			  array( 'REG_ID' => $_REQUEST['id'] ), 
			  array( 
				  '%s',	// value1
			  ), 
			  array( '%d' ) 
		  );
		  global $wpdb;
		  $message = '';
		  $set = 0;
	$thepost = $wpdb->get_row( $wpdb->prepare( 'SELECT option_value from '.$wpdb->prefix . 'options where option_name="ee_config_log"' ), ARRAY_A );
	foreach(unserialize($thepost['option_value']) as $org)
	{	
		if(isset($org['request']['organization_name']) && $set!=1){
			$set = 1;
			$from = $org["request"]["organization_email"];
			 $head ='<table class="head-wrap" bgcolor="#999999" width="100%">
	<tbody>
		<tr>
			<td></td>
			<td class="header container">
				<div class="content">
					<table bgcolor="#999999" width="100%">
						<tbody>
							<tr>
								<td width="75%"><img src="'.$org["request"]["organization_logo_url"].'" width="150px"></td>
								<td align="left">
									<h3>'.$org["request"]["organization_name"].'</h3>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</td>
			<td></td>
		</tr>
	</tbody>
</table>';
}
}?>
<?php  $head .='<table class="body-wrap" width="70%" style="padding-left:20%;">
	<tbody>
		<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF">';
			$attende = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix . 'esp_attendee_meta where ATT_ID =( SELECT ATT_ID FROM '.$wpdb->prefix . 'esp_registration where REG_ID='.$_REQUEST['id'].')' ), ARRAY_A );
			 $registrant ='<div class="content">
					<table>
						<tbody>
							<tr>
								<td>
									<h2>Hello, '.$attende["ATT_fname"].':</h2>

									<p class="lead">Your registration was cancelled for the following tickets:</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>';
		
			$event = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix . 'posts where ID=( SELECT EVT_ID FROM '.$wpdb->prefix . 'esp_registration where REG_ID='.$_REQUEST['id'].')' ), ARRAY_A );
			$reg = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix . 'esp_registration where REG_ID='.$_REQUEST['id'] ), ARRAY_A );
			$message .='<div class="content">
					<ul style="background-color:#ecf8ff;height: 60px; padding-top: 11px"><strong>Event Name :</strong></br><a href="'.$event["guid"].'">'.$event["post_title"].'</a></ul>
					<h2>Registrant(s):</h2>
					<h4><strong>'.$attende["ATT_fname"].' '.$attende["ATT_lname"].'</strong></h4>
					<ul><li><strong>Registration Code:</strong> '.$reg["REG_code"].'</li></ul>
				</div>
			</td>
			<td></td>
		</tr>
	</tbody>
</table>';
$sub = $wpdb->get_row( $wpdb->prepare( 'SELECT MTP_content as subject FROM '.$wpdb->prefix . 'esp_message_template where GRP_ID=10 AND MTP_context="attendee" AND MTP_template_field="subject"' ), ARRAY_A );
		$headers = array(
			'MIME-Version: 1.0',
			'From:' . $from,
			'Reply-To:' . $from,
			'Content-Type:text/html; charset=utf-8'
			);
		  wp_mail( $attende["ATT_email"], 'Your registration has been cancelled', $head.$registrant.$message, $headers);
		  $author = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix . 'users where ID ='.$event["post_author"] ), ARRAY_A );	
				 $owner ='<div class="content">
					<table>
						<tbody>
							<tr>
								<td>
									<h2>Hello, '.$author["display_name"].':</h2>

									<p class="lead">Registration was cancelled for the following tickets:</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>';
		  wp_mail( $author["user_email"], 'Registration Cancelled', $head.$owner.$message, $headers);

		}
		
	
}

add_action( 'init', 'cancel_registration' );

function register_new_tony_shortcodes123(){
	if($_REQUEST['action']=='process_reg_step'){
	global $wpdb;
	$thepost = $wpdb->get_row( $wpdb->prepare( 'SELECT MAX(REG_ID) AS reg_id,REG_code from '.$wpdb->prefix . 'esp_registration' ), ARRAY_A );
	$id = $thepost['reg_id'];
	$code = $thepost['REG_code'];
		$pages = $wpdb->get_row( $wpdb->prepare( 'SELECT option_value from '.$wpdb->prefix . 'options where option_name="ee_config"' ), ARRAY_A );
	foreach(unserialize($pages['option_value']) as $org)
	{
		if(isset($org->cancel_page_id)){
		$pageid = $org->cancel_page_id;
		break;
		}
	}
	echo '<table class="x_social" style="margin:0; font-family:Helvetica,Arial,sans-serif; text-align: center;font-size: 18px !important; margin-left: auto; margin-right: auto; background: #ebebeb none repeat scroll 0 0; position: absolute;color: black !important; width: 600px;" >
	<tbody style="margin:0; padding:0; font-family:Helvetica,Arial,sans-serif">
<tr style="margin:0; padding:0; font-family:Helvetica,Arial,sans-serif">
<td style="margin:0; padding:0; font-family:Helvetica,Arial,sans-serif; padding-top:20px">
<table class="x_column" style="margin:0; padding:3px 7px; font-family:Helvetica,Arial,sans-serif; color:black; display:block; font-size:14px; font-weight:bold; margin-bottom:10px; text-align:center; text-decoration:none; margin:0; padding:0; font-family:Helvetica,Arial,sans-serif; float:left;" align="left">
  <tbody style="margin:0; padding:0; font-family:Helvetica,Arial,sans-serif; ">
If you can\'t make it to this event, please <a href="'.get_home_url().'/?page_id='.$pageid.'&reg_code='.$code.'&id='.$id.'&txn_reg_status_change=1">cancel your registration</a>
</tbody>
</table>
<p style="margin:0; padding:0; Helvetica,Arial,sans-serif; font-size:14px; font-weight:normal; line-height:1.6; margin-bottom:10px">&nbsp;
 </p>
</td>
</tr>
</tbody>
</table>';
	}
}
add_action( 'AHEE__EE_Email_Messenger_main_wrapper_template_after_main_body', 'register_new_tony_shortcodes123', 10, 5 );