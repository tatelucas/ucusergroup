<?php
/*
Plugin Name: Skype Mailchimp Plugin
Plugin URI:
Description: Custom Plugin to update Skype Mailchimp Mailing Lists when users change profile information.  Requires Advanced Custom Fields.
Author: David Lorimer
Version: 1.1.3
Author URI:
*/

/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

require_once( 'mailchimp-api/src/Drewm/MailChimp.php' );
// This is for namespacing since Drew used that.
//use \Drewm;

// Your Mailchimp API Key
//listed here for reference, set for file below
$api = '751f17f8425c740fccbe13dd13457f17-us14';
// Initializing the $MailChimp object
$SkypeMailChimp = new \Drewm\SkypeMailChimp($api);

//print_r($SkypeMailChimp->call('lists/list'));


/*
 $member_info = $MailChimp->call('lists/member-info', array(
    'id' => '5b5fda2cb5', // your mailchimp list id here
    'emails' => array(
      array('email' => "davidtrio04@yahoo.com") // Mr Potato's email here
      )
    )
  );
print_r($member_info);
*/




//set up constants
function get_mc_api () {
   $api = '751f17f8425c740fccbe13dd13457f17-us14';
   return $api;
}

function get_log_file_location () {
    //set up log location
    $SetLogFileLocation = "/";
				/* this doesn't work for some reason
				$uploads = wp_upload_dir();
				$upload_path = $uploads['basedir'];
				$SetLogFileLocation = $upload_path . "/userfile/";

				if (!file_exists($LogFileLocation)) {
					mkdir($SetLogFileLocation, 0755, true);
				}
				*/
   return $SetLogFileLocation;
}

function get_mc_mailing_list ($usergroupID) {
	
		//only update MailChimp from the Prod site
		if (strpos($_SERVER['HTTP_HOST'], 'skypeug') && !strpos($_SERVER['HTTP_HOST'], 'test') && !strpos($_SERVER['HTTP_HOST'], 'dev') ) {	
	
		  //get the correct MC mailing list for their selection
		  //this is a static table to match the Wordpress UserGroupID to the Mailchimp Mailing List ID

				switch ($usergroupID) {
					case "10":
						//Chicago, IL
						$mcListID = '096f8941d6';
						return $mcListID;
						break;
					case "11":
						//Cincinnati, OH
						$mcListID = '64b8ef7270';
						return $mcListID;
						break;
					case "15":
						//Philadelphia, PA
						$mcListID = 'b89ddd79ad';
						return $mcListID;
						break;				
					case "18":
						//Los Angeles, CA
						$mcListID = '846740df10';
						return $mcListID;
						break;
					case "19":
						//Nashville, TN
						$mcListID = 'ceebbf70cb';
						return $mcListID;
						break;
					case "20":
						//Silicon Valley, CA
						$mcListID = '44dcd1357b';
						return $mcListID;
						break;				
					case "21":
						//Atlanta, GA
						$mcListID = 'd399589240';
						return $mcListID;
						break;
					case "30":
						//Baltimore, MD
						$mcListID = 'b883ada8be';
						return $mcListID;
						break;		
					case "31":
						//Kansas City, MO
						$mcListID = '796fab287f';
						return $mcListID;
						break;						
					case "32":
						//Detroit, MI
						$mcListID = '312cd14656';
						return $mcListID;
						break;						
					case "33":
						//Boise, ID
						$mcListID = '88850fc1af';
						return $mcListID;
						break;
					case "34":
						//Charlotte, NC
						$mcListID = '0e7b14de4b';
						return $mcListID;
						break;	
					case "35":
						//Milwaukee, WI
						$mcListID = '294a2cd849';
						return $mcListID;
						break;
					case "36":
						//Seattle, WA
						$mcListID = '13c845292a';
						return $mcListID;
						break;				
					case "37":
						//Portland, OR
						$mcListID = '785d37bd12';
						return $mcListID;
						break;				
					case "38":
						//New York, NY
						$mcListID = '989e634a00';
						return $mcListID;
						break;			
					case "39":
						//San Francisco, CA
						$mcListID = '38c5b62a1c';
						return $mcListID;
						break;						
					case "48":
						//Cleveland, OH
						$mcListID = '96abea3cec';
						return $mcListID;
						break;					
					case "51":
						//Austin, TX
						$mcListID = '667df5d949';
						return $mcListID;
						break;							
					default:
						//we have a problem
						// the list the user selected has an ID that is not in our table
						$mcListID = 'ERROR';
						return 'ERROR';
						break;
				}	
		
		} else {
			//this is a dev site - use the developer test list
			$mcListID = '33c8cfd8ff';
			return $mcListID;			
		}
}



