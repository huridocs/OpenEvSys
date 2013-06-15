
<?php
	if(isset($_GET['filter']) || count($columnValues) ){
?>
<div id="browse" >
   
<?php 
	if($columnValues != null && count($columnValues) ){
		$result_pager->render_pages();
	}	
		
	shn_form_get_html_filter_table($columnNames, $columnValues, $htmlFields);
		
	if($columnValues != null && count($columnValues) ){
		$result_pager->render_pages();
	}		
	
?>
</div>
<br />
<?php
	}
	else{
        shnMessageQueue::addInformation(_t('THERE_IS_NO_INTERVENTION_INFORMATION_YET__YOU_SHOULD_ADD_SOME___CLICK_THE__ADD_NEW_EVENT__BUTTON_TO_ADD_A_NEW_EVENT_'));
	}
