
<?php
	
?>
<div id="browse">
    <?php if (acl_is_mod_allowed('person')) { ?>
      <a  href="<?php get_url('person', 'new_person', null,  null) ?>" class="btn btn-primary">
      <i class="icon-plus icon-white"></i>  <?php echo _t('ADD_NEW_PERSON') ?></a>
<br/><br/>
                        <?php } ?>
<?php
if(isset($_GET['filter']) || count($columnValues)){
	if($columnValues != null && count($columnValues)){		
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
        shnMessageQueue::addInformation(_t('THERE_IS_NO_PERSON_INFORMATION_YET__YOU_SHOULD_ADD_SOME_'));
	}
?>
