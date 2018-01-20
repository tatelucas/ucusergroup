//! skypetimezoneset.js
//! version : 1.2.0
//! authors : David Lorimer

//! this script will attempt to display the event times in the event's timezone, not the user's timezone

	//customize time elements on the page to the event's local time
	
//jQuery(document).ready(function(){
jQuery(window).load(function(){
	
	//For upcoming event date lists -- for each 
	//jQuery(".skypeevent-location-time").each(function(){
		iuglocationblock = "";
		iugllocation = "";
		iuglocationblock = jQuery(".skypeevent-location-time:first");
		//we are assuming that all the events on a page are held in the same timezone.  This saves a lot of processing and hits to google.
	
		iugllocation = jQuery(iuglocationblock).find(".event-location").text().trim();
		//iugllocation = jQuery(this).find(".event-location").text().trim();
		//alert(iugllocation);
		
		if (iugllocation) {
		//get lat lng from location

			jQuery.ajax({
			   url : "https://maps.googleapis.com/maps/api/geocode/json?address="+iugllocation+"&key=AIzaSyBb7YTvbLi3Aj9QFXS2mr13YZE6Llmi1X8&sensor=false",
			   method: "POST",
			   success:function(data){
				   latitude = data.results[0].geometry.location.lat;
				   longitude= data.results[0].geometry.location.lng;
				   ugllatlng = latitude + ',' + longitude;
				   //alert(ugllatlng);
				   
				   
					//using lat lng, get timezone for that location
					jQuery.ajax({
					   url : "https://maps.googleapis.com/maps/api/timezone/json?location="+ugllatlng+"&timestamp=1458000000&key=AIzaSyCWihtfp6mpMQ2TBu5O3qaI7_chR0Z3avE",
					   method: "POST",
					   success:function(data){
						   //console.log(data);
						   //alert(data);
						   ugltimezone = data.timeZoneId;
						   //alert(ugltimezone);
						   
						   if (ugltimezone) {
							ugl_runlocationtimedisplaysingle(ugltimezone);
							//ugl_runlocationtimedisplayblock(ugltimezone);					
							
						   }  
						   
						   
					   }
					});		   
				   
			   }
			});	
	
		} //end if location has value
		
	//});	
	
	
	
	
	
	
	
	
	
	ugllocationzip = "";
	ugllocationcountry = "";
	ugllocation = "";
	ugltimezone = "";
	
	//get location from address block on the page
	jQuery("span").each(function(){  
		if(jQuery(this).attr("itemprop")=="postalCode"){
			//alert(jQuery(this).html());
			ugllocationzip = jQuery(this).html();
		} else if(jQuery(this).attr("itemprop")=="addressCountry"){
			ugllocationcountry = jQuery(this).html();
		}
	});
	
	if (ugllocationzip) {
	   ugllocation = ugllocationzip + ' ' + ugllocationcountry;	
	   //alert(ugllocation);
	
	
	//get lat lng from zipcode

    jQuery.ajax({
       url : "https://maps.googleapis.com/maps/api/geocode/json?address="+ugllocation+"&key=AIzaSyBb7YTvbLi3Aj9QFXS2mr13YZE6Llmi1X8&sensor=false",
       method: "POST",
       success:function(data){
           latitude = data.results[0].geometry.location.lat;
           longitude= data.results[0].geometry.location.lng;
           //alert("Lat = "+latitude+"- Long = "+longitude);
		   ugllatlng = latitude + ',' + longitude;
		   //alert(ugllatlng);
		   
		   
			//using lat lng, get timezone for that location

			jQuery.ajax({
			   url : "https://maps.googleapis.com/maps/api/timezone/json?location="+ugllatlng+"&timestamp=1458000000&key=AIzaSyCWihtfp6mpMQ2TBu5O3qaI7_chR0Z3avE",
			   method: "POST",
			   success:function(data){
				   //console.log(data);
				   //alert(data);
				   ugltimezone = data.timeZoneId;
				   //alert(ugltimezone);
				   
				   if (ugltimezone) {
					ugl_runlocationtimedisplaysingle(ugltimezone);
					ugl_runlocationtimedisplayblock(ugltimezone);
				   }
				   
				   
				   
			   }
			});		   
		   
		   
		   
       }
    });	
	
	} //end if location has value
	
	
	
	//convert displayed time into local event timezone
	
});
	
