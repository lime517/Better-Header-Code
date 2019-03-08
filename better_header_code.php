<?php

/**
 * Plugin Name: Better Header Code
 * Plugin URI:
 * Description: A ultra-lean Wordpress Plugin for implementing header code. Great for Google Analytics!
 * Version: 0.1
 * Author: Safety Llama
 * Author URI: http://safetyllama.com
 */

// Don't want anyone getting in here that shouldn't be.
if ( ! defined( 'ABSPATH' ) ) { return; }

// Register Settings
function sl_bhc_register_settings() {
   add_option( 'sl_bhc_option_headercode', '');
   register_setting( 'sl_bhc_options_group', 'sl_bhc_option_headercode', 'sl_bhc_callback' );
}
add_action( 'admin_init', 'sl_bhc_register_settings' );

// Register Options Page
function sl_bhc_register_options_page() {
  $page = add_options_page('Better Header Code', 'Better Header Code', 'manage_options', 'sl_bhc', 'sl_bhc_options_page');
  add_action( 'admin_print_styles-' . $page, 'sl_bhc_plugin_admin_styles' );
}
add_action('admin_menu', 'sl_bhc_register_options_page');

// Add CSS
add_action( 'admin_init', 'sl_bhc_css' );

function sl_bhc_css() {
    wp_register_style( 'wp_bhc_stylesheet', plugins_url( 'main.css', __FILE__ ) );
}
function sl_bhc_plugin_admin_styles() {
    wp_enqueue_style( 'wp_bhc_stylesheet' );
}

// Hook into our Options page, mostly for adding in CodeMirror support.

add_action( 'current_screen', 'sl_bhc_options_page_hook' );
function sl_bhc_options_page_hook() {
    $currentScreen = get_current_screen();
    if( $currentScreen->id === "settings_page_sl_bhc" ) {

        // Place anything we need to run on our settings page here

        wp_register_script('sl_bhg_admin_script', plugin_dir_url( __FILE__ ) . '/admin.js', array( 'jquery' ), '', true );

        add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');

        function codemirror_enqueue_scripts() {
          $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'htmlmixed'));
          wp_localize_script('sl_bhg_admin_script', 'cm_settings', $cm_settings);
          wp_enqueue_script('sl_bhg_admin_script');
        }
    }
}

// Options Page
function sl_bhc_options_page()
{

  // Options Page Content
  ?>
  <div class="wrap">
  <h1>Better Header Code</h1>
  <form method="post" action="options.php">
  <?php settings_fields( 'sl_bhc_options_group' ); ?>
  <h3>Header Code</h3>
  <p>Place any HTML here, including scripts and styles.</p>
  <table>
  <tr valign="top">
  <td><textarea cols="80" rows="6" type="text" id="sl_bhc_option_headercode" name="sl_bhc_option_headercode"><?php echo get_option('sl_bhc_option_headercode') ?></textarea></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}


// Add Header code to the actual header
function sl_bhc_inject_code() {
  echo get_option('sl_bhc_option_headercode');
}
add_action('wp_head', 'sl_bhc_inject_code');




 ?>
