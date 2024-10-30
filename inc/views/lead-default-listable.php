<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   // ADD NEW COLUMN
function trsleadmgmt_lead_columns_head($defaults) {
	unset( $defaults['date'],$defaults['title'],$defaults['taxonomy-lead_user_group'] );
	$dyn_fields=trsleadmgmt_get_lead_columns();
	$newcolumns=array();
	foreach($dyn_fields as $key=>$item){
		$newcolumns[$key]=$item['label'];
		
		
	}
	$newcolumns['taxonomy-lead_user_group']=__( 'User Group', TRSLEADMGMT_TEXT_DOMAIN );
	$newcolumns['date']=__( 'Date', TRSLEADMGMT_TEXT_DOMAIN );
    return array_merge($defaults, $newcolumns);
}
add_filter('manage_trsleadmgmt_leads_posts_columns', 'trsleadmgmt_lead_columns_head', 20, 1);

function trsleadmgmt_lead_columns_content($column_name, $post_ID) {
	$post               = get_post( $post_ID );
	$can_edit_post      = current_user_can( 'edit_post', $post->ID );
	$dyn_fields			= trsleadmgmt_get_lead_columns();
	if(array_key_exists($column_name,$dyn_fields)!=false){
		$item=$dyn_fields[$column_name];
		$field_name=$item['value_field'];
		$meta = get_post_meta($post->ID, TRSLEADMGMT_LEAD_PREFIX.$field_name, true);
		if(isset($item['title']) && !empty($item['title'])){
			if ( $can_edit_post && 'trash' != $post->post_status ) {
				echo '<a href="' . get_edit_post_link( $post->ID, true ) . '"><strong>' . $meta . '</strong></a>';
			}else{
				echo $meta;
			}
		}else{
			echo $meta;
		}
	}
}
add_action('manage_trsleadmgmt_leads_posts_custom_column', 'trsleadmgmt_lead_columns_content',  20, 2);


function trsleadmgmt_lead_display_sortable_column( $columns ) {
	$dyn_fields			= trsleadmgmt_get_lead_columns();
	$newArray=array();
	foreach($dyn_fields as $key=>$item){
		if(isset($item['sortable']) && $item['sortable']=='true'){
			$newArray[$key]=$key;
		}
	}
    return array_merge($columns,$newArray);
}
add_filter( 'manage_edit-trsleadmgmt_leads_sortable_columns', 'trsleadmgmt_lead_display_sortable_column' );

function trsleadmgmt_lead_display_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
	$dyn_fields			= trsleadmgmt_get_lead_columns();
	if(array_key_exists($orderby,$dyn_fields)!=false){
		$field_name=TRSLEADMGMT_LEAD_PREFIX .$dyn_fields[$orderby]['value_field'];
		$query->set('meta_key',$field_name);
        $query->set('orderby','meta_value meta_value_num');
	}
}
add_action( 'pre_get_posts', 'trsleadmgmt_lead_display_orderby' );

function  trsleadmgmt_lead_display_remove_quick_edit( $actions ) {
	global $post;
    if( $post->post_type == TRSLEADMGMT_LEAD_POSTTYPE ) {
		unset(
			$actions['inline hide-if-no-js'],
			$actions['view']
		);
	}
    return $actions;
}

if (is_admin()) {
	add_filter('post_row_actions','trsleadmgmt_lead_display_remove_quick_edit',10,2);
}

add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = TRSLEADMGMT_LEAD_POSTTYPE; // change to your post type
	$taxonomy  = 'lead_user_group'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

function tsm_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = TRSLEADMGMT_LEAD_POSTTYPE; // change to your post type
	$taxonomy  = 'lead_user_group'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}
add_filter('parse_query', 'tsm_convert_id_to_term_in_query');

function cf_search_join( $join ) {
    global $wpdb, $pagenow,$wp_query;
	if( is_admin() && $pagenow=='edit.php' && $wp_query->query_vars['post_type']==TRSLEADMGMT_LEAD_POSTTYPE){
		if ( is_search() ) {    
			$join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
		}
	}
    
    return $join;
}
add_filter('posts_join', 'cf_search_join' );

function cf_search_where( $where ) {
    global $wpdb, $pagenow,$wp_query;
	if( is_admin() && $pagenow=='edit.php' && $wp_query->query_vars['post_type']==TRSLEADMGMT_LEAD_POSTTYPE){
		if ( is_search() ) {
			$where = preg_replace(
				"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
				"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
		}
	}

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );


function cf_search_distinct( $where ) {
    global $wpdb, $pagenow,$wp_query;
	if( is_admin() && $pagenow=='edit.php' && $wp_query->query_vars['post_type']==TRSLEADMGMT_LEAD_POSTTYPE){
		if ( is_search() ) {
			return "DISTINCT";
		}
	}

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );
