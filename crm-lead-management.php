<?php
/*
Plugin Name:  CRM Lead Management
Plugin URI: http://trssoftwaresolutions.com
Description: CRM Lead Management Plugin
Version: 1.2.1
Author: Sandeep Singh
Author URI: http://trssoftwaresolutions.com

*/

/* 
	This is free plugin for Lead Management. you can modify this plugin according to you, version 1, as published by the TRS Software Solution. 
 */
 
// Set the plugin prefix to be later used in code
if (!defined('TRSLEADMGMT_PREFIX'))
    define('TRSLEADMGMT_PREFIX', 'trsleadmgmt', true);

// Set the plugin prefix to be later used in code
if (!defined('TRSLEADMGMT_LEAD_PREFIX'))
    define('TRSLEADMGMT_LEAD_PREFIX', 'trsleadmgmt_lead_', true);

// Set the plugin FILE Constant
if (!defined('TRSLEADMGMT_FILE_CONSTANT'))
    define('TRSLEADMGMT_FILE_CONSTANT', __FILE__, true);

// Set constant path to the plugin directory to be later used by plugin.
if (!defined('TRSLEADMGMT_DIR'))
    define('TRSLEADMGMT_DIR', plugin_dir_path(__FILE__));

// Set constant URI to the plugin URL to be later used for refrence.
if (!defined('TRSLEADMGMT_URI'))
    define('TRSLEADMGMT_URI', plugin_dir_url(__FILE__));

// Set the path to the plugin's javascript directory in constant.
if (!defined('TRSLEADMGMT_JS'))
    define('TRSLEADMGMT_JS', TRSLEADMGMT_URI . trailingslashit('js'), true);

// Set the path to the plugin's CSS directory.
if (!defined('TRSLEADMGMT_CSS'))
    define('TRSLEADMGMT_CSS', TRSLEADMGMT_URI . trailingslashit('css'), true);

// Set the path to the plugin's includes directory.
if (!defined('TRSLEADMGMT_INC'))
    define('TRSLEADMGMT_INC', TRSLEADMGMT_DIR . trailingslashit('inc'), true);

// Set the path to the plugin's Views directory.
if (!defined('TRSLEADMGMT_VIEWS'))
    define('TRSLEADMGMT_VIEWS', TRSLEADMGMT_INC . trailingslashit('views'), true);

// Set the path to the plugin's log file.
if (!defined('TRSLEADMGMT_LOG_FILE'))
    define('TRSLEADMGMT_LOG_FILE', TRSLEADMGMT_DIR . trailingslashit('log'), true);

if (!defined('TRSLEADMGMT_LOG_FILE_EXT'))
    define('TRSLEADMGMT_LOG_FILE_EXT', '.txt', true);

if (!defined('TRSLEADMGMT_STORE_URL')) {
    define('TRSLEADMGMT_STORE_URL', 'http://www.crmlm.trssoftwaresolutions.in', TRUE);
}

if (!defined('TRSLEADMGMT_TEXT_DOMAIN')) {
    define('TRSLEADMGMT_TEXT_DOMAIN', 'trsleadmgmt');
}
// Set the plugin default post type
if (!defined('TRSLEADMGMT_LEAD_POSTTYPE'))
    define('TRSLEADMGMT_LEAD_POSTTYPE', 'trsleadmgmt_leads', true);

require_once TRSLEADMGMT_INC 	. 	"general-functions.php";
require_once TRSLEADMGMT_VIEWS 	. 	"register-post-types.php";
require_once TRSLEADMGMT_VIEWS 	. 	"lead-metabox-admin.php";
require_once TRSLEADMGMT_VIEWS 	. 	"lead-default-listable.php";
require_once TRSLEADMGMT_VIEWS 	. 	"plugin-process-ajax.php";
require_once TRSLEADMGMT_VIEWS 	. 	"default-plugin-scripts.php";
require_once TRSLEADMGMT_VIEWS 	. 	"default-menus.php";



// Plugin Activation and Deactivation method defined here

register_activation_hook(__FILE__, 'trsleadmgmt_activation_script');
register_deactivation_hook(__FILE__, 'trsleadmgmt_deactivation_script');

/**
 * Plugin activation procedure
 */
function trsleadmgmt_activation_script()
{
    trsleadmgmt_set_activation_script();
}

/**
 * Plugin deactivation procedure
 */
function trsleadmgmt_deactivation_script()
{
    trsleadmgmt_set_deactivation_script();
}
?>