add_action( 'profile_update', 'skype_mc_profile_updatex', 50, 2 );
add_filter( 'update_user_metadata', 'skype_mc_usergroup_update', 10, 5 );


function skype_mc_usergroup_update( $null, $object_id, $meta_key, $meta_value, $prev_value ) {

	if ( 'user_group' == $meta_key ) {
		
		$old_group = get_user_meta($object_id, 'user_group', true);
		
		//dwl - todo - 
		// for the first while, we don't want to use the following check, as many of the registered users are not in mailchimp at all.
		// add this code back in (swich the two following lines) after January of 2018
		
		//this code is already switched - dwl, Jan 2017
		if ($old_group != $meta_value) {
		//if (1==1) {
			//if the value hasn't changed, don't do anything.  If it has changed, then do change on Mailchimp.

			
			$user_id = $object_id;
			
			
			//When a user's profile is updated
			//Get the field for what user group they are a part of
			
			//$usergroupID = get_field('user_group', 'user_'.$user_id);
			$usergroupID = $meta_value;
			
			if (!empty($usergroupID)) {
				//if their usergroup is not blank or null, then grab the list of mailing lists from Mailchimp
				
			
				//add user to appropriate mailchimp list
				  // Mailchimp Info
				  $api = get_mc_api();
				  $mcListId = get_mc_mailing_list($usergroupID);
				  
				  if ($mcListId == "ERROR") {
						$err_msg = 'On SkypeUG, a Wordpress user has selected a UserGroup whose ID is not in the mapping table to match to Mailchimp list ID.  Please investigate and correct as soon as possible.  The Wordpress UserGroup ID submitted is ' . $usergroupID . '. The User ID is ' .$userdata->ID;
						wp_mail( 'dwlorimer@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
						wp_mail( 'tate.lucas@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);			  
				  } else {
				  
					  
					  // Initializing the $MailChimp object
					  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);

					  //get the user's email address
					  $userdata = get_userdata( $user_id );
					  $useremail = $userdata->user_email;
					  
					  //set first name
					  if (!empty($userdata->first_name)) {
						  $first_name = $userdata->first_name;
					  } elseif (isset($_POST['first_name'])) {
						  $first_name = $_POST['first_name'];
					  } else {					  
						  $first_name = '';
					  }
					  
					  //set last name
					  if (!empty($userdata->last_name)) {
						  $last_name = $userdata->last_name;
					  } elseif (isset($_POST['last_name'])) {
						  $last_name = $_POST['last_name'];
					  } else {
						  $last_name = '';
					  }

						  $merge_vars = array(
								   'FNAME'=>    $first_name,
								   'LNAME'=>    $last_name,
								   );

								   //print_r($merge_vars);
								   //print_r($user->user_email);


							// code for single-updates
								$retval = $SkypeMailChimp->call('lists/subscribe', array(
									'id' => $mcListId, // your mailchimp list id here
									'email' => array(
									  'email' => $useremail
									  ),
									'merge_vars' => $merge_vars,
									'update_existing' => true,
									'double_optin' => false,
									'send_welcome' => false
								  )
								);

								//print_r($retval);

								if($retval['error']) {
									$msg .= "\tCode=".$SkypeMailChimp->errorCode."\n";
									$msg .= "\tMsg=".$SkypeMailChimp->errorMessage."\n";
								}		  


					$chimpdelmsg = $userdata->user_email;
					$LogFileLocation = get_log_file_location();

					//if something goes wrong, email admin
					  if ($retval['status'] == "error") {
							//code 232 means the email address was never subscribed in the first place, which we can ignore.
							file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'An error occured while trying to add a Skype user to Mailchimp - not successful: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
							//wp_mail( get_option( 'admin_email' ), 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg );
							wp_mail( 'dwlorimer@gmail.com', 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp, using skype_mc_usergroup_update.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg . PHP_EOL . $SkypeMailChimp->errorCode."\n" . "\tMsg=".$SkypeMailChimp->errorMessage."\n" );
					  } else {
							//file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Added User to Mailchimp: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
					  }		
					
				
				
		//only delete from MailChimp lists on the Prod site, since it's all the same list on the dev site.
		if (strpos($_SERVER['HTTP_HOST'], 'skypeug') && !strpos($_SERVER['HTTP_HOST'], 'test') && !strpos($_SERVER['HTTP_HOST'], 'dev') ) {	
					//remove from old mailchimp list
					  // Mailchimp Info
					  //$api = get_mc_api();
					  $mcListId = get_mc_mailing_list($old_group);
					  // Initializing the $MailChimp object
					  //$MailChimp = new \Drewm\MailChimp($api);

					  //get the user's email address
					  //$userdata = get_userdata( $user_id );
					  //$useremail = $userdata->user_email;
					  
				  if ($mcListId == "ERROR") {
						//do nothing, we were just deleting anyway	  
				  } else {					  

					  //delete user from Mailchimp
								$retval = $SkypeMailChimp->call('lists/unsubscribe', array(
									'id' => $mcListId, // your mailchimp list id here
									'email' => array( 'email' => $useremail ),
									'delete_member' => true,
									'send_goodbye' => false,
									'send_notify' => false
								  )
								);

					$chimpdelmsg = $userdata->user_email;
					$LogFileLocation = get_log_file_location();

					//if something goes wrong, email admin
					  if ($retval['status'] == "error" && $retval['code'] != "232") {
							//code 232 means the email address was never subscribed in the first place, which we can ignore.
							file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'An error occured while trying to delete a Skype user from Mailchimp - not successful: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
							//wp_mail( get_option( 'admin_email' ), 'Error Occured while updating user in Skype', "An error occured while trying to delete a Skype user from a list in Mailchimp.  Please check the following user:" . PHP_EOL . $chimpdelmsg );
							wp_mail( get_option( 'dwlorimer@gmail.com' ), 'Error Occured while updating user in Skype', "An error occured while trying to delete a Skype user from a list in Mailchimp.  Please check the following user:" . PHP_EOL . $chimpdelmsg );
					  } else {
							//file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Removed User from Mailchimp list because they changed to a different list: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
					  }
				  }					  
			  
		}
		  
				} //end no errors, regular process
			} //end usergroupID is not blank	
		} //end check if group is the same		  
		
		
	}

	return null; // this means: go on with the normal execution in meta.php
}



function skype_mc_profile_updatex( $user_id, $old_user_data ) {
	//if email changes, update email on Mailchimp
	//print_R($user_id);
	
	  $userdata = get_userdata( $user_id );
	  $useremail = $userdata->user_email;
	  $olduseremail = $old_user_data->user_email;

	if ($useremail != $olduseremail) {
		//email address is changed, remove old, and add new user
		
		$usergroupID = get_field('user_group', 'user_'.$user_id);
		$mcListId = get_mc_mailing_list($usergroupID);			
		
				//remove from old mailchimp list
				  // Mailchimp Info
				  $api = get_mc_api();
				  $mcListId = get_mc_mailing_list($old_group);
				  
				  if ($mcListId == "ERROR") {
						$err_msg = 'On SkypeUG, a Wordpress user has selected a UserGroup whose ID is not in the mapping table to match to Mailchimp list ID.  Please investigate and correct as soon as possible.  The Wordpress UserGroup ID submitted is ' . $usergroupID . '. The User ID is ' .$userdata->ID;
						wp_mail( 'dwlorimer@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
						wp_mail( 'tate.lucas@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);			  
				  } else {				  
				  
					  // Initializing the $MailChimp object
					  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);

					  //delete user from Mailchimp
								$retval = $SkypeMailChimp->call('lists/unsubscribe', array(
									'id' => $mcListId, // your mailchimp list id here
									'email' => array( 'email' => $olduseremail ),
									'delete_member' => true,
									'send_goodbye' => false,
									'send_notify' => false
								  )
								);		

					
					//add new email to Mailchimp
						$merge_vars = array(
						   'FNAME'=>    $userdata->first_name,
						   'LNAME'=>    $userdata->last_name,
						   );

					// code for single-updates
						$retval = $MailChimp->call('lists/subscribe', array(
							'id' => $mcListId, // your mailchimp list id here
							'email' => array(
							  'email' => $useremail
							  ),
							'merge_vars' => $merge_vars,
							'update_existing' => true,
							'double_optin' => false,
							'send_welcome' => false
						  )
						);	
				  }
	} else {
		//just update the name
		
		$usergroupID = get_field('user_group', 'user_'.$user_id);
		$mcListId = get_mc_mailing_list($usergroupID);	
		
		
				  if ($mcListId == "ERROR") {
						$err_msg = 'On SkypeUG, a Wordpress user has selected a UserGroup whose ID is not in the mapping table to match to Mailchimp list ID.  Please investigate and correct as soon as possible.  The Wordpress UserGroup ID submitted is ' . $usergroupID . '. The User ID is ' .$userdata->ID;
						wp_mail( 'dwlorimer@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
						wp_mail( 'tate.lucas@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);			  
				  } else {		

						  // Mailchimp Info
						  $api = get_mc_api();
						  // Initializing the $MailChimp object
						  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);		
				
					  $merge_vars = array(
							   'FNAME'=>    $userdata->first_name,
							   'LNAME'=>    $userdata->last_name,
							   );

						// code for single-updates
							$retval = $SkypeMailChimp->call('lists/subscribe', array(
								'id' => $mcListId, // your mailchimp list id here
								'email' => array(
								  'email' => $useremail
								  ),
								'merge_vars' => $merge_vars,
								'update_existing' => true,
								'double_optin' => false,
								'send_welcome' => false
							  )
							);
				  }
	}
}










//previous function for reference.  This was triggered using 'profile_update' action.  Worked fine, but had no access to the previous group value
    function skype_mc_profile_update( $user_id, $old_user_data ) {
		
        //When a user's profile is updated
		//Get the field for what user group they are a part of
		
		$usergroupID = get_field('user_group', 'user_'.$user_id);
		
		if (!empty($usergroupID)) {
			//if their usergroup is not blank or null, then grab the list of mailing lists from Mailchimp
			
		
		//add user to appropriate mailchimp list
		  // Mailchimp Info
		  $api = get_mc_api();
		  $mcListId = get_mc_mailing_list($usergroupID);
		  
		  if ($mcListID == "ERROR") {
				$err_msg = 'On SkypeUG, a Wordpress user has selected a UserGroup whose ID is not in the mapping table to match to Mailchimp list ID.  Please investigate and correct as soon as possible.  The Wordpress UserGroup ID submitted is ' . $usergroupID . '. The User ID is ' .$userdata->ID;
				wp_mail( 'dwlorimer@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
				wp_mail( 'tate.lucas@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);			  
		  } else {
		  
		  
		  // Initializing the $MailChimp object
		  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);

		  //get the user's email address
		  $userdata = get_userdata( $user_id );
		  $useremail = $userdata->user_email;

              $merge_vars = array(
                       'FNAME'=>    $userdata->first_name,
                       'LNAME'=>    $userdata->last_name,
                       );

                       //print_r($merge_vars);
                       //print_r($user->user_email);


				// code for single-updates
					$retval = $SkypeMailChimp->call('lists/subscribe', array(
						'id' => $mcListId, // your mailchimp list id here
						'email' => array(
						  'email' => $useremail
						  ),
						'merge_vars' => $merge_vars,
						'update_existing' => true,
						'double_optin' => false,
						'send_welcome' => false
					  )
					);

					//print_r($retval);

					if($retval['error']) {
						$msg .= "\tCode=".$SkypeMailChimp->errorCode."\n";
						$msg .= "\tMsg=".$SkypeMailChimp->errorMessage."\n";
					}		  


		$chimpdelmsg = $userdata->user_email;
		$LogFileLocation = get_log_file_location();

		//if something goes wrong, email admin
		  if ($retval['status'] == "error") {
				//code 232 means the email address was never subscribed in the first place, which we can ignore.
				file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'An error occured while trying to add a Skype user to Mailchimp - not successful: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
				//wp_mail( get_option( 'admin_email' ), 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg );
				//wp_mail( 'dwlorimer@gmail.com', 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg );
				wp_mail( 'dwlorimer@gmail.com', 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp, using skype_mc_profile_update.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg . PHP_EOL . $SkypeMailChimp->errorCode."\n" . "\tMsg=".$SkypeMailChimp->errorMessage."\n" );
		  } else {
				//file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Deleted User from Mailchimp because they were deleted from Wordpress: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
		  }		
			
		
		
		
/*		
		//remove from old mailchimp list
		  // Mailchimp Info
		  $api = get_mc_api();
		  $mcListId = get_mc_mailing_list();
		  // Initializing the $MailChimp object
		  $MailChimp = new \Drewm\MailChimp($api);

		  //get the user's email address
		  $userdata = get_userdata( $user_id );
		  $useremail = $userdata->user_email;

		  //delete user from Mailchimp
					$retval = $MailChimp->call('lists/unsubscribe', array(
						'id' => $mcListId, // your mailchimp list id here
						'email' => array( 'email' => $useremail ),
						'delete_member' => true,
						'send_goodbye' => false,
						'send_notify' => false
					  )
					);

		$chimpdelmsg = $userdata->user_email;
		$LogFileLocation = get_log_file_location();

		//if something goes wrong, email admin
		  if ($retval['status'] == "error" && $retval['code'] != "232") {
				//code 232 means the email address was never subscribed in the first place, which we can ignore.
				file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'An error occured while trying to delete a Waddington user from Mailchimp - not successful: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
				wp_mail( get_option( 'admin_email' ), 'Error Occured while updating user in Waddington', "An error occured while trying to delete a Waddington user from Mailchimp.  Please manually delete the following user from Mailchimp:" . PHP_EOL . $chimpdelmsg );
		  } else {
				file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Deleted User from Mailchimp because they were deleted from Wordpress: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
		  }	

*/		  
		  
		  
		  
		  } //end no errors, regular process
		  
		} //end usergroupID is not blank		  
		
    } //end function

	

//if user is deleted, remove from mailchimp	
add_action( 'delete_user', 'skype_mailchimp_delete_user' );
function skype_mailchimp_delete_user( $user_id ) {
	
	  $userdata = get_userdata( $user_id );
	  $useremail = $userdata->user_email;
		
		$usergroupID = get_field('user_group', 'user_'.$user_id);
		
		if (!empty($usergroupID)) {		
		
			$mcListId = get_mc_mailing_list($usergroupID);			
		
				//remove from mailchimp list
				  // Mailchimp Info
				  $api = get_mc_api();
				  // Initializing the $MailChimp object
				  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);

				  //delete user from Mailchimp
							$retval = $SkypeMailChimp->call('lists/unsubscribe', array(
								'id' => $mcListId, // your mailchimp list id here
								'email' => array( 'email' => $useremail ),
								'delete_member' => true,
								'send_goodbye' => false,
								'send_notify' => false
							  )
							);	
		}
} //end delete user							

?>