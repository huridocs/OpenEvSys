<?php
	global $conf;
	echo "<h2>".  _t($search_header) . "</h2>";
?>
<br />
<div class="panel">
<div class="form-container">
<form action='<?php echo get_url('analysis','search_result')?>' method='get'>
    <input type="hidden" value="analysis" name="mod" />
    <input type="hidden" value="search_result" name="act" />
	<?php 
		shn_form_hidden('Main Entity','main_entity',array('value'=>$main_entity));					
		
		if($fields[$address_index] != null){
			$fields[$address_index] = null;						
			$fields = place_form_elements($search_form,$fields);
			$address_fields = place_form_elements($address_form,$address_fields);																
		}
		else{
			$fields = place_form_elements($search_form,$fields);
		}		
		
		
	?>
	<a class="but" href="<?php echo get_url('analysis','search',null,null) ?>"><?php echo _('BACK'); ?></a>
	<?php echo $fields['search'];?>
</form>
</div>
</div>
