<?php
/*
Plugin Name: Vimeography Theme: Bridge
Plugin URI: http://www.vimeography.com/themes
Theme Name: Bridge
Theme URI: vimeography.com/themes/bridge
Version: 1.2.1
Description: is a complete thumbnail layout that shows your video description, views and tags in a popup player.
Author: Dave Kiss
Author URI: http://www.vimeography.com/
Copyright: Dave Kiss
*/

if (! class_exists('Vimeography_Themes_Bridge') ) {
  class Vimeography_Themes_Bridge {
    public $version = '1.2.1';

    public function __construct() {
      add_action('plugins_loaded', array($this, 'load_theme'));
    }

    public function __set($name, $value) {
      $this->$name = $value;
    }

    /**
     * Has to be public so the wp actions can reach it.
     * @return [type] [description]
     */
    public static function load_theme() {
      do_action('vimeography/load-theme', __FILE__);
    }

    public static function load_scripts() {
      wp_deregister_script('fitvids');
      wp_deregister_script('fancybox');

      wp_dequeue_script('fitvids');
      wp_dequeue_script('fancybox');

      // Register our common scripts
      wp_register_script('fitvids', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.fitvids.js', array('jquery'));

      // Register our common scripts
      wp_register_script('fitfloats', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.fitfloats.js', array('jquery'));
      wp_register_script('spin', VIMEOGRAPHY_ASSETS_URL.'js/plugins/spin.min.js', array('jquery'));
      wp_register_script('jquery-spin', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.spin.js', array('jquery', 'spin'));
      wp_register_script('vimeography-utilities', VIMEOGRAPHY_ASSETS_URL.'js/utilities.js', array('jquery'));
      wp_register_script('vimeography-pagination', VIMEOGRAPHY_ASSETS_URL.'js/pagination.js', array('jquery'));
      wp_register_script('vimeography-froogaloop', VIMEOGRAPHY_ASSETS_URL.'js/plugins/froogaloop2.min.js', array('jquery', 'vimeography-utilities'));

      // This version doesn't quite work in firefox. Check scrollto github and enable when it is fixed!
      //wp_register_script('scrollto', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.scrollto.min.js', array('jquery'));

      // Register our custom scripts
      wp_register_script('fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js', array('jquery'));
      wp_register_script('fancybox-media', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js', array('fancybox'));
      wp_register_style('vimeography-common', VIMEOGRAPHY_ASSETS_URL.'css/vimeography-common.css');
      wp_register_style('fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css');
      wp_register_style('bridge', plugins_url('assets/css/bridge.css', __FILE__));

      wp_enqueue_script('fitvids');
      wp_enqueue_script('fitfloats');
      wp_enqueue_script('spin');
      wp_enqueue_script('jquery-spin');
      wp_enqueue_script('fancybox');
      wp_enqueue_script('fancybox-media');
      wp_enqueue_script('vimeography-utilities');
      wp_enqueue_script('vimeography-pagination');
      wp_enqueue_script('vimeography-froogaloop');
      //wp_enqueue_script('scrollto');

      wp_enqueue_style('vimeography-common');
      wp_enqueue_style('fancybox');
      wp_enqueue_style('bridge');
    }

    public function videos() {
      // optional helpers
      require_once(VIMEOGRAPHY_PATH .'lib/helpers.php');
      $helpers = new Vimeography_Helpers;

      $items = array();

      foreach ($this->data as $video)
      {
        $video->short_description = $helpers->truncate($video->description, 110);
        $items[] = $video;
      }

      $items = $helpers->apply_common_formatting($items);
      return $items;
    }

    public function theme_image_url() {
      return plugins_url('assets/img', __FILE__);
    }
  }
}

function vimeography_themes_bridge() {
  if ( ! class_exists( 'Vimeography', false ) ) {
    return;
  }

  new Vimeography_Themes_Bridge;
}

// Get this theme loaded at a lower priority than the Dev Bundle
add_action( 'plugins_loaded', 'vimeography_themes_bridge', 5 );
