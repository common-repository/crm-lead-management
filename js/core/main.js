jQuery(document).ready(function(){
	jQuery('#trsleadmgmt_lead_send_lead_mail').click(function(event){
		console.log('dum');
		tinyMCE.triggerSave();
		var toemail=jQuery('#trsleadmgmt-lead-mail-to-address').val();
		var fromemail=jQuery('#trsleadmgmt-lead-mail-from-address').val();
		var subject=jQuery('#trsleadmgmt-lead-mail-subject').val();
		var message=jQuery('#trsleadmgmt-lead-mail-body').val();
		if(toemail=='' || validateEmail(toemail)==false){
			alert('Invalid Lead email address');
			return false;
		}
		if(toemail=='' || validateEmail(toemail)==false){
			alert('Invalid Lead email address');
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
			url: trsleadmgmt_lead_main_ajax.ajaxurl,
			type: "POST",
			data: {'action':'trsleadmgmt_lead_send_mail_request_js',toemail:toemail,fromemail:fromemail,subject:subject,message:message},
			success:function(response) {
				console.log(response);
				if(response=='sent'){	
					alert('Mail Sent');
					//location.reload();
				}else{	
					alert('Error Occured, Please retry');	
				}
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