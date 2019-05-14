<?php

/**
 * Plugin Name: Better Header Code
 * Plugin URI:
 * Description: A ultra-lean Wordpress Plugin for implementing header, body, and footer code! Great for Google Analytics!
 * Version: 1.1
 * Author: Safety Llama
 * Author URI: https://safetyllama.com
 */

// Don't want anyone getting in here that shouldn't be.
if (! defined('ABSPATH')) {
    return;
}

// Register Settings
function sl_bhc_register_settings()
{

    // Header
    add_option('sl_bhc_option_headercode', '');
    register_setting('sl_bhc_options_group', 'sl_bhc_option_headercode', 'sl_bhc_headercode_sanitize');
    add_option('sl_bhc_option_header_enable', '');
    register_setting('sl_bhc_options_group', 'sl_bhc_option_header_enable', 'sl_bhc_headercode_enable_sanitize');

    // Body
    add_option('sl_bhc_option_bodycode', '');
    register_setting('sl_bhc_options_group', 'sl_bhc_option_bodycode', 'sl_bhc_headercode_sanitize');
    add_option('sl_bhc_option_body_enable', '');
    register_setting('sl_bhc_options_group', 'sl_bhc_option_body_enable', 'sl_bhc_headercode_enable_sanitize');

    // Footer
    add_option('sl_bhc_option_footercode', '');
    register_setting('sl_bhc_options_group', 'sl_bhc_option_footercode', 'sl_bhc_headercode_sanitize');
    add_option('sl_bhc_option_footer_enable', '');
    register_setting('sl_bhc_options_group', 'sl_bhc_option_footer_enable', 'sl_bhc_headercode_enable_sanitize');
}
add_action('admin_init', 'sl_bhc_register_settings');

// Sanitize functions, for when stuff gets submitted. on the options page.
// Code
function sl_bhc_headercode_sanitize ($content) {
  // We can't really sanitize this HTML. We INTENTIONALLY want the user to be able to put whatever they want in here.
  return $content;
}

// On/Off switch
function sl_bhc_headercode_enable_sanitize ($content) {
  // This shouldn't be anything other than OFF or ON.
  if ($content == 'on' || $content == 'off' || $content == true || $content == false) {
    return $content;
  } else {
    return 'off';
  }
}


// Register Options Page
if (is_admin()) {
  function sl_bhc_register_options_page()
  {
      $page = add_options_page('Better Header Code', 'Better Header Code', 'manage_options', 'sl_bhc', 'sl_bhc_options_page');
      add_action('admin_print_styles-' . $page, 'sl_bhc_plugin_admin_styles');
  }
  add_action('admin_menu', 'sl_bhc_register_options_page');

  // Add CSS
  add_action('admin_init', 'sl_bhc_css');

  function sl_bhc_css()
  {
      wp_register_style('wp_bhc_stylesheet', plugins_url('main.css', __FILE__));
  }
  function sl_bhc_plugin_admin_styles()
  {
      wp_enqueue_style('wp_bhc_stylesheet');
  }
}

// Hook into our Options page, mostly for adding in CodeMirror support.

add_action('current_screen', 'sl_bhc_options_page_hook');
function sl_bhc_options_page_hook()
{
    $currentScreen = get_current_screen();
    if ($currentScreen->id === "settings_page_sl_bhc") {

        // Place anything we need to run on our settings page here

        wp_register_script('sl_bhg_admin_script', plugin_dir_url(__FILE__) . '/admin.js', array( 'jquery' ), '', true);

        add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');

        function codemirror_enqueue_scripts()
        {
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'htmlmixed'));
            wp_localize_script('sl_bhg_admin_script', 'cm_settings', $cm_settings);
            wp_enqueue_script('sl_bhg_admin_script');
        }
    }
}

// Options Page
function sl_bhc_options_page()
{

  // Options Page Content ?>
  <div class="wrap">
  <h1>Better Header Code</h1>
  <form method="post" action="options.php">
  <?php settings_fields('sl_bhc_options_group'); ?>

  <!-- Header Code -->
  <h3>Header Code</h3>
  <p>This code will be placed in the &#60;head&#62; tag</p>
  <input type="checkbox" id="sl_bhc_option_header_enable" name="sl_bhc_option_header_enable"  <?php if (get_option('sl_bhc_option_header_enable') == 'on' || get_option('sl_bhc_option_header_enable') == true) {
      echo 'checked';
  }; ?>/>Enable Header Code?<br><br>
  <table>
  <tr valign="top">
  <td><textarea cols="80" rows="6" type="text" id="sl_bhc_option_headercode" name="sl_bhc_option_headercode"><?php echo esc_html(get_option('sl_bhc_option_headercode')) ?></textarea></td>
  </tr>
  </table>

  <!-- Body Code - Temporarily Disabled until wp_body_open is better supported.
  <h3>Body Code</h3>
  <p>This code will be at the beginning of the &#60;body&#62; tag.</p>
  <input type="checkbox" id="sl_bhc_option_body_enable" name="sl_bhc_option_body_enable"  <?php if (get_option('sl_bhc_option_body_enable') == 'on' || get_option('sl_bhc_option_body_enable') == true) {
      echo 'checked';
  }; ?>/>Enable Body Code?<br><br>
  <table>
  <tr valign="top">
  <td><textarea cols="80" rows="6" type="text" id="sl_bhc_option_bodycode" name="sl_bhc_option_bodycode"><?php echo esc_html(get_option('sl_bhc_option_bodycode')) ?></textarea></td>
  </tr>
  </table>
  -->

  <!-- Footer Code -->
  <h3>Footer Code</h3>
  <p>This code will be placed in the footer.</p>
  <input type="checkbox" id="sl_bhc_option_footer_enable" name="sl_bhc_option_footer_enable"  <?php if (get_option('sl_bhc_option_footer_enable') == 'on' || get_option('sl_bhc_option_footer_enable') == true) {
      echo 'checked';
  }; ?>/>Enable Footer Code?<br><br>
  <table>
  <tr valign="top">
  <td><textarea cols="80" rows="6" type="text" id="sl_bhc_option_footercode" name="sl_bhc_option_footercode"><?php echo esc_html(get_option('sl_bhc_option_footercode')) ?></textarea></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>

  <div class="credits">
    <p><i>Thanks for using Better Header Code by <a target="_blank" href="https://safetyllama.com">Safety Llama</a>!</i></p>
  </div>
  </div>
<?php
}


// Add Header code to the actual header
function sl_bhc_inject_header_code()
{
    if (get_option('sl_bhc_option_header_enable') == 'on' || get_option('sl_bhc_option_header_enable') == true) {
        echo (get_option('sl_bhc_option_headercode'));
    }
}
add_action('wp_head', 'sl_bhc_inject_header_code');

// Body
/*
function sl_bhc_inject_body_code()
{
    if (get_option('sl_bhc_option_body_enable') == 'on' || get_option('sl_bhc_option_body_enable') == true) {
        echo (get_option('sl_bhc_option_bodycode'));
    }
}
add_action('wp_body_open', 'sl_bhc_inject_body_code');
*/

// Footer
function sl_bhc_inject_footer_code()
{
    if (get_option('sl_bhc_option_footer_enable') == 'on' || get_option('sl_bhc_option_footer_enable') == true) {
        echo (get_option('sl_bhc_option_footercode'));
    }
}
add_action('wp_footer', 'sl_bhc_inject_footer_code');

//update_option('sl_bhc_option_body_enable', 'on');


 ?>
