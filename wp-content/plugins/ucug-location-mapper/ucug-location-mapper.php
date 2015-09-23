<?php
/*
Plugin Name: UC UserGroup Location Mapper
Plugin URI:
Description: Custom Plugin to find and map locations
Author: David Lorimer
Version: 0.8
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



//to-do add dependency on event espresso




/*  //-- this is the section from the website he wanted me to copy 
add_action( 'init', 'uc_register_taxonomy' );

function uc_register_taxonomy() {
	global $wpdb;
	$variable_name = $type . 'meta';
	$wpdb->$variable_name = $table_name;
}



$uc_cities_meta = $wpdb->prefix . 'uc_cities_meta';
 
// function to create the DB / Options / Defaults					
function your_plugin_options_install() {
   	global $wpdb;
  	global $uc_cities_meta;
 
	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$uc_cities_meta'") != $uc_cities_meta) 
	{
		$sql = "CREATE TABLE " . $uc_cities_meta . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`city_latitude` varchar(25) NOT NULL,
		`city_longitude` varchar(25) NOT NULL,
		`field_3` tinytext NOT NULL,
		`field_4` tinytext NOT NULL,
		UNIQUE KEY id (id)
		);";
 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
 
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'your_plugin_options_install');

*/



add_shortcode( 'ucugLocationTwo', 'ucugLocationStartTwo' );



function ucugLocationStartTwo () {
	location_start_auto();
?>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<!-- <p class="intro" id="demo">HTML Geolocation is used to locate a user's position.</p>
<p><button class="w3-btn w3-blue" onclick="getLocation()">Try It</button></p> -->
<div id="mapholder"></div>

<?php

?>

<div>
	Your Location: <span id="uclocation"></span><br>
	Your Lat: <span id="uclat"></span><br>
	Your lng: <span id="uclng"></span><br>
</div>

<script>
	var x=document.getElementById("demo");
	var geocoder = new google.maps.Geocoder;
	  var infowindow = new google.maps.InfoWindow;
	  
	function getLocation()
	  {
	  if (navigator.geolocation)
		{
		navigator.geolocation.getCurrentPosition(showPosition,showError);
		}
	  else{x.innerHTML="Geolocation is not supported by this browser.";}
	  }

	  
	function getClosestCity (lat, lng) {
			// We'll pass this variable to the PHP function uc_ajax_request_city
		
		// This does the ajax request
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
		//alert (ajaxurl);
		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: ajaxurl,
			data: {
				'action':'uc_ajax_request_city',
				"lat": lat,
				"lng": lng
			},
			success:function(data) {
				// This outputs the result of the ajax request
				console.log(data);
				alert("Form submitted successfully.\nReturned json: " + data["json"]);
				
				cityInfo = data["json"];
				obj = JSON.parse(cityInfo);
				//term_id, term_city
				
				if (obj.distance < 200) {
					alert ("The closest user group to you is " + obj.term_city);
				} else {
					alert ("There are no user groups near you.  Perhaps you should start one!");
				}
				
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
		
	}

	function showPosition(position)
	  {
	  lat=position.coords.latitude;
	  lng=position.coords.longitude;
	  latlng=new google.maps.LatLng(lat, lng)
	  mapholder=document.getElementById('mapholder')
	  mapholder.style.height='250px';
	  mapholder.style.width='100%';
	  

			<?php wp_localize_script( 'ajax-script', 'ajax_object.ajaxurl', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) ); ?>

			getClosestCity(lat, lng);
  
	  
	  //alert(lat);
	  $('#uclat').text(lat);
	  $('#uclng').text(lng);

	  var myOptions={
	  center:latlng,zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP,
	  mapTypeControl:false,
	  navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
	  };
	  var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
	  var marker=new google.maps.Marker({position:latlng,map:map,title:"You are here!"});
	  
	  codeLatLng(lat, lng);
	  }

	function showError(error)
	  {
	  switch(error.code) 
		{
		case error.PERMISSION_DENIED:
		  x.innerHTML="User denied the request for Geolocation."
		  break;
		case error.POSITION_UNAVAILABLE:
		  x.innerHTML="Location information is unavailable."
		  break;
		case error.TIMEOUT:
		  x.innerHTML="The request to get user location timed out."
		  break;
		case error.UNKNOWN_ERROR:
		  x.innerHTML="An unknown error occurred."
		  break;
		}
	  }
	  
	  
	  function codeLatLng(lat, lng) {

		var latlng = new google.maps.LatLng(lat, lng);
		geocoder.geocode({'location': latlng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
		  console.log(results)
			if (results[1]) {
			 //formatted address
			 //alert(results[0].formatted_address)
			 var fulladdy = results[0].formatted_address;
			 $('#uclocation').text(fulladdy);
			//find country name
				 for (var i=0; i<results[0].address_components.length; i++) {
				for (var b=0;b<results[0].address_components[i].types.length;b++) {

				//there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
					if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
						//this is the object you are looking for
						city= results[0].address_components[i];
						break;
					}
				}
			}
			//city data
			//alert(city.short_name + " " + city.long_name) //this is showing the state right now
			var cityaddy = city.short_name + " " + city.long_name;
			


			} else {
			  alert("No results found");
			}
		  } else {
			alert("Geocoder failed due to: " + status);
		  }
		});
	  } 
	  
	  
	  
	function getCoordsfromAddy (e) {
		e.preventDefault();
		
		/*
		if( $("#ucloc_city").val() ) {
			var city = 1;
			var input_city = $("#ucloc_city").val();
          var address += input_city;
		}
		
		if( $("#ucloc_state").val() ) {
			var input_state = $("#ucloc_state").val();
          var address += input_state;
		}	
		*/
		
		//we are now only using zipcode
		if( $("#ucloc_zip").val() ) {
			var input_zip = $("#ucloc_zip").val();
          var address = input_zip;
		  //alert(input_zip);
		}		
		//var input_city = $("#ucloc_city").val();
		//var input_state = $("#ucloc_state").val();
		//var input_zip = $("#ucloc_zip").val();
		
		var geocoder = new google.maps.Geocoder();
		

		geocoder.geocode( { 'address': address}, function(results, status) {

			if (status == google.maps.GeocoderStatus.OK) {
				//showPosition(position);

				
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				

			  var lat=latitude;
			  var lng=longitude;
			  
			  //alert(lat);
			  
			  getClosestCity(lat, lng);
			  codeLatLng(lat, lng);
			  
			   // this code updated the on-screen map
			  latlng=new google.maps.LatLng(lat, lng)
			  mapholder=document.getElementById('mapholder')
			  mapholder.style.height='250px';
			  mapholder.style.width='100%';
			  
			  //alert(lat);
			  $('#uclat').text(lat);
			  $('#uclng').text(lng);

			  var myOptions={
			  center:latlng,zoom:14,
			  mapTypeId:google.maps.MapTypeId.ROADMAP,
			  mapTypeControl:false,
			  navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
			  };
			  var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
			  var marker=new google.maps.Marker({position:latlng,map:map,title:"You are here!"});
			
				

			}

		}); 	  
	}
	  
