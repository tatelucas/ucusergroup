/*!
 * Custom Geolocation Code for Skype Users Group
 *
 */

	//var x=document.getElementById("demo");
	//var geocoder = new google.maps.Geocoder;
	//  var infowindow = new google.maps.InfoWindow;
	   
	  
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
				console.log(data);
				//alert("Form submitted successfully.\nReturned json: " + data["json"]);
				
				cityInfo = data["json"];
				obj = JSON.parse(cityInfo);
				//term_id, term_city
				
				if (obj.distance < 200) {
					var textToFind = obj.name;
					var dd = document.getElementById('ug_closest_location');
					//var ddarray = document.getElementsByClass('ug_closest_location');
					for (var i = 0; i < dd.options.length; i++) {
						if (dd.options[i].text === textToFind) {
							dd.selectedIndex = i;
							dd.options[i].selected = true;
							//alert(dd.selectedIndex);
							
							//dd.option[id='8'].attr("selected", "selected");
							//dd.option[id='8'].selected = true;
							
							document.getElementsByClassName("jcf-option-hidden")[0].innerHTML = obj.name;
							
							break;
						}
					}				
					
					
					//$("ug_closest_location").val(obj.name);
					//alert ("The closest user group to you is " + obj.name);
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
	  lat=position.coords.latitude;
	  lng=position.coords.longitude;
	  latlng=new google.maps.LatLng(lat, lng)

			getClosestCity(lat, lng);
	  }

	function showError(error)
	  {
	  } 