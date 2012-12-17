<?php

class AddAddress {
        
    public function render()
	{				
		$this->address_form();		
    }

	public function address_form()
	{
		include_once APPROOT.'inc/lib_form_util.inc';        
				
		echo "<h4>".  _t('ADDING_ADDRESS___') . "</h4>";		

		$address_form = address_form('new');		
		$fields = shn_form_get_html_fields($address_form);
		$fields = place_form_elements($address_form,$fields);
		
		foreach($address_form as $field_id => $field_name){
			$validate_field_name = "validate_" . $field_id;
			$validate_field_value = $field_name['extra_opts']['validation'][1];
			echo "<input type='hidden' name='$validate_field_name' value='$validate_field_value'/>";
		}		
?>		
		<input type="submit" class="btn" name="save_address" value="<?php echo _t('SAVE_ADDRESS')?>" onclick="listAddress(); return false;" />
		<a class="btn" id="close_address_frm" href="#address_field"><?php echo _t('CLOSE');?></a>
<?php
		echo "<br />";		
	}
}
?>