/*	
	jQuery( document ).ready(function() {
		var uglocationzip = jQuery('span[itemprop="postalCode"]').attr('content');
		//var uglocationzip = jQuery('meta[itemprop="postalCode"]').content;
		//var uglocationzip = jQuery('meta[itemprop="postalCode"]').each.attr('content');
		console.log(uglocationzip);
		alert (uglocationzip);
	});
*/	

	
function ugl_runlocationtimedisplaysingle(ugltimezone) {
		jQuery( "time" ).each(function( index ) {
			  var timex = jQuery( this ).text();

				//moment interprets the time, and reformats the date using the new timezone
				var format = 'dddd, MMMM D, YYYY h:mm a z'; //'YYYY/MM/DD HH:mm:ss ZZ';
				
				//Event Espresso gives the time correctly, but WP appears to tag on the current timezone (even if that won't be the right daylight savings tag at the actual date)
				//so, we need to get the correct EST/EDT for Eastern time for that date, and then convert it to the regional timezone

				//alert (timex);
				
				var timey = new moment(timex, format).format(format);
				//alert(timey);
				
				//var timeezone = 
				
				if (timey.indexOf('Invalid') <= -1) {		
				//this check is to make sure the date coming in is a full-format date, moment is picky
				  
					//var timez = moment(timex).tz(moment.tz.guess()).format(format);
					var timez = moment(timey).tz(ugltimezone).format(format);
					//alert(timez);

					if (timez.indexOf('Invalid') <= -1) {
						jQuery( this ).text(timez);
					}
					//else, do nothing and leave the date as originally displayed - there was a problem parsing it				  
				  
				}
		});
}


