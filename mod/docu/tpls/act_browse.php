<a class='but' href="<?php get_url('docu','new_document',null,null)?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/list-add.png','image/png') ?>"> <?php echo _t('ADD_NEW_DOCUMENT') ?></a>
<br />
<?php
	if(isset($_GET['filter']) || count($columnValues) ){
?>
<div id="browse">
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
        shnMessageQueue::addInformation(_t('THERE_ARE_NO_DOCUMENTS_YET__YOU_SHOULD_ADD_SOME_'));
	}
	
	

?>
