<h2><?php echo _t('SEARCH_REPORT');?></h2>
<br />
<a class="but" href='JavaScript:window.print();' ><img src="<?php echo data_uri(APPROOT.'www/res/img/document-print.png','image/png') ?>"> <?php echo _t('PRINT_THIS_PAGE')?></a>
<br /><br />
<div id="browse">
<?php
	if($rpt_columnValues != null){		
		shn_form_get_html_table($columnNames, $rpt_columnValues);		
	}
?>
<br />
</div>
<a class="but" href="<?php echo get_url('analysis','search_result',null,array('main_entity'=>$main_entity)); ?>"><?php echo _('BACK'); ?></a>
