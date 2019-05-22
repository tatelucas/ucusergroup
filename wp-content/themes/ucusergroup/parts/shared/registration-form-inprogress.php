<form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="subscribe-form" id="subscribe-form" method="post">
  <fieldset>
    <div class="form-group">
      <input class="form-control" type="text" name="user_login" placeholder="Username" id="user_login" placeholder="Username">
    </div>
    <div class="form-group">
      <input class="form-control" type="text" name="first_name" placeholder="First Name" id="first_name" placeholder="First Name">
    </div>
    <div class="form-group">
      <input class="form-control" type="text" name="last_name" placeholder="Last Name" id="last_name" placeholder="Last Name">
    </div>	
    <div class="form-group">
      <input class="form-control" type="email" name="user_email" placeholder="E-Mail" id="user_email" placeholder="Email">
    </div>
    <div class="form-group">
		<div class="geolocate">
      <?php ug_city_select(false); ?>
      <!-- <select name="user_state">
        <option class="hidden">Select Location</option>
        <option>Chicago</option>
        <option>Texas</option>
        <option>California</option>
        <option>Washington</option>
        <option>Montana</option>
      </select> -->
	  </div>
    </div>
	<div class="inv-recaptcha-holder"></div>
	<div class="form-group-submit">
	
	
    <!-- <div class="g-recaptcha" data-sitekey="6Lc5kSoUAAAAAO-bOQ5v-dEmOm9LClDGywDbzyha" data-bind="register" data-callback="UGsubmitForm"></div>
	<input class="btn btn-success" type="submit" value="Join Today" id="register" />
	-->
    <!-- <input type="submit" name="login" class="loginmodal-submit" id="recaptcha-submit" value="Login">	 -->
	<!-- <div class="g-recaptcha" data-sitekey="6Lf-gSoUAAAAAOuFIm1xBcy0c9ktaopKp7VT988a"></div> -->
	<input class="btn btn-success" type="submit" value="Join Today" id="register" />
    <!-- <input class="btn btn-success g-recaptcha" type="submit" value="Join Today" id="register" data-sitekey="6Lc5kSoUAAAAAO-bOQ5v-dEmOm9LClDGywDbzyha" data-callback="UGsubmitForm" data-badge="inline" /> -->
	<?php // do_action(‘google_invre_render_widget_action’); ?>
	<!-- <button class="g-recaptcha btn btn-success" data-sitekey="6Lc5kSoUAAAAAO-bOQ5v-dEmOm9LClDGywDbzyha" data-callback="UGsubmitForm" data-badge="inline" type="submit">Submit</button> -->
	</div>
  </fieldset>
</form>

<!-- 
<button
class="g-recaptcha"
data-sitekey="6Lc5kSoUAAAAAO-bOQ5v-dEmOm9LClDGywDbzyha"
data-callback="UGsubmitForm">
Submit
</button>
-->

<!--
<script>
function UGsubmitForm() {
    var form = document.getElementById("subscribe-form");
    if (validate_form(form)) {
        form.submit();
    } else {
        grecaptcha.reset();
    }
}
</script>
-->

<script type='text/javascript'>
var renderInvisibleReCaptcha = function() {

    for (var i = 0; i < document.forms.length; ++i) {
        var form = document.forms[i];
        var holder = form.querySelector('.inv-recaptcha-holder');

        if (null === holder) continue;
		holder.innerHTML = '';

        (function(frm){
			var cf7SubmitElm = frm.querySelector('.wpcf7-submit');
            var holderId = grecaptcha.render(holder,{
                'sitekey': '6Lc5kSoUAAAAAO-bOQ5v-dEmOm9LClDGywDbzyha', 'size': 'invisible', 'badge' : 'bottomright',
                'callback' : function (recaptchaToken) {
					((null !== cf7SubmitElm) && (typeof jQuery != 'undefined')) ? jQuery(frm).submit() : HTMLFormElement.prototype.submit.call(frm);
                },
                'expired-callback' : function(){grecaptcha.reset(holderId);}
            });

            frm.onsubmit = function (evt){evt.preventDefault();grecaptcha.execute(holderId);};

			if(null !== cf7SubmitElm && (typeof jQuery != 'undefined') ){
				jQuery(cf7SubmitElm).one('click', function(clickEvt){
					clickEvt.preventDefault();
					grecaptcha.execute(holderId);
				});
			}

        })(form);
    }
};
</script>
<script type='text/javascript' async defer src='https://www.google.com/recaptcha/api.js?onload=renderInvisibleReCaptcha&#038;render=explicit'></script>