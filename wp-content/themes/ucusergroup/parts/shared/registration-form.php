<form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="subscribe-form" method="post">
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
	<div class="form-group-submit">
    <input class="btn btn-success" type="submit" value="Join Today" id="register" />
	</div>
  </fieldset>
</form>