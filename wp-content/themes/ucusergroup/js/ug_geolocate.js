/*!
 * Custom Geolocation Code for Skype Users Group
 *
 */

	//var x=document.getElementById("demo");
	//var geocoder = new google.maps.Geocoder;
	//  var infowindow = new google.maps.InfoWindow;
	

	jQuery(document).ready(function($) {
		/* if page has geolocate elements, run geolocate scripts */
		if ($('#ajaxReplaceClosestLocations').length || $('.geolocate').length) {
		getLocation();
		}
	});	
	  
	function getLocation()
	  {  
		var geocoder = new google.maps.Geocoder;
	  var infowindow = new google.maps.InfoWindow;
		  
	  if (navigator.geolocation)
		{
		navigator.geolocation.getCurrentPosition(showPosition,showError);
		}
	  else{//x.innerHTML="Geolocation is not supported by this browser.";
	  }
	  }

	  
	function getClosestCity (lat, lng) {
			// We'll pass this variable to the PHP function uc_ajax_request_city
		
		// This does the ajax request
		var ajaxurl = ug_object.ajax_url;
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
				//alert("Form submitted successfully.\nReturned json: " + data["json"]);
				
				cityInfo = data["json"];
				var obj = JSON.parse(cityInfo);
				//term_id, term_city
				
				if (obj.distance < 200) {
					
					if ($('.geolocate').length) {

						var textToFind = obj.name;
						//var dd = document.getElementById('ug_closest_location');
											

						//select closest location in all marked drop downs
						var ddArray = document.querySelectorAll(".geolocate .ug_closest_location");
						for (var k = 0; k < ddArray.length; ++k) {
							//alert(ddArray[k]);
							
							for (var i = 0; i < ddArray[k].options.length; i++) {
								//if (ddArray[k].options[i].attr('id')) {
									//console.log(ddArray[k].options[i]);
								//}
								if (ddArray[k].options[i].text === textToFind) {
									ddArray[k].selectedIndex = i;
									ddArray[k].options[i].selected = true;
									//alert(dd.selectedIndex);
									
									break;
								}
							}						
							
						}
						
						//set text display for all selected dropdowns
						var children = document.querySelectorAll(".geolocate .jcf-option-hidden");
						for (var j = 0; j < children.length; ++j) {
							children[j].innerHTML = obj.name;
						}

					}					
					
					
					//$("ug_closest_location").val(obj.name);
					//alert ("The closest user group to you is " + obj.name);
					
					if ($('#ajaxReplaceClosestLocations').length) {
					   //replace closest meeting locations
					   setClosestMeetups (obj.term_id, 3);	
					}
					
				} else {
					//alert ("There are no user groups near you.  Perhaps you should start one!");
				}
				
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
		
	}

	function showPosition(position)
	  {
	  var lat=position.coords.latitude;
	  var lng=position.coords.longitude;
	  latlng=new google.maps.LatLng(lat, lng)

			getClosestCity(lat, lng);
			//setClosestMeetups(8, 3);
	  }
	  
	function setCityDropDowns (obj) {
		//alert (obj.name);

	}
	
	
	function setClosestMeetups (id, limit) {
			// We'll pass this variable to the PHP function uc_ajax_closest_meetups
		
		// This does the ajax request
		var ajaxurl = ug_object.ajax_url;
		
		$.ajax({
			type: "POST",
			dataType: "html",
			url: ajaxurl,
			data: {
				'action':'uc_ajax_closest_meetups',
				"id": id,
				"limit": limit
			},
			success:function(data) {
				// This outputs the result of the ajax request
				//console.log(data);
				//alert (data);
				
				var wrapper = document.createElement('div');
				wrapper.innerHTML = data;
				
				var newbox = document.getElementById("ajaxReplaceClosestLocations");
				//alert(newbox);
				//newbox.innerHTML = wrapper;
				//$newbox.html(data);
				$('#ajaxReplaceClosestLocations').html(wrapper);
				
				//alert("Form submitted successfully.\nReturned json: " + data["json"]);

				
			},
			error: function(errorThrown){
				alert('error');
				console.log(errorThrown);
			}
		});
		
	}
	
	  
	function showError(error)
	  {
	  } 