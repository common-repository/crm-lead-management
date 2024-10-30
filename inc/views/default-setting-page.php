<div class="main-settings-lead-div">
    <h1 class="trsleadmgmt-settings-h1">CRM Lead Management</h1>

<div class="trsleadmgmt-settings-main">
  <form id="trsleadmgmt-setting-form">  
  <input id="trsleadmgmt-settings-tabs-tab1" type="radio" class="nav-tabs-input" name="tabs" value="basic" checked>
  <label class="trsleadmgmt-settings-label"  for="trsleadmgmt-settings-tabs-tab1">Basic Settings</label>
    
  <input id="trsleadmgmt-settings-tabs-tab2" type="radio" class="nav-tabs-input" value="data-field" name="tabs">
  <label class="trsleadmgmt-settings-label"  for="trsleadmgmt-settings-tabs-tab2">Data Fields</label>
    
  <input id="trsleadmgmt-settings-tabs-tab3" type="radio" class="nav-tabs-input" value="data-column" name="tabs">
  <label class="trsleadmgmt-settings-label"  for="trsleadmgmt-settings-tabs-tab3">Display Columns</label>
    
  <!--<input id="trsleadmgmt-settings-tabs-tab4" type="radio" class="nav-tabs-input" value="data-extra" name="tabs">
  <label class="trsleadmgmt-settings-label"  for="trsleadmgmt-settings-tabs-tab4">Advance Settings</label>-->
 
  <section class="trsleadmgmt-settings-section"  id="trsleadmgmt-settings-tabs-content1">
    <table class="form-table">
			<tr>
				<th>General Settings</th>
				<td>
				<table id="trsleadmgmt_basic_settings" class="ud_ui_dynamic_table widefat">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td>Use optimized Lead Management</td>
							<td><input type="checkbox" checked="true" value="checked" name="trsleadmgmt-main-lead-setting-use"></td>
						</tr>
					</tbody>
				</table>
				</td>
			</tr>
	</table>
  </section>
    
  <section class="trsleadmgmt-settings-section"  id="trsleadmgmt-settings-tabs-content2">
		<table class="form-table">
			<tr>
				<th>General Settings</th>
				<td>
				<table id="trsleadmgmt_attribute_fields" class="ud_ui_dynamic_table widefat">
					<thead>
						<tr>
							<th class='trsleadmgmt_draggable_handle'>&nbsp;</th>
							<th class="trsleadmgmt_attribute_col">Attribute</th>
							<th class="trsleadmgmt_settings_col">Settings</th>
							<th class="trsleadmgmt_type_col">Type</th>
							<th class="trsleadmgmt_values_col">Placeholder</th>
							<th class="trsleadmgmt_delete_col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$item=0;
							$all_fields=trsleadmgmt_get_lead_data_fields();
							// echo "<pre>";print_r($all_fields);die;
						foreach($all_fields as $key=>$field){
							++$item;
							?>
						
						<tr id="dynamic_row_<?php echo $item; ?>">
							<th class="trsleadmgmt_draggable_handle">&nbsp;</th>
							<td>
								<ul>
									<li>
										Title
									</li>
									<li>
										<input required="true" <?php echo ($key=='name' || $key=='email')?'readonly="true" disabled="true"':'';?> type="text" value="<?php echo (!empty($field['label']))?$field['label']:'';?>" name="data[<?php echo $item; ?>][title]">
									</li>
									<li>
										<input type="hidden" value="<?php echo (!empty($key))?$key:'';?>" name="data[<?php echo $item; ?>][metaname]">
									</li>
								</ul>
							</td>
							<td>
								<ul>
									<li>
										<input <?php echo ($field['required']=='1')?'checked="true"':''; ?> <?php echo ($key=='name' || $key=='email')?'disabled="true"':'';?> value="required" type="checkbox" name="data[<?php echo $item; ?>][options][]"> Required
									</li>
								</ul>
							</td>
							<td>
								<ul>
									<li>
										<select required="true" <?php echo ($key=='name' || $key=='email')?'disabled="true"':'';?> name="data[<?php echo $item; ?>][type]">
											<option <?php echo ($field['type']=='text')?'selected="true"':'';?> value="text">Text</option>
											<option <?php echo ($field['type']=='number')?'selected="true"':'';?> value="number">Number</option>
											<option <?php echo ($field['type']=='textarea')?'selected="true"':'';?> value="textarea">Textarea</option>
											<option <?php echo ($field['type']=='checkbox')?'selected="true"':'';?> value="checkbox">Checkbox</option>
											<option <?php echo ($field['type']=='file')?'selected="true"':'';?> value="file">File</option>
										</select>
									</li>
								</ul>
							</td>
							<td>
								<ul>
									<li>
										<input <?php echo ($key=='name' || $key=='email')?'disabled="true"':'';?> type="text" value="<?php echo (!empty($field['placeholder']))?$field['placeholder']:'';?>" name="data[<?php echo $item; ?>][placeholder]" placeholder="Placeholder">
									</li>
								</ul>
							</td>
							<td><span class="button trsleadmgmt-settings-data-field-delete-row <?php echo ($key=='name' || $key=='email')?'trsleadmgmt-disabled-btn':'';?>" id="row_delete_<?php echo $item; ?>" data-rowid="<?php echo $item; ?>">Delete</span></td>
						</tr>
						<?php } ?>
						<input type="hidden" id="data-field-count-text" name="data-field-count" value="<?php echo count($all_fields); ?>">
					</tbody>
					<tfoot class="widefat-footer">
					  <tr>
						<td colspan='6'>
						<input type="button" id="trsleadmgmt-settings-data-field-add-row" class="trsleadmgmt_add_row button-secondary" value="Add Row" />
						</td>
					  </tr>
					</tfoot>
				</table>
				</td>
			</tr>
		</table>
  </section>
    
  <section class="trsleadmgmt-settings-section"  id="trsleadmgmt-settings-tabs-content3">
    <table class="form-table">
			<tr>
				<th>Columns Display</th>
				<td>
				<table id="trsleadmgmt_column_settings" class="ud_ui_dynamic_table widefat">
					<thead>
						<tr>
							<th class='trsleadmgmt_draggable_handle'>&nbsp;</th>
							<th class="trsleadmgmt_attribute_col">Column Name</th>
							<th class="trsleadmgmt_settings_col">Display</th>
							<th class="trsleadmgmt_type_col">Sortable</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$all_fields=trsleadmgmt_get_lead_data_fields();
							$all_display_fields=trsleadmgmt_get_lead_columns();
						foreach($all_fields as $key=>$field){
							?>
						<tr class="<?php echo ($key=='name' || $key=='email')?'trsleadmgmt_disabled_row':'';?>" >
							<td></td>
							<td>
								<ul>
									<li >
									<?php echo (!empty($field['label']))?$field['label']:'';?>
									</li>
									<li>
									<!--<input type="hidden" value="<?php echo (!empty($key))?$key:'';?>" name="trsleadmgmt-display-column[key][]">-->
									</li>
								</ul>
							</td>
							<td>
								<input type="checkbox" <?php echo (isset($all_display_fields[$key]))?'checked="true"':'' ?> value="checked" name="trsleadmgmt-display-column[display][<?php echo (!empty($key))?$key:'';?>]">
							</td>
							<td>
								<input type="checkbox" <?php echo (isset($all_display_fields[$key]['sortable']) && $all_display_fields[$key]['sortable']=='true')?'checked="true"':'' ?> value="checked" name="trsleadmgmt-display-column[sortable][<?php echo (!empty($key))?$key:'';?>]">
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				</td>
			</tr>
	</table>
  </section>
    
  <section class="trsleadmgmt-settings-section"  id="trsleadmgmt-settings-tabs-content4">
    <p class="trsleadmgmt-settings-p">
      Bacon ipsum dolor sit amet landjaeger sausage brisket, jerky drumstick fatback boudin ball tip turducken. Pork belly meatball t-bone bresaola tail filet mignon kevin turkey ribeye shank flank doner cow kielbasa shankle. Pig swine chicken hamburger, tenderloin turkey rump ball tip sirloin frankfurter meatloaf boudin brisket ham hock. Hamburger venison brisket tri-tip andouille pork belly ball tip short ribs biltong meatball chuck. Pork chop ribeye tail short ribs, beef hamburger meatball kielbasa rump corned beef porchetta landjaeger flank. Doner rump frankfurter meatball meatloaf, cow kevin pork pork loin venison fatback spare ribs salami beef ribs.
    </p>
    <p class="trsleadmgmt-settings-p">
      Jerky jowl pork chop tongue, kielbasa shank venison. Capicola shank pig ribeye leberkas filet mignon brisket beef kevin tenderloin porchetta. Capicola fatback venison shank kielbasa, drumstick ribeye landjaeger beef kevin tail meatball pastrami prosciutto pancetta. Tail kevin spare ribs ground round ham ham hock brisket shoulder. Corned beef tri-tip leberkas flank sausage ham hock filet mignon beef ribs pancetta turkey.
    </p>
  </section>
  </form> 

	<p class="trsleadmgmt_save_changes_row trsleadmgmt-settings-p">
		<input id="trsleadmgmt-settings-save-button" type="submit" value="Save Changes" class="button-primary" name="Submit">
	</p>
   
</div>
</div>
