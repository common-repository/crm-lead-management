<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'admin_menu', 'trsleadmgmt_register_menu_pages' );
/**
 * Register Code for Menus and Submenus menu and submenus
 */

// Add the admin options pages as submenus to the Feed Custom Post Type
function trsleadmgmt_register_menu_pages() {
	global $submenu;
	
	add_submenu_page( 'edit.php?post_type='.TRSLEADMGMT_LEAD_POSTTYPE, __( 'CRM Lead Management Group Mail', TRSLEADMGMT_TEXT_DOMAIN ), __( 'Group Mail', TRSLEADMGMT_TEXT_DOMAIN ),'manage_options', 'trsleadmgmt-group-mail', 'trsleadmgmt_get_group_mail_page' );
	
	do_action( 'trsleadmgmt_register_menu_groups_mail_after' );
	
	add_submenu_page( 'edit.php?post_type='.TRSLEADMGMT_LEAD_POSTTYPE, __( 'CRM Lead Management Settings', TRSLEADMGMT_TEXT_DOMAIN ), __( 'Settings', TRSLEADMGMT_TEXT_DOMAIN ),'manage_options', 'trsleadmgmt-settings', 'trsleadmgmt_get_tabbed_setting_page' );	
	do_action( 'trsleadmgmt_register_menu_after' );
	
}


function trsleadmgmt_get_tabbed_setting_page()
{
	include "default-setting-page.php";
}
function trsleadmgmt_get_group_mail_page()
{
	include "default-group-mail-page.php";
}