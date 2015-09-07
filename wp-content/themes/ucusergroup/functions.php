<?php

  // ----------------------------------------------------------
  // locations select
  // ----------------------------------------------------------

  function uc_city_select(){

    $venues = get_posts(
      array(
        'post_type' => 'espresso_venues',
        'suppress_filters' => false,
        'order' => 'ASC',
        'orderby' => 'VNU_city'
      )
    );

    $venueSelect  = '<select>';
    $venueSelect .= '<option class="hidden">Select</option>';
    foreach($venues as $venue){
      $venueSelect .= '<option>' . $venue->VNU_city. '</option>';
    }
    $venueSelect .= '</select>';

    echo $venueSelect;

  }









// ----------------------------------------------------------
// starkers functions
// ----------------------------------------------------------

  require_once( 'external/starkers-utilities.php' );

  add_theme_support('post-thumbnails');
  add_theme_support('menus');

  add_action( 'wp_enqueue_scripts', 'script_enqueuer' );

  add_filter( 'body_class', 'add_slug_to_body_class' );

  function script_enqueuer() {}

  function starkers_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <?php if ( $comment->comment_approved == '1' ): ?>
    <li>
      <article id="comment-<?php comment_ID() ?>">
        <?php echo get_avatar( $comment ); ?>
        <h4><?php comment_author_link() ?></h4>
        <time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
        <?php comment_text() ?>
      </article>
    <?php endif;
  }

