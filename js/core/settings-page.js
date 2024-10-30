jQuery(document).ready(function(){
	var i=1;
	var current_rows=jQuery('#data-field-count-text').val();
	var data_fields_table=jQuery('#trsleadmgmt_attribute_fields');
	jQuery('#trsleadmgmt-settings-data-field-add-row').click(function(event){
		console.log('tada');
		jQuery('#trsleadmgmt_attribute_fields').append(getnewrowdata(current_rows));
		current_rows++;
		jQuery('#data-field-count-text').val(current_rows);
		i++; 
	});
	jQuery(document).on('click','.trsleadmgmt-settings-data-field-delete-row',function(event){
		var rowid=event.target.id;
		var delete_id=jQuery('#'+rowid).attr('data-rowid');
		if(delete_id==1 || delete_id==2){
			alert('Sorry, Name and Email are Default Fields');
			return false;
		}
		
		jQuery("#dynamic_row_"+delete_id).remove();
		var refreshrows = parseInt(jQuery('#data-field-count-text').val(), 10);
		--refreshrows;
		--current_rows
		jQuery('#data-field-count-text').val(refreshrows);
		console.log(delete_id);
		jQuery('#trsleadmgmt-settings-save-button').click();
	});
	jQuery('#trsleadmgmt-settings-save-button').click(function(event){
		if(jQuery("#trsleadmgmt-setting-form").valid()){
			var myform = jQuery('#trsleadmgmt-setting-form').get(0);
			var fd = new FormData(myform); 
			fd.append('action',"trsleadmgmt_save_settings_request_js");
			/* console.log(formdata);
			console.log(arraydata); */
			jQuery.ajax({
				url: trsleadmgmt_settings_ajax.ajaxurl,
				type: "POST",
				data: fd,
				processData: false,
				contentType: false,
				cache: false,
				success:function(response) {
					 console.log(response);
					 if(response=='success'){
						 location.reload();
					 }else if(response=='error'){
						 alert('Error');
					 }else{
						 // alert('Exception');
					 }
					

				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});		}else{			jQuery("#trsleadmgmt-setting-form").validate();			return false;		}
	});
});
function getnewrowdata(current_rows){
	current_rows=++current_rows;
	var newrowdata="";
newrowdata += "<tr id=\"dynamic_row_"+current_rows+"\">";
newrowdata += "	<th class=\"wp_crm_draggable_handle\">&nbsp;<\/th>";
newrowdata += "	<td>";
newrowdata += "		<ul>";
newrowdata += "			<li>";
newrowdata += "				Title";
newrowdata += "			<\/li>";
newrowdata += "			<li>";
newrowdata += "				<input required=\"true\" type=\"text\" name=\"data["+current_rows+"][title]\">";
newrowdata += "			<\/li>";
newrowdata += "		<\/ul>";
newrowdata += "	<\/td>";
newrowdata += "	<td>";
newrowdata += "		<ul>";
newrowdata += "			<li>";
newrowdata += "				<input value=\"required\" type=\"checkbox\" name=\"data["+current_rows+"][options][]\"> Required";
newrowdata += "			<\/li>";
/* newrowdata += "			<li>";
newrowdata += "				<input value=\"Uneditable\" type=\"checkbox\" name=\"data["+current_rows+"][options][]\"> Uneditable";
newrowdata += "			<\/li>"; */
newrowdata += "		<\/ul>";
newrowdata += "	<\/td>";
newrowdata += "	<td>";
newrowdata += "		<ul>";
newrowdata += "			<li>";
newrowdata += "				<select required=\"true\" name=\"data["+current_rows+"][type]\">";
newrowdata += "					<option value=\"text\">Text<\/option>";
newrowdata += "					<option value=\"number\">Number<\/option>";
newrowdata += "					<option value=\"textarea\">Textarea<\/option>";
newrowdata += "					<option value=\"checkbox\">Checkbox<\/option>";
newrowdata += "					<option value=\"file\">File<\/option>";
newrowdata += "				<\/select>";
newrowdata += "			<\/li>";
newrowdata += "		<\/ul>";
newrowdata += "	<\/td>";
newrowdata += "	<td>";
newrowdata += "		<ul>";
newrowdata += "			<li>";
newrowdata += "				<input type=\"text\" name=\"data["+current_rows+"][placeholder]\" placeholder=\"Placeholder\">";
newrowdata += "			<\/li>";
newrowdata += "		<\/ul>";
newrowdata += "	<\/td>";
newrowdata += "	<td><span id=\"row_delete_"+current_rows+"\" data-rowid=\""+current_rows+"\" class=\"wp_crm_delete_row trsleadmgmt-settings-data-field-delete-row  button\">Delete<\/span><\/td>";
newrowdata += "<\/tr>";
return newrowdata;
}