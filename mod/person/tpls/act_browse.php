<?php if( acl_is_entity_allowed_boolean('person' , 'create')){?>
	<a class='but' href="<?php get_url('person','new_person',null,array('pid'=>null))?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/list-add.png','image/png') ?>"> <?php echo _t('ADD_NEW_PERSON') ?></a>
<?php } ?>
<?php
	if(isset($_GET['filter']) || count($columnValues)){
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
        shnMessageQueue::addInformation(_t('THERE_IS_NO_PERSON_INFORMATION_YET__YOU_SHOULD_ADD_SOME_'));
	}
?>
