<?php
/*
Plugin Name: Roots Fertilizer
Description: Fuel for WordPress Roots Theme
Version: 0.1
Author: Jonathan Stanley
Author URI: http://bristleconeweb.com/
License: GPL2
Text Domain: roots-fertilizer
*/

add_filter( 'plugin_row_meta', 'rootsfertilizer_plugin_row_meta', 10, 2 ); 
add_action('widgets_init', 'rootsfertilizer_widgets_init');
add_action('admin_init', 'rootsfertilizer_admin_init'); 

/**
 * Plugin meta
 */
function rootsfertilizer_plugin_row_meta($links, $file) {
    $plugin = plugin_basename(__FILE__);
    if ($file == $plugin) {
        return array_merge(
            $links,
            array( sprintf( '<a target="_blank" href="https://github.com/bristweb/roots-fertilizer">%s</a>',  __('Documentation') ) )
        );
    }
    return $links;
}
 
/**
 * Register sidebars and widgets
 */
function rootsfertilizer_widgets_init() {
  // Create header widget
  register_sidebar(array(
    'name'          => __('Header', 'roots'),
    'id'            => 'header',
    'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<span style="display:none;">',
    'after_title'   => '</span>',
  ));
}

/**
 * Remove extra menus / maintain source control / disble access to file changes when on production server
 */
function rootsfertilizer_admin_init() {
  if (!strstr(site_url(),'localhost'))
    return;
  define('DISALLOW_FILE_EDIT', true);
  define('DISALLOW_FILE_MODS', true);
  //perhaps define('FORCE_SSL_ADMIN',true);
}
