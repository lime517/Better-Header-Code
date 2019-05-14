// JS that runs on the Better Header Code admin page

jQuery(document).ready(function($) {
  wp.codeEditor.initialize($('#sl_bhc_option_headercode'), (cm_settings));
  //wp.codeEditor.initialize($('#sl_bhc_option_bodycode'), (cm_settings));
  wp.codeEditor.initialize($('#sl_bhc_option_footercode'), (cm_settings));
})
