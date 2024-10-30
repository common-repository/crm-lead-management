<?php
if (!defined('ABSPATH'))
exit; // Exit if accessed directly    

add_action('admin_init','load_my_script');
function load_my_script() {
  global $pagenow, $typenow;
  if (empty($typenow) && !empty($_GET['post'])) {
    $post = get_post($_GET['post']);
    $typenow = $post->post_type;
  }
  if (is_admin() && $typenow==TRSLEADMGMT_LEAD_POSTTYPE) {
	  $plugin_js_url = TRSLEADMGMT_JS;
	  $plugin_css_url = TRSLEADMGMT_CSS;
    if ($pagenow=='post-new.php' OR $pagenow=='post.php') {
      
		wp_enqueue_script('jquery');
		wp_enqueue_script('trsleadmgmt-lead-main-script',"{$plugin_js_url}core/main.js",array('jquery'));
		wp_localize_script('trsleadmgmt-lead-main-script', 'trsleadmgmt_lead_main_ajax', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));
    }
	wp_enqueue_style( 'trsleadmgmt-lead-admin-style',"{$plugin_css_url}admin/admin-style.css" );
	wp_enqueue_style( 'trsleadmgmt-lead-admin-font-awesome',"{$plugin_css_url}admin/font-awesome.min.css" );
	
	wp_enqueue_style( 'trsleadmgmt-lead-select2-style',"{$plugin_css_url}select2.min.css" );
	wp_enqueue_script('trsleadmgmt-lead-select2-script',"{$plugin_js_url}select2.min.js",array('jquery'));			wp_enqueue_script('trsleadmgmt-lead-jquery-validation-script',"{$plugin_js_url}jquery.validate.min.js",array('jquery'));
	
	wp_enqueue_script('trsleadmgmt_settings_script',"{$plugin_js_url}core/settings-page.js",array('jquery'));
	wp_localize_script('trsleadmgmt_settings_script', 'trsleadmgmt_settings_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php')
    ));
	wp_enqueue_script('trsleadmgmt_group_mail_script',"{$plugin_js_url}core/group-mail.js",array('jquery'));
	wp_localize_script('trsleadmgmt_group_mail_script', 'trsleadmgmt_settings_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php')
    ));		do_action( 'trsleadmgmt_enqueue_scripts_after' );
	
  }
}
add_action('wp_ajax_trsleadmgmt_lead_send_mail_request_js', 'trsleadmgmt_process_lead_send_mail_request');
add_action('wp_ajax_trsleadmgmt_group_mail_send_request_js', 'trsleadmgmt_process_group_mail_send_request');
add_action('wp_ajax_trsleadmgmt_save_settings_request_js', 'trsleadmgmt_process_save_settings_request');