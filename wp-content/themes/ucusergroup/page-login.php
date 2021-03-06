<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/starkers-utilities.php for info on get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

<div class="middle">
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <div class="page-title">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 login-page">
            <h2><?php the_title(); ?></h2>
            <?php
            $subtitle = get_field( 'subtitle' );
            if ($subtitle) {
              echo "<p class='subtitle'>
              $subtitle
              </p>";
            }
            ?>
            <?php the_content(); ?>
            <?php $args = array(
                   'echo'           => true,
                   'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'],
                   'form_id'        => 'loginform',
                   'label_username' => __( '' ),
                   'label_password' => __( '' ),
                   'label_remember' => __( 'Remember Me' ),
                   'label_log_in'   => __( 'Log In' ),
                   'id_username'    => 'user_login',
                   'id_password'    => 'user_pass',
                   'id_remember'    => 'rememberme',
                   'id_submit'      => 'wp-submit',
                   'remember'       => true,
                   'value_username' => '',
                   'value_remember' => false
           ); ?>

           <div class="row">
             <div class="col-sm-5">
               <?php wp_login_form($args); ?>
             </div>
           </div>
          </div><!--/col-sm-12-->
        </div><!--/row-->
      </div><!--/container-fluid -->
    </div>

  <?php endwhile; ?>
</div><!--/middle-->


<?php get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
