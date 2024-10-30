<div>
	<h1>Group Email</h1>
	<div class="trsleadmgmt-settings-main">
		 <table class="form-table">
			<tr>
				<td>
					<table id="trsleadmgmt_group_mail_table" class="ud_ui_dynamic_table widefat">
						<thead>
							<h2>Send Mail</h2>
						</thead>
						<tbody>	
							<tr>
								<td>
									<select style="width:100%;" multiple="multiple" class="trsleadmgmt_group_mail_input_select" id="trsleadmgmt_group_mail_input_select_to_address" name="trsleadmgmt_group_to_mail" >
									<?php 
										$terms = get_terms('lead_user_group', array(											'hide_empty' => false,											) 										);
										foreach($terms as $group){
									?>
										<option value="<?php echo $group->term_id; ?>"><?php echo $group->name; ?></option>
									<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><input value="<?php echo 'site@'.$_SERVER['SERVER_NAME']; ?>" class="trsleadmgmt_user_mail_input" id="trsleadmgmt-lead-group-mail-from-address" type="text" name="tomail" placeholder="From"></td>
							</tr>
							<tr>
								<td><input id="trsleadmgmt-lead-group-mail-subject" class="trsleadmgmt_user_mail_input" type="text" name="tomail" placeholder="Subject"></td>
							</tr>
							<tr>
								<td>
									<?php
									$content = '';
									$editor_id = 'trsleadmgmt-lead-group-mail-body';
									
									$settings = array(
										'textarea_name' =>  'trsleadmgmt-lead-group-mail-body', 
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
								<input style="float:right;" type="button" id="trsleadmgmt_lead_group_send_lead_mail" class="button-secondary" value="Send Mail" />
								</td>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
	</table>
	</div>
</div>