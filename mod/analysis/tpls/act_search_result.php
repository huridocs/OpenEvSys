<?php echo "<h2>".  _t($search_header) . "</h2>"; ?>
<br />
<div class="form-container">
<form action='<?php echo get_url('analysis','search_result')?>' method='get'>
    <input type="hidden" value="analysis" name="mod" />
    <input type="hidden" value="search_result" name="act" />
<?php  shn_form_hidden('Main Entity','main_entity',array('value'=>$main_entity));	?>
<div class="panel" id="search_panel" style="padding:0px">

<?php
    if($save_query){
?>
<fieldset style="border:0px;margin:0px;" >
    <legend><?php echo _t('SAVE_QUERY')?></legend>
    <?php
        echo "<div class='field'>";
		shn_form_text('Query Name','query_name',null);		
        echo "</div>";
        echo "<div class='field'>";
		shn_form_textarea('Query Description','query_desc',null);		
        echo "</div>";
		shn_form_submit('Save','query_save');
		shn_form_submit('Cancel','');
	?>
</fieldset>
<?php
    }
?>

<fieldset style="border:0px;margin:0px;" >
    <legend><?php echo _t('ACTIONS')?></legend>
	<a class="but" href="<?php echo get_url('analysis','search_form',null,array('main_entity'=>$main_entity)) ?>"><?php echo _t('BACK'); ?></a>&nbsp;&nbsp;&nbsp;	
	<?php 
	echo _t('CHANGE_VIEW__');
	
	shn_form_select('','shuffle_results',array('value'=>$search_entity, 'options'=>$shuffle_options,'br'=>false));
	shn_form_submit(_t('SUBMIT'),'shuffle');           
    echo "&nbsp;&nbsp;&nbsp;";
	shn_form_select('','actions',array('value'=>$_POST['actions'], 'options'=>$actions));
    shn_form_submit(_t('SUBMIT'),'action'); 
	?>
</fieldset>

<?php
	if(is_array($search_form) && count($search_form) !=0 ){
?>
<fieldset style="border:0px;margin:0px;" >
    <legend><?php echo _t('SEARCH_FORM')?></legend>
	<?php 
		if($fields['person_addresses'] != null){
			$fields['person_addresses'] = null;						
			$fields = place_form_elements($search_form,$fields);
			$address_fields = place_form_elements($address_form,$address_fields);																
		}
		else{
			$fields = place_form_elements($search_form,$fields);
		}		
		echo $fields['search'];
	?>
</fieldset>
<?php
	}
?>


</div>
</form>
</div>
<script language='javascript'>
    field_set_to_tab('search_panel');
</script>

<div id="browse" >
<h3><?php echo _t('SEARCH_RESULTS'); ?></h3>
<?php 
	if($columnValues != null && count($columnValues) > 0 ){
		$result_pager->render_pages();	
		shn_form_get_html_table($columnNames, $columnValues);	
		$result_pager->render_pages();
	}
    else{
        shnMessageQueue::addInformation(_t('NO_RECORDS_WERE_FOUND_'));
        echo shnMessageQueue::renderMessages();
    }
?>
</div>