</script>
<hr>








	<div id="form_container" class="clearfix" style="max-width: 500px; ">
	
		<form id="form_1053853" class="appnitro"  method="post" action="">
		<div class="form_description">
			<p>Or, enter your location manually:</p>
		</div>						
<!--
		<div class="left">
			<input id="ucloc_city" name="ucloc_city" class="element text medium" value="" type="text">
			<label for="ucloc_city">City</label>
		</div>
	
		<div class="left">
			<input id="ucloc_state" name="ucloc_state" class="element text medium" value="" type="text">
			<label for="ucloc_state">State / Province / Region</label>
		</div>
-->
		<div class="left">
			<input id="ucloc_zip" name="ucloc_zip" class="element text medium" maxlength="15" value="" type="text">
			<label for="ucloc_zip">Zip Code</label>
		</div>
	
		<div class="clearfix">
			
			    <input type="hidden" name="form_id" value="1053853" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" onclick="getCoordsfromAddy(event)" value="Submit" />
		</div>
		</form>	
	</div>
	



<?php	

} //end locationstarttwo


function location_start_auto() {
	
	wp_localize_script( 'ajax-script', 'ajax_object.ajaxurl', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	

?>
<script>
jQuery(document).ready(function($) {
	getLocation();
});
</script>
<?php

}



add_action( 'wp_ajax_uc_ajax_request_city', 'uc_ajax_request_city' );
// If you wanted to also use the function for non-logged in users (in a theme for example)
add_action( 'wp_ajax_nopriv_uc_ajax_request_city', 'uc_ajax_request_city' );



function uc_ajax_request_city() {
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {  //check to see if this is ajax
		
		
		if (isset($_POST["lat"]) && !empty($_POST["lat"])) { //Checks if action value exists
			/*$action = $_POST["action"];
			switch($action) { //Switch case for value of action
			case "test": test_function(); break;
			}
			*/
			
			$lat = "";
			$lng = "";
			
			$lat = $_POST["lat"];
			$lng = $_POST["lng"];
			

			global $wpdb;
			
			$latitude = $lat;
			$longitude = $lng;
 
            // Build the spherical geometry SQL string
            $earthRadius = '3959'; // In miles
 
            $sql = "
                SELECT
                    ROUND(
                        $earthRadius * ACOS(  
                            SIN( $latitude*PI()/180 ) * SIN( term_latitude*PI()/180 )
                            + COS( $latitude*PI()/180 ) * COS( term_latitude*PI()/180 )  *  COS( (term_longitude*PI()/180) - ($longitude*PI()/180) )   ) 
                    , 1)
                    AS distance,
					{$wpdb->prefix}terms_meta.term_id,
                    term_latitude,
                    term_longitude,
					term_city,
					term_state
				FROM {$wpdb->prefix}terms_meta
				
				INNER JOIN {$wpdb->prefix}terms
				ON {$wpdb->prefix}terms_meta.term_id = {$wpdb->prefix}terms.term_id
				
				WHERE {$wpdb->prefix}terms_meta.term_latitude is not NULL AND {$wpdb->prefix}terms_meta.term_longitude is not NULL
				
                ORDER BY
                    distance ASC   
                LIMIT 1";
 
            // Search the database for the nearest agents		
			$result = $wpdb->get_results($sql);			
			
			$result = $result[0];

			$return["json"] = json_encode($result);
			
			//print_r($return);
			echo json_encode($return);
			
			//exit;


	
	
		}	//end post	
			
	} //end if ajax


	
	// Always die in functions echoing ajax content
   wp_die();
} //end