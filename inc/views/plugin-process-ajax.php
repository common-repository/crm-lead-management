<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function trsleadmgmt_process_save_settings_request(){
	$prefix = TRSLEADMGMT_LEAD_PREFIX;
	if(isset($_POST['tabs']) && !empty($_POST['tabs'])){
		if('data-field'==$_POST['tabs']){
			if(count($_POST['data'])>0){
				$postdata=array();
				// echo "<pre>";print_r($_POST['data']);die;
				foreach($_POST['data'] as $row){
					if($row['metaname']=='name' || $row['metaname']=='email')continue;
					if(empty($row['title']) || empty($row['type']))continue;
					$metaname=(empty($row['metaname']))?trsleadmgmt_create_meta_from_title($row['title']):$row['metaname'];
					$required='0';
					if(isset($row['options']) && count($row['options'])>0){
						foreach($row['options'] as $id=>$option){
							$required=($option=='required')?'1':'0';
						}
					}
					$postdata[$metaname]=array(
						'label'			=> __( $row['title'], TRSLEADMGMT_TEXT_DOMAIN ),
						'id'			=> $prefix.$metaname,
						'type'			=> $row['type'],
						'required'		=> $required,
						'after'			=> '',
						'placeholder'	=> (!empty($row['placeholder']))?$row['placeholder']:'',
					);
				}
				$additional_fields_array=array('additional_lead_fields' => $postdata);
				trsleadmgmt_update_general_setting_value($additional_fields_array);
				echo "success";die;
			}
		}elseif('data-column'==$_POST['tabs']){
			// echo "<pre>";print_r($_POST);die;
			$fielddata=trsleadmgmt_get_lead_data_fields();
			// echo "<pre>";print_r($fielddata);die;
			$postdata=array();
				$displaycolumn=$_POST['trsleadmgmt-display-column']['display'];
				foreach($displaycolumn as $key=>$displaybox){
					if($key=='name' || $key=='email')continue;
					$postdata[$key]=array(
						'label'			=> __( $fielddata[$key]['label'], TRSLEADMGMT_TEXT_DOMAIN ),
						'default'		=> 'false',
						'sortable'		=> 'false',
						'title'			=> 'false',
						'value_field'	=> $key,
					);
				}
			if(count($postdata)>0){
				$columnsortable=$_POST['trsleadmgmt-display-column']['sortable'];
				foreach($postdata as $meta=>$item){
					if(isset($columnsortable[$meta])){
						$postdata[$meta]['sortable']='true';
					}
				}
			}
			$additional_fields_array=array('additional_display_lead_fields' => $postdata);
			trsleadmgmt_update_general_setting_value($additional_fields_array);
				// echo "<pre>";print_r($_POST['trsleadmgmt-display-column']);die;
			die('success');
			
		}
	}
	die;
}
function trsleadmgmt_process_lead_send_mail_request(){
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	// More headers
	$headers .= 'From: '.explode('@',$_POST['fromemail'])[0].'<'.$_POST['fromemail'].'>' . "\r\n";
	// $headers .= 'Cc: myboss@example.com' . "\r\n";
	// $headers .= 'From: pank<asdsa@trssoftwaresolutions.in>' . "\r\n";
	$to = $_POST['toemail'];
	$subject = $_POST['subject'];
	$message = stripslashes(nl2br($_POST['message']));
	mail($to,$subject,$message,$headers);
	die('sent');
}
function trsleadmgmt_process_group_mail_send_request(){
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	// More headers
	$headers .= 'From: '.explode('@',$_POST['fromemail'])[0].'<'.$_POST['fromemail'].'>' . "\r\n";
	$subject = stripslashes($_POST['subject']);
	$message = stripslashes(nl2br($_POST['message']));
	if(count($_POST['toemail'])>0){
		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => TRSLEADMGMT_LEAD_POSTTYPE,
			'tax_query' => array(
				'taxonomy' => 'lead_user_group',
				'terms' => $_POST['toemail'],
				'include_children' => false // Remove if you need posts from term 7 child terms
			),
		);
		$posts = get_posts($args);
		$useremail=array();
		foreach($posts as $post){
			$email=get_post_meta($post->ID,TRSLEADMGMT_LEAD_PREFIX.'email',true);
			if(!empty($email)){
				$useremail[]=filter_var($email, FILTER_SANITIZE_EMAIL);
			}
		}
		$useremail=array_unique(array_filter($useremail));
		if(count($useremail)>0){
			foreach($useremail as $email){
				$to = $email;
				mail($to,$subject,$message,$headers);
			}
			die('sent');
		}
	}
	die();
}
function trsleadmgmt_create_meta_from_title($title){
	$title=strtolower($title);
	$specialChars = array(" ", "\r", "\n");
	$replaceChars = array("", "", "");
	$title = str_replace($specialChars, $replaceChars, $title);
	$title=preg_replace("/[^a-zA-Z]+/", "", $title);
	$metaname=uniqid(true)."_".$title;
	return $metaname;
}