function ugl_runlocationtimedisplayblock(ugltimezone) {
		jQuery( ".ee-event-datetimes-li" ).each(function( index ) {
			//this one customizes date in ul li array -- that is, dateblock on individual event page, and dates in sidebar events
			//each li date element has two child spans -- one with the date, and one with the time.  We need them both in order to change the timezones properly

			//first, get the date, or date range
			var dateblock = jQuery(this).find('.ee-event-datetimes-li-daterange').html();
			//dateblock will either be one date or a range.  Test for a hyphen to see if it is a range.
				if (dateblock.indexOf("-") > 0) {
					//split it at the hyphen to get the dates
					var oldStartDate = dateblock.split('-')[0].trim();
					var oldEndDate = dateblock.split('-')[1].trim();
				} else {
					//the timeblock has no hyphen.  It's just a single day event.
					var oldStartDate = dateblock.trim();
					var oldEndDate = oldStartDate;
				}

			//grab timeblock from the page
			var timeblock = jQuery(this).find('.ee-event-datetimes-li-timerange').html();
			//timeblock will either be one date/time or a range.  Test for a hyphen to see if it is a range.
			if (timeblock.indexOf("-") > 0) {
				//split it at the hyphen to get the times
				var oldStartTime = timeblock.split('-')[0].trim();
				var oldEndTime = timeblock.split('-')[1].trim();
				//var oldStartTime = oldStartTime.trim();

			} else {
				//the timeblock has no hyphen.  I really don't think this is supposed to happen with the timerange (it will with daterange)
				var oldStartTime = timeblock;
				var oldEndTime = oldStartTime;
			}

				var oldStartTime = oldStartDate.trim() + ' ' + oldStartTime.trim();
				var oldEndTime = oldEndDate + ' ' + oldEndTime;

				//for whatever reason, event espresso uses $nbsp; instead of space.  This messes up the date function, so they've got to be replaced.
				var oldStartTime = oldStartTime.replace(/&nbsp;/g, ' ');
				var oldEndTime = oldEndTime.replace(/&nbsp;/g, ' ');
				

				var pagetimeformat = 'MMMM D, YYYY h:mm a z';
				var timeformat = 'h:mm a z';
				var dateformat = 'MMMM D, YYYY';

				
				
				var formatx = 'MMMM D, YYYY h:mm a'; //'YYYY/MM/DD HH:mm:ss ZZ';
				//Event Espresso gives the time correctly, but WP appears to tag on the current timezone (even if that won't be the right daylight savings tag at the actual date)
				//so, we need to get the correct EST/EDT for Eastern time for that date, and then convert it to the regional timezone	
				var oldStartTime = moment(oldStartTime, pagetimeformat).format(formatx);
				var oldEndTime = moment(oldEndTime, pagetimeformat).format(formatx);
				
				//alert (oldStartTime);
				//alert (ugltimezone);
				var startDateNew = moment(oldStartTime).tz(ugltimezone).format(dateformat);
				var endDateNew = moment(oldEndTime).tz(ugltimezone).format(dateformat);
				var startTimeNew = moment(oldStartTime).tz(ugltimezone).format(timeformat);
				var endTimeNew = moment(oldEndTime).tz(ugltimezone).format(timeformat);
				//alert(startTimeNew);


				if (startTimeNew.indexOf('Invalid') <= -1 && endTimeNew.indexOf('Invalid') <= -1) {
					jQuery(this).find('.ee-event-datetimes-li-timerange').text(startTimeNew + ' - ' + endTimeNew);
					jQuery(this).find('.ee-event-datetimes-li-timerange').css('opacity', '1');
				}
				//else, do nothing and leave the date as originally displayed - there was a problem parsing it
				if (startTimeNew.indexOf('Invalid') <= -1 && endTimeNew.indexOf('Invalid') <= -1) {
					if (startDateNew != endDateNew) {
						//if this is a date range, show date range
						jQuery(this).find('.ee-event-datetimes-li-daterange').text(startDateNew + ' - ' + endDateNew);
					} else {
						//if single day, just show single day
						jQuery(this).find('.ee-event-datetimes-li-daterange').text(startDateNew);
					}
				}
				//else, do nothing and leave the date as originally displayed - there was a problem parsing it

		});
}


		jQuery(window).load(function ()
		{
			//if this is the calendar page, run the date fixes on the calendar
			if ( jQuery( "#espresso_calendar" ).length ) {
				//note: this code only partially works -- because the actual dates are not accessible in the calendar, moment guesses the timezones based on TODAY.
				// this means that the results will be incorrect if the date of the event is across a timechange that has not yet occurred.

				var i = setInterval(function ()
				{
					if (jQuery('.fc-event-inner').length)
					{
						clearInterval(i);
						// safe to execute your code here

						//time offset at server (Eastern Time)
						var qsite = moment().tz("America/New_York").format('Z');
						//timezone abbreviation of user
						var quser = moment().tz(moment.tz.guess()).format('z');

						jQuery( ".event-start-time" ).each(function( index ) {

							  var timea = jQuery( this ).text();
							  //add offset suffix so moment knows how to change it
							  var timea = timea + ' ' + qsite;

							  var enterformat = 'h:mm a ZZ';
							  var timeb = moment(timea, enterformat).format('h:mm a z');

							if (timeb.indexOf('Invalid') <= -1) {
							  jQuery( this ).text(timeb);
							}
							//else, do nothing, it could not parse the date correctly
						});

						jQuery( ".event-end-time" ).each(function( index ) {

							  var timea = jQuery( this ).text();
							  var timea = timea + ' ' + qsite;

							  var enterformat = 'h:mm a ZZ';

							  //moment interprets the time, and reformats the date using the new timezone
							  var timeb = moment(timea, enterformat).format('h:mm a z');

							if (timeb.indexOf('Invalid') <= -1) {
							  jQuery( this ).text(timeb + " " + quser);
							}
							//else, do nothing, it could not parse the date correctly
						});

					}
				}, 100);


			}

		});