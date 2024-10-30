jQuery(document).ready(function(){
	jQuery(".trsleadmgmt_group_mail_input_select").select2({
		tokenSeparators: [',', ' '],
		placeholder: "Select any group",
	});
	jQuery('#trsleadmgmt_lead_group_send_lead_mail').click(function(event){
		console.log(jQuery(".trsleadmgmt_group_mail_input_select").val());
		tinyMCE.triggerSave();
		var toemail=jQuery(".trsleadmgmt_group_mail_input_select").val();
		var fromemail=jQuery('#trsleadmgmt-lead-group-mail-from-address').val();
		var subject=jQuery('#trsleadmgmt-lead-group-mail-subject').val();
		var message=jQuery('#trsleadmgmt-lead-group-mail-body').val();
		if(toemail=='' || toemail==null){
			alert('Please select any Group First');
			return false;
		}
		if(fromemail=='' || validateEmail(fromemail)==false){
			alert('Invalid From email address');
			return false;
		}
		if(subject==''){
			alert('please Provide any Subject');
			return false;
		}
		if(message==''){
			alert('please Provide any Message');
			return false;
		}
		
		jQuery.ajax({
			url: trsleadmgmt_settings_ajax.ajaxurl,
			type: "POST",
			data: {'action':'trsleadmgmt_group_mail_send_request_js',toemail:toemail,fromemail:fromemail,subject:subject,message:message},
			success:function(response) {
				 console.log(response);				if(response=='sent'){					 alert('Mail Sent');					 location.reload();				}else{				 alert('Error Occured, Please retry');				}

			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
	});
});
function validateEmail(sEmail) {
	var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	if (filter.test(sEmail)) {
		return true;
	}
	else {
	return false;
	}
}
