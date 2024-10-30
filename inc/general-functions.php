<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
function trsleadmgmt_set_activation_script()
{
	trsleadmgmt_settings_activation();
    flush_rewrite_rules();
    trsleadmgmt_log("Plugin Activated");
}
function trsleadmgmt_set_deactivation_script()
{
    trsleadmgmt_log("Plugin Deactivated");
}

function trsleadmgmt_log($message)
{    
    $currentTime = date('y-m-d h:i:s', time());
	$backtrace=debug_backtrace();
    error_log("$currentTime  -->   " . "$message  Log Generation Details :      Filename: ".$backtrace[0]['file']."   at Line number : ".$backtrace[0]['line']."\n\n", 3, TRSLEADMGMT_LOG_FILE . "plugin-logs" . TRSLEADMGMT_LOG_FILE_EXT);
}
/**
 * Setup settings to default static settings if they are not yet set
 *
 */
function trsleadmgmt_settings_activation()
{
     // Get the Prior or Previously Saved Settings  from the options table in the database
    $settings = get_option('trsleadmgmt_general_settings');
    
    // Get the default plugin settings saved in this file as static settings or default settings, in general-functions.php.
    $default_settings = trsleadmgmt_get_default_general_settings();
    
    // Loop through each of the default plugin settings.
    foreach ($default_settings as $setting_key => $setting_value) {
        
        // If the setting didn't previously exist, add the default value to the $settings array.
        if (!isset($settings[$setting_key]))
            $settings[$setting_key] = $setting_value;
    }
    
    // Update the plugin settings.
    update_option('trsleadmgmt_general_settings', $settings);
}
/**
 * This method returns array of default static settings that are stored in DB initially and on activation
 *
 */
function trsleadmgmt_get_default_general_settings()
{
    $prefix = TRSLEADMGMT_LEAD_PREFIX;
    // Set up the array for default plugin settings
    $settings = apply_filters('trsleadmgmt_default_general_settings', array(
		
		'default_lead_fields' => array(
			'name'=>array(
				'label'			=> __( 'Name', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'name',
				'type'			=> 'text',
				'required'		=> '1',
				'after'			=> '',
				'placeholder'	=>	'Name'
			),
			'email'=>array(
				'label'			=> __( 'Email', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'email',
				'type'			=> 'text',
				'required'		=> '1',
				'after'			=> '',
				'placeholder'	=>	'Email'
			),
		),
		'additional_lead_fields' => array(
			'company'=>array(
				'label'			=> __( 'Company', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'company',
				'type'			=> 'text',
				'required'		=> '1',
				'after'			=> '',
				'placeholder'	=>	'Company'
			),
			 'phone'=>array(
				'label'			=> __( 'Phone Number', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'phone',
				'type'			=> 'text',
				'required'		=> '1',
				'after'			=> '',
				'placeholder'	=>	'Phone Number'
			),
			'url'=>array(
				'label'			=> __( 'Url', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'url',
				'type'			=> 'text',
				'required'		=> '0',
				'after'			=> '',
				'placeholder'	=>	'Url'
			),
			'requirements'=>array(
				'label'			=> __( 'Requirements', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'requirements',
				'type'			=> 'textarea',
				'required'		=> '0',
				'after'			=> '',
				'placeholder'	=>	'Requirements'
			),
			'documents'=>array(
				'label'			=> __( 'Documents', TRSLEADMGMT_TEXT_DOMAIN ),
				'id'			=> $prefix .'documents',
				'type'			=> 'file',
				'required'		=> '0',
				'after'			=> '',
				'placeholder'	=>	'Documents'
			),
		),
		'default_display_lead_fields' => array(
			'name'=>array(
				'label'			=> __( 'Lead Name', TRSLEADMGMT_TEXT_DOMAIN ),
				'default'		=> 'false',
				'sortable'		=> 'true',
				'title'			=> 'true',
				'value_field'	=> 'name',
			),
			'email'=>array(
				'label'			=> __( 'Email', TRSLEADMGMT_TEXT_DOMAIN ),
				'default'		=> 'false',
				'sortable'		=> 'true',
				'value_field'	=> 'email',
			),
			/* 'date'=>array(
				'label'			=> __( 'Date', TRSLEADMGMT_TEXT_DOMAIN ),
				'default'		=> 'true',
				'sortable'		=> 'false',
			), */
		),
		'additional_display_lead_fields'=>array(
			/* 'company'=>array(
				'label'			=> __( 'Company', TRSLEADMGMT_TEXT_DOMAIN ),
				'default'		=> 'false',
				'sortable'		=> 'true',
				'value_field'	=> 'company',
			), */
		),
		
        
    ));
    return $settings;
}


function trsleadmgmt_get_general_setting_value($option_name)
{
    $options  = get_option('trsleadmgmt_general_settings', array());
    $defaults = trsleadmgmt_get_default_general_settings();
    return ((isset($options[$option_name])) ? $options[$option_name] : $defaults[$option_name]);
}
/**
 * This method returns value for given option name in parameter/argument.
 */
function trsleadmgmt_update_general_setting_value($updated_option_array)
{
    $options = get_option('trsleadmgmt_general_settings', array());
    $newOptions = array_merge($options, $updated_option_array);
    // Update the plugin settings.
    update_option('trsleadmgmt_general_settings', $newOptions);
    
    
}
function trsleadmgmt_get_lead_data_fields(){
	$default_fields=trsleadmgmt_get_general_setting_value('default_lead_fields');
	$additional_fields=trsleadmgmt_get_general_setting_value('additional_lead_fields');
	$all_fields=array_merge($default_fields,$additional_fields);
	return $all_fields;
}
function trsleadmgmt_get_lead_columns(){
	$default_fields=trsleadmgmt_get_general_setting_value('default_display_lead_fields');
	$additional_fields=trsleadmgmt_get_general_setting_value('additional_display_lead_fields');
	$all_fields=array_merge($default_fields,$additional_fields);
	return $all_fields;
}function trsleadmgmt_get_lead_posttype(){	return TRSLEADMGMT_LEAD_POSTTYPE;}function trsleadmgmt_addon_admin_notice(){    $screen = get_current_screen();    if( ($screen->id !='edit-'.TRSLEADMGMT_LEAD_POSTTYPE))        return;    ?>	<div class="notice notice-success is-dismissible"> 		<a target="_blank" href="<?php echo TRSLEADMGMT_STORE_URL; ?>" style="text-decoration: none;"><div class="trsleadmgmt_download_title"><strong> Download Free Addons.</strong></div></a>		<div class="trsleadmgmt_download_sub_title"><strong>Get  PRO Extensions Like Leads Export To CSV and Leads Conversion Extensions from CRM Lead Management Store for FREE !<br/><a target="_blank" href="<?php echo TRSLEADMGMT_STORE_URL; ?>" style="text-decoration: none;"> Get It Now</a></strong></div>		<button type="button" class="notice-dismiss">			<span class="screen-reader-text">Dismiss this notice.</span>		</button>	</div>	<?php}include_once( ABSPATH . 'wp-admin/includes/plugin.php' );if(!is_plugin_active('crm-lead-mgmt-users/crm-lead-mgmt-users-transfer.php')){	add_action('admin_notices','trsleadmgmt_addon_admin_notice');}
