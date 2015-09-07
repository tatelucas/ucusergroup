<?php
/**
 * Template Name: Home Page
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

    <div class="banner">
      <div class="bg-stretch">
        <img src="<? echo get_template_directory_uri() ?>/images/img-1.jpg" width="1170" height="505" alt="image description">
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-5">
            <h1><span>Welcome to Skype for Business Users Group</span>The definitive meetup for UC Professionals</h1>
            <button type="button" class="btn btn-success hidden-xs"><i class="icon-search"></i>Find a Meetup near you</button>
          </div>
        </div>
      </div>
    </div>

    <div class="location-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
            <div class="visible-xs form-hold">
              <form action="#" class="search-form" role="search">
                <fieldset>
                  <button type="submit" class="btn icon-search"></button>
                  <input type="search" class="form-control" placeholder="Find a Meetup">
                </fieldset>
              </form>
              <div class="btn-group">
                <button type="button" class="btn btn-primary">Register</button>
              </div>
            </div>
            <h1>Upcoming Meetups Near You</h1>
            <div class="select-form">
              <span class="text-hold">location<i class="icon-mapmarker"></i></span>
              <?php uc_city_select(); ?>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.</p>
          </div>
        </div>
        <div class="gray-box">
          <div class="col-sm-4 col-xs-12 location-col">
            <a href="#">
              <time class="time-hold" datetime="2015-07-23"><span>23</span>july</time>
              <span class="name-hold">Chicago Users Group</span>
              <span class="text-hold">Chicago, IL, USA</span>
            </a>
          </div>
          <div class="col-sm-4 col-xs-12 location-col">
            <a href="#">
              <time class="time-hold" datetime="2015-07-26"><span>26</span>july</time>
              <span class="name-hold">Boise Users Group</span>
              <span class="text-hold">Boise, ID, USA</span>
            </a>
          </div>
          <div class="col-sm-4 col-xs-12 location-col">
            <a href="#">
              <time class="time-hold" datetime="2015-07-28"><span>28</span>july</time>
              <span class="name-hold">Nashville Users Group</span>
              <span class="text-hold">Nashville, TN, USA</span>
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0">
            <h2>Other meetups upcoming soon</h2>
            <ul class="location-list">
              <li><a href="#">July TBA - Cincinnati, OH</a></li>
              <li><a href="#">July TBA - Nashville, TN</a></li>
              <li><a href="#">July TBA - Silicon Valley, CA</a></li>
              <li><a href="#">July TBA - Detroit, MI</a></li>
              <li><a href="#">July TBA - Atlanta, GA</a></li>
              <li><a href="#">July TBA - Charlotte, NC</a></li>
              <li><a href="#">July TBA - Milwaukee, WI</a></li>
              <li><a href="#">July TBA - Baltimore, MD</a></li>
              <li><a href="#">July TBA - Kansas City, MO</a></li>
              <li><a href="#">July TBA - San Francisco, CA</a></li>
              <li><a href="#">July TBA- Philadelphia, PA</a></li>
              <li><a href="#">August 3rd - New York, NY</a></li>
              <li><a href="#">August TBA - Seattle, WA</a></li>
              <li><a href="#">August TBA - Portland, OR</a></li>
            </ul>
            <button type="button" class="btn btn-success"><i class="icon-search"></i>View all meetups</button>
          </div>
        </div>
      </div>
    </div>
    <div class="info-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Skype for Business</h1>
            <p>Skype for Business Users Group continues to grow in 2015 with new sponsors! We would like to formally announce IR as a national sponsor of the Skype for Business Users Group!</p>
            <p>IR is a leading global provider of experience management software &amp; solutions for Universal Communications ecosystems. More than 1000 organizations in over 60 </p>
          </div>
          <div class="col-sm-6">
            <h1>Unified User Groups</h1>
            <p>Skype for Business Users Group continues to grow in 2015 with new sponsors! We would like to formally announce IR as a national sponsor of the Skype for Business Users Group! IR is a leading global provider of experience management software &amp; solutions for Universal Communications ecosystems. </p>
            <p>More than 1000 organizations in over 60 countries—including some of the world’s </p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <button type="button" class="btn btn-info"><i class="icon-circle-plus"></i>Find out more</button>
          </div>
        </div>
      </div>
      <div class="picture-hold">
        <img src="<? echo get_template_directory_uri() ?>/images/img-2.png" height="394" width="704" alt="image description">
      </div>
    </div>
    <div class="subscribe-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h1>Join Skype for Business in a matter of seconds!</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <form action="#" class="subscribe-form">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" type="text" placeholder="Name">
                </div>
                <div class="form-group">
                  <input class="form-control" type="email" placeholder="Email">
                </div>
                <div class="form-group">
                  <select>
                    <option class="hidden">Select State</option>
                    <option>Chicago</option>
                    <option>Texas</option>
                    <option>California</option>
                    <option>Washington</option>
                    <option>Montana</option>
                  </select>
                </div>
                <button type="button" class="btn btn-success"><i class="icon-circle-check"></i>Join Today</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="sponsor-section">
      <div class="container-fluid">
        <div class="col-sm-12">
          <h1>Our National Sponsors</h1>
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div class="item active">
                <ul class="sponsor-list">
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-1.jpg" height="83" width="193" alt="actiance"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-2.jpg" height="83" width="175" alt="aruba networks"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-3.jpg" height="83" width="301" alt="audio codes"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-4.jpg" height="83" width="194" alt="talk computer"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-5.jpg" height="83" width="250" alt="enghouse interactive"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-6.jpg" height="82" width="108" alt="grandstream"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-7.jpg" height="83" width="47" alt="il"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-8.jpg" height="83" width="306" alt="plantronics"></a></li>
                </ul>
              </div>
              <div class="item">
                <ul class="sponsor-list">
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-1.jpg" height="83" width="193" alt="actiance"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-2.jpg" height="83" width="175" alt="aruba networks"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-3.jpg" height="83" width="301" alt="audio codes"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-4.jpg" height="83" width="194" alt="talk computer"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-5.jpg" height="83" width="250" alt="enghouse interactive"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-6.jpg" height="82" width="108" alt="grandstream"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-7.jpg" height="83" width="47" alt="il"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-8.jpg" height="83" width="306" alt="plantronics"></a></li>
                </ul>
              </div>
              <div class="item">
                <ul class="sponsor-list">
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-1.jpg" height="83" width="193" alt="actiance"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-2.jpg" height="83" width="175" alt="aruba networks"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-3.jpg" height="83" width="301" alt="audio codes"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-4.jpg" height="83" width="194" alt="talk computer"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-5.jpg" height="83" width="250" alt="enghouse interactive"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-6.jpg" height="82" width="108" alt="grandstream"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-7.jpg" height="83" width="47" alt="il"></a></li>
                  <li><a href="#"><img src="<? echo get_template_directory_uri() ?>/images/sponsor-8.jpg" height="83" width="306" alt="plantronics"></a></li>
                </ul>
              </div>
            </div>
            <ol class="carousel-indicators">
              <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
              <li data-target="#carousel-example-generic" data-slide-to="1"></li>
              <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="news-section">
      <div class="container-fluid">
        <h1>Latest Users Group News</h1>
        <button type="button" class="btn btn-success"><i class="icon-bookmark"></i>View all news</button>
        <ul class="news-list row">
          <li class="col-sm-4">
            <a href="#"><img src="<? echo get_template_directory_uri() ?>/images/img-3.jpg" height="230" width="358" alt="image description"></a>
            <div class="text-block">
              <h2><a href="#">Winter 2015 Events and Ignite Conference Trip Giveaway!</a></h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....</p>
            </div>
          </li>
          <li class="col-sm-4">
            <a href="#"><img src="<? echo get_template_directory_uri() ?>/images/img-4.jpg" alt="image description" width="358" height="230"></a>
            <div class="text-block">
              <h2><a href="#">Welcome Sennheiser as a National Sponsor!</a></h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....</p>
            </div>
          </li>
          <li class="col-sm-4">
            <img src="<? echo get_template_directory_uri() ?>/images/img-5.jpg" height="230" width="358" alt="image description">
            <div class="text-block">
              <h2><a href="#">Welcome ComputerTalk as a National Sponsor!</a></h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....</p>
            </div>
          </li>
        </ul>
      </div>
    </div>


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>