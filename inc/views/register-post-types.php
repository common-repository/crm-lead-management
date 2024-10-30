<?php
if (!defined('ABSPATH'))
exit; // Exit if accessed directly    

add_action('init', 'trsleadmgmt_register_post_types');

function trsleadmgmt_register_post_types()
{
    
    // Set up labels for the 'trsleadmgmt_fd_src' post type
    $labels = apply_filters('trsleadmgmt_lead_post_type_labels', array(
        'name' => __('Leads', TRSLEADMGMT_TEXT_DOMAIN),
        'singular_name' => __('Lead', TRSLEADMGMT_TEXT_DOMAIN),
        'add_new' => __('Add New', TRSLEADMGMT_TEXT_DOMAIN),
        'all_items' => __('All Leads', TRSLEADMGMT_TEXT_DOMAIN),
        'add_new_item' => __('Add New Lead', TRSLEADMGMT_TEXT_DOMAIN),
        'edit_item' => __('Edit Lead', TRSLEADMGMT_TEXT_DOMAIN),
        'new_item' => __('New Lead', TRSLEADMGMT_TEXT_DOMAIN),
        'view_item' => __('View Lead', TRSLEADMGMT_TEXT_DOMAIN),
        'search_items' => __('Search Leads', TRSLEADMGMT_TEXT_DOMAIN),
        'not_found' => __('No Leads Found', TRSLEADMGMT_TEXT_DOMAIN),
        'not_found_in_trash' => __('No Leads Found In Trash', TRSLEADMGMT_TEXT_DOMAIN),
        'menu_name' => __('Leads', TRSLEADMGMT_TEXT_DOMAIN)
    ));
    
    // Set up the arguments for the 'trsleadmgmt_fd_src' post type
    $lead_args = apply_filters('trsleadmgmt_lead_post_type_args', array(
        'exclude_from_search' => true,
        'publicly_querable' => false,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'public' => false,
        'show_ui' => true,
        'query_var' => 'trsleadmgmt_lead',
        'menu_position' => 76,
        'menu_icon' => 'dashicons-admin-users',
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'rewrite' => array(
            'slug' => 'trsleadmgmt_lead',
            'with_front' => false
        ),
		'supports' => array(),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'supports' => array(
            'title'
        ),
        'has_archive' => true,
        'labels' => $labels
    ));
    
    // Register the 'trsleadmgmt_fd_src' post type
    register_post_type(TRSLEADMGMT_LEAD_POSTTYPE, $lead_args);
    remove_post_type_support( TRSLEADMGMT_LEAD_POSTTYPE, 'title' );
    
}


/**
 * Filter the link query arguments to exclude  post types. 
 */
function trsleadmgmt_modify_link_builder_query($query)
{
    
    // custom post type slug to be removed
    $to_remove = array(
        TRSLEADMGMT_LEAD_POSTTYPE,
    );
    
    // find and remove the array keys
    foreach ($to_remove as $post_type) {
        $key = array_search($post_type, $query['post_type']);
        // remove the array item
        if ($key)
            unset($query['post_type'][$key]);
    }
    
    return $query;
}
add_filter('wp_link_query_args', 'trsleadmgmt_modify_link_builder_query');



function trsleadmgmt_create_groups_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => __( 'Groups', TRSLEADMGMT_TEXT_DOMAIN ),
		'singular_name'     => __( 'Group', TRSLEADMGMT_TEXT_DOMAIN ),
		'search_items'      => __( 'Search Groups' ),
		'all_items'         => __( 'All Groups' ),
		'parent_item'       => __( 'Parent Group' ),
		'parent_item_colon' => __( 'Parent Group:' ),
		'edit_item'         => __( 'Edit Group' ),
		'update_item'       => __( 'Update Group' ),
		'add_new_item'      => __( 'Add New Group' ),
		'new_item_name'     => __( 'New Group Name' ),
		'menu_name'         => __( 'Groups' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'lead_user_group' ),
	);

	register_taxonomy( 'lead_user_group', array( TRSLEADMGMT_LEAD_POSTTYPE ), $args );
}
add_action( 'init', 'trsleadmgmt_create_groups_taxonomies', 1 );