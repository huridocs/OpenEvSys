
<?php
	if(isset($_GET['filter']) || count($columnValues) ){
?>
<div id="browse">
       <?php if (acl_is_mod_allowed('docu')) { ?>
                        <a  href="<?php get_url('docu', 'new_document', null,  null) ?>" class="btn btn-primary">
                                  <i class="icon-plus icon-white"></i>  <?php echo _t('ADD_NEW_DOCUMENT') ?></a>
<br/><br/>
                        <?php } ?>
<?php 
	if($columnValues != null && count($columnValues) ){
		$result_pager->render_pages();
	}	

	shn_form_get_html_filter_table($columnNames, $columnValues, $htmlFields, $argumentEncoder);
	
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
