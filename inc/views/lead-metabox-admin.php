<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action( 'add_meta_boxes', 'trsleadmgmt_add_meta_boxes', 99);

function trsleadmgmt_remove_comments_meta_boxes() {
    remove_meta_box('linktargetdiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('linkxfndiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('linkadvanceddiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('postexcerpt', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('trackbacksdiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('postcustom', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('commentstatusdiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('commentsdiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('revisionsdiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('authordiv', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
    remove_meta_box('sqpt-meta-tags', TRSLEADMGMT_LEAD_POSTTYPE, 'normal');
	
}
add_action( 'admin_init', 'trsleadmgmt_remove_comments_meta_boxes' );

function trsleadmgmt_add_meta_boxes() {
	global $trsleadmgmt_meta_fields;

	// Remove the default WordPress Publish box, because we will be using custom ones
	
	remove_meta_box( 'submitdiv', TRSLEADMGMT_LEAD_POSTTYPE, 'side' );
  
	add_meta_box(
		'trsleadmgmt_submitdiv',                            // $id
		__( 'Save Lead', TRSLEADMGMT_TEXT_DOMAIN ),      // $title
		'post_submit_meta_box',                 // $callback
		TRSLEADMGMT_LEAD_POSTTYPE,                           // $page
		'side',                                 // $context
		'high'                                   // $priority
	);


	add_meta_box(
		'trsleadmgmt_custom_meta_box',
		__( 'Lead Details', TRSLEADMGMT_TEXT_DOMAIN ),
		'trsleadmgmt_show_meta_box_callback',
		TRSLEADMGMT_LEAD_POSTTYPE,
		'normal',
		'high'
	);
	
	add_meta_box(
		'trsleadmgmt_lead_comment_meta_box',
		__( 'Lead Activity History', TRSLEADMGMT_TEXT_DOMAIN ),
		'trsleadmgmt_show_comment_meta_box_callback',
		TRSLEADMGMT_LEAD_POSTTYPE,
		'normal',
		'high'
	);
	
	add_meta_box(
		'trsleadmgmt_lead_mail_meta_box',
		__( 'Mail', TRSLEADMGMT_TEXT_DOMAIN ),
		'trsleadmgmt_show_mail_meta_box_callback',
		TRSLEADMGMT_LEAD_POSTTYPE,
		'normal',
		'high'
	);
}

function trsleadmgmt_get_custom_fields() {
	$prefix = 'trsleadmgmt_lead_';
	
	$all_fields=trsleadmgmt_get_lead_data_fields();
	// Field Array
	$trsleadmgmt_meta_fields[ 'url' ] = array(
		'label'			=> __( 'name', TRSLEADMGMT_TEXT_DOMAIN ),
		'id'			=> $prefix .'url',
		'type'			=> 'url',
		'after'			=> '',
		'placeholder'	=>	'http://'
	);

	$trsleadmgmt_meta_fields[ 'limit' ] = array(
		'label' => __( 'Limit', TRSLEADMGMT_TEXT_DOMAIN ),
		'id'    => $prefix . 'limit',
		'type'  => 'number'
	);

	$trsleadmgmt_meta_fields[ 'unique_titles' ] = array(
		'label' => __( 'Unique titles only', TRSLEADMGMT_TEXT_DOMAIN ),
		'id'    => $prefix . 'unique_titles',
		'type'  => 'checkbox'
	);

   
	return apply_filters( 'trsleadmgmt_fields', $all_fields );
}


/**
 * Set up the meta box for the trsleadmgmt_fd_src post type
 *
 */
function trsleadmgmt_show_meta_box_callback() {
	global $post;
	$meta_fields = trsleadmgmt_get_custom_fields();
	
	// Using nonce for verification
	wp_nonce_field( basename( __FILE__ ), 'trsleadmgmt_meta_box_nonce' );
	
	
		?><input type="hidden" id="content" value="" /><?php

		// Begin the field table and loop
		?><table class="form-table trsleadmgmt-form-table"><?php

		foreach ( $meta_fields as $field ) {
			// get value of this field if it exists for this post
			$meta = get_post_meta( $post->ID, $field['id'], true );
			// begin a table row with
			?><tr>
					<th><label for="<?php echo $field['id'] ?>"><?php echo $field['label'] ?></label></th>
					<td><?php

					if ( isset( $field['before'] ) && !empty( $field['before'] ) ) {
						call_user_func( $field['before'] );
					}
					
					
					// Add default placeholder value
					$field = wp_parse_args( $field, array(
						'desc'          => '',
						'placeholder'   => '',
						'type'          => 'text'
					) );
					
					$field_description = __( $field['desc'], TRSLEADMGMT_TEXT_DOMAIN);

					switch( $field['type'] ) {

						// text/url
						case 'url':
						case 'text':
							?><input <?php echo ($field['required'])?'required="true"':''; ?> type="<?php echo $field['type'] ?>" name="<?php echo $field['id'] ?>" id="<?php echo $field['id'] ?>" value="<?php echo esc_attr( $meta ) ?>" placeholder="<?php _e( $field['placeholder'], TRSLEADMGMT_TEXT_DOMAIN ) ?>" class="feedsproforwp-text-input"/><?php
							
							if ( strlen( trim( $field['desc'] ) ) > 0 ) {
								?><br /><label for="<?php echo $field['id'] ?>"><span class="description"><?php _e( $field['desc'], TRSLEADMGMT_TEXT_DOMAIN ) ?></span></label><?php
							}
						break;

						// textarea
						case 'textarea':
							
							$content = $meta;
							$editor_id = $field["id"];
							
							$settings = array(
								'textarea_name' =>  $field['id'], 
								'textarea_rows' =>  4, 
							 );
						
							wp_editor( $content, $editor_id, $settings );
							
							if ( strlen( trim( $field['desc'] ) ) > 0 ) {
								?><br /><label for="<?php echo $field['id'] ?>"><span class="description"><?php echo $field_description ?></span></label><?php
							}
						break;

						// checkbox
						case 'checkbox':
							?>
							<input <?php echo ($field['required'])?'required="true"':''; ?> type="hidden" name="<?php echo $field['id'] ?>" value="false" />
							<input type="checkbox" name="<?php echo $field['id'] ?>" id="<?php echo $field['id'] ?>" value="true" <?php checked( $meta, 'true' ) ?> /><?php
							
							if ( strlen( trim( $field['desc'] ) ) > 0 ) {
								?><label for="<?php echo $field['id'] ?>"><span class="description"><?php echo $field_description ?></span></label><?php
							}
						break;

						// select
						case 'select':
							?><select <?php echo ($field['required'])?'required="true"':''; ?> name="<?php echo $field['id'] ?>" id="<?php $field['id'] ?>"><?php
							foreach ($field['options'] as $option) {
								?><option<?php if ( $meta == $option['value'] ): ?> selected="selected"<?php endif ?> value="<?php echo $option['value'] ?>"><?php echo $option['label'] ?></option><?php
							}

							?></select><?php
							
							if ( strlen( trim( $field['desc'] ) ) > 0 ) {
								?><label for="<?php echo $field['id'] ?>"><span class="description"><?php echo $field_description ?></span></label><?php
							}
						break;

						// number
						case 'number':
							?><input <?php echo ($field['required'])?'required="true"':''; ?> class="trsleadmgmt-input-number" type="number" placeholder="<?php _e( $field['placeholder'], TRSLEADMGMT_TEXT_DOMAIN ) ?>" min="0" name="<?php echo $field['id'] ?>" id="<?php echo $field['id'] ?>" value="<?php echo esc_attr( $meta ) ?>" /><?php
							if ( strlen( trim( $field['desc'] ) ) > 0 ) {
								?><label for="<?php echo $field['id'] ?>"><span class="description"><?php echo $field_description ?></span></label><?php
							}
						break;
						// File
						case 'file':
							?><input <?php echo ($field['required'])?'required="true"':''; ?> class="trsleadmgmt-input-file" type="text" placeholder="<?php _e( $field['placeholder'], TRSLEADMGMT_TEXT_DOMAIN ) ?>" name="<?php echo $field['id'] ?>" id="<?php echo $field['id'] ?>" value="<?php echo esc_attr( $meta ) ?>" /><input id = "<?php echo $field['id'] ?>_upload_image_button" type = "button" value= "Upload"><?php
							if ( strlen( trim( $field['desc'] ) ) > 0 ) {
								?><label for="<?php echo $field['id'] ?>"><span class="description"><?php echo $field_description ?></span></label><?php
							}
							trsleadmgmt_file_view_code($meta);
							echo trsleadmgmt_get_upload_script($field['id'].'_upload_image_button',$field['id']);
						break;

					} //end switch

					if ( isset( $field['after'] ) && !empty( $field['after'] ) ) {
						
						call_user_func( $field['after'] );
					}

			?></td></tr><?php
		} // end foreach
		?></table>
		<?php
		function admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
		}

		function admin_styles() {
			wp_enqueue_style('thickbox');
		}
		add_action('admin_print_scripts', 'admin_scripts');
		add_action('admin_print_styles', 'admin_styles');
	
}
add_action( 'save_post', 'trsleadmgmt_save_custom_fields', 10, 2 );

function trsleadmgmt_save_custom_fields( $post_id, $post ) {
	$meta_fields = trsleadmgmt_get_custom_fields();

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['trsleadmgmt_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['trsleadmgmt_meta_box_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;


	/** Bail out if running an autosave, ajax or a cron */
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;
	
	// loop through fields and save the data
	foreach ( $meta_fields as $field ) {
		$old = get_post_meta( $post_id, $field[ 'id' ], true );
		$new = trim( $_POST[ $field[ 'id' ] ] );
		if ( $new && $new != $old ) {
			update_post_meta( $post_id, $field[ 'id' ], $new );
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field[ 'id' ], $old );
		}
	} 
	
	
}

function trsleadmgmt_show_comment_meta_box_callback(){
	global $post;
	// Using nonce for verification	
	wp_nonce_field( basename( __FILE__ ), 'trsleadmgmt_comment_meta_box_nonce' );
	$commentsdata=get_post_meta( $post->ID, 'trsleadmgmt_commentsdata', true );
	$args = array(
		'post_id' => $post->ID, 
	);
	$comments = get_comments($args);
	// echo "<pre>";print_r ($comments);die;
	?>
	<table id="trsleadmgmt_user_activity_stream" border="0" cellpadding="0" cellspacing="0">
		<thead></thead>
	<tbody>	
		<tr >
			<th><label  for="trsleadmgmt-lead-new-comment">Comment </label></th>
			<td style="margin:0 0 1% 0">
				<textarea placeholder="Comment" name="trsleadmgmt-lead-new-comment" type="text" id="trsleadmgmt-lead-new-comment-text" rows="2"></textarea>
				<input id="trsleadmgmt-lead-new-comment-flag" type="hidden" name="trsleadmgmt-lead-new-comment-flag"></input>
			</td>
		</tr>
		<?php
		if(!empty($comments)){
			foreach($comments as $comment){
			?>
		<table border="1" cellpadding="0" cellspacing="0" style="display: table;border-collapse:collapse; width:100%;border-color:#ddd;">
		
		 <tr class="trsleadmgmt_activity_single general_message user insert">
				<td>
				  <div class="left">
					<ul class="message_meta">
						  <li class="timestamp" style="display:inline;float:left;padding:0 15px;">
							<span class="time"><?php echo date('H:i:s',strtotime($comment->comment_date)); ?></span>
							<span class="date"><?php echo date('l, F jS, Y',strtotime($comment->comment_date)); ?></span> :
						  </li>
						<li class="entry_type" style="display:inline;float:left;padding:0 15px;">General Message </li>      
						<li class="by_user" style="display:inline;float:left;padding:0 15px;" >by <?php echo $comment->comment_author; ?> </li>
					  <li class="entry_type" style="display:inline;float:left;padding:0 15px;"><strong><?php echo $comment->comment_content; ?></strong></li>  
					</ul>
				  </div>
  
				</td>
			</tr>
		</table>
			
			<?php
			}
		}?>
		</tbody>
		</table>
	<?php
}



function trsleadmgmt_save_comment_fields( $post_id, $post ) {
	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['trsleadmgmt_comment_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['trsleadmgmt_comment_meta_box_nonce'], basename( __FILE__ ) ) )
		return $post_id;
	
	/** Bail out if running an autosave, ajax or a cron */
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;
	$commentsdata=get_post_meta( $post->ID, 'trsleadmgmt_commentsdata', true );
	$newArray=array();
	// echo "<pre>";print_r($_POST);die;
	
	$newArray[]=array(
		'comments'	=>(isset($_POST['trsleadmgmt-lead-new-comment']) && !empty($_POST['trsleadmgmt-lead-new-comment']))
					?$_POST['trsleadmgmt-lead-new-comment']
					:'',
		'user'		=>(isset($_POST['trsleadmgmt-lead-new-comment']) && !empty($_POST['trsleadmgmt-lead-new-comment']))
					?'Admin'
					:'',
	);
	$newArray=array_filter(array_map('array_filter', $newArray));
	if(isset($_POST['trsleadmgmt-lead-new-comment']) && !empty($_POST['trsleadmgmt-lead-new-comment'])){
		$time = current_time('mysql');
		$current_user = wp_get_current_user();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment_author' => $current_user->user_login,
			'comment_author_email' => $current_user->user_email,
			'comment_content' => $_POST['trsleadmgmt-lead-new-comment'],
			'user_id' =>  $current_user->ID,
			'comment_author_IP' => preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] ),
			'comment_date' => $time,
			'comment_approved' => 1,
		);
		wp_insert_comment($data);
	}
	
}
add_action( 'save_post', 'trsleadmgmt_save_comment_fields', 10, 2 );

function trsleadmgmt_show_mail_meta_box_callback(){
	global $post;
	?>
	<table id="trsleadmgmt_user_mail_stream" border="0" cellpadding="0" cellspacing="0">
		<thead></thead>
		<tbody>	
			<tr>
				<td><input <?php echo (get_post_meta( $post->ID, TRSLEADMGMT_LEAD_PREFIX.'email', true ))?'value="'.get_post_meta( $post->ID, TRSLEADMGMT_LEAD_PREFIX.'email', true ).'"':''; ?> class="trsleadmgmt_user_mail_input" id="trsleadmgmt-lead-mail-to-address" type="text" name="tomail" placeholder="To"></td>
			</tr>
			<tr>
				<td><input value="<?php echo 'site@'.$_SERVER['SERVER_NAME']; ?>" class="trsleadmgmt_user_mail_input" id="trsleadmgmt-lead-mail-from-address" type="text" name="tomail" placeholder="From"></td>
			</tr>
			<tr>
				<td><input id="trsleadmgmt-lead-mail-subject" class="trsleadmgmt_user_mail_input" type="text" name="tomail" placeholder="Subject"></td>
			</tr>
			<tr>
				<td>
					<?php
					$content = '';
					$editor_id = 'trsleadmgmt-lead-mail-body';
					
					$settings = array(
						'textarea_name' =>  'trsleadmgmt-lead-mail-body', 
						'textarea_rows' =>  10, 
					 );
				
					wp_editor( $content, $editor_id, $settings );
					?>
				</td>
			</tr>
		</tbody>
		<tfoot class="">
			<tr>
				<td style="padding: 5px;" colspan='6'>
				<input style="float:right;" type="button" id="trsleadmgmt_lead_send_lead_mail" class="button-secondary" value="Send Mail" />
				</td>
			</tr>
		</tfoot>
		</table>
	<?php
}

function trsleadmgmt_get_upload_script($btnid,$textboxid){
	?>
	<script type = "text/javascript">
		var file_frame<?php echo $btnid; ?>;
		jQuery("#<?php echo $btnid; ?>").live("click", function(podcast) {
			podcast.preventDefault();
			if (file_frame<?php echo $btnid; ?>) {
				file_frame<?php echo $btnid; ?>.open();
				return;
			}
			file_frame<?php echo $btnid; ?> = wp.media.frames.file_frame<?php echo $btnid; ?> = wp.media({
				title: jQuery(this).data("uploader_title"),
				button: {
					text: jQuery(this).data("uploader_button_text"),
				},
				multiple: false
			});
			file_frame<?php echo $btnid; ?>.on("select", function(){
				attachment = file_frame<?php echo $btnid; ?>.state().get("selection").first().toJSON();
				var url = attachment.url;
				var field = document.getElementById("<?php echo $textboxid; ?>");
				field.value = url;
			});
			file_frame<?php echo $btnid; ?>.open();
		});
	</script>
	<?php
	
}
function trsleadmgmt_file_view_code($file){
	if(!empty($file)){
		
		?>
		<i class="feedsproforwp-validate-feed-icon fa fa-exclamation-circle" style="display:none;"></i>&nbsp;&nbsp;&nbsp;<a href="<?php echo $file; ?>">View / Download</a>
		<?php
	
	}
}