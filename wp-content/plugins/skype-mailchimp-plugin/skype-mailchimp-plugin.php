<?php
/*
Plugin Name: Skype Mailchimp Plugin
Plugin URI:
Description: Custom Plugin to update Skype Mailchimp Mailing Lists when users change profile information.  Requires Advanced Custom Fields.
Author: David Lorimer
Version: 3.0
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

require_once( 'mailchimp-api/src/MailChimp.php' );
require_once( 'mailchimp-api/src/Batch.php' );
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

function get_mc_mailing_list () {
		//only update MailChimp from the Prod site
		if (strpos($_SERVER['HTTP_HOST'], 'skypeug') && !strpos($_SERVER['HTTP_HOST'], 'test') && !strpos($_SERVER['HTTP_HOST'], 'dev') ) {
			//prod list w/ groups
			$mcListID = 'ce15ad4644';
			return $mcListID;
		} else {
			//this is a dev site - use the developer test list with groups
			$mcListID = 'c6f06b5d9b';
			return $mcListID;			
		}
}		

//city interests id list for developer test list 2
//6ffdecd35f

function get_mc_group_list ($usergroupID) {
	
		//only update MailChimp from the Prod site
		if (strpos($_SERVER['HTTP_HOST'], 'skypeug') && !strpos($_SERVER['HTTP_HOST'], 'test') && !strpos($_SERVER['HTTP_HOST'], 'dev') ) {
			//prod mailing list with groups		
	
		  //get the correct MC mailing list for their selection
		  //this is a static table to match the Wordpress UserGroupID to the Mailchimp Mailing List ID

				switch ($usergroupID) {
					case "10":
						//Chicago, IL
						$mcGroupID = '86318e766a';
						return $mcGroupID;
						break;
					case "11":
						//Cincinnati, OH
						$mcGroupID = '08c86f0757';
						return $mcGroupID;
						break;
					case "15":
						//Philadelphia, PA
						$mcGroupID = 'f0826d67c8';
						return $mcGroupID;
						break;				
					case "18":
						//Los Angeles, CA
						$mcGroupID = '37d6dbf400';
						return $mcGroupID;
						break;
					case "19":
						//Nashville, TN
						$mcGroupID = '5a370a2e63';
						return $mcGroupID;
						break;
					case "20":
						//Silicon Valley, CA
						$mcGroupID = '609f079b3e';
						return $mcGroupID;
						break;				
					case "21":
						//Atlanta, GA
						$mcGroupID = '6dac3ee9fe';
						return $mcGroupID;
						break;
					case "30":
						//Baltimore, MD
						$mcGroupID = '326780d0dd';
						return $mcGroupID;
						break;		
					case "31":
						//Kansas City, MO
						$mcGroupID = 'f8a18d201b';
						return $mcGroupID;
						break;						
					case "32":
						//Detroit, MI
						$mcGroupID = '77a77ae57e';
						return $mcGroupID;
						break;						
					case "33":
						//Boise, ID
						$mcGroupID = '03f6671081';
						return $mcGroupID;
						break;
					case "34":
						//Charlotte, NC
						$mcGroupID = '3b0f89e4a3';
						return $mcGroupID;
						break;	
					case "35":
						//Milwaukee, WI
						$mcGroupID = 'f15de7f211';
						return $mcGroupID;
						break;
					case "36":
						//Seattle, WA
						$mcGroupID = '7ee553c3ae';
						return $mcGroupID;
						break;				
					case "37":
						//Portland, OR
						$mcGroupID = '52d4587923';
						return $mcGroupID;
						break;				
					case "38":
						//New York, NY
						$mcGroupID = '3c5e396a0d';
						return $mcGroupID;
						break;			
					case "39":
						//San Francisco, CA
						$mcGroupID = 'be44df93bd';
						return $mcGroupID;
						break;						
					case "48":
						//Cleveland, OH
						$mcGroupID = '858a6041ca';
						return $mcGroupID;
						break;					
					case "51":
						//Austin, TX
						$mcGroupID = '0d3e2602d7';
						return $mcGroupID;
						break;							
					default:
						//we have a problem
						// the list the user selected has an ID that is not in our table
						$mcGroupID = 'ERROR';
						return 'ERROR';
						break;
				}	
		
		} else {
			//this is a dev site - use the developer test list codes
				switch ($usergroupID) {
					case "10":
						//Chicago, IL
						$mcGroupID = '74d3f51a61';
						return $mcGroupID;
						break;
					case "11":
						//Cincinnati, OH
						$mcGroupID = '9983520847';
						return $mcGroupID;
						break;
					case "15":
						//Philadelphia, PA
						$mcGroupID = 'b4db028dcd';
						return $mcGroupID;
						break;				
					case "18":
						//Los Angeles, CA
						$mcGroupID = '3215f0a5df';
						return $mcGroupID;
						break;
					case "19":
						//Nashville, TN
						$mcGroupID = '663a6bb7a9';
						return $mcGroupID;
						break;
					case "20":
						//Silicon Valley, CA
						$mcGroupID = 'f8b02e2926';
						return $mcGroupID;
						break;				
					case "21":
						//Atlanta, GA
						$mcGroupID = 'b018549adc';
						return $mcGroupID;
						break;
					case "30":
						//Baltimore, MD
						$mcGroupID = '403bb66ad7';
						return $mcGroupID;
						break;		
					case "31":
						//Kansas City, MO
						$mcGroupID = 'e952e4b0b0';
						return $mcGroupID;
						break;						
					case "32":
						//Detroit, MI
						$mcGroupID = '5853c10a3e';
						return $mcGroupID;
						break;						
					case "33":
						//Boise, ID
						$mcGroupID = 'ca2c7b1b1d';
						return $mcGroupID;
						break;
					case "34":
						//Charlotte, NC
						$mcGroupID = 'f72415779a';
						return $mcGroupID;
						break;	
					case "35":
						//Milwaukee, WI
						$mcGroupID = '679ff4be7f';
						return $mcGroupID;
						break;
					case "36":
						//Seattle, WA
						$mcGroupID = '0132c35806';
						return $mcGroupID;
						break;				
					case "37":
						//Portland, OR
						$mcGroupID = '20b788a44f';
						return $mcGroupID;
						break;				
					case "38":
						//New York, NY
						$mcGroupID = '8b1073a86c';
						return $mcGroupID;
						break;			
					case "39":
						//San Francisco, CA
						$mcGroupID = 'a98eb802cc';
						return $mcGroupID;
						break;						
					case "48":
						//Cleveland, OH
						$mcGroupID = 'a4eb9eb3a0';
						return $mcGroupID;
						break;					
					case "51":
						//Austin, TX
						$mcGroupID = 'af48196077';
						return $mcGroupID;
						break;							
					default:
						//we have a problem
						// the list the user selected has an ID that is not in our table
						$mcGroupID = 'ERROR';
						return 'ERROR';
						break;
				}	
		}
}

function get_city_interest_ID_from_usergroupID ($usergroupID) {
		
					// Mailchimp Info
					$api = get_mc_api();
					// Initializing the $MailChimp object
					$SkypeMailChimp = new \Drewm\SkypeMailChimp($api);	

					//get proper mailing list for staging or production
					$mcListId = get_mc_mailing_list();
					  
					//build an automatic matching system to match the city locations to the Mailchimp interest group
					  
					//get taxonomy city name to match with Mailchimp
					$wpCityNameObject = get_term_by( 'id', $usergroupID, 'city');
					$wpCityName = $wpCityNameObject->name;
					
					//print_R($wpCityName);
					  
					//get the list of interest categories a part of this mailing list
					$MCintcats = $SkypeMailChimp->get("lists/$mcListId/interest-categories");
					//find the id for the "City" category
					foreach ($MCintcats['categories'] as $MCintcat){
					    if ($MCintcat['title'] == "City") {
							$interestCatID = $MCintcat['id'];
							break;
						}
						  //print_R($MCintcat);
						  //print_R('<BR><BR>');
					}
					  
					  if (isset($interestCatID)) {
							$nomatch = 1;
							//get the list of cities in the "City" category
							$cityList = $SkypeMailChimp->get("lists/$mcListId/interest-categories/$interestCatID/interests?count=100");
							$cityList = $cityList['interests'];
							
							//find the ID of the city this user wants to be a part of
							//Important!!  The Interest name in Mailchimp must be exactly the same as the name in wordpress!
							foreach ($cityList as $city) {
								if ($city['name'] == $wpCityName) {
									//this is the id of the interest group we need to add this user to
									//congratulations, you've just used this lengthy bit of code to match something in the new Mailchimp 3 api.
									$cityID = $city['id'];
									$interestsArray[$city['id']] = true;
									$nomatch = 0;
									//break;
								} else {
									$interestsArray[$city['id']] = false;
								}
							}
							
							if ($nomatch == 0 && isset ($cityID)) {
								//return $cityID;
								return $interestsArray;
							} else {
								if (strpos($_SERVER['HTTP_HOST'], 'skypeug') && !strpos($_SERVER['HTTP_HOST'], 'test') && !strpos($_SERVER['HTTP_HOST'], 'dev') ) {
									//don't sent emails from dev site
									//error!
									$err_msg = 'On SkypeUG, a Wordpress user has selected a UserGroup whose name does NOT match a list in Mailchimp.  Please investigate and correct as soon as possible.  The Wordpress UserGroup ID submitted is ' . $usergroupID . '. The User ID is ' .$userdata->ID;
									wp_mail( 'dwlorimer@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
									wp_mail( 'tate.lucas@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
								}
									echo 'error';										
									return 'ERROR';
							}	
						
					  } else {
						  //error!
								if (strpos($_SERVER['HTTP_HOST'], 'skypeug') && !strpos($_SERVER['HTTP_HOST'], 'test') && !strpos($_SERVER['HTTP_HOST'], 'dev') ) {
									//don't sent emails from dev site						  
									$err_msg = 'On SkypeUG, an interest category, like city, could not be determined on Mailchimp.  Please investigate and correct as soon as possible.  The Wordpress UserGroup ID submitted is ' . $usergroupID . '. The User ID is ' .$userdata->ID;
									wp_mail( 'dwlorimer@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);
									wp_mail( 'tate.lucas@gmail.com', 'Problem with SkypeUG MailChimp Lists', $err_msg);	
								}							
							echo 'error';
							return 'ERROR';							
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
						  
							  
						$mcListId = get_mc_mailing_list();	
						//the array allows us to turn off their old interest groups by setting all of them to false
						$mcGroupIdArray = get_city_interest_ID_from_usergroupID($usergroupID);
					
						if (isset($mcGroupIdArray) && $mcGroupIdArray != 'ERROR') {
							// Mailchimp Info
							$api = get_mc_api();
							$SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
							  $merge_vars = array(
									   'FNAME'=>    $userdata->first_name,
									   'LNAME'=>    $userdata->last_name,
									   );

								$subscriber_hash = $SkypeMailChimp->subscriberHash($useremail);
								$retval = $SkypeMailChimp->put("lists/$mcListId/members/$subscriber_hash", array(
											'email_address'     => $useremail,
											'status_if_new'     => 'subscribed',
											'merge_fields'      => $merge_vars,
											'interests'         => $mcGroupIdArray
											)); 
								//print_r($retval);
								
							$LogFileLocation = get_log_file_location();
						
							if ($SkypeMailChimp->success()) {
								//print_r($retval);	
								//do nothing
								//file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Added User to Mailchimp: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
							} else {
								//if something goes wrong, email admin
								$msg .= $SkypeMailChimp->getLastError();
								$chimpdelmsg = $userdata->user_email;
								file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'An error occured while trying to add a Skype user to Mailchimp - not successful: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
								//wp_mail( get_option( 'admin_email' ), 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg );
								wp_mail( 'dwlorimer@gmail.com', 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp, using skype_mc_usergroup_update.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg . PHP_EOL . $SkypeMailChimp->errorCode."\n" . "\tMsg=".$SkypeMailChimp->errorMessage."\n" );							
							}
						}
			} //end usergroupID is not blank	
		} //end check if group is the same
		
	} //end if meta key is usergroup

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
		
		//delete old email user
			$mcListId = get_mc_mailing_list();			
				//remove from mailchimp list
				  // Mailchimp Info
				  $api = get_mc_api();
				  // Initializing the $MailChimp object
				  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
				  
				  //delete user from Mailchimp
				  $subscriber_hash = $SkypeMailChimp->subscriberHash($olduseremail);
				  $SkypeMailChimp->delete("lists/$mcListId/members/$subscriber_hash");

		//add new email user
		$usergroupID = get_field('user_group', 'user_'.$user_id);
			if (!empty($usergroupID)) {
				//if their usergroup is not blank or null, then grab the list of mailing lists from Mailchimp
						//add user to appropriate mailchimp list
						  // Mailchimp Info
						  $api = get_mc_api();
					  
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
						  
							  
						$mcListId = get_mc_mailing_list();	
						//the array allows us to turn off their old interest groups by setting all of them to false
						$mcGroupIdArray = get_city_interest_ID_from_usergroupID($usergroupID);
					
						if (isset($mcGroupIdArray) && $mcGroupIdArray != 'ERROR') {
							// Mailchimp Info
							$api = get_mc_api();
							$SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
							  $merge_vars = array(
									   'FNAME'=>    $userdata->first_name,
									   'LNAME'=>    $userdata->last_name,
									   );

								$subscriber_hash = $SkypeMailChimp->subscriberHash($useremail);
								$retval = $SkypeMailChimp->put("lists/$mcListId/members/$subscriber_hash", array(
											'email_address'     => $useremail,
											'status_if_new'     => 'subscribed',
											'merge_fields'      => $merge_vars,
											'interests'         => $mcGroupIdArray
											)); 
								//print_r($retval);
								
							$LogFileLocation = get_log_file_location();
						
							if ($SkypeMailChimp->success()) {
								//print_r($retval);	
								//do nothing
								//file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Added User to Mailchimp: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
							} else {
								//if something goes wrong, email admin
								$msg .= $SkypeMailChimp->getLastError();
								$chimpdelmsg = $userdata->user_email;
								file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'An error occured while trying to add a Skype user to Mailchimp - not successful: '. $chimpdelmsg . " __ " . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);
								//wp_mail( get_option( 'admin_email' ), 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg );
								wp_mail( 'dwlorimer@gmail.com', 'Error Occured while updating user in Skype', "An error occured while trying to add a Skype user to Mailchimp, using skype_mc_usergroup_update.  Please check the following user in Mailchimp:" . PHP_EOL . $chimpdelmsg . PHP_EOL . $SkypeMailChimp->errorCode."\n" . "\tMsg=".$SkypeMailChimp->errorMessage."\n" );							
							}
						}
			} //end usergroupID is not blank
	} else {
		//just update the name
		
		$usergroupID = get_field('user_group', 'user_'.$user_id);
		$mcListId = get_mc_mailing_list();
			
				if (isset($mcListId)) {
					// Mailchimp Info
					$api = get_mc_api();
					$SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
					  $merge_vars = array(
							   'FNAME'=>    $userdata->first_name,
							   'LNAME'=>    $userdata->last_name,
							   );

						$subscriber_hash = $SkypeMailChimp->subscriberHash($useremail);
						$result = $SkypeMailChimp->put("lists/$mcListId/members/$subscriber_hash", array(
									'email_address'     => $useremail,
									'status_if_new'     => 'subscribed',
									'merge_fields'      => $merge_vars
									)); 
				}
	}
}


	

//if user is deleted, remove from mailchimp	
add_action( 'delete_user', 'skype_mailchimp_delete_user' );
function skype_mailchimp_delete_user( $user_id ) {
	
	  $userdata = get_userdata( $user_id );
	  $useremail = $userdata->user_email;
		
			$mcListId = get_mc_mailing_list();			
		
				//remove from mailchimp list
				  // Mailchimp Info
				  $api = get_mc_api();
				  // Initializing the $MailChimp object
				  $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
				  
				  //delete user from Mailchimp
				  $subscriber_hash = $SkypeMailChimp->subscriberHash($useremail);
				  $SkypeMailChimp->delete("lists/$mcListId/members/$subscriber_hash");
} //end delete user							






//add_action( 'admin_menu', 'add_mailchimp_test' );
function add_mailchimp_test(){
    add_submenu_page( 'tools.php', 'Mailchimp Usergroup Test', 'Mailchimp Usergroup Test', 'manage_options', '/skype-mailchimp-plugin', 'mailchimpusergroupupdate');
}

function mailchimpusergroupupdate() {
    //set php timeout to 5 minutes - this might take awhile.
    set_time_limit(300);

    // Your Mailchimp API Key
      $api = get_mc_api(); //
      $mcListId = get_mc_mailing_list();
      // Initializing the $MailChimp object
      $SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
	  $SkypeMailChimpBatch = $SkypeMailChimp->new_batch();
    ?>

    <div class="wrap">
        <div>
        <h2>MailChimp Update Test Page</h2>
        </div>
    </div>
  <?php
  //set up log location
  $LogFileLocation = get_log_file_location();
  //file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Started Mailchimp Sync ' . date("M d Y H:i:s A") . PHP_EOL, FILE_APPEND);

  //find all users
  //where wpcf-autogenerated-email is NOT true
  //exclude mailchimp array of dropped users (and put them in a separate file list)
  // fields will be first name, last name, username, employee id, and location


  //find users where wpcf-autogenerated-email is true
  //set up arguments
  $args = array(
  	'blog_id'      => $GLOBALS['blog_id'],
  	'count_total'  => false,
  	'fields'       => 'all',
	'orderby'      => 'registered',
  	'order'        => 'DESC',
	'offset'		=> 4000,
	'number'			=> 1
   );
   //TODO -- be sure to update offset and number

    $blogusers = get_users( $args );
	//print_R($blogusers);
	//die();
	
  // Array of WP_User objects.
  $i=1;
  $subscriber_hash = "";
  foreach ( $blogusers as $user ) {
  	echo '<span>' . $i . ".  " . esc_html( $user->user_email ) . '</span><br />';
  	$userinfo = get_userdata( $user->ID );
  	$uid = $user->ID;
          //$usermeta = get_user_meta($user->ID);
          echo $userinfo->first_name . "<br />";
          echo $userinfo->last_name . "<br />";
          echo $userinfo->user_login . "<br />";
          echo $uid . "<br />";

  	//ok, let's start working with this data

		//new api3 way
//if ($i < 6) {

			$usergroupID = get_field('user_group', 'user_'.$user->ID);
			if (!empty($usergroupID)) {
						  
							  
						$mcListId = get_mc_mailing_list();	
						//the array allows us to turn off their old interest groups by setting all of them to false
						$mcGroupIdArray = get_city_interest_ID_from_usergroupID($usergroupID);
					
						if (isset($mcGroupIdArray) && $mcGroupIdArray != 'ERROR') {
							// Mailchimp Info
							//$api = get_mc_api();
							//$SkypeMailChimp = new \Drewm\SkypeMailChimp($api);
	

							$subscriber_hash = "";
							$subscriber_hash = $SkypeMailChimp->subscriberHash($user->user_email);
							$SkypeMailChimpBatch->patch($user->user_email, "lists/$mcListId/members/" . $subscriber_hash, [
								'email_address' => $user->user_email,
								'status_if_new' => 'subscribed',
								'merge_fields'  => array(                
									'FNAME' => $userinfo->first_name,
									'LNAME' => $userinfo->last_name
									),
								'interests'     => $mcGroupIdArray
								]);		
						}
			}
//}

  	$i++;
  } //end foreach


  //after foreach, run batch subscribe
  		//once the batch is prepped, execute batch.  We won't wait around testing for completion.
		$result = $SkypeMailChimpBatch->execute();
		//print_R($result);
		echo "<p>Sent subscribe batch on a one-way trip to Mailchimp</p>";
		//file_put_contents($LogFileLocation . 'mailchimpLOG.txt', 'Sent subscribe batch on a one-way trip to Mailchimp.  Completed.' . PHP_EOL, FILE_APPEND);
		
		//$batchid = $result['id'];

} // end mailchimpdailyupdate

?>