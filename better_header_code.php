<?php

/**
 * Plugin Name: Better Header Code
 * Plugin URI:
 * Description: A ultra-lean Wordpress Plugin for implementing header code. Great for Google Analytics!
 * Version: 0.1
 * Author: Safety Llama
 * Author URI: http://safetyllama.com
 */


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

// Options Page
function sl_bhc_options_page()
{
  ?>
  <div class="wrap">
  <h1>Better Header Code</h1>
  <form method="post" action="options.php">
  <?php settings_fields( 'sl_bhc_options_group' ); ?>
  <h3>Header Code</h3>
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


// // KSES to allow script tags
// wp_kses(0, 'script');
//
// // Hooks into head
// add_action('wp_head','better_header_code');
//
// // Displays the code in header
// function better_header_code() {
//   $sl_header_code = get_option('sl_bhc_option_headercode');
//   echo  $sl_header_code;
// }




 ?>
