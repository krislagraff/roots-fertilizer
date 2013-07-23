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
 * Add a documentation link to Git Hub for the plugin
 * @param  [type] $links the plugin links to be filtered
 * @param  [type] $file  the plugin to be filtered
 * @return [type]        the filtered links
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
 * Initialize widgits
 * @return [type] [description]
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
 * Run functions for admin
 * @return [type] [description]
 */
function rootsfertilizer_admin_init() {
  rootsfertilizer_update_options();
  rootsfertilizer_file_permissions();
  rootsfertilizer_theme_caps();
}

  /**
   * Standard image sizes adjusted for standard bootstrap widths
   * Thumbnails are span2xspan2
   * Medium is span3 width
   * Large is span4 width
   * Upload full resolution for most images.  Images that will be shown at full resolution should be properly resized before uploading
   * @return [type] [description]
   */
  function rootsfertilizer_update_options(){
    update_option('thumbnail_size_w', 170);
    update_option('thumbnail_size_h', 170);
    update_option('medium_size_w', 270);
    update_option('medium_size_h', 9999);
    update_option('large_size_w', 370);
    update_option('large_size_h', 9999);
  }

  /**
   * Allow contributors to view form entries
   * @return [type] [description]
   */
  function rootsfertilizer_theme_caps() {
      $role = get_role( 'contributor');
      $role->add_cap( 'gravityforms_view_entries'); 
      $role->add_cap( 'gravityforms_edit_entries'); 

      $role->add_cap( 'gravityforms_view_entry_notes'); 
      $role->add_cap( 'gravityforms_edit_entry_notes');

      $role->add_cap( 'gravityforms_export_entries'); 
  }

  /**
   * Remove extra menus / maintain source control / disble access to file changes when on production server
   * @return [type] [description]
   */
  function rootsfertilizer_file_permissions(){
    if (!strstr(site_url(),'localhost')){
      define('DISALLOW_FILE_EDIT', true);
      define('DISALLOW_FILE_MODS', true);
      //perhaps define('FORCE_SSL_ADMIN',true);
    }
  }


/**
 * The following are to extend WordPress conditional tags
 */


/**
 * see http://codex.wordpress.org/Conditional_Tags#Testing_for_sub-Pages snippet 2
 * @return boolean [description]
 */
function is_subpage() {
    global $post;
    if ( is_page() && $post->post_parent ) {
        return $post->post_parent;
    } else {  
        return false;
    }
}

/**
 * see http://codex.wordpress.org/Conditional_Tags#Testing_for_sub-Pages snippet 4
 * @param  [type]  $pid the id of the post to test
 * @return boolean      true if $pid is an ancestor of the current post.  Otherwise returnse false.
 */
function is_tree( $pid ) {
    global $post;
    if ( is_page($pid) )
        return true;
    $anc = get_post_ancestors( $post->ID );
    foreach ( $anc as $ancestor ) {
        if( is_page() && $ancestor == $pid ) {
            return true;
        }
    }
    return false;
}

/**
 * determine if current page has child pages
 * @return boolean true if current post has children
 */
function has_children(){
  global $post;
  $children = get_pages('child_of='.$post->ID);
  if( count( $children ) != 0 ) 
    return true;
  else 
    return false;
}
