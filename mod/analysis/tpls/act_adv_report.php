<h2><?php echo "Advanced Search Report"?></h2>
<br />
<a style="float:left;" class="but" href='JavaScript:window.print();' ><img src="<?php echo data_uri(APPROOT.'www/res/img/document-print.png','image/png') ?>"> <?php echo _t('PRINT_THIS_PAGE')?></a>
<a style="float:right; margin-right: 20px;" class="but" href="<?php echo get_url('analysis','adv_search',null, array('query'=>$_GET['query'])); ?>"><?php echo _('BACK'); ?></a>
<br /><br />
<div id="browse">
<?php
	if($columnValues != null){		
		shn_form_get_html_table($columnNames, $columnValues);		
	}
?>
<br />
</div>
